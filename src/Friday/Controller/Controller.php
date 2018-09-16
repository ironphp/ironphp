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
     * @var \App\View\{Name}View
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
     * @param  \Friday\Foundation\Application  $app
     *
     * @return  void
     */
    public function __construct($app)
    {
        $this->app = $app;

        /*
        if ($name !== null) {
            $this->name = $name;
        }

        if ($this->name === null && $request && $request->getParam('controller')) {
            $this->name = $request->getParam('controller');
        }

        if ($this->name === null) {
            list(, $name) = namespaceSplit(get_class($this));
            $this->name = substr($name, 0, -10);
        }

        $this->setRequest($request ?: new ServerRequest());
        $this->response = $response ?: new Response();

        if ($eventManager !== null) {
            $this->setEventManager($eventManager);
        }

        $this->modelFactory('Table', [$this->getTableLocator(), 'get']);
        $plugin = $this->request->getParam('plugin');
        $modelClass = ($plugin ? $plugin . '.' : '') . $this->name;
        $this->_setModelClass($modelClass);

        if ($components !== null) {
            $this->components($components);
        }

        $this->initialize();

        $this->_mergeControllerVars();
        $this->_loadComponents();
        $this->getEventManager()->on($this);
        */
    }

    /**
     * Initialization hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @return void
     */
    public function initialize()
    {
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
     * Instantiates the correct view class, hands it its data, and uses it to render the view output.
     *
     * @param string|null $view View to use for rendering
     * @param string|null $layout Layout to use
     * @return \Cake\Http\Response A response object containing the rendered view.
     * @link https://book.cakephp.org/3.0/en/controllers.html#rendering-a-view
     */
    public function render($view = null, $layout = null)
    {
        $builder = $this->viewBuilder();
        if (!$builder->getTemplatePath()) {
            $builder->setTemplatePath($this->_viewPath());
        }

        if ($this->request->getParam('bare')) {
            $builder->enableAutoLayout(false);
        }
        $this->autoRender = false;

        $event = $this->dispatchEvent('Controller.beforeRender');
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        }
        if ($event->isStopped()) {
            return $this->response;
        }

        if ($builder->getTemplate() === null && $this->request->getParam('action')) {
            $builder->setTemplate($this->request->getParam('action'));
        }

        $this->View = $this->createView();
        $contents = $this->View->render($view, $layout);
        $this->response = $this->View->response->withStringBody($contents);

        return $this->response;
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
     * @param  string       $view View to use for rendering
     * @param  string|null  $args Arguments to use
     *
     * @return void
     */
    public function view($view, $args = null)
    {
        #$view = ucfirst($view).'View';
        $viewPath = self::$instance->app->findView($view);
        #$viewClass = "App\\View\\".$view;
        #$this->view = new $viewClass();
        $viewData = file_get_contents($viewPath);
        foreach($args as $key => $val) {
            $viewData = str_replace('{{'.$key.'}}', $val, $viewData);
        }
        echo $viewData;
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
            $this->controller->$method();
            self::$instance = null;
        }
    }

}
