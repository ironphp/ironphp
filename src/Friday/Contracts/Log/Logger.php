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
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Contracts\Log;

use Psr\Log\LoggerInterface;

interface Logger extends LoggerInterface
{
    /**
     * Create a new log writer instance.
     *
     * @param \Friday\Contracts\Log\Logger|null $logger
     *
     * @return void
     */
    public function __construct(self $logger = null);

    /**
     * Log an alert message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = []);

    /**
     * Log a critical message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = []);

    /**
     * Log a debug message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = []);

    /**
     * Log an emergency message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = []);

    /**
     * Log an error message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = []);

    /**
     * Log an informational message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = []);

    /**
     * Log a notice to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = []);

    /**
     * Log a warning message to the logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = []);

    /**
     * Log a message to the logs.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []);

    /**
     * Dynamically pass log calls into the writer.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function write($level, $message, array $context = []);

    /**
     * Get the underlying logger implementation.
     *
     * @return \Psr\Log\LoggerInterface|null
     */
    public function getLogger();

    /**
     * Dynamically proxy method calls to the underlying logger.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters);
}
