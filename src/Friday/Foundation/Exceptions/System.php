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
 * @since         1.0.1
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Foundation\Exceptions;

class System
{
    /**
     * Turns on output buffering.
     *
     * @return bool
     */
    public function startOutputBuffering()
    {
        return ob_start();
    }

    /**
     * @param callable $handler
     * @param int      $types
     *
     * @return callable|null
     */
    public function setErrorHandler(callable $handler, $types = 32767)
    {
        // Workaround for PHP 5.5 / $types = E_ALL | E_STRICT
        //set_error_handler([$this, 'onError'], error_reporting());
        return set_error_handler($handler, $types);
    }

    /**
     * @param callable $handler
     *
     * @return callable|null
     */
    public function setExceptionHandler(callable $handler)
    {
        return set_exception_handler($handler);
    }

    /**
     * @return void
     */
    public function restoreExceptionHandler()
    {
        restore_exception_handler();
    }

    /**
     * @return void
     */
    public function restoreErrorHandler()
    {
        restore_error_handler();
    }

    /**
     * @param callable $function
     *
     * @return void
     */
    public function registerShutdownFunction(callable $function)
    {
        register_shutdown_function($function);
    }

    /**
     * @return string|false
     */
    public function cleanOutputBuffer()
    {
        return ob_get_clean();
    }

    /**
     * @return int
     */
    public function getOutputBufferLevel()
    {
        return ob_get_level();
    }

    /**
     * @return bool
     */
    public function endOutputBuffering()
    {
        return ob_end_clean();
    }

    /**
     * @return void
     */
    public function flushOutputBuffer()
    {
        flush();
    }

    /**
     * @return int
     */
    public function getErrorReportingLevel()
    {
        return error_reporting();
    }

    /**
     * @return array|null
     */
    public function getLastError()
    {
        return error_get_last();
    }

    /**
     * @param int $httpCode
     *
     * @returns bool|int
     */
    public function setHttpResponseCode($httpCode)
    {
        return http_response_code($httpCode);
    }

    /**
     * @param int $exitStatus
     */
    public function stopExecution($exitStatus)
    {
        exit($exitStatus);
    }
}
