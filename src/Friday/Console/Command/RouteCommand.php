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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console\Command;

use Friday\Console\Colors;
use Friday\Console\Command;
use Friday\Foundation\Application;

class RouteCommand extends Command
{
    /**
     * Command options.
     *
     * @var array
     */
    private $option;

    /**
     * Create a List Commands instance.
     *
     * @param array  $option
     * @return void
     */
    public function __construct($option = [])
    {
        $this->option = $option;
    }

    /**
     * Execute Commands.
     *
     * @return void
     */
    public function run()
    {
        /*
        $host = '127.0.0.1';
        $port = '8000';
        if(isset($this->option[0])) {
            if($this->option[0] == 'help') {
                return $this->help();
            }
            if(strpos($this->option[0], '--host=') === 0) {
                $host = trim(str_replace('--host=', '', $this->option[0]));
            }
            elseif(strpos($this->option[0], '--port=') === 0) {
                $port = trim(str_replace('--port=', '', $this->option[0]));
            }
        }
        if(isset($this->option[1])) {
            if(strpos($this->option[1], '--host=') === 0) {
                $host = trim(str_replace('--host=', '', $this->option[1]));
            }
            elseif(strpos($this->option[1], '--port=') === 0) {
                $port = trim(str_replace('--port=', '', $this->option[1]));
            }
        }
        print $this->getWelInfo();
        $this->serve($host, $port);
        return;
        */
        $output = Colors::WHITE.Colors::BG_BLACK.
        $this->getWelInfo().
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }

    /**
     * Run app in development server.
     *
     * @param  string  $host
     * @param  string  $port
     * @param  string  $public_html
     * @return void
     */
    public function serve($host = '127.0.0.1', $port = '8000', $public_html = 'public')
    {
        print Colors::LIGHT_CYAN."Built-in development server started [/public]: ".Colors::YELLOW."<http://$host:$port>".Colors::WHITE.PHP_EOL.
        "You can exit with `CTRL+C`".PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        exec("php -S $host:$port -t $public_html");
    }

    /**
     * Help for this Commands.
     *
     * @return void
     */
    public function help()
    {
        $output = $this->getInfo().
        Colors::YELLOW."Description:".PHP_EOL.
        Colors::WHITE."  List all registered routes".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Usage:".PHP_EOL.
        Colors::WHITE."  route [options]".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Arguments:".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Options:".PHP_EOL.
        Colors::GREEN."  -r, --reverse".Colors::WHITE."\t\tReverse the ordering of the routes.".PHP_EOL.
        Colors::GREEN."  -h, --help".Colors::WHITE."\t\tDisplay this help message".PHP_EOL.
        Colors::GREEN."  -V, --version".Colors::WHITE."\t\tDisplay this application version".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Help:".PHP_EOL.
        Colors::WHITE."  The ".Colors::GREEN."route".Colors::WHITE." display list of registered routes:".PHP_EOL.
        PHP_EOL.
        Colors::GREEN."    php jarvis route".PHP_EOL.
        PHP_EOL.
        Colors::WHITE."  To display list in reverse order use --reverse or -r".PHP_EOL.
        PHP_EOL.
        Colors::GREEN."    php jarvis route --reverse".PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }
}