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
    public function __construct(/*array $options = array()*/);

    /**
     * Parse Uri and get path uri params.
     *
     * @return void
     */
    public function parseUri();

    /**
     * Create Responce instance.
     *
     * @return object
     */
    public function request();

    /*
    public function setController($controller);
    public function setAction($action);
    public function setParams(array $params);
    */

    public function run();

}