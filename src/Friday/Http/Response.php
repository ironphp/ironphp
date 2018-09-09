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

class Response implements ResponseInterface
{
    /**
     * HTTP version.
     *
     * @var string
     */
    public $version;

    /**
     * Header to be sent.
     *
     * @var string
     */
    public $header;

    /**
     * Create new Responce instance.
     *
     * @param  string  $version
     * @return void
     */
    public function __construct($version)
    {
        $this->version = $version;
    }
 
    /*
    public function getVersion() {
        return $this->version;
    }
    */

    /**
     * Add header.
     *
     * @return  Object  Responce
     */
    public function addHeader($header = null)
    {
        $this->header = $header;
        return $this;
    }
 
    /*
    public function addHeaders(array $headers) {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }
        return $this;
    }
 
    public function getHeaders() {
        return $this->headers;
    }
    */

    /**
     * Sent a HTTP header.
     *
     * @param  string  $output
     * @return void
     */
    public function send($output)
    {
        if (!headers_sent()) {
            if($this->version === 'HTTP/1.1') {
                header("$this->version $this->header", true);
                print $output;
            }
            else {
                throw new \Exception("Invalid HTTP version ".$this->version.".");
                exit;
            }
        } 
    }
}