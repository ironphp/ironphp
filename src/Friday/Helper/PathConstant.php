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
 * @link	 	  https://github.com/IronPHP/IronPHP
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

/**
 * Path Constants.
 */

/*
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 */
if (!defined('ROOT')) {
	define('ROOT', dirname(__DIR__));
}

/*
 * The actual directory name for the application directory. Normally
 * named 'app'.
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', 'app');
}

/*
 * Path to the application's directory.
 */
if (!defined('APP')) {
    define('APP', ROOT.DS.APP_DIR.DS);
}

/*
 * Path to the config directory.
 */
if (!defined('CONFIG')) {
    define('CONFIG', ROOT.DS.'config'.DS);
}

/*
 * File path to the webroot directory.
 *
 * To derive your webroot from your webserver change this to:
 *
 * `define('WEB_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 */
if (!defined('WEB_ROOT')) {
    define('WEB_ROOT', ROOT.DS.'public'.DS);
}

/*
 * Path to the tests directory.
 */
if (!defined('TESTS')) {
    define('TESTS', ROOT.DS.'tests'.DS);
}

/*
 * Path to the temporary files directory.
 */
if (!defined('TMP')) {
    define('TMP', ROOT.DS.'tmp'.DS);
}

/*
 * Path to the logs directory.
 */
if (!defined('LOGS')) {
    define('LOGS', TMP.DS.'logs'.DS);
}

/*
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
if (!defined('CACHE')) {
    define('CACHE', TMP.'cache'.DS);
}

/*
 * The absolute path to the "IronPHP" directory, WITHOUT a trailing DS.
 *
 * IronPHP should always be installed with composer, so look there.
 * if installed as library by composer require ironphp/ironphp
 * then define('IRON_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'ironphp' . DS . 'ironphp');
 * if installed as project by composer create-project ironphp/ironphp
 * then define('IRON_CORE_INCLUDE_PATH', ROOT );
 */
if (!defined('IRON_CORE_INCLUDE_PATH')) {
    define('IRON_CORE_INCLUDE_PATH', ROOT);
}

/*
 * Path to the cake directory.
 */
if (!defined('CORE_PATH')) {
    define('CORE_PATH', IRON_CORE_INCLUDE_PATH.DS);
}
if (!defined('IRON')) {
    define('IRON', CORE_PATH.'src'.DS);
}
