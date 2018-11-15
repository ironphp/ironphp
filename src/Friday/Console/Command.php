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
     * Commands instance.
     *
     * @var
     */
    protected $commands;

    /**
     * Short Commands.
     *
     * @array
     */
    protected $short = ['-h' => 'help', '--help' => 'help', '-V' => 'version', '--version' => 'version'];

    /**
     * Create a new console command instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        $app = new \Friday\Foundation\Application(
            $basePath
        );

        $this->argvInput = ($_SERVER['argv'][0] === "jarvis") ? array_slice($_SERVER['argv'], 1) : [] ;

        $this->commands = new \Friday\Console\Commands($app);
        define('APP_INIT', microtime(true));
        
        // run commands
        if(count($this->argvInput)) {
            $command = $this->argvInput[0];
            if(isset($this->short[$command])) {
                $command = $this->short[$command];
            }
            if($this->findCommand($command)) {
                $this->commands->$command();
            }
            else {
                print "
".\Friday\Console\Colors::BG_RED."                                 
".\Friday\Console\Colors::WHITE ."  Command \"".$command."\" is not defined.  
".\Friday\Console\Colors::BG_RED."                                 \n";
                echo \Friday\Console\Colors::BG_BLACK.''.\Friday\Console\Colors::WHITE;
            }
        }
        else {
            $this->commands->list();
        }
        define('CMD_RUN', microtime(true));
    }

    /**
     * Find Command.
     *
     * @param  string  $command
     * @return bool
     */
    public function findCommand($command)
    {
        return method_exists($this->commands, $command);
    }
}
