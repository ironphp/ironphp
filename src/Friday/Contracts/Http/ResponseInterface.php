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
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Http;

interface ResponseInterface
{
    /**
     * Create new Responce instance.
     *
     * @param string $version
     *
     * @return void
     */
    public function __construct($version);

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Add header.
     *
     * @param string $header
     *
     * @return \Friday\Http\Response
     */
    public function addHeader($header = null);

    /**
     * Get header.
     *
     * @return string
     */
    public function getHeader();

    /**
     * Add headers.
     *
     * @param array $headers
     *
     * @return \Friday\Http\Response
     */
    public function addHeaders(array $headers);

    /**
     * Get headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Send a HTTP header.
     *
     * @param string   $output
     * @param bool     $replace
     * @param int|null $http_response_code
     *
     * @throws \Exception
     *
     * @return void
     */
    public function sendHeader($output = null, $replace = true, $http_response_code = null);

    /**
     * Redirect to URL.
     *
     * @return void
     */
    public function redirect();
}
