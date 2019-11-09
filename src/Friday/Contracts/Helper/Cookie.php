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
 * @link		  https://github.com/IronPHP/IronPHP
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Helper;

/*
 * @note    includes session hijacking prevention by bounding sessions to ip
 *          addresses and user agents
 */
interface Cookie
{
    /**
     * Create instance of Cookie.
     *
     * @return void
     */
    public function __construct();

    /**
     * _invalid.
     *
     * @note    decoupled from <open> method to allow for logging by child
     *          classes
     *
     * @return void
     */
    public function _invalid();

    /**
     * _ip.
     *
     * Returns the client's IP address, either directly, or whichever was
     * forwarded by the detected load balancer.
     *
     * @return string
     */
    protected function _ip();

    /**
     * _setup.
     *
     * @return void
     */
    protected function _setup();

    /**
     * _sign.
     *
     * Generates a signature by appending the <stamp> method response with
     * the a secret. This signature is hashed before being returned.
     *
     * @param string $sid
     *
     * @return string
     */
    protected function _sign($sid);

    /**
     * _stamp.
     *
     * Returns a stamp to aid in securing a server, by concatenating the
     * user agent and IP of the client.
     *
     * @note    decoupled from <_sign> to allow for customizing the stamp
     *
     * @return string
     */
    protected function _stamp();

    /**
     * _valid.
     *
     * Checks whether the session is valid (eg. hasn't been tampered with)
     * by regenerating the signature and comparing it to what was passed.
     *
     * @param string $sid
     * @param string $signature
     *
     * @return bool
     */
    protected function _valid($sid, $signature);

    /**
     * destroy.
     *
     * @return void
     */
    public function destroy();

    /**
     * open.
     *
     * @return void
     */
    public function open();

    /**
     * setExpiry.
     *
     * @param int $seconds
     *
     * @return void
     */
    public function setExpiry($seconds);

    /**
     * setHost.
     *
     * @param string $host
     *
     * @return void
     */
    public function setHost($host);

    /**
     * setLifetime.
     *
     * @param int $lifetime
     *
     * @return void
     */
    public function setLifetime($lifetime);

    /**
     * setName.
     *
     * Sets the name of the session (cookie-wise).
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * setPath.
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path);

    /**
     * setSecret.
     *
     * Secret used for the hashing/signature process.
     *
     * @param string $secret
     *
     * @return void
     */
    public function setSecret($secret);

    /**
     * setSecured.
     *
     * @return void
     */
    public function setSecured();
}
