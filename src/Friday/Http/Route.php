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

namespace Friday\Http;

use BadMethodCallException;
use Closure;
use Friday\Contracts\Http\RouteInterface;

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
     * @var Route|null
     */
    public static $instance = null;

    /**
     * Route prefix name.
     *
     * @var string|null
     */
    public $prefix = null;

    /**
     * Current Route count.
     *
     * @var int
     */
    public static $routeCount = 0;

    /**
     * Current Route.
     *
     * @var array
     */
    public static $currentRoute;

    /**
     * The view factory instance.
     *
     * @var \Friday\Http\Router
     */
    private $router;

    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct(/*$path, $controllerClass*/)
    {
        self::$instance = $this->setRouter(new Router());
        //$this->path = $path;
        //$this->controllerClass = $controllerClass;
    }

    /**
     * register a GET method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function get($route, $mix = null, $view = null)
    {
        return self::$instance->register('GET', $route, $mix, $view);
    }

    /**
     * register a POST method route.
     *
     * @param string      $route
     * @param string|null $mix
     * @param string|null $view
     *
     * @return \Friday\Http\Route
     */
    public static function post($route, $mix = null, $view = null)
    {
        return self::$instance->register('POST', $route, $mix, $view);
    }

    /**
     * register a GET method route with view.
     *
     * @param string              $route
     * @param string|Closure|null $view
     * @param array               $data
     *
     * @return \Friday\Http\Route
     */
    public static function view($route, $view = null, array $data = [])
    {
        return self::$instance->register('GET', $route, null, $view, $data);
    }

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
    public function register($method, $route, $mix = null, $view = null, $data = [])
    {
        $args = [];
        if ($mix == null && $view == null) {
            $mix = 'IndexController@index';
        }

        if (is_string($mix) && $mix !== null) {
            if (strpos($mix, '@') === false) {
                $mix = $mix.'@index';
            }
        }

        $route = trim($route, '/ ');
        $route = (self::$instance->prefix == null) ? $route : rtrim(self::$instance->prefix.'/'.$route, '/');
        $array = $route === '' ? [] : explode('/', $route);
        $size = count($array);
        $route = '/'.$route;

        if (strpos($route, '{') !== false) {
            $to = 0;
            $param = true;
            foreach ($array as $i => $uriPiece) {
                $uriPiece = trim($uriPiece);
                if (strpos($uriPiece, '{') !== false) {
                    if (
                        strpos($uriPiece, '{') === 0 &&
                        strpos($uriPiece, '}') !== false &&
                        strpos($uriPiece, '}') === (strlen($uriPiece) - 1)
                    ) {
                        $args[$uriPiece] = rtrim(ltrim($uriPiece, '{'), '}');
                    } else {
                        $args[$uriPiece] = null;
                    }
                } else {
                    $to = $i + 1;
                    $args[$uriPiece] = null;
                }
            }
            $base_size = $to;
            $base_route = array_slice($array, 0, $to, true);
        } else {
            $param = false;
            $base_size = $size;
            $args = null;
            $base_route = $route;
        }
        $base_route = is_array($base_route) ? implode('/', $base_route) : $base_route;

        if (trim($base_route) === '') {
            $base_route = '/';
        }

        self::$routeCount = count(self::$instance->routes);
        self::$instance->routes[] = [$method, $route, $mix, $view, $data, $args, $size, $base_size, $param];

        return self::$instance;
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
     * @param string $prefix
     *
     * @return \Friday\Http\Route
     */
    public static function prefix($prefix)
    {
        self::$instance->prefix = $prefix;

        return self::$instance;
    }

    /**
     * Registered group routes.
     *
     * @param \Closure $closure
     *
     * @return void
     */
    public static function group($closure)
    {
        $backtrace = debug_backtrace();
        //!isset($this) || $backtrace[0]['type'] == '::' ||
        if (self::$instance->prefix == null) {
            exit('Can not be called statically or directly. Use Route::prefix(name)->group(routes-to-be-registered)');
        }
        if ($closure instanceof Closure) {
            call_user_func($closure);
        }
        self::$instance->prefix = null;
    }

    /**
     * Registered redirect routes.
     *
     * @param string $routeFrom
     * @param string $routeTo
     * @param int    $http_response_code
     *
     * @return void
     */
    public static function redirect($routeFrom, $routeTo, $http_response_code = 302)
    {
        $closure = function ($routeTo, $replace, $http_response_code) {
            Response::$redirectHeader = [$routeTo, $replace, $http_response_code];
        };
        self::$instance->register('GET', $routeFrom, $closure, null, [$routeTo, true, $http_response_code]);
    }

    /**
     * Get specific or all Registered redirect routes.
     *
     * @param string|null $uri
     *
     * @return array
     */
    public static function getRoute($uri = null)
    {
        $routes = self::$instance->routes;
        if ($uri === null) {
            foreach ($routes as $i => $route) {
                //TODO
                $routes[$i] = \array_diff_key($routes[$i], [3 => 'xy', 4 => 'xy', 5 => 'xy', 6 => 'xy', 7 => 'xy', 8 => 'xy']);
                if (!is_string($routes[$i][2]) && is_object($routes[$i][2]) && is_callable($routes[$i][2]) && $routes[$i][2] instanceof \Closure) {
                    $routes[$i][2] = 'Closure';
                }
            }

            return $routes;
        } else {
            foreach ($routes as $i => $route) {
                if ($route[1] === '/'.ltrim($uri, '/')) {
                    return $route;
                }
            }
        }
    }

    /**
     * register a PUT method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function put($route, $mix = null, $view = null)
    {
        return self::$instance->register('PUT', $route, $mix, $view);
    }

    /**
     * register a DELETE method route.
     *
     * @param string              $route
     * @param string|Closure|null $mix
     * @param string|null         $view
     *
     * @return \Friday\Http\Route
     */
    public static function delete($route, $mix = null, $view = null)
    {
        return self::$instance->register('DELETE', $route, $mix, $view);
    }

    /**
     * register a resource CRUD route.
     *
     * @param string      $route
     * @param string|null $controller
     *
     * @return bool
     */
    public static function resource($route, $controller = null)
    {
        if ($controller === null) {
            $controller = 'IndexController';
        }
        $prefix = strtolower(str_replace(['Controller', '\\'], ['', '.'], $controller));

        //index
        self::$instance->register('GET', $route, "$controller@index")->name("$prefix.index");

        //show
        self::$instance->register('GET', $route.'/{id}', "$controller@show")->name("$prefix.show");

        //create
        self::$instance->register('GET', $route.'/create', "$controller@create")->name("$prefix.create");

        //store
        self::$instance->register('POST', $route.'/', "$controller@store")->name("$prefix.store");

        //show
        self::$instance->register('GET', $route.'/{id}/edit', "$controller@edit")->name("$prefix.edit");

        //store
        self::$instance->register('PUT', $route.'/{id}', "$controller@update")->name("$prefix.update");

        //show
        self::$instance->register('DELETE', $route.'/{id}', "$controller@destroy")->name("$prefix.destroy");
    }

    /**
     * name routes.
     *
     * @param string $name
     *
     * @return void
     *
     * @since 1.0.7
     */
    public static function name($name)
    {
        self::$instance->routes[$name] = self::$instance->routes[self::$routeCount];
        unset(self::$instance->routes[self::$routeCount]);
    }

    /**
     * Set the router instance on the route.
     *
     * @param \Friday\Http\Router $router
     *
     * @return $this
     *
     * @since 1.0.7
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Get the router instance.
     *
     * @return \Friday\Http\Router
     *
     * @since 1.0.7
     */
    public function getRouter()
    {
        if ($this->router != null) {
            return $this->router;
        } else {
            return new Router();
        }
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return \Friday\Http\Route
     *
     * @since 1.0.7
     */
    public static function __callStatic($method, $parameters)
    {
        if (!method_exists(self::$instance->router, $method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            ));
        }

        return call_user_func_array([self::$instance->router, $method], $parameters);
    }
}
