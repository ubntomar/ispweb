# Approaches with client
Description of the various approaches in using the Client class

## Synchronous requests
The easiest approach in using PEAR2_Net_RouterOS is to connect, send a request, get the responses, and use them if you need to, all at one time. This is reffered to as "Synchonious request".

### Simple requests
If the request you want to send is just a simple command with no arguments, the easiest way is to pass it right there at the Client::sendSync() method, like this:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
    //Inspect $e if you want to know details about the failure.
}

$responses = $client->sendSync(new RouterOS\Request('/ip/arp/print'));

foreach ($responses as $response) {
    if ($response->getType() === RouterOS\Response::TYPE_DATA) {
        echo 'IP: ', $response->getProperty('address'),
        ' MAC: ', $response->getProperty('mac-address'),
        "\n";
    }
}
//Example output:
/*
IP: 192.168.88.100 MAC: 00:00:00:00:00:01
IP: 192.168.88.101 MAC: 00:00:00:00:00:02
 */
```

You can also use the syntax from RouterOS's shell (spaces between words instead of "/"). Either way, the command needs to be absolute (begin with "/"). Note also that auto completion is not supported (e.g. "/ip f n p" will NOT be translated to "/ip/firewall/nat/print", but will instead be passed to RouterOS as "/ip/f/n/p", which in current versions results in an error). Examples in the rest of this documentation will use the API syntax.

### Requests with arguments
To add arguments to a command, you need to use the Request::setArgument() method before you send the request. You can reuse the same request object by clearing its arguments and/or setting new values appropriately, as in the following example.

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.100'");
}
 
$addRequest->setArgument('address', '192.168.0.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.101'");
}
 
echo 'OK';
```

You can also enter the arguments with a shell syntax at the request constructor, but with a few caveats:

1. Nameless arguments are not supported. You have to explicitly specify the argument names. This is optional in shell, but is required by the API protocol. e.g.
    ```php
$pingRequest = new RouterOS\Request('/ping 192.168.88.100');
    ```
    becomes
    
    ```php
$pingRequest = new RouterOS\Request('/ping address=192.168.88.100');
    ```
    To find out the name of a nameless argument, go to a terminal, and type "?" after the command to see its help. The real names of nameless arguments can be seen in the form "&lt; __argument name__ >".
2. Only literal values are allowed. No operators of any kind.
    * Arithmetic and logic operators can be done using PHP instead (i.e. you just pass the resulting value).
    * To emulate the subcommand operator (the "[" and "]"), you must execute the other command separately beforehand (with another Request object), and take its result.
    * To use a global RouterOS variable's value, you can make a get or print request to the "/system script environment" menu.
3. A double quote and a backslash are the only escapable characters in a double quoted string. Everything else is treated literally.
4. The "where" argument on "print" doesn't work. [Use queries](Using-queries) instead, as MikroTik intended.
5. Arguments without value (a.k.a. "empty arguments") are supported, but to avoid ambiguities between the command's end and the argument list's start, the first argument in the argument list MUST have a value. e.g.
    ```php
$printRequest = new RouterOS\Request('/ip arp print file="ARP list prinout.txt" detail');
    ```
    is allowed, but if you write
    
    ```php
$printRequest = new RouterOS\Request('/ip arp print detail file="ARP list prinout.txt"');
    ```
    you'll be calling the *command* "ip/arp/print/detail" with a "file" argument. Because there is no "detail" command, you'll get an error. If you need to use only empty arguments, you can assign an empty string to the first one, e.g.
    ```php
$printRequest = new RouterOS\Request('/ip arp print detail=""');
    ```

Here's the last example, rewritten with the aforementioned abilities in mind (though without reusing the request):
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
$addRequest = new RouterOS\Request('/ip arp add address=192.168.88.100 mac-address=00:00:00:00:00:01');
 
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.100'");
}
 
$addRequest = new RouterOS\Request('/ip arp add address=192.168.88.101 mac-address=00:00:00:00:00:02');
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.101'");
}
 
