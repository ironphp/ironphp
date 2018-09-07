<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       IronPHP
 * @copyright     Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 * @link          
 * @since         0.0.1
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console;

class Commands
{

    /**
     * Create a new Commands instance.
     *
     * @return void
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Get all command.
     *
     * @return void
     */
    public function help($command = null) {
        print "".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
".\Friday\Console\Colors::GREEN."IronPHP".\Friday\Console\Colors::WHITE." Framework ".\Friday\Console\Colors::YELLOW."".$this->version."".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
".\Friday\Console\Colors::YELLOW."Usage:
".\Friday\Console\Colors::WHITE."  command [options] [arguments]\n
".\Friday\Console\Colors::YELLOW."Options:
".\Friday\Console\Colors::GREEN."  -h, --help".\Friday\Console\Colors::WHITE."\t\tDisplay this help message\n
".\Friday\Console\Colors::GREEN."Available commands:
".\Friday\Console\Colors::GREEN."  serve".\Friday\Console\Colors::WHITE."\t\t- Serve the application on the PHP development server
".\Friday\Console\Colors::GREEN."  version".\Friday\Console\Colors::WHITE."\t- Display Version
".\Friday\Console\Colors::GREEN."  help".\Friday\Console\Colors::WHITE."\t\t- Displays help for a command\n";
        echo \Friday\Console\Colors::BG_BLACK.''.\Friday\Console\Colors::WHITE;
    }

    /**
     * Display Version.
     *
     * @return void
     */
    public function version() {
        $packagistJson = file_get_contents('https://repo.packagist.org/p/ironphp/ironphp.json');
        $packagistArray = json_decode($packagistJson,true);
        $time = $packagistArray['packages']['ironphp/ironphp']['dev-master']['time'];
        date_default_timezone_set('Asia/Kolkata');
        $parsedTime = date_parse($time);
        $timestamp = mktime($parsedTime['hour'], $parsedTime['minute'], $parsedTime['second'], $parsedTime['month'], $parsedTime['day'], $parsedTime['year']);

        echo \Friday\Console\Colors::RED.''.\Friday\Console\Colors::BG_BLACK;
        print "
_________  _______    ______   _____    ___  _______   ___   ___  _______
|       |  | ___  \  /      \  |    \   | |  |  __  \  | |   | |  |  __  \
```| |```  | |  | |  | /``\ |  | |\  \  | |  | |  |  | | |___| |  | |  |  |
   | |     | ``` /   | |  | |  | | \  \ | |  | |``  /  |       |  | |``  /
___| |___  | |`\ \   | \  / |  | |  \  \| |  | |````   | |```| |  | |````
|       |  | |  \ \  \  ``  /  | |   \    |  | |       | |   | |  | |
`````````  ```   ```  ``````    ``    `````  ```       ```   ```  ```   
";
echo \Friday\Console\Colors::GREEN."
IronPHP ".\Friday\Console\Colors::WHITE."Framework ".\Friday\Console\Colors::BROWN.$this->version." ".\Friday\Console\Colors::WHITE.str_replace('T', ' ', substr(date(DATE_ATOM, $timestamp), 0, 19))."
".\Friday\Console\Colors::GREEN."Checking updates... ".\Friday\Console\Colors::WHITE;
        if($timestamp > time()) {
            print "Package have an update";
        }
        else {
            print "There is no update.";
        }
        echo \Friday\Console\Colors::BG_BLACK.''.\Friday\Console\Colors::WHITE;
    }

    /**
     * Run app in development server.
     *
     * @return void
     */
    public function serve($port = '8000') {
        print "".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
Welcome to ".\Friday\Console\Colors::GREEN."IronPHP".\Friday\Console\Colors::WHITE." Framework ".\Friday\Console\Colors::YELLOW."".$this->version.\Friday\Console\Colors::WHITE." Console".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
".\Friday\Console\Colors::LIGHT_CYAN."Built-in development server started: ".\Friday\Console\Colors::YELLOW."<http://localhost:$port>".\Friday\Console\Colors::WHITE."
You can exit with `CTRL-C`\n";
        echo exec("php -S localhost:$port", $output);
        print_r($output);
    }
}