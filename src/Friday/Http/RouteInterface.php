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

interface RouteInterface {

    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct();
 
    /**
     * register a GET method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function get($route, $mix);

    /**
     * register a POST method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function post($route, $mix);


    /**
     * register a GET method route with view.
     *
     * @param  string                $route
     * @param  string|null           $view
     * @param  array                 $data
     * @return bool
     */
    public function view($route, $view = null, array $data = []);

    /**
     * register a route.
     *
     * @param  string                $route
     * @param  string|callback|null  $mix
     * @param  string|null           $view
     * @param  array                 $data
     * @return void
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
     * @param  string  $prefix
     * @return \Friday\Http\Route
     */
    public function prefix($prefix);

    /**
     * Registered group routes.
     *
     * @param  \Closure  $closure
     * @return void
     */
    public function group($closure);

    /**
     * Registered redirect routes.
     *
     * @param  string  $routeFrom
     * @param  string  $routeTo
     * @param  int     $http_response_code
     * @return void
     */
    public function redirect($routeFrom, $routeTo, $http_response_code);
}
