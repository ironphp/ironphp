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

namespace Friday\Http;

class Dispatcher
{
    /**
     * Create new Dispatcher instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Dispatch Request to controller or method.
     *
     * @param  array   $route
     * @param  object  $request
     * @return void
     */
    public function dispatch($route, $request) {
        if(is_string($route[2])) {
            list($controller, $method) = explode('@', $route[2]);
            return ['controller_method', $controller, $method];
        }
        elseif(is_object($route[2])) {
            $function = $route[2];
            ob_start();
            $function();
            $output = ob_get_clean();
            return ['output', $output];
        }
        else {
            throw new \Exception('Invaliding route register for: '.$route[1]);
        }
    }
}