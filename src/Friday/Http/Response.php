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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Http;

use Exception;

/**
 * Send Responce to client.
 */
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
     * Header for redirect.
     *
     * @var string
     */
    public static $redirectHeader;

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
     * @return  \Friday\Http\Response
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Add header.
     *
     * @param   string   $header
     * @return  \Friday\Http\Response
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
     * @return  \Friday\Http\Response
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
     * Send a HTTP header.
     *
     * @param  string    $output
     * @param  bool      $replace
     * @param  int|null  $http_response_code
     * @return void
     * @throws \Exception
     */
    public function sendHeader($output = null, $replace = true, $http_response_code = null)
    {
        if (!headers_sent()) {
            if(static::$redirectHeader !== null) {
                $this->redirect();
            }
            if($this->version === 'HTTP/1.1') {
                if(count($this->headers) === 0) {
                    header("$this->version $this->header");
                }
            }
            else {
                throw new Exception("Invalid HTTP version ".$this->version.".");
                exit;
            }
            foreach($this->headers as $header) {
                if($http_response_code == null) {
                    header("$header", $replace);
                }
                else {
                    header("$header", $replace, $http_response_code);
                }
            }
            if($output) {
                print $output;
                exit;
            }
        } 
    }

    /**
     * Redirect to URL.
     *
     * @return void
     */
    public function redirect()
    {
        header("Location: " . SERVER_ROOT.static::$redirectHeader[0], static::$redirectHeader[1], static::$redirectHeader[2]);
        exit;
    }
}