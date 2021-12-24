#Roscon
The PHAR file is also an executable, that is an API protocol console. Running the PHAR file with any arguments will run the console. Also, installing PEAR2_Net_RouterOS with Pyrus, PEAR or Composer will give you an executable in your bin folder, called "roscon", which is that same console.

You can use the console to quickly test for connectivity, login and API protocol related issues, making sure that certain errors are ultimately due to RouterOS and/or configuration, and not due to a bug in PEAR2_Net_RouterOS or your code.

(Strictly speaking, the console is more of a [REPL](http://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop) application)

## Options and arguments
You can get a list of all command line options and arguments by either running the "roscon" executable without arguments, or running the PHAR with the "--help" option.

Here's a more nicely formatted version of what you'd see:

### Options

<table>
    <thead>
        <tr>
            <th>Option</th>
            <th>Short option</th>
            <th>Default</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><pre>--port</pre></td>
            <td>-p</td>
            <td></td>
            <td style="white-space:pre-line;">Port to connect to. Default is either 8728 or 8729, depending on whether an encryption is specified.</td>
        </tr>
        <tr>
            <td><pre>--cTimeout</pre></td>
            <td></td>
            <td></td>
            <td style="white-space:pre-line;">Time in seconds to wait for the connection to be estabilished. If "--timeout" is specified, its value will be used when this option is not specified.
                Defaults to PHP's default_socket_timeout ini option.</td>
        </tr>
        <tr>
            <td><pre>--enc</pre></td>
            <td></td>
            <td></td>
            <td style="white-space:pre-line;">Encryption to use, if at all. Currently, RouterOS supports only "TLS".
                (Default: "")</td>
        </tr>
        <tr>
            <td><pre>--ca</pre></td>
            <td></td>
            <td></td>
            <td style="white-space:pre-line;">Optional path to a file or folder to use for certification authority, when using encryption. Ignored when not using encryption or using ADH cipher.</td>
        </tr>
        <tr>
            <td><pre>--timeout</pre></td>
            <td>-t</td>
            <td></td>
            <td style="white-space:pre-line;">Time in seconds to wait when receiving. If this time passes without data awaiting, control is passed back for further input.
                (Default: 3)</td>
        </tr>
        <tr>
            <td><pre>--verbose</pre></td>
            <td>-v</td>
            <td></td>
            <td style="white-space:pre-line;">Turn on verbose output.</td>
        </tr>
        <tr>
            <td><pre>--colors</pre></td>
            <td></td>
            <td>auto</td>
            <td style="white-space:pre-line;">Choose whether to color output (requires PEAR2_Console_Color). Possible values:
                "auto" - color is always enabled, except on Windows, where ANSICON must be installed (detected via the ANSICON_VER environment variable).
                "yes"  - force colored output.
                "no"   - force no coloring of output.
                (Default: "auto")</td>
        </tr>
        <tr>
            <td><pre>--width</pre></td>
            <td>-w</td>
            <td>80</td>
            <td style="white-space:pre-line;">Width of console screen. Used in verbose mode to wrap output in this length.
                (Default: 80)</td>
        </tr>
        <tr>
            <td><pre>--command-mode</pre></td>
            <td></td>
            <td>s</td>
            <td style="white-space:pre-line;">Mode to send commands in. Can be one of:
                "w" - send every word as soon as it is entered
                "s" - wait for a sentence to be formed, and send all its words then
                "e" - wait for an empty sentence, and send all previous sentences then. You can send an empty sentence by sending two consecutive empty words.
                (Default: "s")</td>
        </tr>
        <tr>
            <td><pre>--reply-mode</pre></td>
            <td></td>
            <td>s</td>
            <td style="white-space:pre-line;">Mode to get replies in. Can be one of:
                "w" - after every send, try to get a word
                "s" - after every send, try to get a sentence
                "e" - after every send, try to get all sentences until a timeout.
                (Default: "s")</td>
        </tr>
        <tr>
            <td><pre>--multiline</pre></td>
            <td>-m</td>
            <td></td>
            <td style="white-space:pre-line;">Turn on multiline mode. Without this mode, every line of input is considered a word. With it, every line is a line within the word, and the end of the word is marked instead by an "end of text" character as the only character on a line. To write out such a character, you can use ALT+Numpad3. If you want to write this character as part of the word, you can write two such characters on a line.</td>
        </tr>
    </tbody>
</table>

### Arguments

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>hostname</td>
            <td>Yes</td>
            <td>Hostname of the RouterOS to connect to.</td>
        </tr>
        <tr>
            <td>username</td>
            <td>No</td>
            <td>Username to log in with. If left empty, no login will be performed.</td>
        </tr>
        <tr>
            <td>password</td>
            <td>No</td>
            <td>Password to log in with.</td>
        </tr>
    </tbody>
</table>

## Example command lines
1. Connecting to RouterOS at 192.168.88.1 without credentials over the default API port (8728):

    ```sh
    roscon "192.168.88.1"
    ```
    This mode allows you to diagnose issues occurring prior or during the login procedure itself or with commands that may otherwise be issued anonymously.

2. Same as above, but with username "admin" and no password:

    ```sh
    roscon "192.168.88.1" "admin"
    ```
3. Same as above, but with "password" being used as the password for "admin" (as all other examples in this wiki):

    ```sh
    roscon "192.168.88.1" "admin" "password"
    ```
4. Same as above, but on port 443 instead of port 8728:

    ```sh
    roscon -p 443 "192.168.88.1" "admin" "password"
    ```

## Flow
As mentioned in the beginning, the console is a REPL application, and as such, it follows that flow. In other words, after a connection is established (and perhaps after you're logged in):

1. You write an API protocol word.
2. At some point (defined by the ```--command-mode``` option), no more reading is done, and all collected input is sent to RouterOS. The console goes back to step 1 if that point is not yet reached.
3. The console waits for data up to the time defined by ```--timeout```, and once a word is received or the time is up, it prints on screen what's been received.
4. Up to a certain point (defined by the ```--reply-mode``` option), the console keeps repeating step 3.
5. After receiving is done, if the connection is still alive, things go back to step 1. Otherwise, connection is terminated, and the console exits.

### Example session
Using the command line

```sh
roscon -v "192.168.88.1" "admin" "password"
```

Here's the output from "/system/package/print" on RouterOS 6.19, followed by a "/quit" after its completion:

```
MODE |   LENGTH    |    LENGTH    |  CONTENTS
     |  (decoded)  |  (encoded)   | 
-----|-------------|--------------|--------------------------------------------
SEND |    <prompt> |     <prompt> | 
SEND |    <prompt> |     <prompt> | 
SENT |          21 |         0x15 | /system/package/print
SENT |           0 |         0x00 | 
RECV |           3 |         0x03 | !re
RECV |           7 |         0x07 | =.id=*1
RECV |          12 |         0x0C | =name=system
RECV |          13 |         0x0D | =version=6.19
RECV |          32 |         0x20 | =build-time=aug/26/2014 14:05:51
RECV |          11 |         0x0B | =scheduled=
RECV |          15 |         0x0F | =disabled=false
RECV |           0 |         0x00 | 
SEND |    <prompt> |     <prompt> | 
SENT |           0 |         0x00 | 
RECV |           5 |         0x05 | !done
RECV |           0 |         0x00 | 
SEND |    <prompt> |     <prompt> | 
SEND |    <prompt> |     <prompt> | 
SENT |           5 |         0x05 | /quit
SENT |           0 |         0x00 | 
RECV |           6 |         0x06 | !fatal
RECV |          29 |         0x1D | session terminated on request
RECV |           0 |         0x00 | 
NOTE |   Connection terminated    | 
```

All rows with "\<prompt\>" (mode "SEND") are the points where the console reads from STDIN (i.e. your input). If you're saving the output to a file (as was done to generate this example), you can see what was written as input in the "SENT" rows just below. Those, as well as the length, are only visible in verbose mode, which you can enable with the "-v" option, as shown above. Non-verbose mode looks more like [the examples from the API protocol specification](http://wiki.mikrotik.com/wiki/Manual:API#Command_examples) where no length is shown, and direction is only indicated with color (as is done here, if you have the needed requirements, described above in the ```--colors``` option).