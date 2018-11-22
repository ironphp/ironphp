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
        if(0 < \count($tokens)) {
            $command = $tokens[0];
            if(isset($this->short[$command])) {
                $command = $this->short[$command];
            }
            if($console->findCommand($command)) {
                $this->execute($command);
            }
            else {
                $this->output = $console->commandError("Command \"".$command."\" is not defined.");
            }
            //if(isset($this->short[$command])) {
            //    $command = $this->short[$command];
            //}
        }
        else {
            $command = $default;
            if($console->findCommand($command)) {
                $this->execute($command);
            }
            else {
                $this->output = $console->commandError("Command \"".$command."\" is not defined.");
            }
        }

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
     * @return string
     */
    public function execute($command)
    {
        $commandClass = "\\Friday\\Console\\Command\\".ucfirst($command)."Command";
        $cmd = new $commandClass();
        $this->output = $cmd->run();
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
}
