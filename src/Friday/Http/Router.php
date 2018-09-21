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

class Router
{
    /**
     * Closure arguments of matched route.
     *
     * @var array
     */
    public $args = [];

    /**
     * Create a new Router instance.
     *
     * @return void
     */
    public function __construct() {
    }
 
    /**
     * match uri, http method to routes.
     *
     * @param  array   $allRoute
     * @param  string  $uriRoute
     * @param  enum    $httpMethod  GET/POST
     * @return object  Route
     */
    public function route($allRoute, $uriRoute, $httpMethod) {
        if(is_null($allRoute) || (is_array($allRoute) && !count($allRoute)) ) {
            throw new \Exception("No routes. Define them at /app/Route/web.php");
        }
        foreach ($allRoute as $route) {
            if ($this->match($route, $uriRoute, $httpMethod)) {
                return $route;
            }
        }
        // need a 404 route
        //$response->addHeader("404 Page Not Found")->send();
        throw new \OutOfRangeException("No route matched the given URI : ".$uriRoute);
    }

    /**
     * match uri, http method to routes.
     *
     * @param  array   $allRoute
     * @param  string  $uriRoute
     * @param  enum    $httpMethod  GET/POST
     * @return object  Route
     */
    public function match($route, $uriRoute, $httpMethod) {
        if($route[0] !== $httpMethod) {
            return false;
        }
        if($route[0] === $httpMethod && $route[1] === $uriRoute) {
            return true;
        }
        else {
            if(strpos($route[1], '{') !== false) {
                $array = explode('/', trim($route[1], '/'));
                $arrayUriRoute = explode('/', trim($uriRoute, '/'));
                foreach($array as $i => $piece) {
                    if(!isset($arrayUriRoute[$i])) {
                        if(strpos($piece, '{') === false || strpos($piece, '?') !== 1) {
                            return false;
                        }
                        else {
                            //$args[trim($piece, '{?}')] = NULL;
                        }
                    }
                    elseif($arrayUriRoute[$i] != $piece) {
                        if(strpos($piece, '{') === false) {
                            return false;
                        }
                        else {
                            $args[trim($piece, '{}')] = $arrayUriRoute[$i];
                        }
                    }
                }
                $this->args = isset($args) ? $args : [];
                return true;
            }
            return false;
        }
    }
}