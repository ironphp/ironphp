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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Model;

use BadMethodCallException;
use Friday\Helper\Inflector;
use Friday\Helper\Pagination;
use Friday\Model\DataMapper;

class ModelService
{
    /**
     * Instance of the Controller.
     *
     * @var \Friday\Controller\Controller
     */
    private static $controller;

    /**
     * Instance of the Application.
     *
     * @var \Friday\Foundation\Application
     */
    private static $app;

    /**
     * Instance of the DataMapper.
     *
     * @var \Friday\Model\DataMapper
     */
    private $dataMapper = null;

    /**
     * Instance of the Pagination.
     *
     * @var \Friday\Helper\Pagination
     */
    private $pagination = null;

    /**
     * Instance of the ModelService.
     *
     * @var \Friday\Model\ModelService|null
     */
    private static $instance;

    /**
     * Initialize ModelService instance.
     *
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function initialize($app)
    {
        self::$instance = $this;
        if (self::$app === null) {
            self::$app = $app;
        }
    }

    /**
     * Create Instance of Table.
     *
     * @param string $tableName
     * @param bool   $pagination
     *
     * @return \Friday\Model\Table
     */
    public function table($tableName, $pagination = true)
    {
        return $this->getDataMapper()
            ->getTable($tableName, $pagination ? $this->getPagination() : null);
    }

    /**
     * Get Instance of Pagination.
     *
     * @return \Friday\Helper\Pagination
     */
    private function getPagination()
    {
        if ($this->pagination == null) {
            $this->pagination = new Pagination();
        }

        return $this->pagination;
    }

    /**
     * Is user logged or not.
     *
     * @return bool
     */
    protected function isLogged()
    {
        // TODO: SESS_MEMBER_ID key predefined by user
        // TODO: user auth method can be inside user-auth class
        if (self::$app->session->get('SESS_MEMBER_ID')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is user superadmin or not.
     *
     * @return bool
     */
    protected function isAdmin()
    {
        // TODO: SESS_USER_TYPE key predefined or optional by user
        // TODO: user auth method can be inside user-auth class
        $usertype = self::$app->session->get('SESS_USER_TYPE');
        if ($usertype === 'Master') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param mixed $string
     *
     * @return mixed
     */
    public function sanitizeFormValue($string)
    {
        return $this->getConnection()
            ->sanitizeFormValue($string);
    }

    /**
     * Get instance of Request.
     *
     * @return \Friday\Http\Request
     */
    protected function request()
    {
        return self::$app->request;
    }

    /**
     * Get APP_KEY value.
     *
     * @return string|false
     */
    protected function getAppKey()
    {
        return base64_decode(
            str_replace('base64:', '', env('APP_KEY'))
        );
    }

    /**
     * Execute SQL Query.
     *
     * @param string $query
     *
     * @return bool|\mysqli_result
     */
    protected function runQuery($query)
    {
        return $this->getConnection()
            ->executeQuery($query);
    }

    /**
     * Get instance of DataMapper.
     *
     * @return \Friday\Model\DataMapper
     */
    private function getDataMapper()
    {
        if ($this->dataMapper == null) {
            $this->dataMapper = new DataMapper(self::$app->config);
        }

        return $this->dataMapper;
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return \Friday\Model\ModelService
     *
     * @since 1.0.7
     */
    public static function __callStatic($method, $parameters)
    {
        self::$instance->parseAndAddTable(
            get_called_class(),
            $parameters
        );

        if (!method_exists(self::$instance->getConnection(), $method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                static::class,
                $method
            ));
        }

        return call_user_func_array([self::$instance->getConnection(), $method], $parameters);
    }

    /**
     * Get Table instance.
     *
     * @return \Friday\Model\Table
     */
    private function getConnection()
    {
        return $this->getDataMapper()
            ->getConnection();
    }

    /**
     * Parse and Get Table from Class name.
     *
     * @param string $class
     *
     * @return string
     *
     * @renamed 1.0.12
     */
    private function parseAndAddTable($class)
    {
        return $this->getDataMapper()
            ->getConnection()
            ->parseAndAddTable($class, $this->getPagination());
    }

    /**
     * Auth Middleware.
     *
     * @param string $auth
     * @param string|null $page
     *
     * @since 1.0.12
     *
     * @return Friday\Model\ModelService|void
     */
    public static function middleware($auth = 'user', $page = null)
    {
        // TODO: login, index - $page can predefined by user
        switch ($auth) {
            case 'user':
                if (self::$instance->isLogged() === false) {
                    redirect($page ?: route('login'));
                }
                break;

            case 'admin':
                if (self::$instance->isAdmin() === false) {
                    redirect($page ?: route('login'));
                }
                break;

            case 'guest':
                if (self::$instance->isLogged() === true) {
                    redirect($page ?: route('index'));
                }
                break;

            case 'admin-guest':
                if (self::$instance->isLogged() === true && self::$instance->isAdmin() === true) {
                    redirect($page ?: route('index'));
                }
                break;

            default:
                if (self::$instance->isLogged() === false) {
                    redirect($page ?: route('login'));
                }
                break;
        }
    }
}
