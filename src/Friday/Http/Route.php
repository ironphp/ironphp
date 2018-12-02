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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Closure;
use Friday\Http\Response;

class Route implements RouteInterface
{
    /**
     * All registered route.
     *
     * @var array
     */
    public $routes = [];

    /**
     * Route instance.
     *
     * @var Route
     */
    public static $instance;

    /**
     * Route prefix name.
     *
     * @var string
     */
    public $prefix = NULL;

    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct(/*$path, $controllerClass*/)
    {
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
    static public function get($route, $mix = null, $view = null)
    {
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
    static public function post($route, $mix = null, $view = null)
    {
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
    static public function view($route, $view = null, array $data = [])
    {
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
    public function register($method, $route, $mix = null, $view = null, $data = [])
    {
        $route = trim($route, '/ ');
        $route = (self::$instance->prefix == null) ? $route : self::$instance->prefix.'/'.$route;
        $array = $route==='' ? [] : explode('/', $route);
        $size = count($array);
        $route = '/'.$route;
        if(strpos($route, '{') !== false) {
            $to = 0;
            $param = true;
            foreach($array as $i => $uriPiece) {
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
                    $to = $i+1;
                    $args[$uriPiece] = null;
                }
            }
            $base_size = $to;
            $base_route = array_slice($array, 0, $to, true);
        }
        else {
            $param = false;
            $base_size = $size;
            $args = null;
            $base_route = $route;
        }
        $base_route = is_array($base_route) ? implode('/', $base_route) : $base_route;
        if(trim($base_route) === '') $base_route = '/';
        self::$instance->routes[] = [$method, $route, $mix, $view, $data, $args, $size, $base_size, $param];
    }

    /**
     * sort registered routes by there base uri.
     *
     * @return void
     */
    public function sortRoute()
    {
        $sort = uasort($this->routes, function ($a, $b) {
            if ($a[7] == $b[7]) {
                return 0;
            }
            return ($a[7] > $b[7]) ? -1 : 1;
        });
    }

    /**
     * Group routes by prefix.
     *
     * @param  string  $prefix
     * @return \Friday\Http\Route
     */
    static public function prefix($prefix)
    {
        self::$instance->prefix = $prefix;
        return self::$instance;
    }

    /**
     * Registered group routes.
     *
     * @param  \Closure  $closure
     * @return void
     */
    static public function group($closure)
    {
        $backtrace = debug_backtrace();
        if(!isset($this) || $backtrace[0]['type'] == '::' || self::$instance->prefix == null) {
            exit('Can not be called statically or directly. Use Route::prefix(name)->group(routes-to-be-registered)');
        }
        if($closure instanceof Closure) {
            call_user_func($closure);
        }
        $this->prefix = null;
    }

    /**
     * Registered redirect routes.
     *
     * @param  string  $routeFrom
     * @param  string  $routeTo
     * @param  int     $http_response_code
     * @return void
     */
    static public function redirect($routeFrom, $routeTo, $http_response_code)
    {
        $closure = function($routeTo, $replace, $http_response_code) {
            Response::$redirectHeader = [$routeTo, $replace, $http_response_code];
        };
        self::$instance->register('GET', $routeFrom, $closure, null, [$routeTo, true, $http_response_code]);
    }
}
