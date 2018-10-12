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

interface FrontControllerInterface
{
    /**
     * Create a new FrontController instance.
     *
     * @return void
     */
    public function __construct();
    
    /**
     * Parse Uri and get path uri, params, server method.
     *
     * @return array
     */
    public function parseUri();
    
    /**
     * Create Responce instance.
     *
     * @return \Friday\Http\Request
     */
    public function request();

    /**
     * Create Route instance.
     *
     * @return \Friday\Http\Route
     */
    public function route();

    /**
     * Create Router instance.
     *
     * @return \Friday\Http\Router
     */
    public function router();

    /**
     * Create Dispatcher instance.
     *
     * @return \Friday\Http\Dispatcher
     */
    public function dispatcher();

    /**
     * Create Response instance.
     *
     * @param  string  $serverProtocol
     * @return \Friday\Http\Response
     */
    public function response($serverProtocol);

    /**
     * Call method of controller with parameters.
     *
     * @return void
     */
    public function run();
}