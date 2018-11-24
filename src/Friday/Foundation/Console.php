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

namespace Friday\Foundation;

use Friday\Console\Command;
use Friday\Console\Colors;

class Console extends Application
{
    /**
     * FrontController instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $frontController;

    /**
     * Request instance.
     *
     * @var \Friday\Http\Request
     */
    public $request;

    /**
     * Route instance.
     *
     * @var \Friday\Http\Route
     */
    public $route;

    /**
     * Router instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $router;

    /**
     * Dispatcher instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $dispatcher;

    /**
     * Response instance.
     *
     * @var \Friday\Http\FrontController
     */
    public $response;

    /**
     * Matched Route to uri.
     *
     * @var array
     */
    public $matchRoute;

    /**
     * Instanse of Session.
     *
     * @var \Friday\Helper\Session
     */
    public $session;

    /**
     * Instanse of Cookie.
     *
     * @var \Friday\Helper\Cookie
     */
    public $cookie;

    /**
     * Headers to be sent.
     *
     * @var array
     */
    public $headers = [];

    /**
     * Command and arguments from argv.
     *
     * @var array
     */
    private $tokens;

    /**
     * Command instance.
     *
     * @var \Friday\Console\Command
     */
    protected $command;

    /**
     * Create a new Friday application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        $argv = $_SERVER['argv'];

        # Strip the application name
        array_shift($argv);

        $this->tokens = $argv;

        define('APP_INIT', microtime(true));
        $this->command = new Command($this);
    }
    
    /**
     * Get Token.
     *
     * @return array
     */
    public function getToken()
    {
        return $this->tokens;
    }
    
    /**
     * Command Error.
     *
     * @param  string  $errorMessage
     * @return string
     */
    public function commandError($errorMessage)
    {
        return PHP_EOL.Colors::BG_RED.str_repeat(" ", strlen($errorMessage)+4).
        PHP_EOL.Colors::WHITE ."  $errorMessage  ".
        PHP_EOL.Colors::BG_RED.str_repeat(" ", strlen($errorMessage)+4).              
        PHP_EOL.Colors::BG_BLACK.''.Colors::WHITE;
    }
}