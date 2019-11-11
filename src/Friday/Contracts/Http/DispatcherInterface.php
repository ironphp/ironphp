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
 * @since         1.0.5
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Http;

use Exception;

interface DispatcherInterface
{
    /**
     * Create new Dispatcher instance.
     *
     * @return void
     */
    public function __construct();

    /**
     * Dispatch Request to controller or method.
     *
     * @param array                $route
     * @param \Friday\Http\Request $request
     *
     * @return array
     * @throw  Exception
     */
    public function dispatch($route, $request);
}
