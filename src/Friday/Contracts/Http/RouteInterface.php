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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Http;

use Closure;
use Friday\Http\Router;

interface RouteInterface
{
    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct();

    /**
     * register a GET method route.
     *
     * @param string      $route
     * @param string|null $mix
     * @param string|null $view
     *
     * @return \Friday\Http\Route
     */
    public static function get($route, $mix, $view = null);

    /**
     * register a POST method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function post($route, $mix = null, $view = null);

    /**
     * register a GET method route with view.
     *
     * @param string      $route
     * @param string|null $view
     * @param array       $data
     *
     * @return \Friday\Http\Route
     */
    public static function view($route, $view = null, array $data = []);

    /**
     * register a route.
     *
     * @param string              $method
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     * @param array               $data
     *
     * @return \Friday\Http\Route
     */
    public function register($method, $route, $mix = null, $view = null, $data = []);

    /**
     * sort registered routes by there base uri.
     *
     * @return void
     */
    public function sortRoute();

    /**
     * Group routes by prefix.
     *
     * @param string $prefix
     *
     * @return \Friday\Http\Route
     */
    public static function prefix($prefix);

    /**
     * Registered group routes.
     *
     * @param \Closure $closure
     *
     * @return void
     */
    public static function group($closure);

    /**
     * Registered redirect routes.
     *
     * @param string $routeFrom
     * @param string $routeTo
     * @param int    $http_response_code
     *
     * @return void
     */
    public static function redirect($routeFrom, $routeTo, $http_response_code);

    /**
     * Get specific or all Registered redirect routes.
     *
     * @param string $uri
     *
     * @return array
     */
    public static function getRoute($uri = null);

    /**
     * register a PUT method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function put($route, $mix = null, $view = null);

    /**
     * register a DELETE method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function delete($route, $mix = null, $view = null);

    /**
     * register a resource CRUD route.
     *
     * @param string      $route
     * @param string|null $controller
     *
     * @return bool
     */
    public static function resource($route, $controller = null);

    /**
     * name routes.
     *
     * @param string $name
     *
     * @return void
     *
     * @since 1.0.7
     */
    public static function name($name);

    /**
     * Set the router instance on the route.
     *
     * @param  \Friday\Http\Router  $router
     * @return $this
     * @since 1.0.7
     */
    public function setRouter(Router $router);

    /**
     * Get the router instance.
     *
     * @return \Friday\Http\Router
     * @since 1.0.7
     */
    public function getRouter();
}
