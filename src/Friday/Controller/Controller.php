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

namespace Friday\Controller;

class Controller
{

    /**
     * The name of this controller. Controller names are plural, named after the model they manipulate.
     *
     * @var string
     */
    protected $name;

    /**
     * An instance of a Request
     *
     * @var \Friday\Http\Request
     */
    public $request;

    /**
     * An instance of a Response
     *
     * @var \Friday\Http\Response
     */
    public $response;

    /**
     * Instance of the Application.
     *
     * @var \Friday\Foundation\Application
     */
    private $app;

    /**
     * Instance of the Controller.
     *
     * @var \App\Controller\{Name}Controller
     */
    public $controller;

    /**
     * Instance of the View.
     *
     * @var \Friday\View\View
     */
    public $view;

    /**
     * Instance of the Model.
     *
     * @var \App\Model\{Name}Model
     */
    public $model;

    /**
     * Instance of the Controller.
     *
     * @var \Friday\Controller\Controller
     */
    private static $instance;

    /**
     * Instance of the ModelService.
     *
     * @var \Friday\Model\ModelService
     */
    public $modelService;

    /**
     * Create a new Controller instance.
     *
     * @return  void
     */
    public function __construct()
    {
    }

    /**
     * Initialization hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @param  \Friday\Foundation\Application  $app
     * @return void
     */
    public function initialize($app)
    {
        $this->app = $app;
        $this->view = new \Friday\View\View($app);
    }

    /**
     * Returns the controller name.
     *
     * @return string
     * @since 3.6.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the controller name.
     *
     * @param string $name Controller name.
     * @return $this
     * @since 3.6.0
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null  $view
     * @param string       $data
     * @param string|null  $layout
     * @return void.
     */
    public function renderView($viewPath = null, $data = [], $layout = null)
    {
        $renderedView = $this->view->renderView($viewPath, $data, $layout);
        return $renderedView;
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null  $view
     * @param string       $data
     * @param string|null  $layout
     * @return void.
     */
    public function renderTemplate($templatePath = null, $data = [])
    {
        $renderedTemplate = $this->view->renderTemplate($templatePath, $data);
        return $renderedTemplate;
    }

    /**
     * Create Instance of Model.
     *
     * @param  string       $model   View to use for rendering
     *
     * @return \App\Model\{Name}Model
     */
    public function model($model)
    {
        #$model = ucfirst($model).'Model';
        $modelPath = self::$instance->app->findModel($model);
        $modelClass = "App\\Model\\".$model;
        $this->model = new $modelClass();
        self::$instance->modelService = new \Friday\Model\ModelService();
        self::$instance->modelService->initialize(self::$instance->app);
        return $this->model;
        //$appModel->handleModel($controller, $method);
    }

    /**
     * Display View.
     *
     * @param  string       $view  View to use for rendering
     * @param  string       $data  Arguments to use
     *
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewPath = self::$instance->app->findView($view);
        echo self::$instance->render($viewPath, $data);
    }

    /**
     * Display Template.
     *
     * @param  string       $view  Template to use for rendering
     * @param  string       $data  Arguments to use
     *
     * @return void
     */
    public function template($template, $data = [])
    {
        $templatePath = self::$instance->app->findTemplate($template);
        echo self::$instance->renderTemplate($templatePath, $data);
    }

    /**
     * Handle new controller@method from route.
     *
     * @param  string  $controller
     * @param  string  $method
     *
     * @return bool
     */
    public function handleController($controller, $method)
    {
        if($this->app->findController($controller)) {
            $controllerClass = "App\\Controller\\".$controller;
            $this->controller = new $controllerClass();
        }
        if($this->app->hasMethod($this->controller, $method)) {
            self::$instance = $this;
            $output = $this->controller->$method();
            self::$instance = null;
        }
        return $output;
    }

    /**
     * Get value from route->args.
     *
     * @return bool
     */
    protected function getParam()
    {
        return self::$instance->app->getRouteParam();
    }
}
