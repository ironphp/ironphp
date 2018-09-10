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
     * Application instance.
     *
     * @var \Friday\Foundation\Application
     */
    private $app;

    /**
     * Create a new Commands instance.
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get all command.
     *
     * @return void
     */
    public function help($command = null) {
        print "".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
".\Friday\Console\Colors::GREEN."IronPHP".\Friday\Console\Colors::WHITE." Framework ".\Friday\Console\Colors::YELLOW."".$this->app->version()."".\Friday\Console\Colors::LIGHT_BLUE."
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
        $url = 'https://repo.packagist.org/p/ironphp/ironphp.json';
        $packagistJson = @file_get_contents($url);
        if($packagistJson === true) {
            $packagistArray = json_decode($packagistJson,true);
            $packagistData = $packagistArray['packages']['ironphp/ironphp']['dev-master'];
            $time = $packagistData['time'];
            $version = $packagistData['version']; //dev-master
            $branchAlias = $packagistData['extra']['branch-alias']['dev-master']; // 0.0.1-dev
            date_default_timezone_set('Asia/Kolkata');
            $parsedTime = date_parse($time);
            $timeStamp = mktime($parsedTime['hour'], $parsedTime['minute'], $parsedTime['second'], $parsedTime['month'], $parsedTime['day'], $parsedTime['year']);
        }
        $installData = $this->app->getIntallTime();

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
IronPHP ".\Friday\Console\Colors::WHITE."Framework ".\Friday\Console\Colors::BROWN.$this->app->version();

        if($packagistJson === true) {
            echo \Friday\Console\Colors::WHITE." ".str_replace('T', ' ', substr(date(DATE_ATOM, $timeStamp), 0, 19));
            echo \Friday\Console\Colors::GREEN."\nChecking updates... ".\Friday\Console\Colors::WHITE;
            if($timeStamp > $installData->time) {
                if($branchAlias != $this->app->version()) {
                    print "Package have an update ".$branchAlias;
                }
                else {
                    print "Package have an update ".$version;
                }
            }
            else {
                print "There is no update.";
            }
        }
        else {
            echo \Friday\Console\Colors::GREEN."\nChecking updates... ".\Friday\Console\Colors::WHITE." No internet!";
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
Welcome to ".\Friday\Console\Colors::GREEN."IronPHP".\Friday\Console\Colors::WHITE." Framework ".\Friday\Console\Colors::YELLOW."".$this->app->version().\Friday\Console\Colors::WHITE." Console".\Friday\Console\Colors::LIGHT_BLUE."
---------------------------------------------------------------
".\Friday\Console\Colors::LIGHT_CYAN."Built-in development server started: ".\Friday\Console\Colors::YELLOW."<http://localhost:$port>".\Friday\Console\Colors::WHITE."
You can exit with `CTRL-C`\n";
        echo exec("php -S localhost:$port", $output);
        print_r($output);
    }
}