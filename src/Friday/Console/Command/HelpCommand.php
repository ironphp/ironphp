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

class HelpCommand extends Command
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
        if(\count($this->option) === 0) {
            return $this->help();
        }
        else {
            return $this->executeHelp($this->option[0]);
        }
    }

    /**
     * Help for this Commands.
     *
     * @param  string  $command
     * @return void
     */
    public function help($command = 'help')
    {
        $output = $this->getInfo().
        Colors::YELLOW."Description:".PHP_EOL.
        Colors::WHITE."  Displays help for a command".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Usage:".PHP_EOL.
        Colors::WHITE."  help [options] [--] [<command_name>]".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Arguments:".PHP_EOL.
        Colors::GREEN."  command ".Colors::WHITE."\tThe command to execute".PHP_EOL.
        Colors::GREEN."  command_name ".Colors::WHITE."\tThe command name ".Colors::YELLOW."[default: \"help\"]".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Options:".PHP_EOL.
        Colors::GREEN."  -h, --help".Colors::WHITE."\t\tDisplay this help message".PHP_EOL.
        Colors::GREEN."  -V, --version".Colors::WHITE."\t\tDisplay this application version".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Help:".PHP_EOL.
        Colors::WHITE."  The ".Colors::GREEN."help".Colors::WHITE." command displays help for a given command:".PHP_EOL.
        PHP_EOL.
        Colors::GREEN."    php jarvis help".PHP_EOL.
        PHP_EOL.
        Colors::WHITE."  To display the list of available commands, please use the ".Colors::GREEN."list".Colors::WHITE." command.".PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }
}