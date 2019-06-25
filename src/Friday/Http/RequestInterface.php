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

namespace Friday\Http;

interface RequestInterface
{
    /**
     * Create new Request instance with uri and param.
     *
     * @param string $uri
     * @param string $host
     * @param string $ip
     * @param array  $params
     * @param enum   $method (GET, POST)
     * @param bool   $https
     *
     * @return void
     */
    public function __construct($uri, $host, $ip, $params = [], $method = 'GET', $https = false);

    /**
     * Get Host.
     *
     * @return string
     */
    public function getHost();

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get URI.
     *
     * @return string
     */
    public function getUri();

    /**
     * Set parameters.
     *
     * @param string $key
     * @param mix    $value
     *
     * @return $this
     */
    public function setParam($key, $value);

    /**
     * Get specific parameter.
     *
     * @return mix
     */
    public function getParam($key);

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function getParams();

    /**
     * Get user IP.
     *
     * @return string
     */
    public function getIp();

    /**
     * Define constants.
     * Without trailing slash.
     *
     * @return void
     */
    public function setConstant();
}