echo 'OK';
```

Note that using the request constructor is not recommended when you're dealing with user input, as there's the potential of code injection. A literal like the above is of course completely safe and recommended.

### Asynchronous requests
When you don't want the script to immediately wait for all responses to a request, you can make the request asynchronous, using the Client::sendAsync() method. This is useful when
* You want to deal with the responses from commands later down in the script, instead of right after you send them (for the sake of neatness, let's say).
* You only need to deal with one of the responses, and yet you need to send several requests at about the same time.
* You want to use a command which returns responses continuously, i.e. it never finishes on its own, until you explicitly cancel it yourself.

Depending on the way you want to deal with the responses, there are various other methods which you may use along with Client::sendAsync().

#### Send and forget
If you don't care about the responses, you can just do something like the following
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
$addRequest->setTag('arp1');
$client->sendAsync($addRequest);
 
$addRequest->setArgument('address', '192.168.88.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
$addRequest->setTag('arp2');
$client->sendAsync($addRequest);

$client->loop();
```

Note that, as in the example above, different asynchronous requests need to have a different "tag", regardless of whether you care about the responses or not. A "tag" in this context is a RouterOS API specific construct that allows clients like PEAR2_Net_RouterOS to keep track of responses coming from multiple requests, since they don't appear in the order of their execution. You can only reuse a tag once you get its final response.

Besides using the Request::setTag() method, you can also set a tag as the third argument of the request's constructor.

#### Loop and extract
One way to get responses is to let PEAR2_Net_RouterOS process any new ones, and then extract those that interest you. You can start processing with the Client::loop() method. If you've made requests that you know will eventually be finished, you can use Client::loop() without an argument to let processing stop only once all requests have returned their final response. Here's an example that continues from the previous one.

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
$addRequest->setTag('arp1');
$client->sendAsync($addRequest);
 
$addRequest->setArgument('address', '192.168.88.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
$addRequest->setTag('arp2');
$client->sendAsync($addRequest);
 
$client->loop();
 
$responses = $client->extractNewResponses();
foreach ($responses as $response) {
    if ($responses->getType() !== RouterOS\Response::TYPE_FINAL) {
        echo "Error with {$response->getTag()}!\n";
    } else {
        echo "OK with {$response->getTag()}!\n";
    }
}
//Example output:
/*
OK with arp1
OK with arp2
*/
```

#### Callback and loop
Instead of extracting responses, you may instead assign responses for a request to a callback. Once you do that, starting the processing is all you need to do.

```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
//Custom function, defined specifically for the example
function responseHandler($response) {
    if ($response->getType() === RouterOS\Response::TYPE_FINAL) {
        echo "{$response->getTag()} is done.\n";
    }
}
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
$addRequest->setTag('arp1');
$client->sendAsync($addRequest, 'responseHandler');
 
$addRequest->setArgument('address', '192.168.88.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
$addRequest->setTag('arp2');
$client->sendAsync($addRequest, 'responseHandler');
 
$client->loop();
//Example output:
/*
arp1 is done.
arp2 is done.
*/
```

#### Send and complete
Processing of responses can also be started with Client::completeRequest(). The difference is that Client::loop() ends when a certain timeout is reached, or when all requests are finished, and Client::completeRequest() instead ends when the final response of a specified request has been processed, regardless of the time it takes. The return value is a collection of all responses, or an empty collection if the request was assigned to a callback.

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
} catch (Exception $e) {
    die('Unable to connect to the router.');
}
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
$addRequest->setTag('arp1');
$client->sendAsync($addRequest);
 
$addRequest->setArgument('address', '192.168.88.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
$addRequest->setTag('arp2');
$client->sendAsync($addRequest);
 
foreach ($client->completeRequest('arp1') as $response) {
    if ($response->getType() === RouterOS\Response::TYPE_ERROR) {
        echo "Error response for 'arp1'!\n";
    }
}
 
foreach ($client->completeRequest('arp2') as $response) {
    if ($response->getType() === RouterOS\Response::TYPE_ERROR) {
        echo "Error response for 'arp2'!\n";
    }
}
 
echo 'OK';
```