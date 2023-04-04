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
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Foundation;

use Exception;
use Friday\Controller\Controller;
use Friday\Helper\Cookie;

/**
 * Runs a server application.
 */
class Server extends Application
{
    /**
     * Matched Route to uri.
     *
     * @var \Friday\Http\Route
     */
    public $matchRoute;

    /**
     * Instanse of Cookie.
     *
     * @var \Friday\Helper\Cookie
     */
    public $cookie;

    /**
     * Create a new Friday application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     *
     * @exception \Exception
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        //TODO - boot http server ???

        //load cookie
        // TODO - Friday\Helper\Cookie::_host is .127.0.0.1:8000
        $this->cookie = new Cookie();

        //request - get url, client data
        $this->request = $this->frontController->request();
        $this->request->setConstant();

        // TODO - token checking should be in request
        if ($this->request->getRequestMethod() == 'POST') {
            if (!isset($_POST['_token']) || $_POST['_token'] != $this->session->get('_token')) {
                throw new Exception('Token is missing or invalid for this request.');
                exit;
            }
        }
        define('REQUEST_CATCHED', microtime(true));

        //router
        $this->router = $this->frontController->router();
        $this->matchRoute = $this->router->findRoute(
            $this->route->routes,
            $this->request->uri,
            $this->request->serverRequestMethod
        );

        if ($this->matchRoute === false) {
            $this->matchRoute = ['GET', '/404', function () {
                http_response_code(404);
                echo 'Page not found. Error 404';
                //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                exit;
            }, null, [], [], 4, 4, 1];
        }

        $this->request->setParam('Closure', $this->router->args);

        define('ROUTE_MATCHED', microtime(true));

        //dispatcher
        $this->dispatcher = $this->frontController->dispatcher();
        $action = $this->dispatcher->dispatch(
            $this->matchRoute,
            $this->request
        );
        define('DISPATCHER_INIT', microtime(true));

        //dispatch process
        $output = null;
        $appController = new Controller();
        $appController->initialize($this);
        if (isset($action['output'])) {
            $output = $action['output'][0].$action['output'][1];
        } elseif (isset($action['controller'])) {
            $controller = $action['controller'][0];
            $method = $action['controller'][1];
            $output = $appController->handleController($controller, $method);
        } elseif (isset($action['view'])) {
            $view = $action['view'][0];
            $data = $action['view'][1];
            $viewPath = $this->findView($view);
            $output = $appController->renderView($viewPath, $data);
            if ($output === false) {
                throw new Exception('Can not render view.');
                exit;
            }
        }
        define('DISPATCHED', microtime(true));

        //responce
        $this->response = $this->frontController->response($_SERVER['SERVER_PROTOCOL']);
        $this->response->addHeaders($this->headers)->sendHeader($output);
        define('RESPONSE_SEND', microtime(true));
    }
}
