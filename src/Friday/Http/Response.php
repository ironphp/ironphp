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
    private $version;

    /**
     * Header to be sent.
     *
     * @var string
     */
    private $header = null;

    /**
     * Headers to be sent.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Create new Responce instance.
     *
     * @param  string  $version
     * @return void
     */
    public function __construct($version = null)
    {
        $this->version = $version;
    }
 
    /**
     * Get version.
     *
     * @return  Friday\Http\Response
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Add header.
     *
     * @param   string   $header
     * @return  Friday\Http\Response
     */
    public function addHeader($header = null)
    {
        $this->header = $header;
        return $this;
    }
 
    /**
     * Get header.
     *
     * @return  string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Add headers.
     *
     * @param   array   $headers
     * @return  Friday\Http\Response
     */
    public function addHeaders(array $headers = [])
    {
        $this->headers = [];
        if($headers != []) {
            foreach ($headers as $header) {
                $this->headers[] = $header;
            }
        }
        return $this;
    }
 
    /**
     * Get headers.
     *
     * @return  array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sent a HTTP header.
     *
     * @param  string  $output
     * @return void
     */
    public function sendHeader($output = null)
    {
        if (!headers_sent()) {
            if($this->version === 'HTTP/1.1') {
                //header("$this->version $this->header");
            }
            else {
                throw new \Exception("Invalid HTTP version ".$this->version.".");
                exit;
            }
            foreach($this->headers as $header) {
                header("$header");
            }
            if($output) {
                print $output;
                exit;
            }
        } 
    }
}