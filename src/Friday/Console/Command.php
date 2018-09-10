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
        
        // get all commands
        $this->commands = new \Friday\Console\Commands($app);
        
        // run commands
        if(count($this->argvInput)) {
            $command = $this->argvInput[0];
            if($this->findCommand($command)) {
                $this->commands->$command();
            }
            else {
                print "Command not found.\n";
                $this->commands->help();
            }
        }
        else {
            $this->commands->help();
        }
    }

    /**
     * Find aCommand command.
     *
     * @return bool
     */
    public function findCommand($command) {
        return method_exists($this->commands, $command);
    }
}
