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

namespace Friday\Foundation;

//use Closure; //comment does not affect on synonymous function, bound to closure & Application instance // *RECURSION*

class Application
{
    /**
     * The IronPHP framework version.
     *
     * @var string
     */
    const VERSION = '0.0.1-dev';

    /**
     * The base path for the IronPHP installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * FrontController instance.
     *
     * @var object
     */
    public $frontController;

    /**
     * Request instance.
     *
     * @var object
     */
    public $request;

    /**
     * Route instance.
     *
     * @var object
     */
    public $route;

    /**
     * Router instance.
     *
     * @var object
     */
    public $router;

    /**
     * Dispatcher instance.
     *
     * @var object
     */
    public $dispatcher;

    /**
     * Response instance.
     *
     * @var object
     */
    public $response;

    /**
     * Matched Route to uri.
     *
     * @var array
     */
    public $matchRoute;

    /**
     * Configurations from /config/*.php.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new Friday application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->setIntallTime();

        $this->config['basePath'] = $this->basePath(); 

        $this->config['app'] = $this->requireFile(
            $this->basePath('config/app.php')
        );
        $this->config['db'] = $this->requireFile(
            $this->basePath('config/database.php')
        );

        if (PHP_SAPI !== 'cli') {
            $this->frontController = new \Friday\Http\FrontController();

            $this->request = $this->frontController->request();

            $this->route = $this->frontController->route();
            \Friday\Http\Route::$instance = $this->route;

            $this->requireFile(
                $this->basePath('app/Route/web.php')
            );

            $this->router = $this->frontController->router();
            $this->matchRoute = $this->router->route(
                $this->route->routes,
                $this->request->uri,
                $this->request->serverRequestMethod
            );

            $this->dispatcher = $this->frontController->dispatcher();
            $action = $this->dispatcher->dispatch(
                $this->matchRoute,
                $this->request
            );

            if($action[0] == 'output') {
                $output = $action[1];
            }
            elseif($action[0] == 'controller_method') {
                $controller = $action[1];
                $method = $action[2];
                ob_start();
                $appController = new \Friday\Controller\Controller($this);
                $appController->handleController($controller, $method);
                $output = ob_get_clean();
            }

            $this->response = $this->frontController->response($_SERVER['SERVER_PROTOCOL']);
            $this->response->addHeader()->send($output);
        }
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * Get the base path of the IronPHP installation.
     *
     * @param  string  $path Optionally, a path to append to the base path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Find a file.
     *
     * @param  string  $path
     * @return bool
     */
    public function findFile($path)
    {
        if(file_exists($path)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Find a Model.
     *
     * @param  string  $model
     * @return string  full model file path
     */
    public function findModel($model)
    {
        $file = $this->basePath("app/Model/$model.php");
        if($this->findFile($file)) {
            return $file;
        }
        else {
            throw new \Exception($file." Model file is missing.");
            exit;
        }
    }

    /**
     * Find a View.
     *
     * @param  string  $view
     * @return string  full view file path
     */
    public function findView($view)
    {
        $file = $this->basePath("app/View/$view.html");
        if($this->findFile($file)) {
            return $file;
        }
        else {
            throw new \Exception($file." View file is missing.");
            exit;
        }
    }

    /**
     * Find a Controller.
     *
     * @param  string  $controller
     * @return bool
     */
    public function findController($controller)
    {
        $file = $this->basePath("app/Controller/$controller.php");
        if($this->findFile($file)) {
            return true;
        }
        else {
            throw new \Exception($file." Controller file is missing.");
            exit;
        }
    }

    /**
     * Check if Controller has method or not.
     *
     * @param  Object  $controllerObj
     * @param  string  $method
     * @return bool
     */
    public function hasMethod($controllerObj, $method)
    {
        if(method_exists($controllerObj, $method)) {
            return true;
        }
        else {
            throw new \Exception($method." method is missing in ".get_class($controllerObj)."Controller.");
            exit;
        }
    }

    /**
     * Require a file.
     *
     * @param  string  $file
     * @return void
     */
    public function requireFile($file)
    {
        if($this->findFile($file)) {
            return require($file);
        }
        else {
            throw new \Exception($file." file is missing.");
            exit;
        }
    }

    /**
     * Set Installtion Time/Version to app/install file used for checking updates.
     *
     * @return bool
     */
    public function setIntallTime()
    {
        $file = $this->basePath('app/install');
        if(!file_exists($file)) {
            $content = json_encode(['time'=>time(), 'version' => $this->version()]);
            file_put_contents($file, $content);
        }
    }
}
