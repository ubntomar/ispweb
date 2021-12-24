# The Script class
The Script class contains various methods related to composing scripts, and parsing RouterOS values.

## prepare()
The prepare() method is similar to [Util::exec()](/pear2/Net_RouterOS/wiki/Util-extras#exec), in that it is used to create a script with a source, followed by parameters in an array.

While Util::exec() runs the script, Script::prepare() merely generates a script source (as a temp stream). The resulting script can be specified as the value in Request::setArgument(), or an Util CRUD method. This can be useful if instead of executing the script right away, you want to insert it for later somewhere (e.g. in "/system scheduler" or "/system script").

Here's an example that adds a script which adds a log entry with the PHP version every minute:

```php
<?php
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/system scheduler')->add(
    array(
        'name' => 'logger',
        'interval' => '1m',
        'on-event' => RouterOS\Script::prepare(
            '/log info $phpver',
            array(
                'phpver' => phpversion()
            )
        )
    )
);
```

The exact rules for parameters are exactly the same as those for Util::exec(), as described [further down that same page](/pear2/Net_RouterOS/wiki/Util-extras#supplying-arguments).

## Other composition methods

If you want to merge several scripts into one, you can use the Script::append() method. It works similarly to Script::prepare(), except that it requires a pre-existing stream at the first argument (where the contents will be written to), and the stream's position is left at the end (i.e. you need to manually rewind() it when you're done).

If the parameter passing rules don't quite work for you, but you still want to reuse some parts, you can use the Script::escape\*() family of methods. Script::escapeValue() applies the full rules on a value, leaving you in control as to where exactly that value goes (f.e. as part of a larger expression, instead of a local variable).

## parseValue()
The Script class has methods not just for converting values from PHP to RouterOS, but also backwards - it can convert a RouterOS value into a PHP value, based on how RouterOS would interpret that value. In particular, consider a line in scripting like

```
:local variable VALUE
```

What will happen if you replace ```VALUE``` with something else? Like ```1d00:01:02```? Scripting would recognize that as a value of type "time". The Script::parseValue() method will in turn convert such a value into a DateInterval object. Similarly for arrays and scalar values (though that's probably less interesting).

Keep in mind that only constant values are supported. Expressions will be treated as part of whatever surrounds them (or an unquoted string, if not surrounded).

These conversions can be particularly useful when you're reading out data that RouterOS stores as such data type.

For example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);

$util->setMenu('/system resource');
$uptime = RouterOS\Script::parseValue($util->get(null, 'uptime'));

$now = new DateTime;

//Will output something akin to 'Running since: Sunday, 18 Aug 2013 14:03:01'
echo 'Running since: ' . $now->sub($uptime)->format(DateTime::COOKIE);
```

## Other parsing methods
If all the different conversion rules don't quite work for you, but you still want some parts, you can use the Script::parseValueTo\*() family of methods. Each expects the value to be in a certain form, and throws a ParserException if those expectations are not met, giving you greater control and awareness of issues with the input.