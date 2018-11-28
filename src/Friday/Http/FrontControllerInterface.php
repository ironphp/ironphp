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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */
namespace Friday\Http;

/**
 * FrontController Interface
 */
interface FrontControllerInterface
{
    /**
     * Default controller.
     */
    const DEFAULT_CONTROLLER = "IndexController";

    /**
     * Default method.
     */
    const DEFAULT_ACTION = "index";

    /**
     * Create a new FrontController instance.
     *
     * @return void
     */
    public function __construct();
    
    /**
     * Create Responce instance.
     *
     * @param  array  $parsedUrl
     * @return \Friday\Http\Request
     */
    public function request($parsedUrl);

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