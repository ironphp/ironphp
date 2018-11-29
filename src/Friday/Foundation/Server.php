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

namespace Friday\Foundation;

use Friday\Helper\Cookie;
use Friday\Http\FrontController;
use Friday\Http\Route;
use Friday\Controller\Controller;

/**
 * Runs a server application.
 */
class Server extends Application
{
    /**
     * FrontController instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $frontController;

    /**
     * Request instance.
     *
     * @var \Friday\Http\Request
     */
    public $request;

    /**
     * Route instance.
     *
     * @var \Friday\Http\Route
     */
    public $route;

    /**
     * Router instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $router;

    /**
     * Dispatcher instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $dispatcher;

    /**
     * Response instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $response;

    /**
     * Matched Route to uri.
     *
     * @var array
     */
    public $matchRoute;

    /**
     * Instanse of Session.
     *
     * @var \Friday\Helper\Session
     */
    public $session;

    /**
     * Headers to be sent.
     *
     * @var array
     */
    public $headers = [];

    /**
     * Create a new Friday application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        #boot http server ???

        $this->requireFile(
            $this->basePath('config/paths.php')
        );

        #load cookie
        $this->cookie = new Cookie();

        #frontcontroller
        $this->frontController = new FrontController();

        #get url, client data
        $parse = $this->parseUri();

        #request
        $this->request = $this->frontController->request($parse);
        $this->request->setConstant();
        define('REQUEST_CATCHED', microtime(true));

        #route
        $this->route = $this->frontController->route();
        Route::$instance = $this->route;
        define('ROUTES_LOADED', microtime(true));

        #load routes
        $this->requireFile(
            $this->basePath('app/Route/web.php')
        );
        $this->route->sortRoute();

        #router
        $this->router = $this->frontController->router();
        $this->matchRoute = $this->router->route(
            $this->route->routes,
            $this->request->uri,
            $this->request->serverRequestMethod
        );
        $this->request->setParam('Closure', $this->router->args);

        define('ROUTE_MATCHED', microtime(true));

        #dispatcher
        $this->dispatcher = $this->frontController->dispatcher();
        $action = $this->dispatcher->dispatch(
            $this->matchRoute,
            $this->request
        );
        define('DISPATCHER_INIT', microtime(true));

        #dispatch process
        $appController = new Controller();
        $appController->initialize($this);
        if(isset($action['output'])) {
            $output = $action['output'][0].$action['output'][1];
        }
        elseif(isset($action['controller'])) {
            $controller = $action['controller'][0];
            $method = $action['controller'][1];
            $output = $appController->handleController($controller, $method);
        }
        elseif(isset($action['view'])) {
            $view = $action['view'][0];
            $data = $action['view'][1];
            $viewPath = $this->findView($view);
            $output = $appController->render($viewPath, $data);
        }
        define('DISPATCHED', microtime(true));

        #responce
        $this->response = $this->frontController->response($_SERVER['SERVER_PROTOCOL']);
        $this->response->addHeaders($this->headers)->sendHeader($output);
        define('RESPONSE_SEND', microtime(true));
    }

    /**
     * Get parameter passed in route.
     *
     * @return array
     */
    public function getRouteParam()
    {
        return $this->router->args;
    }
    
    /**
     * Parse Uri and get path uri, params, server method.
     *
     * @return array
     */
    public function parseUri()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $uri = str_replace(['{', '}'], '', urldecode($uri));
        $extDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $uri = str_replace($extDir, '', $uri);
        $uri = rtrim($uri, '/');
        $uri = empty($uri) ? '/' : $uri;
        $serverRequestMethod = $_SERVER['REQUEST_METHOD'];
        $params = $GLOBALS['_'.$serverRequestMethod];
        if(!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $https = true;
        }
        else {
            $https = false;
        }
        $host = $_SERVER['HTTP_HOST'].str_replace('\\', '/', $extDir);
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if($serverRequestMethod === 'POST') {
            $params = ['GET' => $_GET, 'POST' => $params];
        }
        else {
            $params = ['GET' => $params, 'POST' => []];
        }

        return ['uri' => $uri, 'params' => $params, 'method' => $serverRequestMethod, 'https' => $https, 'host' => $host, 'ip' => $ip];
    }
}