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

class FrontController {

    /*
    const DEFAULT_CONTROLLER = "IndexController";
    const DEFAULT_ACTION = "index";
    
    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    */

    /**
     * Create a new FrontController instance.
     *
     * @return void
     */
    public function __construct(/*array $options = array()*/) {
    }
    
    /**
     * Parse Uri and get path uri, params, server method.
     *
     * @return array
     */
    public function parseUri() {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $uri = str_replace(['{', '}'], '', urldecode($uri));
        $extDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $uri = str_replace($extDir, '', $uri);
        $uri = rtrim($uri, '/');
        $uri = empty($uri) ? '/' : $uri;
        $serverRequestMethod = $_SERVER['REQUEST_METHOD'];
        $params = $GLOBALS['_'.$serverRequestMethod];
        if(!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $https = true;
        }
        else {
            $https = false;
        }
        $host = $_SERVER['HTTP_HOST'].$extDir;
        $ip = $_SERVER['REMOTE_ADDR'];
        /**
         * Test GET === _GET
         * _GET !== QUERY
         * Test POST === _POST
         */
        if($serverRequestMethod === 'GET' && $diff = array_diff_assoc($params, $_GET)) {
            echo '<pre>';
            var_dump($diff);
            var_dump($params);
            var_dump($_GET);
            echo '</pre>';
            throw new \Exception("POST and _POST are not same at line ".__LINE__);
            exit;
        }
        if($serverRequestMethod === 'POST' && $diff = array_diff_assoc($params, $_POST)) {
            echo '<pre>';
            var_dump($diff);
            var_dump($params);
            var_dump($_POST);
            echo '</pre>';
            throw new \Exception("POST and _POST are not same at line ".__LINE__);
            exit;
        }
        
        if($serverRequestMethod === 'POST') {
            $params = ['GET' => $_GET, 'POST' => $params];
        }
        else {
            $params = ['GET' => $params, 'POST' => []];
        }

        return ['uri' => $uri, 'params' => $params, 'method' => $serverRequestMethod, 'https' => $https, 'host' => $host, 'ip' => $ip];
        /*
        if (strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }

        @list($controller, $action, $params) = explode("/", $path, 3);
        if (isset($controller) && !empty($controller)) {
            $this->setController($controller);
        }
        else {
            $this->setController('Index');
        }

        if (isset($action)) {
            $this->setAction($action);
        }
        else {
            $this->setAction('Index');
        }

        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }
        */
    }
    
    /**
     * Create Responce instance.
     *
     * @return object
     */
    public function request() {
        //if (empty($options)) {
           $parse = $this->parseUri();
        //}
        /*
        else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);     
            }
            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
        }
        */
        return new \Friday\Http\Request($parse['uri'], $parse['params'], $parse['method'], $parse['https'], $parse['host'], $parse['ip']);
    }

    /**
     * Create Route instance.
     *
     * @return object
     */
    public function route() {
        return new \Friday\Http\Route();
    }

    /**
     * Create Router instance.
     *
     * @return object
     */
    public function router() {
        return new \Friday\Http\Router();
    }

    /**
     * Create Dispatcher instance.
     *
     * @return object
     */
    public function dispatcher() {
        return new \Friday\Http\Dispatcher();
    }

    /**
     * Create Response instance.
     *
     * @param  string  $serverProtocol
     * @return object
     */
    public function response($serverProtocol) {
        return new \Friday\Http\Response($serverProtocol);
    }

    /*
    public function setController($controller) {
        $controller = ucfirst(strtolower($controller)) . "Controller";
        if (!class_exists($controller)) {
            throw new \InvalidArgumentException(
                "The action controller '$controller' has not been defined.");
        }
        $this->controller = $controller;
        return $this;
    }
    
    public function setAction($action) {
        $reflector = new ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) {
            throw new \InvalidArgumentException(
                "The controller action '$action' has been not defined.");
        }
        $this->action = $action;
        return $this;
    }
    
    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }
    */

    public function run() {
        $controller = "Controller\\".$this->controller;
        call_user_func_array(array($controller, $this->action), $this->params);
    }


}
