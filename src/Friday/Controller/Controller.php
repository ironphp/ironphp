<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * @link
 * @since 1.0.0
 *
 * @license MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther  GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Controller;

use Friday\Model\ModelService;
use Friday\View\View;

class Controller
{
    /**
     * The name of this controller. Controller names are plural, named after the model they manipulate.
     *
     * @var string
     */
    protected $name;

    /**
     * An instance of a Request.
     *
     * @var \Friday\Http\Request
     */
    public $request;

    /**
     * An instance of a Response.
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
     * @var \{App}\Model\{Name}Model
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
     * @return void
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
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function initialize($app)
    {
        $this->app = $app;
        $this->view = new View($app);
    }

    /**
     * Returns the controller name.
     *
     * @return string
     *
     * @since 0.0.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the controller name.
     *
     * @param string $name Controller name.
     *
     * @return $this
     *
     * @since 0.0.0
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $view
     * @param string      $data
     * @param string|null $layout
     *
     * @return void.
     */
    public function renderView($viewPath = null, $data = [], $layout = null)
    {
        if (self::$instance == null) {
            return false;
        }
        $_token = $this->app->session->getToken();
        $data['_token'] = $_token;
        $renderedView = $this->view->renderView($viewPath, $data, $layout);

        return $renderedView;
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $view
     * @param string      $data
     * @param string|null $layout
     *
     * @return void.
     */
    public function renderTemplate($templatePath = null, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }
        $_token = $this->app->session->getToken();
        $data['_token'] = $_token;
        $renderedTemplate = $this->view->renderTemplate($templatePath, $data);

        return $renderedTemplate;
    }

    /**
     * Create Instance of Model.
     *
     * @param string $model View to use for rendering
     *
     * @return \{App}\Model\{Name}Model
     */
    public function model($model)
    {
        if (self::$instance == null) {
            return false;
        }
        //$model = ucfirst($model).'Model';
        $modelPath = self::$instance->app->findModel($model);
        $modelClass = 'App\\Model\\'.$model;
        $this->model = new $modelClass();
        self::$instance->modelService = new ModelService();
        self::$instance->modelService->initialize(self::$instance->app);

        return $this->model;
        //$appModel->handleModel($controller, $method);
    }

    /**
     * Display View.
     *
     * @param string $view View to use for rendering
     * @param string $data Arguments to use
     *
     * @return void
     */
    public function view($view, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }
        $viewPath = self::$instance->app->findView($view);
        echo self::$instance->renderView($viewPath, $data);
    }

    /**
     * Display Template.
     *
     * @param string $view Template to use for rendering
     * @param string $data Arguments to use
     *
     * @return void
     */
    public function template($template, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }
        $templatePath = self::$instance->app->findTemplate($template);
        echo self::$instance->renderTemplate($templatePath, $data);
    }

    /**
     * Handle new controller@method from route.
     *
     * @param string $controller
     * @param string $method
     *
     * @return mix
     */
    public function handleController($controller, $method)
    {
        $this->setName($controller);
        if ($this->app->findController($controller)) {
            $controllerClass = 'App\\Controller\\'.$controller;
            $this->controller = new $controllerClass();
        }
        if ($this->app->hasMethod($this->controller, $method)) {
            self::$instance = $this;
            ob_start();
            $return = $this->controller->$method();
            $output = ob_get_clean();
            self::$instance = null;
        }
        if ($return !== null) {
            //handle returned value
        }
        $output = $output ?: null;

        return $output;
    }

    /**
     * Get value from route->args.
     *
     * @return bool
     */
    protected function getParam()
    {
        if (self::$instance == null) {
            return false;
        }

        return self::$instance->app->getRouteParam();
    }

    /**
     * Download a file.
     *
     * @param string $file
     * @param string $name
     *
     * @return bool
     */
    protected function downloadFile($file, $name = null)
    {
        if (self::$instance == null) {
            return false;
        }
        if (file_exists($file) && is_file($file) && is_readable($file)) {
            $mime_type = mime_content_type($file);
            if (in_array($mime_type, ['image/gif', 'image/jpeg', 'image/png'])) {
                $exif_type = exif_imagetype($file);
                $img_ext = image_type_to_extension($exif_type);
                if ($img_ext == '.jpeg') {
                    $img_ext = '.jpg';
                }
                switch ($exif_type) {
                case IMAGETYPE_JPEG:
                    $img = imagecreatefromjpeg($file);
                    imagejpeg($img, null, 100);
                    break;
                case IMAGETYPE_GIF:
                    $img = imagecreatefromgif($file);
                    imagegif($img);
                    break;
                case IMAGETYPE_PNG:
                    $img = imagecreatefrompng($file);
                    imagepng($img, null, 100);
                    break;
                }
                if (!$img) {
                } else {
                    imagedestroy($img);
                }
                $headers[] = "Content-Type: $mime_type";
                if ($name !== null && trim($name) !== '') {
                    //download - without it image will not be downloaded just displayed on broswer
                    if ((strlen($name) - strlen($img_ext)) !== strpos($name, $img_ext)) {
                        $name .= $img_ext;
                    }
                    $headers[] = "Content-Disposition: attachment; filename=$name";
                }
                self::$instance->app->headers = $headers;
            }
        }
    }

    /**
     * Get Request instance.
     *
     * @return \Friday\Http\Request
     */
    protected function getRequest()
    {
        if (self::$instance == null) {
            return false;
        }

        return self::$instance->app->request;
    }

    /**
     * Display Theme.
     *
     * @param string $theme Theme to use for rendering
     * @param string $data  Arguments to use
     * @param string $file  File to use for rendering
     *
     * @return void
     */
    protected function theme($theme, $data = [], $file = null)
    {
        if (self::$instance == null) {
            return false;
        }
        $themeInfo = self::$instance->app->findTheme($theme, $file);
        echo self::$instance->renderTheme($themeInfo, $data);
    }

    /**
     * Renders theme for given data.
     *
     * @param array  $themeInfo
     * @param string $data
     *
     * @return void
     */
    public function renderTheme($themeInfo, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }

        return $this->view->renderTheme($themeInfo, $data);
    }
}
