# What is Util?
Util is a class which wraps around a Client object to make frequent tasks easier. It doesn't give you full control however, which is why you may still find yourself using Client from time to time, even if you're using mostly Util.

# Basic setup
## New instance
Similarly to how you do it with Client. In fact, as already mentioned, you need to create a Client to wrap around, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(new RouterOS\Client('192.168.88.1', 'admin', 'password'));
//Use $util from here on
```

If you also need the raw power of Client, you may want to also save it into an additional variable, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
//Use $util or $client from here on
```

## Navigating around
Before executing a command with Util, you must first navigate to the menu you'll perform operations on. You do that with the setMenu() method. By default you're at the root menu "/". If, for example, you want to edit ARP items, you'd do:
```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
//Target entries in the "/ip arp" menu from here on
```

Notice that you can use shell syntax, as well as API syntax. In addition, you can also use relative shell paths, e.g.
```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');//We're now at "/ip arp"
$util->setMenu('.. addresses');//We're now at the "/ip addresses" menu.
//Target entries in the "/ip addresses'" menu from here on
```

# CRUD operations
Util has an add(), count(), getAll(), find(), get(), unsetValue(), set(), edit(), comment(), remove(), enable(), disable() and move() methods, and you can probably already figure out what each one of them does. The important thing to keep in mind that __in addition__ to accepting IDs to target, each of these methods can also accept numbers, just like in terminal. This is implemented ON TOP of the API protocol, which doesn't support this natively. This is in fact Util's main super power compared to a plain Client.

Let's look at some examples...
## add()
The add() method accepts an array of properties to assign to a new entry in the current menu. You can also supply multiple arguments, each of which will be treated as an entry to be added.

Here's the second example from the last tutorial, rewritten with Util in mind:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
$util->add(
    array(
        'address' => '192.168.88.100',
        'mac-address' => '00:00:00:00:00:01'
    ),
    array(
        'address' => '192.168.88.101',
        'mac-address' => '00:00:00:00:00:02'
    )
);
```

Note that add() returns the IDs of the new entries, so if you're interested in later targeting them, you may want to store their IDs.

## count()
The count() method returns the number of items in the current menu. Optionally, only those that match a Query.  See [this tutorial](Using-queries) for details on working with queries.

With this method, the Util class implements PHP's Countable interface, so if you want to get the count of all items, instead of calling the count() method, you can just give the object to PHP's count() function.

Example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

//With function
echo count($util) . "\n";

//With method call
echo $util->count() . "\n";

//Count only disabled ARP items
echo $util->count(RouterOS\Query::where('disabled', 'true')) . "\n";
```

## getAll()
The getAll() method is almost equivalent to issuing a "print" request in a menu - it gets all items at the current menu. "Almost", because unlike a "print" request with Client, this method automatically strips the !done reply, which normally signals the end of the request. This makes this method perfect for simply getting all items at a menu.

Additional arguments are accepted as an array, given in the first argument, and you can filter responses with a Query at the second argument.

Here's the very first example from Approaches with Client, written with getAll() instead:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

foreach ($util->getAll() as $item) {
    echo 'IP: ', $item->getProperty('address'),
         ' MAC: ', $item->getProperty('mac-address'),
         "\n";
}
```

## find()
The find() method is by far the most important method in the whole class, as it's what separates it form Client. You can specify zero or more arguments of entries you'll be targeting, and get their IDs in a comma separated list for use in all of the methods below (as well as plain Client use). Zero arguments will give you the IDs of all entries in the current menu, which is probably not much useful. What's more interesting is when you specify numbers, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

//Outputs something similar to "*4de,*16a", since we targeted two entries:
//the one in position 0 and position 1. 
echo $util->find(0, 1);
```

Note that most other methods below also accept at least one of these criteria, including numbers, so if you're targeting just one entry, you don't need to use this method directly. They'll call it automatically.

The find() method can also accept a Query object. The ID of any entry matching the query will be part of the result. See [this tutorial](Using-queries) for details on working with queries.

In addition to accepting queries and numbers, find() can also accepts callback as criteria, which can let you match entries fitting more complicated conditions. This can be particularly useful when you're targeting stuff that can't be easily matched by a Query, such as regular expression matches. Each callback receives an entry (a Response object) as an argument, and if it returns a "truthy" value (```true```, ```1```, etc.), the entry's ID will be included in the results.

Here's an example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
echo $util->find(
    function ($response) {
        //Matches any item with a comment that starts with two digits
        return preg_match('/^\d\d/', $response->getArgument('comment'));
    }
);
```

If there are no matches, an empty string will be returned. This is a valid value, as an empty string is interpreted by other functions as meaning "apply on nothing at all", as opposed to no argument at all which is instead read as "apply to everything".

## get()
In most menus, you just target an entry, and the property name you want to get, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

//echoes "192.168.88.1", assuming an otherwise empty ARP list
echo $util->get(0, 'address');
```

There are some menus on which there are no entries, but there are properties to get non the less, such as "/system identity" for example, where you have the "name" property. In those menus, you need to specify ```null``` as the first argument, e.g.:
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

You could theoretically also specify ```null``` to get the first entry in other menus, but this is not recommended, as PEAR2_Net_RouterOS will get ALL entries before showing you the first entry's property.

## set()
To alter properties of an existing entry, you can use the set() method.

To use the set() method, as a first argument you must specify criteria for the entry or entries you want to edit, and then as a second argument, an array with the modified properties as key names, and their respective values as array values. Naturally, other properties will remain unmodified.

As already mentioned with regards to find(), if you have just a single criteria, you can specify it directly, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
$util->set(
    0,
    array(
        'address' => '192.168.88.103'
    )
);
```

or if you want to modify more entries at once, you can specify the result of find() as the first argument, e.g.:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
$util->set(
    $util->find(
        0,
        function ($response) {
            //Matches any item with a comment that starts with two digits
            return preg_match('/^\d\d/', $response->getArgument('comment'));
        }
    ),
    array(
        'address' => '192.168.88.103'
    )
);
```

## unsetValue()
In some menus on RouterOS, there's a difference between unsetting a value and setting it to an empty string. This method allows you to unset a specified property from an entry. It works in exactly the same way as get(), except that instead of returning the value, it unsets it.

## edit()
Purely for convinience and brevity, the edit() method can set or unset a single property of matched item(s).

The first example with set() can be rewritten as the following:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
$util->edit(0, 'address', '192.168.88.103');
```

and to unset a property, you can specify NULL as the value.

## remove(), enable() and disable()
These methods work EXACTLY like find(), except that instead of returning the IDs of matching entries, they remove/enable/disable them, respectively. Yes, this means calling remove() without arguments will remove all entries in the current menu, so be careful with that one.

Here's a simple example for illustrative purposes:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');
$util->remove(0);
$util->disable(RouterOS\Query::where('comment', 'DISABLE ME'));
$util->enable(1);
```

## move()
The move() method is applicable only in menus where the order of entries has significance, such as in the firewall or queues. As a first argument, it accepts an entry or entries to be moved, and as a second argument, it accepts a single entry above which matched entries will be placed. Note that PEAR2_Net_RouterOS doesn't check if the menu you're at has a move command.

Example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/queue simple');
$util->move(2, 0);//Place the queue at position 2 above the queue at position 0

//Place the queues at positions 3 and 4 above the queue at position 0
//(the same one that was at position 2, before it was moved above)
$util->move($util->find(3, 4), 0);
```