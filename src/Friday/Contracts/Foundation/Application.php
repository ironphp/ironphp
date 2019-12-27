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
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Foundation;

use Exception;
use Friday\Http\Route;

/**
 * Runs an application invoking all the registered application.
 */
interface Application
{
    /**
     * Create a new Friday application instance.
     *
     * @param string|null $basePath
     *
     * @return void
     */
    public function __construct($basePath = null);

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version();

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath($basePath);

    /**
     * Get the base path of the IronPHP installation.
     *
     * @param string $path Optionally, a path to append to the base path
     *
     * @return string
     */
    public function basePath($path = '');

    /**
     * Find a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function findFile($path);

    /**
     * Find a Model.
     *
     * @param string $model
     *
     * @throws Exception
     *
     * @return string full model file path
     */
    public function findModel($model);

    /**
     * Find a View.
     *
     * @param string $view
     *
     * @throws Exception
     *
     * @return string full view file path
     */
    public function findView($view);

    /**
     * Find a Template.
     *
     * @param string $template
     *
     * @throws Exception
     *
     * @return string full template file path
     */
    public function findTemplate($template);

    /**
     * Find a Controller.
     *
     * @param string $controller
     *
     * @throws Exception
     *
     * @return bool
     */
    public function findController($controller);

    /**
     * Check if Controller has method or not.
     *
     * @param \Friday\Controller\Controller $controllerObj
     * @param string                        $method
     *
     * @throws Exception
     *
     * @return bool
     */
    public function hasMethod($controllerObj, $method);

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
    public function requireFile($file, $return = false);

    /**
     * Set Installtion Time/Version to app/install file used for checking updates.
     *
     * @return bool
     */
    public function setIntallTime();

    /**
     * Get Installtion Time/Version to app/install file used for checking updates.
     *
     * @param bool $checkSet
     *
     * @return bool|array
     */
    public function getIntallTime($checkSet = false);

    /**
     * Set Application secret key.
     *
     * @throws Exception
     *
     * @return string|bool
     */
    public function setKey();

    /**
     * Parse .env file gets its lines in array.
     *
     * @param string $file
     *
     * @return array
     */
    public function parseEnvFile($file);

    /**
     * Ensures the given filePath is readable.
     *
     * @param string $file
     *
     * @throws Exception
     *
     * @return void
     */
    public function ensureFileIsReadable($file);

    /**
     * Find a Command.
     *
     * @param string $command
     * @param bool   $system
     *
     * @return bool
     */
    public function findCommand($command, $system = true);

    /**
     * Set timezone.
     *
     * @param string $default
     *
     * @return bool
     */
    public function setTimezone($default);

    /**
     * Get specific or all Registered redirect routes.
     *
     * @param string|null $uri
     *
     * @return array
     */
    public function getRoutes($uri = null);

    /**
     * Find a Theme.
     *
     * @param string $theme
     * @param string $file  File to use for rendering
     *
     * @throws Exception
     *
     * @return array
     */
    public function findTheme($theme, $file = null);

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
    public function parseDir($dir, $allowedExt, $json, $byType = false);

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
    public function getList($dir, $types, $ignoreDir, $byType = false);

    /*
     * Copy theme files into public dir.
     *
     * @param   string  $jsonTheme
     * @param   array   $allowExt
     * @return  void
     */
    public function installTheme($jsonTheme, $allowExt);

    /**
     * Get parameter passed in route.
     *
     * @return array
     */
    public function getRouteParam();

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     *
     * @since 1.0.7
     */
    public function environmentPath();

    /**
     * Set the directory for the environment file.
     *
     * @param string $path
     *
     * @return $this
     *
     * @since 1.0.7
     */
    public function setEnvironmentPath($path);

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param string $file
     *
     * @return $this
     *
     * @since 1.0.7
     */
    public function loadEnvironmentFrom($file);

    /**
     * Get the environment file the application is using.
     *
     * @return string
     *
     * @since 1.0.7
     */
    public function environmentFile();
}
