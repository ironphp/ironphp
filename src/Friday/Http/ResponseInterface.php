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

interface ResponseInterface {

    /**
     * Create new Responce instance.
     *
     * @param  string  $version
     * @return void
     */
    public function __construct($version);
 
    /**
     * Add header.
     *
     * @return  Object  Responce
     */
    public function addHeader($header);
 
    /**
     * Sent a HTTP header.
     *
     * @param  string  $output
     * @return void
     */
    public function send($output);

}