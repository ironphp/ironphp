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
 * @since         1.0.1
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Foundation\Exceptions;

use ErrorException;
use Friday\Foundation\Errors\Error;
use Friday\Foundation\Errors\Fatal;
use Friday\Foundation\Errors\Notice;
use Friday\Foundation\Errors\Warning;

class Handler implements HandlerInterface
{
    /**
     * @var Notifier
     */
    private $notifier;

    /**
     * @var array
     */
    private $lastError;

    /**
     * @var bool
     */
    private $isRegistered;

    /**
     * @var bool
     */
    private $allowQuit = true;

    /**
     * @var bool
     */
    private $sendOutput = true;

    public static $LIST = [];

    /**
     * @var integer|false
     */
    private $sendHttpCode = 500;

    /**
     * @var HandlerInterface[]
     */
    private $handlerStack = [];

    private $silencedPatterns = [];

    private $system;

    private $canThrowExceptions;

    /**
     * Create a new exception handler instance.
     *
     * @param  System|null  system
     *
     * @return void
     */
    public function __construct(System $system = null)
    {
        $this->system = $system ?: new System();
        if (env('APP_DEBUG') === true) {
            ini_set('display_errors', 'on');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'off');
            error_reporting(0);
        }
    }

    /**
     * Set PHP internal logging file.
     *
     * @param bool|string $log
     *
     * @return void
     */
    public function logging($log = false)
    {
        if ($log !== false) {
            if (!ini_get('log_errors')) {
                ini_set('log_errors', true);
            }
            if (!ini_get('error_log')) {
                ini_set('error_log', $log);
            }
        }
    }

    /**
     * Registers this instance as an error handler.
     *
     * @return $this
     */
    public function register()
    {
        if (!$this->isRegistered) {
            $this->system->setErrorHandler([$this, self::ERROR_HANDLER]);
            $this->system->setExceptionHandler([$this, self::EXCEPTION_HANDLER]);
            $this->system->registerShutdownFunction([$this, self::SHUTDOWN_HANDLER]);

            $this->isRegistered = true;
        }

        return $this;
    }

    /**
     * Unregisters all handlers registered by this Whoops\Run instance.
     *
     * @return $this
     */
    public function unregister()
    {
        if ($this->isRegistered) {
            $this->system->restoreExceptionHandler();
            $this->system->restoreErrorHandler();

            $this->isRegistered = false;
        }

        return $this;
    }

    /**
     * Should Whoops allow Handlers to force the script to quit?
     *
     * @param bool|int $exit
     *
     * @return bool
     */
    public function allowQuit($exit = null)
    {
        if (func_num_args() == 0) {
            return $this->allowQuit;
        }

        return $this->allowQuit = (bool) $exit;
    }

    /**
     * Handles an exception, ultimately generating a Whoops error
     * page.
     *
     * @param \Throwable $exception
     *
     * @return string Output generated by handlers
     */
    public function handleException($exception)
    {
        //$this->system->startOutputBuffering();
        //$willQuit = $handlerResponse == Handler::QUIT && $this->allowQuit();
        //$output = $this->system->cleanOutputBuffer();
        /*
        if ($this->writeToOutput()) {
            if ($willQuit) {
                while ($this->system->getOutputBufferLevel() > 0) {
                    $this->system->endOutputBuffering();
                }
                if (Misc::canSendHeaders() && $handlerContentType) {
                    header("Content-Type: {$handlerContentType}");
                }
            }
            $this->writeToOutputNow($output);
        }
        if ($willQuit) {
            $this->system->flushOutputBuffer();
            $this->system->stopExecution(1);
        }
        */
        $log = $exception->getMessage()."\n".$exception->getTraceAsString().LINEBREAK;
        if (ini_get('log_errors')) {
            error_log($log, 0);
        }
        $output = get_class($exception).':'.$log;
        //return $output;
        if (method_exists($exception, 'getSeverity')) {
            $severityCode = $exception->getSeverity();
            switch ($severityCode) {
            case E_ERROR: // 1 //
                $severity = 'E_ERROR';
                break;
            case E_WARNING: // 2 //
                $severity = 'E_WARNING';
                break;
            case E_PARSE: // 4 //
                $severity = 'E_PARSE';
                break;
            case E_NOTICE: // 8 //
                $severity = 'E_NOTICE';
                break;
            case E_CORE_ERROR: // 16 //
                $severity = 'E_CORE_ERROR';
                break;
            case E_CORE_WARNING: // 32 //
                $severity = 'E_CORE_WARNING';
                break;
            case E_COMPILE_ERROR: // 64 //
                $severity = 'E_COMPILE_ERROR';
                break;
            case E_COMPILE_WARNING: // 128 //
                $severity = 'E_COMPILE_WARNING';
                break;
            case E_USER_ERROR: // 256 //
                $severity = 'E_USER_ERROR';
                break;
            case E_USER_WARNING: // 512 //
                $severity = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE: // 1024 //
                $severity = 'E_USER_NOTICE';
                break;
            case E_STRICT: // 2048 //
                $severity = 'E_STRICT';
                break;
            case E_RECOVERABLE_ERROR: // 4096 //
                $severity = 'E_RECOVERABLE_ERROR';
                break;
            case E_DEPRECATED: // 8192 //
                $severity = 'E_DEPRECATED';
                break;
            case E_USER_DEPRECATED: // 16384 //
                $severity = 'E_USER_DEPRECATED';
                break;
            case E_ALL: // 32767 //
                $severity = 'E_ALL';
                break;
            default:
                $severity = 'UNKOWN';
                break;
        }
        }
        if (env('APP_DEBUG') === true) {
            echo '
				<!DOCTYPE html>
				<html lang="en">
					<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<title>Error</title>
						<!-- Fonts -->
						<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
						<style>html, body {background-color: #fff;color: #636b6f;font-family: \'Nunito\', sans-serif;font-weight: 100;height: 100vh;margin: 0;}.full-height {height: 90vh;}.flex-center {align-items: center;display: flex;justify-content: center;}.content {text-align: center;}.title {font-size: 36px;padding: 20px;}</style>
					</head>
					<body>
						<div class="flex-center position-ref full-height">
							<div class="content">
								<h2 class="title" style="color: rgb(190, 50, 50)">Exception Occured</h2>
        						<table style="width: 800px; display: inline-block;text-align:left">
        							<tr style="background-color:rgb(230,230,230)">
										<th style="width: 80px">Type</th>
										<td>'.get_class($exception).'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Code</th>
										<td>'.$exception->getCode().'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Trace</th>
										<td>'.trim(str_replace('#', '<br>#', $exception->getTraceAsString()), '<br>').'</td>
									</tr>'.
                                        (isset($severityCode) ? '
        							<tr style="background-color:rgb(240,240,240)">
										<th>Severity</th>
										<td>'.$severityCode.' - '.$severity.'</td>
									</tr>.'
                                          : '').'
        							<tr style="background-color:rgb(240,240,240)">
										<th>Message</th>
										<td>'.$exception->getMessage().'</td>
									</tr>
        							<tr style="background-color:rgb(230,230,230)">
										<th>File</th>
										<td>'.$exception->getFile().'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Line</th>
										<td>'.$exception->getLine().'</td>
									</tr>
        						</table>
							</div>
						</div>
					</body>
				</html>
			';
        } else {
            $message = 'Type: '.get_class($exception)."; Message: {$exception->getMessage()}; File: {$exception->getFile()}; Line: {$exception->getLine()};";
            if (!file_exists(LOGS.'/debug.log') || !is_file(LOGS.'/debug.log')) {
                file_put_contents(LOGS.'/debug.log', '');
            }
            error_log('['.date('Y-y-d H:i:s').'] '.$message.PHP_EOL.PHP_EOL, 3, LOGS.'/debug.log');
            //header( "Location: {$config["error_page"]}" );
            echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Error</title><!-- Fonts --><link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
<style>html, body {background-color: #fff;color: #636b6f;font-family: \'Nunito\', sans-serif;font-weight: 100;height: 100vh;margin: 0;}.full-height {height: 90vh;}.flex-center {align-items: center;display: flex;justify-content: center;}.content {text-align: center;}.title {font-size: 36px;padding: 20px;}</style><body><div class="flex-center position-ref full-height"><div class="content"><div class="title">It looks like something went wrong.</div></div></div></body></html>';
        }
        exit();
    }

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
    public function handleError($level, $message, $file = null, $line = null)
    {
        $this->lastError = [
            'message' => $message,
            'file'    => $file,
            'line'    => $line,
        ];

        $trace = debug_backtrace();
        if (count($trace) > 0 && !isset($trace[0]['file'])) {
            array_shift($trace);
        }

        switch ($level) {
            case E_NOTICE:
            case E_USER_NOTICE:
            //case @E_STRICT:
                $exc = new Notice($message, $trace);
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $exc = new Warning($message, $trace);
                break;
            case E_ERROR:
            case E_CORE_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_USER_ERROR:
                $exc = new Fatal($message, $trace);
                break;
            //case @E_RECOVERABLE_ERROR:
                //'Catchable';
            default:
                $exc = new Error($message, $trace);
                break;
        }
        $notice = $this->buildNotice($exc);

        if ($level & $this->system->getErrorReportingLevel()) {
            $exception = new ErrorException($message, /*code*/ $level, /*severity*/ $level, $file, $line);
            if ($this->canThrowExceptions) {
                throw $exception;
            } else {
                $this->handleException($exception);
            }
            // Do not propagate errors which were already handled.
            return true;
        }

        // Propagate error to the next handler, allows error_get_last() to
        // work on silenced errors.
        return false;
//
        /**
         * Exception handler callable.
         *
         * @since  1.0.1
         *
         * @param int    $errno  (severity)
         * @param string $errstr (message)
         * @param string $file
         * @param int    $line
         *
         * @throws \ErrorException
         *
         * @return void
         */
        //function exception_error_handler($errno, $errstr, $file, $line) {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $errstr<br>\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ', PHP '.PHP_VERSION.' ('.PHP_OS.")<br>\n";
                echo "Aborting...<br>\n";
                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>My WARNING</b> [$errno] $errstr<br>\n";
                break;

            case E_USER_NOTICE:
                echo "<b>My NOTICE</b> [$errno] $errstr<br>\n";
                break;

            default:
                echo "<b>Error</b>: [$errno] $errstr<br>\n";
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;

        throw new ErrorException($errstr, 0, $errno, $file, $line);
//
        /*
         * Error handler, passes flow over the
         * exception logger with new ErrorException.
         *
         * @since  1.0.1
         * @param  int      $errno (severity)
         * @param  string   $errstr (message)
         * @param  string   $errfile
         * @param  int      $errline
         * @return void
         */
        //function log_error( $errno, $errstr, $errfile, $errline, $context = null ) {
        //print_r(func_get_args());exit;
        log_exception(new ErrorException($errstr, 0, $errno, $errfile, $errline));
    }

    /**
     * Special case to deal with Fatal errors and the like.
     *
     * @return void
     */
    public function handleShutdown()
    {
        $error = $this->system->getLastError();
        if ($error === null) {
            return;
        }
        if (($error['type'] & error_reporting()) === 0) {
            return;
        }
        if ($this->lastError !== null &&
            $error['message'] === $this->lastError['message'] &&
            $error['file'] === $this->lastError['file'] &&
            $error['line'] === $this->lastError['line']) {
            return;
        }
        $trace = [[
            'file' => $error['file'],
            'line' => $error['line'],
        ]];
        $exc = new Fatal($error['message'], $trace);
        $notice = $this->buildNotice($exc);

        // If we reached this step, we are in shutdown handler.
        // An exception thrown in a shutdown handler will not be propagated
        // to the exception handler. Pass that information along.
        $this->canThrowExceptions = false;

        if ($error && $this->isLevelFatal($error['type'])) {
            // If there was a fatal error,
            // it was not handled in handleError yet.
            $this->handleError(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        }
        if ($error['type'] != 0) {
            //call_user_func($this->errorHandler,new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
            //log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
        }
    }

    /**
     * Builds Airbrake notice from exception.
     *
     * @param \Throwable|\Exception $exc Exception or class that implements similar interface.
     *
     * @return array Airbrake notice
     */
    public function buildNotice($exc)
    {
        $error = [
            'type'      => get_class($exc),
            'message'   => $exc->getMessage(),
            'backtrace' => $exc->getTrace(),
        ];

        $notice = [
            'errors' => [$error],
        ];
        if (!empty($_REQUEST)) {
            $notice['params'] = $_REQUEST;
        }
        if (!empty($_SESSION)) {
            $notice['session'] = $_SESSION;
        }

        return $notice;
    }

    /**
     * Determine if an error level is fatal (halts execution).
     *
     * @param int $level
     *
     * @return bool
     */
    public static function isLevelFatal($level)
    {
        $errors = E_ERROR;
        $errors |= E_PARSE;
        $errors |= E_CORE_ERROR;
        $errors |= E_CORE_WARNING;
        $errors |= E_COMPILE_ERROR;
        $errors |= E_COMPILE_WARNING;

        return ($level & $errors) > 0;
    }
}
