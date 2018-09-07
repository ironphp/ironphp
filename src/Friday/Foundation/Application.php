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
            $this->dispatcher->dispatch(
                $this->matchRoute,
                $this->request
            );

            #$this->response = $this->frontController->response($_SERVER['SERVER_PROTOCOL']);
            #$this->response->addHeader('nice')->send();
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
    public function findFile($path) {
        if(file_exists($path)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Require a file.
     *
     * @param  string  $file
     * @return void
     */
    public function requireFile($file) {
        if($this->findFile($file)) {
            require($file);
        }
        else {
            throw new Exception($file." file is missing.");
            exit;
        }
    }
}
