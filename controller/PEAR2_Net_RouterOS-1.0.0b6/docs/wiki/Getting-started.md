# Getting started
## Introduction
RouterOS is the flag product of the company [MikroTik](http://mikrotik.com) and is a powerful router software. One of its many abilities is to allow control over it via an API. This package provides a client for [that API](http://wiki.mikrotik.com/wiki/Manual:API), in turn allowing you to use PHP to control RouterOS hosts.
## Requirements
The requirements to watch out for are:
* PHP 5.3.0 or later. 
* A host with RouterOS v3 or later. 
* Enabled API service on the RouterOS host.
* Enabled **outgoing** connections with PHP's [stream_socket_client()](http://php.net/stream_socket_client) (check your OS' firewall)
* Enabled **incoming** connections to the API port in the firewall of RouterOS (check any rules in the "input" chain).

Other requirements are not a problem in most scenarios. For reference, they are:
* The PCRE and SPL extensions (compiled into PHP by default)
* [PEAR2_Net_Transmitter](http://pear2.github.com/Net_Transmitter/) (bundled in the archive; installed automatically by Pyrus and Composer)
* [optional] The iconv extension (compiled into PHP by default; required only if you want to use [automatic charset conversion](Optional-features#wiki-automatic-charset-conversion))
* [optional] The OpenSSL extension (bundled with PHP by default; needs to be enabled in php.ini or during compilation; required only if you want to use [encrypted connections](Optional-features#wiki-encrypted-connections))
* [optional] [PEAR2_Cache_SHM](http://pear2.github.com/Cache_SHM/) (bundled in the archive; needed only if you use [persistent connections](Optional-features#wiki-persistent-connections))
* [optional] [PEAR2_Console_CommandLine](https://github.com/pear2/Console_CommandLine) (bundled in the archive; needed only when using the [API console](Roscon))
* [optional] [PEAR2_Console_Color](https://github.com/pear2/Console_Color) (bundled in the archive; needed only if you'd like to have colors in the API console)
* [optional] A [PSR-0](http://www.php-fig.org/psr/psr-0/) or [PSR-4](http://www.php-fig.org/psr/psr-4/) compliant autoloader (highly recommended; [PEAR2_Autoload](http://pear2.php.net/PEAR2_Autoload) is one PSR-0 autoloader that is bundled in the archive)

### Notes
* The API service in RouterOS is disabled by default in versions prior to 6.0. To enable it, you need to execute
    ```sh
/ip service set numbers="api" address="" disabled="no"
    ```
    from a RouterOS terminal. The "address" argument in the command above allows you to limit access to this service only to certain client IP addresses. For security's sake, it's better that you limit connections only to the IP address(es) of the server(s) from which PHP will access RouterOS. An empty value will allow anyone to use the API (as long as they can login).

* Many shared web hosts choose to disable stream_socket_client(), and it's close relative fsockopen() as well. When they don't disable them, they often render them useless by forbidding outgoing connections with the server's firewall. A frequently possible workaround is to use the API service on a different, more popular port, such as 21, 80, 443, or something similar. If even that doesn't work, you need to contact your host. If you're on your own server, and fail to connect, configure your server's firewall so that it enables PHP to make outgoing connections (at least to the ip:port combo of where your router uses the API service). Depending on how you run PHP as:
<table>
    <thead>
        <tr>
            <th rowspan="2">PHP running as (SAPI)</th>
            <th rowspan="2">Executable folder</th>
            <th colspan="2">Executable file to whitelist</th>
        </tr>
        <tr>
            <th>UNIX</th>
            <th>Windows</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Apache module</td>
            <td>Apache's "bin" folder</td>
            <td>httpd</td>
            <td>httpd.exe</td>
        </tr>
        <tr>
            <td>(F)CGI</td>
            <td>PHP's folder</td>
            <td>php-cgi</td>
            <td>php-cgi.exe</td>
        </tr>
        <tr>
            <td>CLI</td>
            <td>PHP's folder</td>
            <td>php</td>
            <td>php.exe</td>
        </tr>
    </tbody>
</table>
    - On Windows, even if you haven't installed a 3rd party firewall program, you certainly have Windows' firewall. One way you can access it (in Windows Vista and later) is by pressing Win+R, then type ```wf.msc``` in the input box, and press OK. Add the appropriate outgoing connection rule from there, or consider disabling it entirely if you use a 3rd party firewall.
    - Many Linux distributions include [SELinux](http://selinuxproject.org/) as a firewall solution. If PHP is running as an Apache module, then you can allow Apache to make outgoing connections with the command:

        ```sh
setsebool -P httpd_can_network_connect 1
        ```
        See the SELinux documentation for details.
    - Linux distributions that don't include SELinux typically use [iptables](http://www.netfilter.org/projects/iptables/) instead. It acts similarly to RouterOS' firewall, in that it can (apparently?) enable/disable only based on packet contents, and not based on application. You can enable outgoing connections to port 8728 (from any interface, to any IP) with the command:

        ```sh
iptables -A OUTPUT -p tcp --dport 8728 -j ACCEPT
        ```
        See [iptables' documentation](http://www.netfilter.org/documentation/) for details.

* Many RouterBOARD devices come, by default, with a rule in "/ip firewall filter" that drops any incoming connections to the router, coming from the WAN interface. If your web server is outside the LAN (e.g. a web host, as opposed to your own web server inside your network), you must explicitly whitelist RouterOS' API port or (not recommended) disable that rule entirely. You can whitelist the API port for all interfaces with the following command:
    ```sh
/ip firewall filter add \
        place-before=[:pick [find where chain="input"] 0] \
        chain="input" action="accept" \
        protocol="tcp" dst-port=[/ip service get "api" "port"]
    ```


## Installation
### Direct PHAR usage
If you download the ".phar" archive, you can just include the archive, and be ready to go.

To keep the names of the classes short, you may also add a "use" statement, so for example:

```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2_Net_RouterOS-1.0.0b6.phar';
//Use any PEAR2_Net_RouterOS class from here on
```

(See [this page from the PHP manual](http://php.net/manual/en/language.namespaces.importing.php) if you want to learn more about namespaces and aliasing in general)

In virtually all places of this documentation, the line

```php
require_once 'PEAR2/Autoload.php';
```

can be replaced with the path to the PHAR file, e.g.

```php
require_once 'PEAR2_Net_RouterOS-1.0.0b6.phar';
```

and then everything should work the same.

### Installation with [Pyrus/PEAR2](http://pear2.php.net/)
Assuming you have [installed Pyrus](http://pear.php.net/manual/en/installationpyrus.introduction.php), you can install PEAR2_Net_RouterOS from the PEAR2 channel with just

```sh
php pyrus.phar install PEAR2_Net_RouterOS-beta
```

or, if you want to also get the optional dependencies (see above), you can use

```sh
php pyrus.phar install -o PEAR2_Net_RouterOS-alpha
```

You might notice that the version number of PEAR2_Net_RouterOS suggests it's a beta, and yet we use "-alpha" in the command above. Well, yes, PEAR2_Net_RouterOS is a beta, but it has optional dependencies which are in an alpha state. To avoid getting errors, you need to use "-alpha" until those packages reach beta.

__Note also that this package is a "beta" according to the [PEAR2 version standard](https://wiki.php.net/pear/rfc/pear2_versioning_standard_revision), which essentially means that it works, it's documented, has large code coverage (100% in this case), but future versions of it are still a potential subject to breaking API changes (e.g. the next release could rename methods, add/remove/rename classes, swap arguments, etc.) as long as those changes are still working, documented and covered by tests. In other words, the "beta" does not refer to error prone-ness of this version, but to "how likely is it that your code will break when *upgrading* this package".__

If you've decided to not use the PEAR2 channel, but instead install directly from the archive distributed at the project page, you can use

```sh
php pyrus.phar install /path/to/downloaded/archive.tgz
```

If you haven't installed PEAR_Net_Transmitter previously, Pyrus will install the one at the PEAR2 channel (not the bundled version, although the two are equivalent at the time of this writing).

### Installation with [PEAR](http://pear.php.net/)
Like most PEAR2 packages, PEAR2_Net_RouterOS is compatible with the [PEAR installer](http://pear.php.net/manual/en/installation.getting.php). However, since the "pear2" channel is not its default channel, you need to be more explicit (at least the first time around):

```sh
pear install pear2.php.net/PEAR2_Net_RouterOS-beta
```

or

```sh
pear install -a pear2.php.net/PEAR2_Net_RouterOS-alpha
```

to install optional dependencies as well.

### Installation with [Composer](http://getcomposer.org/)
This package is [available from packagist.org](https://packagist.org/packages/pear2/net_routeros), so all you have to do is to go to your project's directory (where your composer.json is), and run
```
composer require pear2/net_routeros:*@beta
```

Due to the way composer works, you need to add each optional dependency manually. So for example, if you want to use persistent connections, you'd also need to execute
```
composer require pear2/cache_shm:*@alpha
```

and if you want to use the API console
```
composer require pear2/console_commandline:*@alpha
composer require pear2/console_color
```

In virtually all places of this documentation, the line

```php
require_once 'PEAR2/Autoload.php';
```

can be replaced with the autoloader generated by Composer, e.g.

```php
require_once 'vendor/autoload.php';
```

and then everything should work the same.

### Manual installation
Instead of using the PEAR(2) installer or Composer, you can just download a packaged archive, and extract its contents. To emulate the PEAR(2) installer, you can place the files from the "src" folder at a folder that's within your include_path. The packaged archive includes a version of PEAR2_Net_Transmitter (as well as all optional dependencies), so there's nothing to worry about beyond extracting the archive, and including the bundled 'PEAR2/Autoload.php' file.

### Installation from the repository (with [Git](http://git-scm.com/))
If you want to get the "bleeding edge", unpackaged version of PEAR2_Net_RouterOS, you'll need to have Git. Once you have it, create a folder to place the package and its dependencies in, navigate to it from the command line, and execute the following commands:

```sh
git clone https://github.com/pear2/Net_RouterOS.git Net_RouterOS.git
git clone https://github.com/pear2/Net_Transmitter.git Net_Transmitter.git
git clone https://github.com/pear2/Cache_SHM.git Cache_SHM.git
```

Once you've cloned the repositories, switch to the "develop" branch on each of them. The "master" branch contains the latest *released* versions, while the "develop" branch contains the *unreleased* versions.

__Note: If you plan to contribute to this project, please use GitHub's "fork" feature instead, and then apply "git clone" on *it*, instead of the original repository. This will then allow you to easily make pull requests from your fork. Needless to say that pull requests should be based on the "develop" branch.__

## Troubleshooting
If the package doesn't work, you can download the "phar" file (maybe rename it from ".phar" to ".php"), and run it in your browser or command line.

When you do that, you should see the version of the package, along with some messages indicating if you're missing any of the requirements. If all requirements are present, you'll see a message suggesting you use the PHAR itself as a [console](Roscon). Try doing that, and see if any error messages show up. If all is OK, the console will start with no error messages on screen, and you should be able to start typing input. Otherwise, you'll see whether the problem is at login or at connecting, and see the exact error message from the OS, which should hopefully make it easier to see where the problem is.

If the console works, but a web page fails, then there are two possible problems left. Either the PHP in the web server is a different one (a different version and/or configured by a separate php.ini), or a different executable needs to be whitelisted in the OS' firewall.

To check the first issue, you can rename the PHAR file to ".php", and run it from a web browser. The output should be the same as from the command line.

To check the second issue, try to run the following test code from a web browser (replacing the router details accordingly of course):
```php
<?php
use PEAR2\Net\RouterOS;

require_once 'PEAR2_Net_RouterOS-1.0.0b6.phar';

try {
    $client = new RouterOS\Client('192.168.88.1', 'admin', 'password');
    echo 'OK';
} catch (Exception $e) {
    die($e);
}
```

If you don't see "OK", and yet the console works, the most likely issue is the web server's firewall. See [the Notes section above](#notes).

## Further information
The [rest of this documentation](../wiki) contains more tutorials and examples on how to use this package. If you have trouble doing a certain thing with the RouterOS API, the best place to ask for help is [MikroTik's forum on scripting](http://forum.mikrotik.com/viewforum.php?f=9). If you believe you've found a bug in this package or would like to have a certain feature, don't hesitate to [submit an issue](../issues/new) for it.