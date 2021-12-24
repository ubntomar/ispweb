# Using queries
A brief guide to using queries

## Commands handling queries
Queries are a RouterOS API specific construct that allows you to limit the results returned for a request. It's an analog for the "where" argument on the command line.

Currently, the "print" command is the only one in RouterOS that handles queries, since version 3.21. PEAR2_Net_RouterOS doesn't check whether the command handles queries, so if future versions of RouterOS have other such commands, you can use queries with them right away.

## Setting a query
To set a query for a request, you need to either set it as a second argument to a Request's constructor or use the Request::setQuery() method. If later in the script you want to remove the query, you can pass NULL to the Request::setQuery() method. The rest of the examples in this tutorial will assume a script similar to the following, where the $query variable is defined separately:

```php
<?php
use PEAR2\Net\RouterOS;

include_once 'PEAR2/Autoload.php';
$client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
 
$request = new RouterOS\Request('/ip/arp/print');
 
//Define $query here
 
$request->setQuery($query);
$responses = $client->sendSync($request);
 
foreach ($responses as $response) {
    foreach ($response as $name => $value) {
        echo "{$name}: {$value}\n";
    }
    echo "====\n";
}
```

## A simple query
You can create a query by calling the static Query::where() method, along with the first criteria of the query. For example, if you wanted to limit results to the entry about 192.168.88.10, you can use a query like:

```php
$query = RouterOS\Query::where('address', '192.168.88.10');
```

Using the optional third parameter, you can specify exactly what do you want to do with the value. Possible values are the Query::OP_* constants. For example, if you wanted to get all addresses greather than 192.168.88.10, you can use:

```php
$query = RouterOS\Query::where('address', '192.168.88.10', RouterOS\Query::OP_GT);
```

## Chaining conditions
The Query class uses a "fluent" interface, i.e. it always returns the query object itself, similarly to how [jQuery](http://jquery.com) and [Zend\Db\Sql\Select](https://docs.zendframework.com/zend-db/sql/#select) do it. Thanks to that, you can chain conditions right when defining the $query variable (though you can also alter it later). For example, if you wanted to get all addresses greather than or equal to 192.168.88.10, you can do:

```php
$query = RouterOS\Query::where('address', '192.168.88.10', RouterOS\Query::OP_GT)
    ->orWhere('address', '192.168.88.10');
```

Note that each next condition applies over the whole of what came before it. You can think of it as having the whole expression on a command line surrounded with braces before the next step. e.g.

```php
$query = RouterOS\Query::where('address', '192.168.88.10', RouterOS\Query::OP_GT)
    ->orWhere('address', '192.168.88.10')
    ->not()
    ->andWhere('interface', 'bridge-local');
```

is the same as the command line

```
where (!((address>"192.168.88.10") || address="192.168.88.10") && interface="bridge-local")
```

while

```php
$query = RouterOS\Query::where('address', '192.168.88.10', RouterOS\Query::OP_GT)
    ->andWhere('interface', 'bridge-local')
    ->not()
    ->orWhere('address', '192.168.88.10');
```

is the same as the command line

```
where (!((address>"192.168.88.10") && interface="bridge-local") || address="192.168.88.10")
```

## Limiting returned properties
The query works a little like the "WHERE" clause in an SQL statement - it limits the amount of responses returned (which can be thought of as a "record" in DB terms) - but it doesn't limit the properties of each response (which can be thought of as "fields" in DB terms).

To do that, you need to set an API specific argument called ".proplist". The value is a comma separated list of properties to be listed in the responses. For example, if you were only interested in the MAC addresses, you can do:

```php
$request->setArgument('.proplist', 'mac-address');
```

or if you wanted MAC addresses and comments

```php
$request->setArgument('.proplist', 'mac-address,comment');
```
