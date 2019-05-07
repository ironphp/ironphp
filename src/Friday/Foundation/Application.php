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

use Friday\Foundation\Exceptions\Handler;
use Friday\Http\FrontController;
use Friday\Http\Route;
use Friday\Helper\Session;
use Dotenv\Dotenv;
use Exception;

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
    const VERSION = '1.0.3-alpha1';

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
     * FrontController instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $frontController;

    /**
     * Route instance.
     *
     * @var \Friday\Http\Route
     */
    public $route;

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

        $this->config['basePath'] = $this->basePath(); 

		## Configurator
        #enviro config
        $dotenv = Dotenv::create( $this->basePath() );
        $dotenv->load();

        #Set Exception Handler
		$e = new Handler();
        $e->register();

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

        #set locale-timezone
        $this->setTimezone($this->config['app']['timezone']);
        $timezone = date_default_timezone_get();
        if (strcmp($timezone, ini_get('date.timezone'))){
            ini_set('date.timezone', $timezone);
        }

        #load session
        $this->session = new Session();
        if(!$this->session->isRegistered()) {
            $this->session->register();
        }

        #frontcontroller
        $this->frontController = new FrontController();

        #route
        $this->route = $this->frontController->route();

        Route::$instance = $this->route;
        define('ROUTES_LOADED', microtime(true));

        #load routes
        $this->requireFile(
            $this->basePath('app/Route/web.php')
        );
        $this->route->sortRoute();
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
     * @param   string  $model
     * @return  string  full model file path
     * @throws  Exception
     */
    public function findModel($model)
    {
        $file = $this->basePath("app/Model/$model.php");
        if($this->findFile($file)) {
            return $file;
        }
        else {
            throw new Exception($file." Model file is missing.");
            exit;
        }
    }

    /**
     * Find a View.
     *
     * @param   string  $view
     * @return  string  full view file path
     * @throws  Exception
     */
    public function findView($view)
    {
        $file = $this->basePath("app/View/$view.php");
        if($this->findFile($file)) {
            return $file;
        }
        else {
            throw new Exception($file." View file is missing.");
            exit;
        }
    }

    /**
     * Find a Template.
     *
     * @param   string  $template
     * @return  string  full template file path
     * @throws  Exception
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
            throw new Exception($file." Template file is missing.");
            exit;
        }
    }

    /**
     * Find a Controller.
     *
     * @param   string  $controller
     * @return  bool
     * @throws  Exception
     */
    public function findController($controller)
    {
        $file = $this->basePath("app/Controller/$controller.php");
        if($this->findFile($file)) {
            return true;
        }
        else {
            throw new Exception($file." Controller file is missing.");
            exit;
        }
    }

    /**
     * Check if Controller has method or not.
     *
     * @param   {App}\Controller\{Name}Controller  $controllerObj
     * @param   string                             $method
     * @return  bool
     * @throws  Exception
     */
    public function hasMethod($controllerObj, $method)
    {
        if(method_exists($controllerObj, $method)) {
            return true;
        }
        else {
            throw new Exception($method." method is missing in ".get_class($controllerObj)." Controller.");
            exit;
        }
    }

    /**
     * Require a file.
     *
     * @param   string  $file
     * @param   bool    $return
     * @return  void
     * @throws  Exception
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
            throw new Exception($file." file is missing.");
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
     * @param  bool       $checkSet
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
     * @throws  Exception
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
            throw new Exception('Failed to write in .env file.');
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
     * @param  string  $file
     * @return void
     * @throws  Exception
     */
    protected function ensureFileIsReadable($file)
    {
        if (!is_readable($file) || !is_file($file)) {
            throw new Exception(sprintf('Unable to read the environment file at %s.', $$file));
        }
    }

    /**
     * Find a Command.
     *
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
            #throw new Exception($file." Command Class is missing.");
            #exit;
        }
    }

    /**
     * Set timezone.
     *
     * @param  string  default
     * @return bool
     */
    public function setTimezone($default)
    {
        /*
        I'm sure I'm not the only one who is distressed by the recent default behavior change to E_NOTICE when the timezone isn't explicitly set in the program or in .ini.  I insure that the clock on the server IS correct, and I don't want to have to set it in two places (the system AND PHP).  So I want to read it from the system.  But PHP won't accept that answer, and insists on a call to this function
        Use it by calling it with a fallback default answer. It doesn't work on Windows.
        */
        $timezone = "";
    
        // On many systems (Mac, for instance) "/etc/localtime" is a symlink
        // to the file with the timezone info
        if (is_link("/etc/localtime")) {
        
            // If it is, that file's name is actually the "Olsen" format timezone
            $filename = readlink("/etc/localtime");
        
            $pos = strpos($filename, "zoneinfo");
            if ($pos) {
                // When it is, it's in the "/usr/share/zoneinfo/" folder
                $timezone = substr($filename, $pos + strlen("zoneinfo/"));
            }
            else {
                // If not, bail
                $timezone = $default;
            }
        }
        elseif (is_link("/etc/timezone")) {
            // On other systems, like Ubuntu, there's file with the Olsen time
            // right inside it.
            $timezone = file_get_contents("/etc/timezone");
            if (!strlen($timezone)) {
                $timezone = $default;
            }
        }
        else {
            $timezone = $default;
        }
        date_default_timezone_set($timezone);
    }

    /**
     * Get specific or all Registered redirect routes.
     *
     * @param  string  uri
     * @return array
     */
    public function getRoutes($uri = null)
    {
        return $this->route->getRoute($uri);
    }

    /**
     * Find a Theme.
     *
     * @param   string  theme
     * @return  string  full theme file/dir path
     * @throws  Exception
     */
    public function findTheme($theme)
    {
        $dir = $this->basePath("app/Theme/$theme");
        $this->parseDir($dir);exit;
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
            throw new Exception($file." Template file is missing.");
            exit;
        }
    }

    /**
     * Parse files/dir in dir.
     *
     * @param   string  dir
     * @return  array
     */
    public function parseDir($dir)
    {
        $ext = ['html', 'htm', 'css', 'js', 'php'];
        $files = [];
        $handle = dir($dir);
        /*
        while(false != ($entry = $handle->read())) {
            if($entry != '.' && $entry != '..') {
                $path = $handle->path.'/'.$entry;
                if(is_file($path)) {
                    if(false !== ($pos = strrpos($entry, '.'))) {
                        if(in_array(substr($entry, $pos+1), $ext)) {
                            $files[] = $path;
                        }
                    }
                } elseif(is_dir($path)) {
                    if(strrpos($path, '.')) {
                        continue;
                    }
                    $handle2 = dir($path);
                    while(false != ($entry = $handle2->read())) {
                        if($entry != '.' && $entry != '..') {
                            $path2 = $handle2->path.'/'.$entry;
                    var_dump(($path2));
                            if(is_file($path2)) {
                                if(false !== ($pos = strrpos($entry, '.'))) {
                                    if(in_array(substr($entry, $pos+1), $ext)) {
                                        $files[] = $path2;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        */
        $rdi = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::KEY_AS_PATHNAME);
        foreach (new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::SELF_FIRST) as $file => $info) {
            echo $file.$info."\n";
        }
        exit;
    }
}