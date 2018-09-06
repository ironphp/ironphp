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
        if($route[0] === $httpMethod && $route[1] === $uriRoute) {
            return true;
        }
        else {
            return false;
        }
    }
}