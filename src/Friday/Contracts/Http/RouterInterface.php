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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Http;

use Exception;

interface RouterInterface
{
    /**
     * Create a new Router instance.
     *
     * @return void
     */
    public function __construct();

    /**
     * match uri, http method to routes.
     *
     * @param array  $allRoute
     * @param string $uriRoute
     * @param string $httpMethod GET/POST
     *
     * @return \Friday\Http\Route|bool
     * @exception \Exception
     */
    public function route($allRoute, $uriRoute, $httpMethod);

    /**
     * match uri, http method to routes.
     *
     * @param array  $route
     * @param string $uriRoute
     * @param bool   $parameterized
     *
     * @return bool
     */
    public function match($route, $uriRoute, $parameterized = false);

    /**
     * Get the current route name.
     *
     * @return string|null
     *
     * @since 1.0.7
     */
    public function currentRouteName();
}
