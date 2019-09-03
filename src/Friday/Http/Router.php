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
 * @link          https://github.com/IronPHP/IronPHP
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Exception;
use OutOfRangeException;

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
    public function __construct()
    {
    }

    /**
     * match uri, http method to routes.
     *
     * @param array  $allRoute
     * @param string $uriRoute
     * @param string $httpMethod GET/POST
     *
     * @throws Exception|OutOfRangeException
     *
     * @return \Friday\Http\Route
     */
    public function route($allRoute, $uriRoute, $httpMethod)
    {
        if (is_null($allRoute) || (is_array($allRoute) && !count($allRoute))) {
            throw new Exception('No routes. Define them at /app/Route/web.php');
        }
        $route = trim($uriRoute, '/ ');
        $array = $route === '' ? [] : explode('/', $route);
        $size = count($array);
        $allRoute = array_filter($allRoute, function ($v) use ($size, $httpMethod) {
            if ($v[0] != $httpMethod) {
                return false;
            }
            if ($v[6] == $size) {
                return true;
            }
            if ($v[8] == true && $v[7] <= $size && $v[6] >= $size) {
                return true;
            }
        });
        foreach ($allRoute as $route) {
            if ($this->match($route, $uriRoute)) {
                return $route;
            }
        }
        foreach ($allRoute as $route) {
            if ($this->match($route, $uriRoute, true)) {
                return $route;
            }
        }
        // need a 404 route
        //$response->addHeader("404 Page Not Found")->send();
        throw new OutOfRangeException('No route matched the given URI : '.$uriRoute);
    }

    /**
     * match uri, http method to routes.
     *
     * @param array  $route
     * @param string $uriRoute
     * @param bool   $parameterized
     *
     * @return bool
     */
    public function match($route, $uriRoute, $parameterized = false)
    {
        if ($route[1] === $uriRoute) {
            return true;
        }
        if ($parameterized) {
            $array = explode('/', trim($route[1], '/'));
            $size = count($array);
            if (strpos($route[1], '{') !== false) {
                $array = explode('/', trim($route[1], '/'));
                $arrayUriRoute = explode('/', trim($uriRoute, '/'));
                foreach ($array as $i => $piece) {
                    if (!isset($arrayUriRoute[$i]) || $arrayUriRoute[$i] === null || $arrayUriRoute[$i] === '') {
                        if (strpos($piece, '{') === false || strpos($piece, '?') !== 1) {
                            return false;
                        } else {
                            //$args[trim($piece, '{?}')] = NULL; Couses problem in default param closure passed in route
                        }
                    } elseif ($arrayUriRoute[$i] != $piece) {
                        if (strpos($piece, '{') === false) {
                            return false;
                        } else {
                            $args[trim($piece, '{?}')] = $arrayUriRoute[$i];
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
