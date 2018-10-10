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

namespace Friday\Helper;

class Session
{
    /**
     * Create a new Session instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(is_session_started() === false ) {
            session_start();
        }
    }

    /**
     * Register the session.
     *
     * @param integer $time.
     */
    public function register($time = 60)
    {
        $_SESSION['session_id'] = session_id();
        $_SESSION['session_time'] = intval($time);
        $_SESSION['session_start'] = $this->newTime();
    }

    /**
     * Checks to see if the session is registered.
     *
     * @return  True if it is, False if not.
     */
    public function isRegistered()
    {
        if (! empty($_SESSION['session_id'])) {
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
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve value stored in session by key.
     *
     * @var mixed
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
     * @return integer - session id
     */
    public function getSessionId()
    {
        return $_SESSION['session_id'];
    }

    /**
     * Checks to see if the session is over based on the amount of time given.
     *
     * @return boolean
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
     * @return unix timestamp
     */
    private function timeNow()
    {
        $currentHour = date('H');
        $currentMin = date('i');
        $currentSec = date('s');
        $currentMon = date('m');
        $currentDay = date('d');
        $currentYear = date('y');
        return mktime($currentHour, $currentMin, $currentSec, $currentMon, $currentDay, $currentYear);
    }

    /**
     * Generates new time.
     *
     * @return unix timestamp
     */
    private function newTime()
    {
        $currentHour = date('H');
        $currentMin = date('i');
        $currentSec = date('s');
        $currentMon = date('m');
        $currentDay = date('d');
        $currentYear = date('y');
        return mktime($currentHour, ($currentMin + $_SESSION['session_time']), $currentSec, $currentMon, $currentDay, $currentYear);
    }

    /**
     * Destroys the session.
     */
    public function end()
    {
        session_destroy();
        $_SESSION = array();
    }
}
/*
##### Logging in. (login.php)
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Browser\Session\Session;

    $errors = array();

    // You'll definitely want to add more validation here and check against a
    // database or something. This is just an example.
    if (! empty($_POST)) {
        if ($_POST['username'] == 'test' && $_POST['password'] == 'test') {
            $session = new Session();

            // You can define what you like to be stored.
            $user = array(
                'user_id' => 1,
                'username' => $_POST['username']
            );

            $session->register(120); // Register for 2 hours.
            $session->set('current_user', $user);
            header('location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid login.';
        }
    }
?>

// Your form here.
```


##### Secure area once authenticated. (index.php/controller/whatever)
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Browser\Session\Session;

    $session = new Session();

    // Check if the session registered.
    if ($session->isRegistered()) {
        // Check to see if the session has expired.
        // If it has, end the session and redirect to login.
        if ($session->isExpired()) {
            $session->end();
            header('location: login.php');
            exit;
        } else {
            // Keep renewing the session as long as they keep taking action.
            $session->renew();
        }
    } else {
        header('location: login.php');
        exit;
    }
?>
```


##### Logging out. (logout.php)
```php
<?php
    require 'vendor/autoload.php';
    use rcastera\Browser\Session\Session;

    $session = new Session();
    $session->end();
    header('location: login.php');
    exit;
?>
```
*/