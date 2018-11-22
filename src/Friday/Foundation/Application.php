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

use Friday\Environment\GetEnv;
use Friday\Helper\Session;

/**
 * Runs an application invoking all the registered application.
 */
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

        #set locale
        date_default_timezone_set("Asia/Kolkata");

        #load function
        $this->requireFile(
            $this->basePath('src\Friday\Helper\Function.php')
        );

        $this->config['basePath'] = $this->basePath(); 

        #Configurator
        #enviro config
        $env = new GetEnv( $this->basePath(), '.env' );
        $env->load();

        #set install config
        if($this->getIntallTime(true) === false) {
            $this->setIntallTime();
        }
        elseif(empty(env('APP_KEY'))) {
            echo "APP_KEY is not defined in .env file, define it by command: php jarvis key";
        }

        #load config
        $this->config['app'] = $this->requireFile(
            $this->basePath('config/app.php'), true
        );
        $this->config['db'] = $this->requireFile(
            $this->basePath('config/database.php'), true
        );
        define('CONFIG_LOADED', microtime(true));

        #load session
        $this->session = new Session();
        if(!$this->session->isRegistered()) {
            $this->session->register();
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
        if(file_exists($path) && is_file($path)) {
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
        $file = $this->basePath("app/View/$view.php");
        if($this->findFile($file)) {
            return $file;
        }
        else {
            throw new \Exception($file." View file is missing.");
            exit;
        }
    }

    /**
     * Find a Template.
     *
     * @param  string  $template
     * @return string  full template file path
     */
    public function findTemplate($template)
    {
        $file = $this->basePath("app/Template/$template");
        if($this->findFile($file)) {
            return $file;
        }
        elseif($this->findFile($file.'.html')) {
            return $file.'.html';
        }
        elseif($this->findFile($file.'.php')) {
            return $file.'.php';
        }
        else {
            throw new \Exception($file." Template file is missing.");
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
            throw new \Exception($method." method is missing in ".get_class($controllerObj)." Controller.");
            exit;
        }
    }

    /**
     * Require a file.
     *
     * @param  string  $file
     * @param  bool    $return
     * @return void
     */
    public function requireFile($file, $return = false)
    {
        if($this->findFile($file)) {
            if($return !== false) {
                return require($file);
            }
            else {
                require($file);
            }
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
            $byte = file_put_contents($file, $content);
            if($byte) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    /**
     * Get Installtion Time/Version to app/install file used for checking updates.
     *
     * @param  bool  $checkSet
     * @return bool|array
     */
    public function getIntallTime($checkSet = false)
    {
        $file = $this->basePath('app/install');
        if(!file_exists($file)) {
            return false;
        }
        else {
            if($checkSet === true) {
                return true;
            }
            $content = file_get_contents($file);
            $data = json_decode($content);
        }
        return $data;
    }

    /**
     * Set Application secret key.
     *
     * @return string
     */
    public function setKey()
    {
        if(trim(env('APP_KEY')) != '') {
            return true;
        }
        $appKey='';
        for($i=0;$i<32;$i++) {
            $appKey.=chr(rand(0,255));
        }
        $appKey = 'base64:'.base64_encode($appKey);
        $file = $this->basePath('.env');
        $lines = $this->parseEnvFile($file);
        $flag = false;
        foreach($lines as $i => $line) {
            $lines[$i] = trim($line);
            $lines[$i] = trim($line, "\n");
            if(strpos($line, 'APP_KEY') !== false) {
                $data = explode('=', $line, 2);
                if(!isset($data[1]) || trim($data[1]) == '') {
                    $lines[$i] = "APP_KEY=".$appKey;
                    $flag = true;
                }
            }
        }
        if($flag == false) {
            $lines = ["APP_KEY=".$appKey] + $lines;
        }
        $data = implode("\n", $lines);
        if(file_put_contents($file, $data)) {
            putenv("APP_KEY=$appKey");
            $_ENV['APP_KEY'] = $appKey;
            $_SERVER['APP_KEY'] = $appKey;
            return true;
        }
        else {
            throw new \Exception('Failed to write in .env file.');
        }
    }

    /**
     * Parse .env file gets its lines in array.
     *
     * @param  string  $file
     * @return array
     */
    public function parseEnvFile($file)
    {
        $this->ensureFileIsReadable($file);

        $lines = file($file);

        return $lines;
    }

    /**
     * Ensures the given filePath is readable.
     *
     * @throws \Exception
     * @param  string  $file
     * @return void
     */
    protected function ensureFileIsReadable($file)
    {
        if (!is_readable($file) || !is_file($file)) {
            throw new \Exception(sprintf('Unable to read the environment file at %s.', $$file));
        }
    }

    /**
     * Find a Command.
     *
     * @throws \Exception
     * @param  string  $command
     * @param  bool    $system
     * @return bool
     */
    public function findCommand($command, $system = true)
    {
        $file = dirname(__DIR__) . "/Console/Command/" . ucfirst($command) . "Command.php";
        if($this->findFile($file)) {
            return true;
        }
        else {
            return false;
            #throw new \Exception($file." Command Class is missing.");
            #exit;
        }
    }
}