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
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Helper;

use Friday\Contracts\Helper\Session as SessionInterface;

class Session implements SessionInterface
{
    /**
     * The Session instance.
     *
     * @var \Friday\Helper\Session|null
     */
    protected static $instance = null;

    /**
     * Create a new Session instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (is_session_started() === false) {
            session_start();
        }
        static::$instance = $this;
    }

    /**
     * Register the session.
     *
     * @param int $time
     */
    public function register($time = 60 * 60 * 24)
    {
        $_SESSION['session_id'] = session_id();
        $_SESSION['session_time'] = intval($time);
        $_SESSION['session_start'] = $this->newTime();
    }

    /**
     * Checks to see if the session is registered.
     *
     * @return bool
     */
    public function isRegistered()
    {
        if (!empty($_SESSION['session_id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set key/value in session.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve value stored in session by key.
     *
     * @param mixed $key
     *
     * @return string|bool
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    /**
     * Retrieve the global session variable.
     *
     * @return array
     */
    public function getSession()
    {
        return $_SESSION;
    }

    /**
     * Gets the id for the current session.
     *
     * @return int
     */
    public function getSessionId()
    {
        return $_SESSION['session_id'];
    }

    /**
     * Checks to see if the session is over based on the amount of time given.
     *
     * @return bool
     */
    public function isExpired()
    {
        if ($_SESSION['session_start'] < $this->timeNow()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Renews the session when the given time is not up and there is activity on the site.
     */
    public function renew()
    {
        $_SESSION['session_start'] = $this->newTime();
    }

    /**
     * Returns the current time.
     *
     * @return int
     */
    private function timeNow()
    {
        return time();
        /*
                $currentHour = date('H');
                $currentMin = date('i');
                $currentSec = date('s');
                $currentMon = date('m');
                $currentDay = date('d');
                $currentYear = date('y');

                return mktime($currentHour, $currentMin, $currentSec, $currentMon, $currentDay, $currentYear);
        */
    }

    /**
     * Generates new time.
     *
     * @return int
     */
    private function newTime()
    {
        return time() + $_SESSION['session_time'];
        /*
                $currentHour = date('H');
                $currentMin = date('i');
                $currentSec = date('s');
                $currentMon = date('m');
                $currentDay = date('d');
                $currentYear = date('y');

                return mktime($currentHour, ($currentMin + $_SESSION['session_time']), $currentSec, $currentMon, $currentDay, $currentYear);
        */
    }

    /**
     * Destroys the session.
     *
     * @return void
     */
    public function end()
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Get a new token.
     *
     * @return string
     */
    public function getToken()
    {
        $this->set('_token', base64_encode(openssl_random_pseudo_bytes(32)));

        return $this->get('_token');
    }

    /**
     * Check a token.
     *
     * @param string $token
     *
     * @return bool
     */
    public function checkToken($token)
    {
        return !empty($token) && $token === $token;
    }

    /**
     * Get Session instance.
     *
     * @return \Friday\Helper\Session
     *
     * @since 1.0.8
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            return new static();
        }

        return static::$instance;
    }

    /**
     * Get a token.
     *
     * @return string
     *
     * @since 1.0.8
     */
    public function token()
    {
        return $this->get('_token');
    }

    /**
     * Get old form data.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     *
     * @since 1.0.10
     */
    public function old($key, $default)
    {
        $old = $this->get('old');
        if (is_array($old)) {
            return $old[$key] ?? $default;
        } else {
            return $default;
        }
    }

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     *
     * @param string|array $key
     * @param mixed        $value
     *
     * @return void
     *
     * @since 1.0.10
     */
    public function put($key, $value = null)
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            $this->set($arrayKey, $arrayValue);
        }
    }

    /**
     * Get all of the session data.
     *
     * @return array
     *
     * @since 1.0.10
     */
    public function all()
    {
        //TODO store SESSION in $this->attributes
        return $_SESSION;
    }
}
