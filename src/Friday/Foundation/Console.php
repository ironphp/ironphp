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

namespace Friday\Foundation;

use Friday\Console\Colors;
use Friday\Console\Command;

class Console extends Application
{
    /**
     * Instanse of Session.
     *
     * @var \Friday\Helper\Session
     */
    public $session;

    /**
     * Instanse of Cookie.
     *
     * @var \Friday\Helper\Cookie
     */
    public $cookie;

    /**
     * Command and arguments from argv.
     *
     * @var array
     */
    private $tokens;

    /**
     * Command instance.
     *
     * @var \Friday\Console\Command
     */
    protected $command;

    /**
     * Short Commands.
     *
     * @array
     */
    protected $short = ['-h' => 'help', '--help' => 'help', '-V' => 'version', '--version' => 'version'];

    /**
     * Output to display.
     *
     * @string
     */
    protected $output;

    /**
     * Create a new Friday application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        $tokens = $_SERVER['argv'];

		// Strip the application name
        array_shift($tokens);

        $this->tokens = $tokens;

        define('APP_INIT', microtime(true));

        $default = 'list';

        // Run commands
        switch (\count($tokens)) {
            // php jarvis
            case 0:
                $command = $default;
                $this->findExecute($command);
                break;

            // php jarvis cmd
            case 1:
                $command = $tokens[0];
                if ($this->findCommand($command)) {
                    $this->execute($command);
                } elseif ($command[0] == '-') {
                    if (isset($this->short[$command])) {
                        $command = $this->short[$command];
                        $this->findExecute($command, $tokens[0]);
                    } else {
                        $this->output = $this->commandError('Option "'.$tokens[0].'" is not defined.');
                    }
                } else {
                    $this->output = $this->commandError('Command "'.$command.'" is not defined.');
                }
                break;

            // php jarvis cmd arg ...
            default:
                $command = $tokens[0];
                array_shift($tokens);
                if ($this->findCommand($command)) {
                    $opt = $tokens[0];
                    if (isset($this->short[$opt])) {
                        $cmd = $this->short[$opt];
                        // php jarvis cmd help ...
                        if ($cmd == 'help') {
                            $tokens[0] = $command;
                            $this->execute($cmd, $tokens);
                        }
                        // php jarvis cmd -/--opt ...
                        else {
                            array_shift($tokens);
                            $this->execute($command, $tokens);
                        }
                    }
                    // php jarvis cmd opt ...
                    else {
                        $this->execute($command, $tokens);
                    }
                }
                // php jarvis -/--opt opt ...
                elseif ($command[0] == '-') {
                    if (isset($this->short[$command])) {
                        $command = $this->short[$command];
                        $this->findExecute($command, $tokens[0], $tokens);
                    } else {
                        $this->output = $this->commandError('Option "'.$tokens[0].'" is not defined.');
                    }
                } else {
                    $this->output = $this->commandError('Command "'.$command.'" is not defined.');
                }
                break;
        }

		echo $this->getOutput();

		define('CMD_RUN', microtime(true));
	}

    /**
     * Get Token.
     *
     * @return array
     */
    public function getToken()
    {
        return $this->tokens;
    }

    /**
     * Command Error.
     *
     * @param string $errorMessage
     *
     * @return string
     */
    public function commandError($errorMessage)
    {
        $output = PHP_EOL.Colors::BG_RED.str_repeat(' ', strlen($errorMessage) + 4).PHP_EOL.
        Colors::WHITE."  $errorMessage  ".PHP_EOL.
        Colors::BG_RED.str_repeat(' ', strlen($errorMessage) + 4).PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;

        return $output;
    }

    /**
     * Execute command.
     *
     * @param string $command
     * @param  array   option
     *
     * @return void
     */
    public function execute($command, $option = [])
    {
		$cmd = $this->getCommand($command, $option);

		$this->output = $cmd->run();
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
		$cmd = $this->getCommand($command, $option);

        return $cmd->help();
    }

<<<<<<< HEAD
	/**
	 * Find and Execute command.
	 *
	 * @param string      $command
	 * @param string|null $option
	 * @param array  $tokens
	 *
	 * @return void
	 */
	public function findExecute($command, $option = null, $tokens = [])
	{
		if ($this->findCommand($command)) {
			$this->execute($command, $tokens);
		} else {
			$this->output = $this->commandError(($option==null?'Command '.$command:'Option'.$option)."  is not defined.");
		}
	}

	/**
	 * Get command instance.
	 *
	 * @param string      $command
	 * @param string|null $option
	 *
	 * @return Object
	 */
	public function getCommand($command, $option = null)
	{
        $commandClass = '\\Friday\\Console\\Command\\'.ucfirst($command).'Command';
        return new $commandClass($this, $option);
	}

    /**
     * Display Output of command execution.
     *
     * @return string
     */
    public function getOutput()
    {
        echo $this->output;
=======
    /**
     * Find and Execute command.
     *
     * @param string      $command
     * @param string|null $option
     * @param array       $tokens
     *
     * @return void
     */
    public function findExecute($command, $option = null, $tokens = [])
    {
        if ($this->findCommand($command)) {
            $this->execute($command, $tokens);
        } else {
            $this->output = $this->commandError(($option == null ? 'Command '.$command : 'Option'.$option).'  is not defined.');
        }
>>>>>>> 42f87ba7df336ef369a84a5d1d06c72637df904a
    }
}
