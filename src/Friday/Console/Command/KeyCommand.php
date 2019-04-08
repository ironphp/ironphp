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

class KeyCommand extends Command
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
     * Execute Command.
     *
     * @return void
     */
    public function run()
    {
        $output = PHP_EOL.$this->key().Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }

    /**
     * Generate new key.
     *
     * @return void
     */
    public function key() {
        $set = $this->getConsole()->setKey();
        return $set ? Colors::GREEN."Application key set successfully." : Colors::RED."Error: Key is not set.";
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
        Colors::WHITE."  Set the application key".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Usage:".PHP_EOL.
        Colors::WHITE."  key".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Arguments:".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Options:".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Help:".PHP_EOL.
        Colors::WHITE."  The ".Colors::GREEN."key".Colors::WHITE." command sets the application key:".PHP_EOL.
        PHP_EOL.
        Colors::GREEN."    php jarvis key".PHP_EOL.
        PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }
}