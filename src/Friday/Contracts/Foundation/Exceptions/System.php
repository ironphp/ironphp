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

interface System
{
    /**
     * Turns on output buffering.
     *
     * @return bool
     */
    public function startOutputBuffering();

    /**
     * @param callable   $handler
     * @param int|string $types
     *
     * @return callable|null
     */
    public function setErrorHandler(callable $handler, $types = 'use-php-defaults');

    /**
     * @param callable $handler
     *
     * @return callable|null
     */
    public function setExceptionHandler(callable $handler);

    /**
     * @return void
     */
    public function restoreExceptionHandler();

    /**
     * @return void
     */
    public function restoreErrorHandler();

    /**
     * @param callable $function
     *
     * @return void
     */
    public function registerShutdownFunction(callable $function);

    /**
     * @return string|false
     */
    public function cleanOutputBuffer();

    /**
     * @return int
     */
    public function getOutputBufferLevel();

    /**
     * @return bool
     */
    public function endOutputBuffering();

    /**
     * @return void
     */
    public function flushOutputBuffer();

    /**
     * @return int
     */
    public function getErrorReportingLevel();

    /**
     * @return array|null
     */
    public function getLastError();

    /**
     * @param int $httpCode
     *
     * @return int
     */
    public function setHttpResponseCode($httpCode);

    /**
     * @param int $exitStatus
     */
    public function stopExecution($exitStatus);
}
