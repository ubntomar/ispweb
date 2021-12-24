**NOTE: The following is for when you're not using the Util class, but are instead working directly with the Client class. The Util class hides this under the hood for you.**

## Getting individual properties
### In RouterOS v6.0 and later
Getting an individual item property is as easy as in shell, but a little more explicit. The value is in a property called "ret". For example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$getRequest = new RouterOS\Request('/interface/get');
$getRequest->setArgument('value-name', 'type');
$getRequest->setArgument('number', 'ether1');
$type = $client->sendSync($getRequest)->getProperty('ret');
//$type now contains the "type" of the interface "ether1"
```

On menus where items are not identified with a unique name (e.g. "/ip arp", "/ip firewall", etc.), it's best that you use the same approach as for RouterOS versions before 6.0, as IDs may change from one connection to the next, and numbers are not natively supported by the protocol (see below).

### Prior to RouterOS v6.0
If you try to use shell's "get" command with the API protocol on older RouterOS versions, you'll find it doesn't really work. It returns just a single empty response, as if only to confirm the command's existence.

Getting an individual property is not directly possible with the API protocol, but it can be closely emulated by the use of the "print" command.

A "print" command can take queries, which are described in more detail in [a separate tutorial](Using-queries). Combined with the API specific ".proplist" argument (also described in that tutorial), you can make the print command return just one reply, containing just the one property of the one item that interests you.

For example, if you were interested in getting the MAC address of a certain "arp" entry you've targeted by its IP address:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', 'mac-address');
$printRequest->setQuery(RouterOS\Query::where('address', '192.168.88.100'));
$mac = $client->sendSync($printRequest)->getProperty('mac-address');
//$mac now conains the MAC address for the IP 192.168.88.100
```

## The "numbers" argument
All commands that require you to use the "numbers" argument to target an item (enable, disable, set, unset and remove), ironically enough, do not accept numbers as their value. Instead, they accept a list of IDs.  __The IDs you get from API are NOT the same as the numbers you see in the "#" column in shell/Winbox.__

There is no way of knowing the IDs without doing a "print" of the targeted item - from the API protocol - at some point. Ideally, you should do it right when you're about to target the item. When you do that, the ID is available from an API specific argument called ".id".

Side note: When you "add" an entry, it's ID is available in the "ret" argument of the response.

### Examples
#### Remove
To delete an entry in the ARP list that has a comment saying "my", we'd do:

```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', '.id');
$printRequest->setQuery(RouterOS\Query::where('comment', 'my'));
$id = $client->sendSync($printRequest)->getProperty('.id');
//$id now contains the ID of the entry we're targeting

$removeRequest = new RouterOS\Request('/ip/arp/remove');
$removeRequest->setArgument('numbers', $id);
$client->sendSync($removeRequest);
```

#### Edit/Set
If we wanted to change the comment of an entry, we would do something like:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', '.id');
$printRequest->setQuery(RouterOS\Query::where('comment', 'my'));
$id = $client->sendSync($printRequest)->getProperty('.id');
//$id now contains the ID of the entry we're targeting

$setRequest = new RouterOS\Request('/ip/arp/set');
$setRequest->setArgument('numbers', $id);
$setRequest->setArgument('comment', 'new comment');
$client->sendSync($setRequest);
```

#### Unset
There are some places in RouterOS where it matters whether you have a property with an empty value, or don't have a value for the property at all. In such cases, it is useful to use the "unset" command to remove the property from the entry.

The following example removes the comment from an ARP entry:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', '.id');
$printRequest->setQuery(RouterOS\Query::where('comment', 'my'));
$id = $client->sendSync($printRequest)->getProperty('.id');
//$id now contains the ID of the entry we're targeting

$unsetRequest = new RouterOS\Request('/ip/arp/unset');
$unsetRequest->setArgument('numbers', $id);
$unsetRequest->setArgument('value-name', 'comment');
$client->sendSync($unsetRequest);
```

#### Enable/Disable
The following example uses the "disable" command to disable an ARP item. Analogously, the "enable" command would enable an item:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', '.id');
$printRequest->setQuery(RouterOS\Query::where('comment', 'my'));
$id = $client->sendSync($printRequest)->getProperty('.id');
//$id now contains the ID of the entry we're targeting

$disableRequest = new RouterOS\Request('/ip/arp/disable');
$disableRequest->setArgument('numbers', $id);
$client->sendSync($disableRequest);
```

### Note: It's a list
Keep in mind the "numbers" argument accepts a list - a comma separated list to be exact. So if you need to, you can have several IDs lined up, like if you had multiple ARP entries for a MAC address, you can remove them all at once with something like the following:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';
 
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');

$printRequest = new RouterOS\Request('/ip/arp/print');
$printRequest->setArgument('.proplist', '.id');
$printRequest->setQuery(RouterOS\Query::where('mac-address', '00:00:00:00:00:01'));

$idList = '';
foreach ($client->sendSync($printRequest)->getAllOfType(RouterOS\Response::TYPE_DATA) as $item) {
    $idList .= ',' . $item->getProperty('.id');
}
$idList = substr($idList, 1);
//$idList now contains a comma separated list of all IDs.

$removeRequest = new RouterOS\Request('/ip/arp/remove');
$removeRequest->setArgument('numbers', $idList);
$client->sendSync($removeRequest);
```
