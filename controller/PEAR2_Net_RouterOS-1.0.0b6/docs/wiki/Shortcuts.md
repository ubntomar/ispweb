# Shortcuts
PEAR2_Net_RouterOS allows you to write not just code that works, but code that looks readable.

A lot of the examples you see in other tutorials are intentionally verbose, so that you can clearly see what's happening. But once you get used to these constructs, you'll probably start to find this verbosity annoying. Here are some shortcuts PEAR2_Net_RouterOS allows you to use.

## set*() chaining
The setArgument(), setTag() and setQuery() from the Request object all return the request object itself. This means you can chain one after the other. For example, you can write

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest->setArgument('address', '192.168.88.100');
$addRequest->setArgument('mac-address', '00:00:00:00:00:01');
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.100'");
}
 
$addRequest->setArgument('address', '192.168.88.101');
$addRequest->setArgument('mac-address', '00:00:00:00:00:02');
if ($client->sendSync($addRequest)->getType() !== RouterOS\Response::TYPE_FINAL) {
    die("Error when creating ARP entry for '192.168.88.101'");
}
 
echo 'OK';
```

as

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
$addRequest = new RouterOS\Request('/ip/arp/add');

if ($client->sendSync(
    $addRequest
        ->setArgument('address', '192.168.88.100')
        ->setArgument('mac-address', '00:00:00:00:00:01')
    )->getType() !== RouterOS\Response::TYPE_FINAL
) {
    die("Error when creating ARP entry for '192.168.88.100'");
}

if ($client->sendSync(
    $addRequest
        ->setArgument('address', '192.168.88.101')
        ->setArgument('mac-address', '00:00:00:00:00:02')
    )->getType() !== RouterOS\Response::TYPE_FINAL
) {
    die("Error when creating ARP entry for '192.168.88.101'");
}
 
echo 'OK';
```

In addition to those, the removeAllArguments() returns the request object too. Client::sendAsync() is also another method that returns the object itself, which means you could chain several requests one after another, e.g. the code

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
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
could be written as

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
$addRequest = new RouterOS\Request('/ip/arp/add');

$client
    ->sendAsync(
        $addRequest
            ->setArgument('address', '192.168.88.100')
            ->setArgument('mac-address', '00:00:00:00:00:01')
            ->setTag('arp1')
    )
    ->sendAsync(
        $addRequest
            ->setArgument('address', '192.168.88.101')
            ->setArgument('mac-address', '00:00:00:00:00:02')
            ->setTag('arp2')
    )
    ->loop();
```

The Util::setMenu() is another method that returns the object itself, so for example, the code

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/system identity');

//echoes "MikroTik", assuming you've never altered your router's identity.
echo $util->get(null, 'name');
```

can be written as

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);

//echoes "MikroTik", assuming you've never altered your router's identity.
echo $util->setMenu('/system identity')->get(null, 'name');
```

## \_\_invoke() magic
Most objects can be invoked as functions, and which point they're like a shorthand for the most common functionality of the object. You can see full details in the API reference, under the \_\_invoke() magic method's description for that object. Using that, the example above could be written as

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
$addRequest = new RouterOS\Request('/ip/arp/add');
 
$addRequest('address', '192.168.88.100');
$addRequest('mac-address', '00:00:00:00:00:01');
$addRequest->setTag('arp1');
$client($addRequest);
 
$addRequest('address', '192.168.88.101');
$addRequest('mac-address', '00:00:00:00:00:02');
$addRequest->setTag('arp2');
$client($addRequest);
 
$client();
```

## Since PHP 7
In the upcoming PHP 7, there's support for function chaining (see [uniform variable syntax RFC](https://wiki.php.net/rfc/uniform_variable_syntax)).

With that in place, the above for example could be rewritten as

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
(new RouterOS\Client('192.168.88.1', 'admin', 'password'))
    (
        ($addRequest = new RouterOS\Request('/ip/arp/add'))
        ('address', '192.168.88.100')
        ('mac-address', '00:00:00:00:00:01')
        ->setTag('arp1')
    )
    (
        $addRequest
        ('address', '192.168.88.101')
        ('mac-address', '00:00:00:00:00:02')
        ->setTag('arp2')
    )
 ();
```

or perhaps even

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
(new RouterOS\Client('192.168.88.1', 'admin', 'password'))
    (
        (new RouterOS\Request('/ip/arp/add', null, 'arp1'))
        ('address', '192.168.88.100')
        ('mac-address', '00:00:00:00:00:01')
    )
    (
        (new RouterOS\Request('/ip/arp/add', null, 'arp2'))
        ('address', '192.168.88.101')
        ('mac-address', '00:00:00:00:00:02')
    )
 ();
```
