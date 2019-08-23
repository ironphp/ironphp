<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * @link
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console;

use Friday\Foundation\Application;

class Command
{
    /**
     * Argv Inputs from console parameters.
     *
     * @var
     */
    protected $argvInput;

    /**
     * Console instance.
     *
     * @var
     */
    protected static $console;

    /**
     * Default Command.
     *
     * @string
     */
    protected $default;

    /**
     * Create a new Command instance.
     *
     * @param \Friday\Foundation\Console $console
     *
     * @return void
     */
    public function __construct($console)
    {
        self::$console = $console;

        /*
        if(isset($this->short[$command])) {
            $command = $this->short[$command];
            if($command == 'help') {
                $options = (array)$tokens[0];
            }
            else {
                $options = $tokens;
            }
        }
        if($console->findCommand($command)) {
            if($command != 'help') {
                if(isset($this->short[$tokens[0]])) {
                    $option = $this->short[$tokens[0]];
                    if($option == 'help') {
                        $options = (array)$command;
                        $command = $option;
                    }
                    else {
                        $options = $tokens;
                    }
                }
                else {
                    $options = (array)$options[0];
                }
            }
            $this->execute($command, $tokens);
        }
        else {
            $this->output = $console->commandError("Command \"".$command."\" is not defined.");
        }
        */
        //$this->getOutput();
    }

    /**
     * Get instance of Console.
     *
     * @return \Friday\Foundation\Console
     */
    public function getConsole()
    {
        return self::$console;
    }

    /**
     * Get info of Console Application.
     *
     * @return string
     */
    public function getInfo()
    {
        return Colors::BG_BLACK.Colors::LIGHT_BLUE.str_repeat('-', 63).PHP_EOL.
        Colors::GREEN.'IronPHP'.Colors::WHITE.' Framework '.
        Colors::YELLOW.''.Application::VERSION.Colors::WHITE.' (env: '.Colors::YELLOW.(env('APP_ENV') === 'dev' ? 'development' : 'production').Colors::WHITE.', debug: '.Colors::YELLOW.(env('APP_DEBUG') ? 'true' : 'false').Colors::WHITE.')'.PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat('-', 63).PHP_EOL;
    }

    /**
     * Get welcome info of Console Application.
     *
     * @return string
     */
    public function getWelInfo()
    {
        return Colors::BG_BLACK.Colors::LIGHT_BLUE.str_repeat('-', 73).PHP_EOL.
        Colors::WHITE.'Welcome to '.Colors::GREEN.'IronPHP'.Colors::WHITE.' Framework '.
        Colors::YELLOW.''.Application::VERSION.Colors::WHITE.' (env: '.Colors::YELLOW.(env('APP_ENV') === 'dev' ? 'development' : 'production').Colors::WHITE.', debug: '.Colors::YELLOW.(env('APP_DEBUG') ? 'true' : 'false').Colors::WHITE.')'.PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat('-', 73).PHP_EOL;
    }

    /**
     * Execute Help command.
     *
     * @param string $command
     * @param  array   option
     *
     * @return string
     */
    public function executeHelp($command, $option = [])
    {
        return self::$console->executeHelp($command, $option);
    }
}
