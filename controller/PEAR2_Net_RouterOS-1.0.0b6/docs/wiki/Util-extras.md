# exec()
## Basic run
The Util class allows you to run a RouterOS script right from within PHP using the exec() method. Note that the script is run relative to the menu you're at, so you can move from one to the other with ease.

This is particularly useful when you need to execute commands that are either unavailable from the API protocol, or are otherwise buggy.

For example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

$util->exec(
    '
add address=192.168.88.100 mac-address=00:00:00:00:00:01 comment=customer_1
add address=192.168.88.101 mac-address=00:00:00:00:00:02 comment=customer_2
/tool
fetch url="http://example.com/?name=customer_1"
fetch url="http://example.com/?name=customer_2"
    '
);
```

## Supplying arguments
Running a RouterOS script from PHP with a literal source wouldn't be much useful if you didn't also have some variables in there that change based on some sort of user input. On the other hand, simply writing them out as part of the source can lead to code injection, which can be as catastrophic for RouterOS as an SQL injection is for an SQL database.

The exec() method also accepts a second argument where you can supply an associative array of values that will be made available to the script as local variables. The array key will be the name of the variable, and the array's value will be sanitized so as to not cause any sort of code injection.

The previous example could be written as:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/ip arp');

$source = '
add address="192.168.88.$ip" mac-address="00:00:00:00:00:$mac" comment=$name
/tool
fetch url="http://example.com/?name=$name"
';
$util->exec(
    $source,
    array(
        'ip' => 100,
        'mac' => '01',
        'name' => 'customer_1'
    )
);
$util->exec(
    $source,
    array(
        'ip' => 101,
        'mac' => '02',
        'name' => 'customer_2'
    )
);
```

Note also that PEAR2_Net_RouterOS is smart enough to convert native PHP types into equivalent RouterOS types. While this may not sound much interesting and/or useful for scalar values (string, boolean, (int/double)/number), consider fancier types like arrays, which are processed recursively for all of their values, and made available in the script as an array you can then ":pick" apart. Even more interestingly - PHP's DateInterval objects are converted into a RouterOS "time" typed value, and a PHP DateTime object is converted into a "time" object relative to the UNIX epoch time.

**IMPORANT NOTE: Watch out for PHP's double quotes and HEREDOC notation. They both accept PHP variables, and since both they and RouterOS variables are accessed with "$" in front of the name, you may end up writing a literal value when you meant to address a supplied variable. To be safe, make sure you're using either single quotes or NEWDOC notation, like the examples above.**

## Restricting script access
OK, so let's say that you use the above approach, and yet you're still somewhat paranoid about the values being escaped properly, or maybe your script is depending on untrusted data that's not supplied by PHP itself (e.g. a fetch of a script that's then being "/import"-ed). How do you make sure the script doesn't do anything too damaging? Lucky, MikroTik already have the solution, and PEAR2_Net_RouterOS supports it - you can simply set a policy on the script, which would contain the minimum permissions needed for it to work properly. In the case of a code injection or otherwise "normal" execution, the script will fail as soon as it tried to violate its granted permissions.

You can set permissions as the third argument of exec(). Without this argument, all permissions of the current RouterOS user are assumed. You can see the acceptable values by typing
```sh
/system script add policy=?
```
from a terminal, or better yet, see [this page in the MikroTik wiki](http://wiki.mikrotik.com/wiki/Manual:Router_AAA#Properties) about a detailed description of each policy.

Here's one example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);
$util->setMenu('/tool');

//If $_GET['url'] equals "http://example.com/geoip.rsc?filter=all"...
$url = $_GET['url'];

$source = '
fetch url=$db keep-result=yes dst-path=$filename
# Give the script time to be written onto disk
:delay 2
/import file=$filename
';
$util->exec(
    $source,
    array(
        'db' => $url,
        //... then this would be equal to "geoip.rsc"
        'filename' => pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME)
    ),
    'read,write'
);
```

With the policy being specified as "read,write", the script could do lots of damage, but at least it won't be able to read sensitive information like passwords for hotspots, wifi, and RouterOS (because that requires the "sensitive" permission) or change them (because that requires the "password" permission)... and it also won't be able to forcefully reboot your router (because that requires the "reboot" permission), which combined with the write permission can prove fatal if a startup script is made to again reboot the router... And it can't sniff the rest of your network (which requires the "test" and/or "sniff" permission, depending on the tools we're talking about). With all of those restrictions, you'll be able to easily recover form any damage that the remote script could possibly do, assuming you have backup of course, and no one else would know.

# File transfer
The Util class makes it easy to do transfer of files over the API protocol. Keep in mind however, that since the API protocol was not designed for that, the larger the files you're dealing with, the higher the chance you'll break RouterOS. Limit yourself to KBs of data, if possible.

## Reading files
Regardless of the menu you're at, you can use Util's fileGetContents() method to get the contents of a file at the "/file" menu. Once you have the contents, it's up to you to save them locally if you need to, or just use them.

An example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);

$filename = 'backup.rsc';
file_put_contents($filename, $util->fileGetContents($filename));
```

Note that due to the way this is implemented (a temporary script being created, that writes the contents into itself, with PHP then retrieving the script), the username you're logging in with needs to have writing permissions for reading files.

## Writing files
Regardless of the menu you're at, you can use Util's filePutContents() method to place a file in RouterOS' "/file" menu. The prototype is similar to that of PHP's own file_put_contents() - filename first, contents for it second. As a third argument however, you have a flag saying whether to replace the file. If false, writing will fail, and if true, the file will be overwritten. There's no append option, though you could manually do that by getting the contents first.

It's important to note that **this method is VERY VERY VERY slow**. It takes a little over 4 seconds per file, most of which are in sleep, waiting for RouterOS to write the data to disk - 2 for the initial file creation, another 2 for the content itself. If you want an efficient file transfer, use (T)FTP.

For the sake of example:
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2/Autoload.php';

$util = new RouterOS\Util(
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password')
);

$filename = 'backup.auto.rsc';
$util->filePutContents($filename, file_get_contents($filename));
```

# getCurrentTime()
Getting the current time out of the router into a form that can easily be manipulated in PHP, while also being accurate is a surprisingly non trivial process. So to make it easier, Util includes this method, which, as the same suggests, gets the current time from the router, and gives you a DateTime object you can then use in whatever way you'd like.