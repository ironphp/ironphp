<?php
/**
 * The Front Controller for handling every request
 *
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

$root = isset($root) ? $root : true;

// Check platform requirements
require dirname(__DIR__) . '/config/requirements.php';

// For built-in server
if (PHP_SAPI === 'cli-server') {
    $_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

    $url = parse_url(urldecode($_SERVER['REQUEST_URI']));
    $file = __DIR__ . $url['path'];
    if (strpos($url['path'], '..') === false && strpos($url['path'], '.') !== false && is_file($file)) {
        return false;
    }
}

/**
 *--------------------------------------------------------------------------
 * Register The Auto Loader
 *--------------------------------------------------------------------------
 *
 * Composer provides a convenient, automatically generated class loader for
 * our application. We just need to utilize it! We'll simply require it
 * into the script here so that we don't have to worry about manual
 * loading any of our classes later on. It feels great to relax.
 *
 */

require dirname(__DIR__) . '/vendor/autoload.php';

/**
 *--------------------------------------------------------------------------
 * Turn On The Lights
 *--------------------------------------------------------------------------
 *
 * We need to Friday PHP development, so let us turn on the lights.
 * This bootstraps the framework and gets it ready for use, then it
 * will load up this application so that we can run it and send
 * the responses back to the browser and delight our users.
 *
 */

$app = require_once dirname(__DIR__) . '/app/bootstrap/app.php';
