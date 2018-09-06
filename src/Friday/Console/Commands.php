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
        print "
---------------------------------------------------------------
IronPHP Framework ".$this->version."
---------------------------------------------------------------
Usage:
  command [options] [arguments]\n
Options:
  -h, --help\t\tDisplay this help message\n
Available commands:
  serve\t\t- Serve the application on the PHP development server
  version\t- Display Version
  help\t\t- Displays help for a command\n";
    }

    /**
     * Display Version.
     *
     * @return void
     */
    public function version() {
        print "IronPHP Framework ".$this->version."\n";
    }

    /**
     * Run app in development server.
     *
     * @return void
     */
    public function serve($port = '8000') {
        print "---------------------------------------------------------------
Welcome to IronPHP ".$this->version." Console
---------------------------------------------------------------
Built-in development server started: <http://localhost:$port>
You can exit with `CTRL-C`\n";
        echo exec("php -S localhost:$port", $output);
        print_r($output);
    }
}