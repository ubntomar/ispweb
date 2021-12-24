# Optional features

## Automatic charset conversion
Let's say you're not a native English speaker, and that your native language doesn't use just the latin alphabet (e.g. Portuguese) or doesn't use it at all (e.g. Bulgarian, Georgian, etc.).

You were probably tempted to write in your native language from within Winbox. You'll see you can successfully do that, and read everything from within Winbox later. But if you do some more extensive testing, you'll see that your non-latin text is only readable from computers with the same regional settings. Furthermore, if you were to view the text at the router itself or with the API, you'll see the non-latin text as gibberish.

All of this is because the charsets are different in all of these environments - Winbox uses your regional settings' charset, the terminal shows non ANSI characters with their code points, and the API gets the raw data (without any charset directions).

PEAR2_Net_RouterOS allows you to tell it the charset your content is stored in, and the charset that your web server content is in. After you specify those two, the conversions between them are done automatically.

But what charset pair to use?

On UNIX, you can use ```nl_langinfo(CODESET);``` from PHP to get the charset of your current locale settings, but you can't do the same on Windows, and there's no easily accessible place in the Windows GUI where you can view your current charset either.

For Windows, check out "Control Panel > Region (and Language Settings) > Administrative > Language for non-Unicode programs". Then find that language in [this table](http://msdn.microsoft.com/en-us/goglobal/bb896001.aspx). The "ANSI codepage" column is the charset you're looking for. That is, the charset is "windows-XXXX" where "XXXX" is what that column says.

Let's say that we find our charset pair. In the example below, we'll assume that what the investigation revealed (using the above instructions) was "windows-1251", which is what we would specify as the charset our content is stored in. If you're experienced enough developer, you're probably writing your application using "UTF-8", so that's what we'll use as our web server's charset. This is done like so:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

//Ensuring our text on screen in UTF-8 too.
//Note that for this to work, the PHP file itself must also be encoded with UTF-8.
//If you're using Notepad, you can ensure that from the "Encoding" drop down
//at the "Save As..." dialog.
header('Content-Type: text/html;charset=UTF-8');

$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

//Here's where we specify the charset pair.
$client->setCharset(
    array(
        RouterOS\Communicator::CHARSET_REMOTE => 'windows-1251',
        RouterOS\Communicator::CHARSET_LOCAL  => 'UTF-8'
    )
);

$client->sendSync(new RouterOS\Request('/queue/simple/add name=Йес'));
//"Йес" should appear in the exact same way in Winbox now,
//assuming your Windows' regional settings use this charset.

//Let's assume you already have another queue list entry with the name "ягода"
echo $client->sendSync(
    new RouterOS\Request('/queue/simple/print', RouterOS\Query::where('name', 'ягода'))
)->getProperty('name');
//Should output "ягода" in the exact same fashion as you see it here, and in Winbox.
```

## Persistent connections
PEAR2_Net_RouterOS offers persistent connections, which in essence provides the exact same thing as the [database equivalent](http://php.net/manual/en/features.persistent-connections.php) - all PHP requests during the PHP process' life cycle will share the same TCP connection for each username:password@hostname:port combo.

This may reduce the required bandwidth between your web server and your router, and should also take off some of the load from the router.

It's important to note that unlike the database equivalent, these persistent connections come with a penalty of their own - A penalty on the web server. Because multiple requests can come in at the same time, and they're all "meshed", PEAR2_Net_RouterOS needs to marshal the different requests and responses to each Client instance, which is done measurably slower in PHP than in C. How much slower? About a few microseconds per router message (i.e. API sentence). A negligible penalty for the amount of work done during a single PHP request, but accumulated when you have lots of simultaneous PHP requests, which is when you'd consider using persistent connections to begin with.

If you have a powerful enough web server (as in, you're happy with the web server's performance of non-persistent connections), and a not-so-powerful router (e.g. a cheap x86 PC or a weaker RouterBOARD), this trade off is probably well worth it. In other scenarios, YMMV.

You can create a persistent connection by specifying ```true``` at the 5th argument of the Client constructor (the 4th being the API port).

Note that persistent connections require [PEAR2_Cache_SHM](http://pear2.github.io/Cache_SHM/) (bundled in the archive), which in turn requires the [APC](http://php.net/apc) (>= 3.0.13) or [WinCache](http://php.net/wincache) (>= 1.1.0) extension.

__There is no difference in terms of "features" between persistent and non-persistent connections. If you find a difference, you've found a bug. The only difference is in performance.__

## Encrypted connections
Since RouterOS 6.1, the API protocol supports encrypting the connection using TLS, and can do so with and without a certificate. PEAR2_Net_RouterOS also supports this in both modes.

To establish an encrypted connection, you need to enable it at the 7th constructor argument (the 6th being a timeout for the connection). You may want to add a "use" for "PEAR2\Net\Transmitter\NetworkTransmitter", because the value of that argument needs to be a constant from that class.

In particular, to establish an encrypted connection without a certificate, all you need to do is:
```php
<?php
use PEAR2\Net\RouterOS;
use PEAR2\Net\Transmitter\NetworkStream;

require_once 'PEAR2/Autoload.php';

$client = new RouterOS\Client(
    '192.168.88.1',
    'admin',
    'password',
    null,
    false,
    null,
    NetworkStream::CRYPTO_TLS
);
```

Notice that we left the port (the 4th argument) to ```null```. The port is automatically chosen between 8728 and 8729 depending on whether we don't or do have an encrypted connection, respectively. If you want to use a different port, set it at both the 4th argument, and at "/ip service" under the "api-ssl" service.

The reason we use a class constant is in case MikroTik and PHP later support additional encryption methods - this would allow you to easily switch to them.

If you need to use a certificate, you could leave it like that, but while you're at it, you'll probably want to check the certificate - to do that, you need to supply a stream context argument as the 8th argument. The stream context needs to contain a CA file (or a folder with such file(s)) that will verify RouterOS' certificate. For example:
```php
<?php
use PEAR2\Net\RouterOS;
use PEAR2\Net\Transmitter\NetworkStream;

require_once 'PEAR2/Autoload.php';

$context = stream_context_create(
    array(
        'ssl' => array(
            'verify_peer' => true,
            'cafile' => 'myca.cer'
        )
    )
);

$client = new RouterOS\Client(
    '192.168.88.1',
    'admin',
    'password',
    null,
    false,
    null,
    NetworkStream::CRYPTO_TLS,
    $context
);
```

**NOTE: Due to known issues with PHP itself ([61285](https://bugs.php.net/bug.php?id=61285), [62605](https://bugs.php.net/bug.php?id=62605), [65137](https://bugs.php.net/bug.php?id=65137), [68853](https://bugs.php.net/bug.php?id=68853) and possibly others), encrypted connections can be very unstable.**