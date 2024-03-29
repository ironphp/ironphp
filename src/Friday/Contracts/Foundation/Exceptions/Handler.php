<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) IronPHP
 *
 * @link		  https://github.com/IronPHP/IronPHP
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Foundation\Exceptions;

use ErrorException;
use Friday\Foundation\Errors\Error;
use Friday\Foundation\Errors\Fatal;
use Friday\Foundation\Errors\Notice;
use Friday\Foundation\Exceptions\System;

interface Handler
{
    const EXCEPTION_HANDLER = 'handleException';
    const ERROR_HANDLER = 'handleError';
    const SHUTDOWN_HANDLER = 'handleShutdown';

    /**
     * Create a new exception handler instance.
     *
     * @param System|null $system
     *
     * @return void
     */
    public function __construct(System $system = null);

    /**
     * Set PHP internal logging file.
     *
     * @param bool|string $log
     *
     * @return void
     */
    public function logging($log = false);

    /**
     * Registers this instance as an error handler.
     *
     * @return $this
     */
    public function register();

    /**
     * Unregisters all handlers registered by this Whoops\Run instance.
     *
     * @return $this
     */
    public function unregister();

    /**
     * Should Whoops allow Handlers to force the script to quit?
     *
     * @param bool|int $exit
     *
     * @return bool
     */
    public function allowQuit($exit = null);

    /**
     * Handles an exception, ultimately generating a Whoops error
     * page.
     *
     * @param \Throwable $exception
     *
     * @return string Output generated by handlers
     */
    public function handleException($exception);

    /**
     * Converts generic PHP errors to \ErrorException
     * instances, before passing them off to be handled.
     *
     * This method MUST be compatible with set_error_handler.
     *
     * @param int    $level
     * @param string $message
     * @param string $file
     * @param int    $line
     *
     * @throws ErrorException
     *
     * @return bool
     */
    public function handleError($level, $message, $file = null, $line = null);

    /**
     * Special case to deal with Fatal errors and the like.
     *
     * @return void
     */
    public function handleShutdown();

    /**
     * Builds Airbrake notice from exception.
     *
     * @param \Throwable|\Exception $exc Exception or class that implements similar interface.
     *
     * @return array Airbrake notice
     */
    public function buildNotice($exc);

    /**
     * Determine if an error level is fatal (halts execution).
     *
     * @param int $level
     *
     * @return bool
     */
    public static function isLevelFatal($level);

    /**
     * Get severity name by severity code.
     *
     * @param int $severityCode
     *
     * @return string
     *
     * @since 1.0.6
     */
    public function getSeverity($severityCode);

    /**
     * check if interface is CLI or not.
     *
     * @return bool
     *
     * @since 1.0.6
     */
    public function isCommandLineInterface();
}
