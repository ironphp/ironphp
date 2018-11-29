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
    static protected $console;

    /**
     * Short Commands.
     *
     * @array
     */
    protected $short = ['-h' => 'help', '--help' => 'help', '-V' => 'version', '--version' => 'version'];

    /**
     * Default Command.
     *
     * @string
     */
    protected $default;

    /**
     * Output to display.
     *
     * @string
     */
    protected $output;

    /**
     * Create a new Command instance.
     *
     * @param \Friday\Foundation\Console
     * @return void
     */
    public function __construct($console)
    {
        self::$console = $console;
        $tokens = $console->getToken();
        $default = 'list';
        # Run commands
        switch(\count($tokens)) {
            # php jarvis
            case 0:
                $command = $default;
                if($console->findCommand($command)) {
                    $this->execute($command);
                }
                else {
                    $this->output = $console->commandError("Command \"".$command."\" is not defined.");
                }
                break;

            # php jarvis cmd
            case 1:
                $command = $tokens[0];
                if($console->findCommand($command)) {
                    $this->execute($command);
                }
                elseif($command[0] == '-') {
                    if(isset($this->short[$command])) {
                        $command = $this->short[$command];
                        if($console->findCommand($command)) {
                            $this->execute($command);
                        }
                        else {
                            $this->output = $console->commandError("Option \"".$tokens[0]."\" is not defined.");
                        }
                    }
                    else {
                        $this->output = $console->commandError("Option \"".$tokens[0]."\" is not defined.");
                    }
                }
                else {
                    $this->output = $console->commandError("Command \"".$command."\" is not defined.");
                }
                break;

            # php jarvis cmd arg ...
            default:
                $command = $tokens[0];
                array_shift($tokens);
                if($console->findCommand($command)) {
                    $opt = $tokens[0];
                    if(isset($this->short[$opt])) {
                        $cmd = $this->short[$opt];
                        # php jarvis cmd help ...
                        if($cmd == 'help') {
                            $tokens[0] = $command;
                            $this->execute($cmd, $tokens);
                        }
                        # php jarvis cmd -/--opt ...
                        else {
                            array_shift($tokens);
                            $this->execute($command, $tokens);
                        }
                    }
                    # php jarvis cmd opt ...
                    else {
                        $this->execute($command, $tokens);
                    }
                }
                # php jarvis -/--opt opt ...
                elseif($command[0] == '-') {
                    if(isset($this->short[$command])) {
                        $command = $this->short[$command];
                        if($console->findCommand($command)) {
                            $this->execute($command, $tokens);
                        }
                        else {
                            $this->output = $console->commandError("Option \"".$tokens[0]."\" is not defined.");
                        }
                    }
                    else {
                        $this->output = $console->commandError("Option \"".$tokens[0]."\" is not defined.");
                    }
                }
                else {
                    $this->output = $console->commandError("Command \"".$command."\" is not defined.");
                }
                break;
        }
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
        $this->getOutput();
        define('CMD_RUN', microtime(true));
    }

    /**
     * Display Output of command execution.
     *
     * @return string
     */
    public function getOutput()
    {
        print($this->output);
    }

    /**
     * Execute command.
     *
     * @param  string  $command
     * @param  array   option
     * @return void
     */
    public function execute($command, $option = [])
    {
        $commandClass = "\\Friday\\Console\\Command\\".ucfirst($command)."Command";
        $cmd = new $commandClass($option);
        $this->output = $cmd->run();
    }

    /**
     * Execute Help command.
     *
     * @param  string  $command
     * @param  array   option
     * @return string
     */
    public function executeHelp($command, $option = [])
    {
        $commandClass = "\\Friday\\Console\\Command\\".ucfirst($command)."Command";
        $cmd = new $commandClass($option);
        return $cmd->help();
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
        return Colors::LIGHT_BLUE.str_repeat("-", 73).PHP_EOL.
        Colors::GREEN."IronPHP".Colors::WHITE." Framework ".
        Colors::YELLOW."".Application::VERSION.Colors::WHITE." (env: ".Colors::YELLOW.(env('APP_ENV') === 'dev' ? 'development' : 'production').Colors::WHITE.", debug: ".Colors::YELLOW.(env('APP_DEBUG') ? 'true' : 'false').Colors::WHITE.")".PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat("-", 73).PHP_EOL;
    }

    /**
     * Get welcome info of Console Application.
     *
     * @return string
     */
    public function getWelInfo()
    {
        return Colors::LIGHT_BLUE.str_repeat("-", 73).PHP_EOL.
        Colors::WHITE."Welcome to ".Colors::GREEN."IronPHP".Colors::WHITE." Framework ".
        Colors::YELLOW."".Application::VERSION.Colors::WHITE." (env: ".Colors::YELLOW.(env('APP_ENV') === 'dev' ? 'development' : 'production').Colors::WHITE.", debug: ".Colors::YELLOW.(env('APP_DEBUG') ? 'true' : 'false').Colors::WHITE.")".PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat("-", 73).PHP_EOL;
    }
}
