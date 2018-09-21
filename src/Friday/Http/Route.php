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
    public $routes = NULL;

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
     * @param  string                $route
     * @param  string|callback|null  $mix
     * @param  string|null           $view
     * @return bool
     */
    public function get($route, $mix = null, $view = null) {
        self::$instance->register('GET', $route, $mix, $view);
    }

    /**
     * register a POST method route.
     *
     * @param  string                $route
     * @param  string|callback|null  $mix
     * @param  string|null           $view
     * @return bool
     */
    public function post($route, $mix = null, $view = null) {
        self::$instance->register('POST', $route, $mix, $view);
    }

    /**
     * register a GET method route with view.
     *
     * @param  string                $route
     * @param  string|null           $view
     * @param  array                 $data
     * @return bool
     */
    public function view($route, $view = null, array $data = []) {
        self::$instance->register('GET', $route, null, $view, $data);
    }

    /**
     * register a route.
     *
     * @param  string                $route
     * @param  string|callback|null  $mix
     * @param  string|null           $view
     * @param  array                 $data
     * @return void
     */
    public function register($method, $route, $mix = null, $view = null, $data = []) {
        $route = trim($route, '/');
        $array = explode('/', $route);
        $route = '/'.$route;
        if(strpos($route, '{') !== false) {
            foreach($array as $uriPiece) {
                $uriPiece = trim($uriPiece);
                if(strpos($uriPiece, '{') !== false) {
                    if(
                        strpos($uriPiece, '{') === 0 && 
                        strpos($uriPiece, '}') !== false && 
                        strpos($uriPiece, '}') === (strlen($uriPiece) - 1)
                    ) {
                        $args[$uriPiece] = rtrim(ltrim($uriPiece, '{'), '}');
                    }
                    else {
                        $args[$uriPiece] = null;
                    }
                }
                else {
                    $args[$uriPiece] = null;
                }
            }
        }
        else {
            $args = null;
        }
        self::$instance->routes[] = [$method, $route, $mix, $view, $data, $args];
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
