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
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Closure;
use Friday\Contracts\Http\RequestInterface;
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
    //public $uri;

    /**
     * Parameter.
     *
     * @var array
     */
    //public $params;

    /**
     * HTTP Request Method.
     *
     * @var string
     */
    //public $serverRequestMethod;

    /**
     * User IP.
     *
     * @var string
     */
    //public $ip;

    /**
     * Http/Htts.
     *
     * @var bool
     */
    //public $https;

    /**
     * Host.
     *
     * @var string
     */
    //public $host;

    /**
     * Instance of Request.
     *
     * @var \Friday\Http\Request
     */
    private static $instance;

    /**
     * Send data.
     *
     * @var array
     */
    //public $data;

    /**
     * Create new Request instance with uri and param.
     *
     * @param string|null $uri
     * @param string|null $host
     * @param string|null $ip
     * @param array       $params
     * @param string      $method (GET, POST)
     * @param bool        $https
     *
     * @return void
     */
    public function __construct($uri = null, $host = null, $ip = null, $params = [], $method = 'GET', $https = false)
    {
        if (static::$instance == null) {
            $this->parseUri();
            static::$instance = $this;
        }
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
        return $this->getHost().ltrim($this->uri, '/');
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
     * @param string         $key
     * @param string|Closure $value
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

    /**
     * Get Request instance.
     *
     * @return \Friday\Http\Request
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Check server used HTTPS or not.
     *
     * @return bool
     */
    public function isHttps()
    {
        return $this->https;
    }

    /**
     * Get all GET data.
     *
     * @return stdClass
     *
     * @since 1.0.12
     */
    public static function get()
    {
        return static::getInstance()->formatData($_GET);
    }

    /**
     * Get all POST data.
     *
     * @return stdClass
     *
     * @since 1.0.12
     */
    public static function post()
    {
        return static::getInstance()->formatData($_POST);
    }

    /**
     * Get all FILES data.
     *
     * @return stdClass
     *
     * @since 1.0.12
     */
    public static function files()
    {
        return static::getInstance()->formatData($_FILES);
    }

    /**
     * Format data.
     *
     * @return stdClass
     *
     * @since 1.0.12
     */
    public function formatData($array)
    {
        $request = new Request();
        foreach ($array as $key => $val) {
            $key = str_replace('-', '_', $key);
            $request->$key = $val;
        }

        return $request;
    }

    /**
     * Parse Uri and get path uri, params, server method.
     *
     * @return void
     *
     * @moved 1.0.12
     */
    public function parseUri()
    {
        // TODO
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = str_replace(['{', '}'], '', urldecode($this->uri));
        $extDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $this->uri = ($extDir == '/' || $extDir == '\\')
            ? $this->uri
            : str_replace($extDir, '', $this->uri);
        $this->uri = rtrim($this->uri, '/');
        $this->uri = empty($this->uri) ? '/' : $this->uri;

        $this->serverRequestMethod = $_SERVER['REQUEST_METHOD'];

        if ($this->serverRequestMethod == 'POST') {
            if (isset($_POST['_method'])
                && ($_POST['_method'] === 'PUT' || $_POST['_method'] === 'DELETE')
            ) {
                $this->serverRequestMethod = $_POST['_method'];
                $GLOBALS['_'.$this->serverRequestMethod] = $GLOBALS['_POST'];
            }
        }

        $params = $GLOBALS['_'.$this->serverRequestMethod];

        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $this->https = true;
        } else {
            $this->https = false;
        }

        $this->host = rtrim($_SERVER['HTTP_HOST'].str_replace('\\', '/', $extDir), '/\\');
        $this->ip = $_SERVER['REMOTE_ADDR'];

        if ($this->serverRequestMethod === 'POST') {
            $this->setParam('GET', $_GET);
            $this->setParam('POST', $params);
        } else {
            $this->setParam('GET', $params);
            $this->setParam('POST', []);
        }

        $this->setParam('COOKIE', $GLOBALS['_COOKIE']);
        $this->setParam('FILES', $GLOBALS['_FILES']);
        $this->setParam('ENV', $GLOBALS['_ENV']);
        $this->setParam('SERVER', $GLOBALS['_COOKIE']);
        $this->setParam('SESSION', $GLOBALS['_SERVER']);
        $this->setParam('Url', $this->host.$this->uri);
        $this->setParam('Headers', getallheaders());
    }

    /**
     * Get all GET/POST/FILES data.
     *
     * @return stdClass
     *
     * @since 1.0.12
     */
    public static function all()
    {
        return static::getInstance()->formatData($_GET);
    }

    /**
     * Get Server Request Method.
     *
     * @return string
     *
     * @since 1.0.12
     */
    public function getServerRequestMethod()
    {
        return $this->serverRequestMethod;
    }

    /**
     * Validate data.
     *
     * @param array      $rules
     * @param array|null $messages
     * @param array|null $customAttributes
     *
     * @return bool|void
     *
     * @since 1.0.12
     */
    public function validate($rules, $messages = null, $customAttributes = [])
    {
        // TODO update validations
        foreach ($rules as $key => $rule) {
            $rule_items = explode('|', $rule);
            foreach ($rule_items as $rule_item) {
                switch ($rule_item) {
                    case 'required':
                        if (!isset($this->$key) || empty($this->$key)) {
                            echo $key.' is required.';
                            exit;
                        }
                }
            }
        }

        return true;
    }
}
