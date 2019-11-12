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

interface Session
{
    /**
     * Create a new Session instance.
     *
     * @return void
     */
    public function __construct();

    /**
     * Register the session.
     *
     * @param int $time
     */
    public function register($time = 60);

    /**
     * Checks to see if the session is registered.
     *
     * @return bool
     */
    public function isRegistered();

    /**
     * Set key/value in session.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Retrieve value stored in session by key.
     *
     * @param mixed $key
     *
     * @return string|bool
     */
    public function get($key);

    /**
     * Retrieve the global session variable.
     *
     * @return array
     */
    public function getSession();

    /**
     * Gets the id for the current session.
     *
     * @return int
     */
    public function getSessionId();

    /**
     * Checks to see if the session is over based on the amount of time given.
     *
     * @return bool
     */
    public function isExpired();

    /**
     * Renews the session when the given time is not up and there is activity on the site.
     */
    public function renew();

    /**
     * Destroys the session.
     *
     * @return void
     */
    public function end();

    /**
     * Get a new token.
     *
     * @return string
     */
    public function getToken();

    /**
     * Check a token.
     *
     * @param string $token
     *
     * @return bool
     */
    public function checkToken($token);
}
