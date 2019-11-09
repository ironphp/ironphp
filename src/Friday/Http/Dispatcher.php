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
 * @link          https://github.com/IronPHP/IronPHP
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Closure;
use Exception;
use ReflectionFunction;
use Friday\Contracts\Console\DispatcherInterface;

class Dispatcher implements DispatcherInterface
{
    /**
     * Create new Dispatcher instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Dispatch Request to controller or method.
     *
     * @param array                $route
     * @param \Friday\Http\Request $request
     *
     * @return array
     * @throw  Exception
     */
    public function dispatch($route, $request)
    {
        $request->setParam('Closure', $route[4]);

        if (isset($route[3]) && is_string($route[3])) {
            $view = $route[3];
            $data = $route[4];

            return ['view' => [$view, $data]];
        } elseif (isset($route[2]) && is_string($route[2])) {
            list($controller, $method) = explode('@', $route[2]);

            return ['controller' => [$controller, $method]];
        } elseif (isset($route[2]) && ($route[2] instanceof Closure || get_class($route[2]) === 'Closure')) {
            $function = $route[2];
            $reflectionFunction = new ReflectionFunction($function);
            $numReqParam = $reflectionFunction->getNumberOfRequiredParameters();
            if ($numReqParam > count($request->getParam('Closure'))) {
                throw new Exception('Invaliding number of required parameter passed in route\'s callable function: '.$route[1]);
            }
            ob_start();
            if ($request->getParam('Closure') !== false && is_array($request->getParam('Closure'))) {
                $return = call_user_func_array($route[2], $request->getParam('Closure'));
            } else {
                //$function();
                throw new Exception('Invaliding parameter passed in route\'s callable function: '.$route[1]);
            }
            $output = ob_get_clean();

            return ['output' => [$output, $return]];
        } else {
            throw new Exception('Invaliding route is registered for: '.$route[1]);
        }
    }
}
