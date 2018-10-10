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

interface RouteInterface {

    /**
     * Create Route instance.
     *
     * @return void
     */
    public function __construct();
 
    /**
     * register a GET method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function get($route, $mix);

    /**
     * register a POST method route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return bool
     */
    public function post($route, $mix);

    /**
     * register a route.
     *
     * @param  string           $route
     * @param  string|callback  $mix
     * @return void
     */
    public function register($method, $route, $mix);

    /**
     * sort registered routes by there base uri.
     *
     * @return void
     */
    public function sortRoute();
}
