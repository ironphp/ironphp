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

/*
 * @note    includes session hijacking prevention by bounding sessions to ip
 *          addresses and user agents
 */
class Cookie
{
    /**
     * _expiry.
     *
     * (default value: 0)
     *
     * @var int
     */
    protected $_expiry = 0;

    /**
     * _host.
     *
     * @note    default value will be pulled from <_SERVER>
     *
     * @var string
     */
    protected $_host;

    /**
     * _httponly.
     *
     * @var bool
     */
    protected $_httponly = true;

    /**
     * _lifetime.
     *
     * @var int
     */
    protected $_lifetime = 900;

    /**
     * _name.
     *
     * @var string
     */
    protected $_name = 'SN';

    /**
     * _open.
     *
     * @var bool
     */
    protected $_open = false;

    /**
     * _path.
     *
     * @var string
     */
    protected $_path = '/';

    /**
     * _secret.
     *
     * Secret used for generating the signature. Is used in conjunction with
     * the <stamp> method for securing sessions.
     *
     * @var string
     */
    protected $_secret = 'jkn*#j34!';

    /**
     * _secure.
     *
     * @var bool
     */
    protected $_secure = false;

    /**
     * _secureWithIpAddress.
     *
     * @var bool
     */
    protected $_secureWithIpAddress = false;

    /**
     * Create instance of Cookie.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setHost('.'.($_SERVER['HTTP_HOST']));
    }

    /**
     * _invalid.
     *
     * @note    decoupled from <open> method to allow for logging by child
     *          classes
     *
     * @return void
     */
    public function _invalid()
    {
        // reset session
        $this->destroy();
        $this->open();
    }

    /**
     * _ip.
     *
     * Returns the client's IP address, either directly, or whichever was
     * forwarded by the detected load balancer.
     *
     * @return string
     */
    protected function _ip()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) === true) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['REMOTE_ADDR']) === true) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '(unknown)';
    }

    /**
     * _setup.
     *
     * @return void
     */
    protected function _setup()
    {
        ini_set('session.name', $this->_name);
        ini_set('session.gc_maxlifetime', $this->_lifetime);
        session_set_cookie_params(
            $this->_expiry,
            $this->_path,
            $this->_host,
            $this->_secure,
            $this->_httponly
        );
    }

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
    protected function _sign($sid)
    {
        $stamp = $this->_stamp().$this->_secret;
        $signature = hash('sha256', $sid.$stamp);

        return $signature;
    }

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
    protected function _stamp()
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) === true ? $_SERVER['HTTP_USER_AGENT'] : '(unknown)';
        if ($this->_secureWithIpAddress === true) {
            return $agent.$this->_ip();
        }

        return $agent;
    }

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
    protected function _valid($sid, $signature)
    {
        // return regenerated vs passed in
        $regenerated = $this->_sign($sid);

        return $signature === $regenerated;
    }

    /**
     * destroy.
     *
     * @return void
     */
    public function destroy()
    {
        // empty
        $_SESSION = [];

        // clear cookies from agent
        $signature = ($this->_name).'Signature';
        setcookie(
            $this->_name,
            '',
            time() - 42000,
            $this->_path,
            $this->_host,
            $this->_secure,
            $this->_httponly
        );
        setcookie(
            $signature,
            '',
            time() - 42000,
            $this->_path,
            $this->_host,
            $this->_secure,
            $this->_httponly
        );

        /*
         * Clear out of global scope, since setcookie requires buffer flush
         * to update global <_COOKIE> array.
         */
        unset($_COOKIE[$this->_name]);
        unset($_COOKIE[$signature]);

        // destroy
        session_destroy();
    }

    /**
     * open.
     *
     * @return void
     */
    public function open()
    {
        // setup session
        $this->_setup();

        // open up session
        session_start();
        $sid = session_id();

        // mark that a session has been opened
        $this->_open = true;

        // signature check
        $key = ($this->_name).'Signature';
        if (isset($_COOKIE[$key]) === true) {

            // if session id is invalid
            $signature = $_COOKIE[$key];
            $valid = $this->_valid($sid, $signature);
            if ($valid === false) {

                // invalid session processing
                $this->_invalid();
            }
        }
        // session not yet opened
        else {

            // create signature-cookie
            $signature = $this->_sign($sid);
            setcookie(
                $key,
                $signature,
                $this->_expiry,
                $this->_path,
                $this->_host,
                $this->_secure,
                $this->_httponly
            );
        }
    }

    /**
     * setExpiry.
     *
     * @param int $seconds
     *
     * @return void
     */
    public function setExpiry($seconds)
    {
        $this->_expiry = $seconds;
    }

    /**
     * setHost.
     *
     * @param string $host
     *
     * @return void
     */
    public function setHost($host)
    {
        $this->_host = $host;
    }

    /**
     * setLifetime.
     *
     * @param int $lifetime
     *
     * @return void
     */
    public function setLifetime($lifetime)
    {
        $this->_lifetime = $lifetime;
    }

    /**
     * setName.
     *
     * Sets the name of the session (cookie-wise).
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * setPath.
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * setSecret.
     *
     * Secret used for the hashing/signature process.
     *
     * @param string $secret
     *
     * @return void
     */
    public function setSecret($secret)
    {
        $this->_secret = $secret;
    }

    /**
     * setSecured.
     *
     * @return void
     */
    public function setSecured()
    {
        $this->_secure = true;
    }
}
