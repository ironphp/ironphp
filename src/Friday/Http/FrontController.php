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
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Closure;

/**
 * Runs a Front Controller.
 */
class FrontController implements FrontControllerInterface
{
    /**
     * Controller name.
     *
     * @var string
     */
    private $controller;

    /**
     * Action.
     *
     * @var string|Closure
     */
    private $action;

    /**
     * Parameters.
     *
     * @var array
     */
    private $params;

    /*
    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    */

    /**
     * Create a new FrontController instance.
     *
     * @return void
     */
    public function __construct(/*array $options = array()*/)
    {
    }

    /**
     * Create Responce instance.
     *
     * @param array $parsedUrl
     *
     * @return \Friday\Http\Request
     */
    public function request($parsedUrl)
    {
        //if (empty($options)) {
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
        return new Request($parsedUrl['uri'], $parsedUrl['host'], $parsedUrl['ip'], $parsedUrl['params'], $parsedUrl['method'], $parsedUrl['https']);
    }

    /**
     * Create Route instance.
     *
     * @return \Friday\Http\Route
     */
    public function route()
    {
        return new Route();
    }

    /**
     * Create Router instance.
     *
     * @return \Friday\Http\Router
     */
    public function router()
    {
        return new Router();
    }

    /**
     * Create Dispatcher instance.
     *
     * @return \Friday\Http\Dispatcher
     */
    public function dispatcher()
    {
        return new Dispatcher();
    }

    /**
     * Create Response instance.
     *
     * @param string $serverProtocol
     *
     * @return \Friday\Http\Response
     */
    public function response($serverProtocol)
    {
        return new Response($serverProtocol);
    }

    /*
    public function setController($controller)
    {
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

    /**
     * Call method of controller with parameters.
     *
     * @return void
     */
    public function run()
    {
        $controller = 'Controller\\'.$this->controller;
        call_user_func_array([$controller, $this->action], $this->params);
    }
}
