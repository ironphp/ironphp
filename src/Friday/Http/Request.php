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

namespace Friday\Http;

class Request implements RequestInterface {

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
     * Create new Request instance with uri and param.
     *
     * @param  string  $uri
     * @param  array   $params
     * @param  enum    $method  (GET, POST)
     * @return void
     */
    public function __construct($uri, $params = array(), $method = 'GET') { 
        $this->uri = $uri;
        $this->params = $params;
        $this->serverRequestMethod = $method;
    }
 
    /**
     * Get URI.
     *
     * @return string
     */
    public function getUri() {
        return $this->uri;
    }
 
    /**
     * Set parameters.
     *
     * @param  string  $key
     * @param  mix     $value
     * @return object
     */
    public function setParam($key, $value) {
        $this->params[$key] = $value;
        return $this;
    }
 
    /**
     * Get specific parameter.
     *
     * @return mix
     */
    public function getParam($key) {
        if (!isset($this->params[$key])) {
            throw new \InvalidArgumentException("The request parameter with key '$key' is invalid."); 
        }
        return $this->params[$key];
    }
 
    /**
     * Get all parameters.
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

}