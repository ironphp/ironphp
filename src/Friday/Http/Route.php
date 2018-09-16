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

class Route implements RouteInterface
{
    /**
     * All registered route.
     *
     * @var array
     */
    public $routes;

    /**
     * Route instance.
     *
     * @var Route
     */
    public static $instance;

    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct(/*$path, $controllerClass*/) {
        //$this->path = $path;
        //$this->controllerClass = $controllerClass;
    }
 
    /**
     * register a GET method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function get($route, $mix) {
        self::$instance->register('GET', $route, $mix);
    }

    /**
     * register a POST method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function post($route, $mix) {
        self::$instance->register('POST', $route, $mix);
    }

    /**
     * register a route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return void
     */
    public function register($method, $route, $mix) {
        $route = '/'.trim($route, '/');
        self::$instance->routes[] = [$method, $route, $mix];
    }

    /**
     * Match a uri route to registered routes.
     *
     * @param  object  $request  RequestInterface
     * @return bool
    public function match(RequestInterface $request) {
        return $this->path === $request->getUri();
    }
     */
 
    /**
     * Create Controller instance.
     *
     * @return object
    public function createController() {
        return new $this->controllerClass;
    }
     */

}
