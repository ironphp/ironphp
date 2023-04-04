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

namespace Friday\Controller;

use Exception;
use Friday\Contracts\Controller\Controller as ControllerInterface;
use Friday\Model\ModelService;
use Friday\View\View;

class Controller implements ControllerInterface
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
     * @var \Friday\Controller\Controller
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
     * @var \Friday\Model\ModelService
     */
    public $model;

    /**
     * Instance of the Controller.
     *
     * @var \Friday\Controller\Controller|null
     */
    private static $instance;

    /**
     * Instance of the ModelService.
     *
     * @var \Friday\Model\ModelService
     */
    public $modelService;

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

        $this->modelService = new ModelService();
        $this->modelService->initialize($app);
        self::$instance = $this;
    }

    /**
     * Returns the controller name.
     *
     * @return string
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
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $viewPath
     * @param array       $data
     * @param string|null $layout
     *
     * @return string
     */
    public function renderView($viewPath = null, $data = [], $layout = null)
    {
        $_token = $this->app->session->getToken();
        $data['_token'] = $_token;

        return $this->view->renderView($viewPath, $data, $layout);
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $templatePath
     * @param array       $data
     *
     * @return string|bool
     */
    public function renderTemplate($templatePath = null, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }

        $_token = $this->app->session->token() ?: $this->app->session->getToken();
        $data['_token'] = $_token;

        return $this->view->renderTemplate($templatePath, $data);
    }

    /**
     * Create Instance of Model.
     *
     * @param string $model View to use for rendering
     *
     * @throws Exception
     *
     * @return \Friday\Model\ModelService|bool
     */
    public function model($model)
    {
        if (self::$instance == null) {
            return false;
        }
        $modelPath = self::$instance->app->findModel($model);

        if ($modelPath === false) {
            $modelPath = self::$instance->app->findModel(ucfirst($model).'Model');
            if ($modelPath === false) {
                throw new Exception($model.' Model file is missing.');
                exit;
            } else {
                $modelClass = 'App\\Model\\'.ucfirst($model).'Model'.'Model';
            }
        } else {
            $modelClass = 'App\\Model\\'.$model;
        }

        return $this->model = new $modelClass();
        //$appModel->handleModel($controller, $method);
    }

    /**
     * Display View.
     *
     * @param string $view View to use for rendering
     * @param array  $data Arguments to use
     *
     * @return void|bool
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
     * @param string $template Template to use for rendering
     * @param array  $data     Arguments to use
     *
     * @return void|bool
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
     * @return null|string
     */
    public function handleController($controller, $method)
    {
        $return = $output = null;
        $this->setName($controller);

        if ($this->app->findController($controller)) {
            $controller = str_replace(DS, '\\', $controller);
            $controllerClass = 'App\\Controller\\'.$controller;
            $this->controller = new $controllerClass();
        }

        // TODO in_array($method, get_class_methods($controllerClass))
        if ($this->app->hasMethod($this->controller, $method)) {
            self::$instance = $this;
            ob_start();
            $return = $this->controller->$method();
            $output = ob_get_clean();
            self::$instance = null;
        }

        if ($return !== null) {
            //handle returned value
            $output .= $return;
        }
        $output = $output ?: null;

        return $output;
    }

    /**
     * Get value from route->args.
     *
     * @return bool|array
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
            //TODO replace mime_content_type with FileInfo
            $mime_type = mime_content_type($file);
            if (in_array($mime_type, ['image/gif', 'image/jpeg', 'image/png'])) {
                $exif_type = exif_imagetype($file);
                if ($exif_type !== false) {
                    $img_ext = image_type_to_extension($exif_type);
                    if ($img_ext == '.jpeg') {
                        $img_ext = '.jpg';
                    }
                    switch ($exif_type) {
                        case IMAGETYPE_JPEG:
                            $img = imagecreatefromjpeg($file);
                            if ($img !== false) {
                                imagejpeg($img, null, 100);
                            }
                            break;
                        case IMAGETYPE_GIF:
                            $img = imagecreatefromgif($file);
                            if ($img !== false) {
                                imagegif($img);
                            }
                            break;
                        case IMAGETYPE_PNG:
                            $img = imagecreatefrompng($file);
                            if ($img !== false) {
                                imagepng($img, null, 100);
                            }
                            break;
                    }
                    if (isset($img) && !empty($img)) {
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
    }

    /**
     * Get Request instance.
     *
     * @return \Friday\Http\Request|bool
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
     * @param string      $theme Theme to use for rendering
     * @param array       $data  Arguments to use
     * @param string|null $file  File to use for rendering
     *
     * @return void|bool
     */
    protected function theme($theme, $data = [], $file = null)
    {
        if (self::$instance == null) {
            return false;
        }
        $themeInfo = self::$instance->app->findTheme($theme, $file);
        $output = self::$instance->renderTheme($themeInfo, $data);
        if ($output) {
            echo $output;
        }
    }

    /**
     * Renders theme for given data.
     *
     * @param array $themeInfo
     * @param array $data
     *
     * @return string|bool
     */
    public function renderTheme($themeInfo, $data = [])
    {
        if (self::$instance == null) {
            return false;
        }

        $this->view->setTheme($themeInfo['themeName']);
        $this->view->setThemePath($themeInfo['themePath']);

        return $this->view->renderTheme($themeInfo, $data);
    }

    /**
     * Save file from $_FILES data.
     *
     * @param array       $file
     * @param string|null $path
     * @param string|null $name
     *
     * @return bool
     *
     * @since 1.0.11
     */
    public function saveFile($file, $path = null, $name = null)
    {
        if ($file['name'] == '' || $file['error'] > 0) {
            return false;
        }

        $storage = STORAGE;

        if (!(file_exists($storage) && is_dir($storage))) {
            if (mkdir($storage) == false) {
                return false;
            }
        }

        $relative_path = trim($path, '\\/').'/';
        $storage = STORAGE.$relative_path;

        if (!(file_exists($storage) && is_dir($storage))) {
            if (mkdir($storage) == false) {
                return false;
            }
        }

        $ext = \mime2ext($file['type']);
        $relative_path .= ($name ?: md5($file['name'].time())).'.'.$ext;
        $filepath = STORAGE.$relative_path;

        $f = move_uploaded_file($file['tmp_name'], $filepath);

        if ($f) {
            return $relative_path;
        } else {
            return false;
        }
    }

    /**
     * Get hash salt value.
     *
     * @return string|bool
     *
     * @moved 1.0.11
     */
    protected function getSalt()
    {
        if (self::$instance == null) {
            return false;
        }

        return self::$instance->app->config['app']['salt'];
    }
}
