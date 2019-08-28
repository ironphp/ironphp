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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Foundation;

use Dotenv\Dotenv;
use Exception;
use Friday\Foundation\Exceptions\Handler;
use Friday\Helper\Session;
use Friday\Http\FrontController;
use Friday\Http\Route;

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
    const VERSION = '1.0.5-alpha1';

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
     * Instanse of Session.
     *
     * @var \Friday\Helper\Session
     */
    public $session;

    /**
     * Create a new Friday application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->config['basePath'] = $this->basePath();

        //# Configurator
        //enviro config
        $dotenv = Dotenv::create($this->basePath());
        $dotenv->load();

        //Set Exception Handler
        $e = new Handler();
        $e->register();

        //set install config
        if ($this->getIntallTime(true) === false) {
            $this->setIntallTime();
        } elseif (empty(env('APP_KEY'))) {
            echo 'APP_KEY is not defined in .env file, define it by command: php jarvis key';
        }

        //load config
        $this->config['app'] = $this->requireFile(
            $this->basePath('config/app.php'),
            true
        );
        $this->config['db'] = $this->requireFile(
            $this->basePath('config/database.php'),
            true
        );
        define('CONFIG_LOADED', microtime(true));

        //set locale-timezone
        $this->setTimezone($this->config['app']['timezone']);
        $timezone = date_default_timezone_get();
        if (strcmp($timezone, ini_get('date.timezone'))) {
            ini_set('date.timezone', $timezone);
        }

        //load session
        $this->session = new Session();
        if (!$this->session->isRegistered()) {
            $this->session->register();
        }

        //frontcontroller
        $this->frontController = new FrontController();

        //route
        $this->route = $this->frontController->route();

        Route::$instance = $this->route;
        define('ROUTES_LOADED', microtime(true));

        //load routes
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
     * @param string $basePath
     *
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
     * @param string $path Optionally, a path to append to the base path
     *
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath.($path ? DS.$path : $path);
    }

    /**
     * Find a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function findFile($path)
    {
        if (file_exists($path) && is_file($path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Find a Model.
     *
     * @param string $model
     *
     * @throws Exception
     *
     * @return string full model file path
     */
    public function findModel($model)
    {
        $file = $this->basePath("app/Model/$model.php");
        if ($this->findFile($file)) {
            return $file;
        } else {
            throw new Exception($file.' Model file is missing.');
            exit;
        }
    }

    /**
     * Find a View.
     *
     * @param string $view
     *
     * @throws Exception
     *
     * @return string full view file path
     */
    public function findView($view)
    {
        $file = $this->basePath("app/View/$view.php");
        if ($this->findFile($file)) {
            return $file;
        } else {
            throw new Exception($file.' View file is missing.');
            exit;
        }
    }

    /**
     * Find a Template.
     *
     * @param string $template
     *
     * @throws Exception
     *
     * @return string full template file path
     */
    public function findTemplate($template)
    {
        $file = $this->basePath("app/Template/$template");
        if ($this->findFile($file)) {
            return $file;
        } elseif ($this->findFile($file.'.html')) {
            return $file.'.html';
        } elseif ($this->findFile($file.'.php')) {
            return $file.'.php';
        } else {
            throw new Exception($file.' Template file is missing.');
            exit;
        }
    }

    /**
     * Find a Controller.
     *
     * @param string $controller
     *
     * @throws Exception
     *
     * @return bool
     */
    public function findController($controller)
    {
        $file = $this->basePath("app/Controller/$controller.php");
        if ($this->findFile($file)) {
            return true;
        } else {
            throw new Exception($file.' Controller file is missing.');
            exit;
        }
    }

    /**
     * Check if Controller has method or not.
     *
     * @param {App}\Controller\{Name}Controller $controllerObj
     * @param string                            $method
     *
     * @throws Exception
     *
     * @return bool
     */
    public function hasMethod($controllerObj, $method)
    {
        if (method_exists($controllerObj, $method)) {
            return true;
        } else {
            throw new Exception($method.' method is missing in '.get_class($controllerObj).' Controller.');
            exit;
        }
    }

    /**
     * Require a file.
     *
     * @param string $file
     * @param bool   $return
     *
     * @throws Exception
     *
     * @return void|string
     */
    public function requireFile($file, $return = false)
    {
        if ($this->findFile($file)) {
            if ($return !== false) {
                return require $file;
            } else {
                require $file;
            }
        } else {
            throw new Exception($file.' file is missing.');
            exit(0);
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
        if (!file_exists($file)) {
            $content = json_encode(['time'=>time(), 'version' => $this->version()]);
            $byte = file_put_contents($file, $content);
            if ($byte) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get Installtion Time/Version to app/install file used for checking updates.
     *
     * @param bool $checkSet
     *
     * @return bool|array
     */
    public function getIntallTime($checkSet = false)
    {
        $file = $this->basePath('app/install');
        if (!file_exists($file)) {
            return false;
        } else {
            if ($checkSet === true) {
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
     * @throws Exception
     *
     * @return string
     */
    public function setKey()
    {
        if (trim(env('APP_KEY')) != '') {
            return true;
        }
        $appKey = '';
        for ($i = 0; $i < 32; $i++) {
            $appKey .= chr(rand(0, 255));
        }
        $appKey = 'base64:'.base64_encode($appKey);
        $file = $this->basePath('.env');
        $lines = $this->parseEnvFile($file);
        $flag = false;
        foreach ($lines as $i => $line) {
            $lines[$i] = trim($line);
            $lines[$i] = trim($line, "\n");
            if (strpos($line, 'APP_KEY') !== false) {
                $data = explode('=', $line, 2);
                if (!isset($data[1]) || trim($data[1]) == '') {
                    $lines[$i] = 'APP_KEY='.$appKey;
                    $flag = true;
                }
            }
        }
        if ($flag == false) {
            $lines = ['APP_KEY='.$appKey] + $lines;
        }
        $data = implode("\n", $lines);
        if (file_put_contents($file, $data)) {
            putenv("APP_KEY=$appKey");
            $_ENV['APP_KEY'] = $appKey;
            $_SERVER['APP_KEY'] = $appKey;

            return true;
        } else {
            throw new Exception('Failed to write in .env file.');
        }
    }

    /**
     * Parse .env file gets its lines in array.
     *
     * @param string $file
     *
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
     * @param string $file
     *
     * @throws Exception
     *
     * @return void
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
     * @param string $command
     * @param bool   $system
     *
     * @return bool
     */
    public function findCommand($command, $system = true)
    {
        $file = dirname(__DIR__).'/Console/Command/'.ucfirst($command).'Command.php';
        if ($this->findFile($file)) {
            return true;
        } else {
            return false;
            //throw new Exception($file." Command Class is missing.");
            //exit;
        }
    }

    /**
     * Set timezone.
     *
     * @param  string  default
     *
     * @return bool
     */
    public function setTimezone($default)
    {
        /*
        I'm sure I'm not the only one who is distressed by the recent default behavior change to E_NOTICE when the timezone isn't explicitly set in the program or in .ini.  I insure that the clock on the server IS correct, and I don't want to have to set it in two places (the system AND PHP).  So I want to read it from the system.  But PHP won't accept that answer, and insists on a call to this function
        Use it by calling it with a fallback default answer. It doesn't work on Windows.
        */
        $timezone = '';

        // On many systems (Mac, for instance) "/etc/localtime" is a symlink
        // to the file with the timezone info
        if (is_link('/etc/localtime')) {

            // If it is, that file's name is actually the "Olsen" format timezone
            $filename = readlink('/etc/localtime');

            $pos = strpos($filename, 'zoneinfo');
            if ($pos) {
                // When it is, it's in the "/usr/share/zoneinfo/" folder
                $timezone = substr($filename, $pos + strlen('zoneinfo/'));
            } else {
                // If not, bail
                $timezone = $default;
            }
        } elseif (is_link('/etc/timezone')) {
            // On other systems, like Ubuntu, there's file with the Olsen time
            // right inside it.
            $timezone = file_get_contents('/etc/timezone');
            if (!strlen($timezone)) {
                $timezone = $default;
            }
        } else {
            $timezone = $default;
        }
        date_default_timezone_set($timezone);
    }

    /**
     * Get specific or all Registered redirect routes.
     *
     * @param  string  uri
     *
     * @return array
     */
    public function getRoutes($uri = null)
    {
        return $this->route->getRoute($uri);
    }

    /**
     * Find a Theme.
     *
     * @param string $theme
     * @param string $file  File to use for rendering
     *
     * @throws Exception
     *
     * @return string
     */
    public function findTheme($theme, $file = null)
    {
        $extMain = ['html', 'htm'];
        if ($file) {
            $array = explode('.', $file);
            $ext = array_pop($array);
        } else {
            $ext = null;
        }

        if (!in_array($ext, $extMain)) {
            throw new Exception('Theme file must be HTML/HTM : '.$theme.DS.$file);
            exit;
        }

        $fileName = ltrim($file, '/\\');
        $allowedExt = ['html', 'htm', 'css', 'js', 'jpg', 'png', 'svg', 'eot', 'ttf', 'woff', 'woof2', 'scss', 'less'];
        $dir = THEME.$theme.DS;

        if (!file_exists($dir) || is_file($dir)) {
            throw new Exception($dir.' Directory is missing.');
            exit;
        }

        $json = $dir.'/ironphp-theme.json';
        if (!file_exists($json) || !is_file($json) || !filesize($json) || (round(fileatime($json) / 10) < round(fileatime($dir) / 10))) {
            $themeFiles = $this->parseDir($dir, $allowedExt, $json, true);
            if (!isset($themeFiles['html']) && isset($themeFiles['htm'])) {
                throw new Exception('HTML/HTM files are missing in Theme: '.$theme);
                exit;
            }
        } else {
            $themeFiles = json_decode(file_get_contents($json), true);
        }

        if (in_array($fileName, $themeFiles[$ext])) {
            $themeFilepath = $themeFiles['theme_path'].$fileName;
        } else {
            throw new Exception($fileName.' is missing in Theme: '.$theme);
            exit;
        }
        $this->installTheme($themeFiles, ['css', 'js', 'jpg', 'png', 'svg', 'eot', 'ttf', 'woff', 'woof2', 'scss', 'less']);

        return ['themeName' => $theme, 'themePath' => $dir, 'themeFilePath' => $themeFilepath];
    }

    /**
     * Parse files/dir in dir.
     *
     * @param string $dir
     * @param array  $allowedExt
     * @param string $json
     * @param bool   $byType
     *
     * @return array
     */
    public function parseDir($dir, $allowedExt, $json, $byType = false)
    {
        $fp = fopen($json, 'w');
        $getList = $this->getList($dir, $allowedExt, [], $byType);
        fwrite($fp, json_encode($getList, JSON_PRETTY_PRINT));
        fclose($fp);

        return $getList;
    }

    /*
     * Get files-dir by RecursiveDirectoryIterator
     *
     * @param   array  $dir
     * @param   array  $types
     * @param   array  $ignoreDir
     * @param   bool   $byType
     * @abstract Recursively iterates over specified directory
     *           populating array based on array of file extensions
     *           while ignoring directories specified in ignoreDir
     * @return  array
     */
    public function getList($dir, $types, $ignoreDir, $byType = false)
    {
        $files['theme_path'] = $dir;
        $it = new \RecursiveDirectoryIterator($dir);
        foreach (new \RecursiveIteratorIterator($it) as $info => $file) {
            $basename = $file->getBasename();
            if ($basename == '.' || $basename == '..' || $basename[0] == '.') {
                continue;
            }
            $relPath = str_ireplace($dir, '', $file->getRealPath());
            if (!in_array($it, $ignoreDir) && $types == [] || in_array(strtolower($file->getExtension()), $types)) {
                if ($byType) {
                    $files[$file->getExtension()][] = $relPath;
                } else {
                    $files[] = $relPath;
                }
            }
        }

        return $files;
    }

    /*
     * Copy theme files into public dir.
     *
     * @param   string  $jsonTheme
     * @param   array   $allowExt
     * @return  void
     */
    public function installTheme($jsonTheme, $allowExt)
    {
        foreach ($jsonTheme as $key => $val) {
            if ($key == 'theme_path') {
                $theme_path = $val;
            } elseif (in_array($key, $allowExt)) {
				$theme_path = $val;
                foreach ($val as $file) {
                    if (file_exists($theme_path.$file) && is_file($theme_path.$file)) {
                        if (file_exists(WEB_ROOT.$file) && is_file(WEB_ROOT.$file)) {
                            if (filesize($theme_path.$file) === filesize(WEB_ROOT.$file)) {
                                //file is already copied
                            } else {
                                throw new Exception('Different file with same name already exist: '.WEB_ROOT.$file);
                                exit;
                            }
                        } else {
                            $dirname = dirname(WEB_ROOT.$file);
                            $makedir = [];
                            while (!file_exists($dirname) || !is_dir($dirname)) {
                                $makedir[] = $dirname;
                                $dirname = dirname($dirname);
                            }
                            $makedir = array_reverse($makedir);
                            foreach ($makedir as $dir) {
                                mkdir($dir);
                            }
                            copy($theme_path.$file, WEB_ROOT.$file);
                        }
                    } else {
                        throw new Exception('File is not exist: '.$theme_path.$file);
                        exit;
                    }
                }
            }
        }
    }
}
