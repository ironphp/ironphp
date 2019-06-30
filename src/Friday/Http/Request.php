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
 * @link          https://github.com/IronPHP/IronPHP
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Closure;

use InvalidArgumentException;

/**
 * Get HTTP Server Request.
 */
class Request implements RequestInterface
{
    /**
     * URI without parameter.
     *
     * @var string
     */
    public $uri;

    /**
     * Parameter.
     *
     * @var array
     */
    public $params;

    /**
     * HTTP Request Method.
     *
     * @var string
     */
    public $serverRequestMethod;

    /**
     * User IP.
     *
     * @var string
     */
    public $ip;

    /**
     * Http/Htts.
     *
     * @var bool
     */
    public $https;

    /**
     * Host.
     *
     * @var string
     */
    public $host;

    /**
     * Create new Request instance with uri and param.
     *
     * @param string $uri
     * @param string $host
     * @param string $ip
     * @param array  $params
     * @param string $method (GET, POST)
     * @param bool   $https
     *
     * @return void
     */
    public function __construct($uri, $host, $ip, $params = [], $method = 'GET', $https = false)
    {
        $this->uri = $uri;
        $this->params = $params;
        $this->serverRequestMethod = $method;
        $this->https = $https;
        $this->host = rtrim($host, '/\\');
        $this->ip = $ip;
    }

    /**
     * Get Host.
     *
     * @return string
     */
    public function getHost()
    {
        if ($this->https) {
            $pre = 'https://';
        } else {
            $pre = 'http://';
        }

        return $pre.$this->host.'/';
    }

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getHost().$this->uri;
    }

    /**
     * Get URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set parameters.
     *
     * @param string           $key
     * @param string|Closure   $value
     *
     * @return $this
     */
    public function setParam($key, $value)
    {
        if (!isset($this->params[$key]) || $this->params[$key] === []) {
            $this->params[$key] = $value;
        } else {
            $this->params[$key] = array_merge($this->params[$key], [$value]);
        }

        return $this;
    }

    /**
     * Get specific parameter.
     *
     * @throws \InvalidArgumentException
     *
     * @return string|Closure
     */
    public function getParam($key)
    {
        if (!isset($this->params[$key])) {
            throw new InvalidArgumentException("The request parameter with key '$key' is invalid.");
        }

        return $this->params[$key];
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get user IP.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Define constants.
     * Without trailing slash.
     *
     * @note   Defining case-insensitive constants is deprecated as of PHP 7.3.0.
     *
     * @return void
     */
    public function setConstant()
    {
        define('SERVER_ROOT', $this->getHost());
        define('PUBLIC_ROOT', $this->getHost().'public/');
        define('HOST', $_SERVER['HTTP_HOST']);
    }

    /**
     * Get Server Request Method.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->serverRequestMethod;
    }
}
