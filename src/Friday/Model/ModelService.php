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
        return $this->getDataMapper()->getTable($tableName, $pagination ? $this->getPagination() : null);
    }

    /**
     * Get Instance of Pagination.
     *
     * @return \Friday\Helper\Pagination
     */
    private function getPagination()
    {
        if ($this->pagination == null) {
            $this->pagination = new \Friday\Helper\Pagination();
        }

        return $this->pagination;
    }

    /**
     * Get pagination html.
     *
     * @param string $url
     * @param int    $style
     * @param array  $cssClass
     * @param bool   $replaceClass
     *
     * @return bool|string|null
     */
    protected function getPaginationHtml($url = '?', $style = 0, $cssClass = null, $replaceClass = false)
    {
        return $this->pagination->getPaginationHtml($url, $style, $cssClass, $replaceClass);
    }

    /**
     * Is user logged or not.
     *
     * @return bool
     */
    protected function isLogged()
    {
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
    protected function sanitizeFormValue($string)
    {
        return $this->getConnection()->sanitizeFormValue($string);
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
        $key = env('APP_KEY');
        $key = str_replace('base64:', '', $key);

        return base64_decode($key);
    }

    /**
     * Get hash salt value.
     *
     * @return string
     */
    protected function getSalt()
    {
        return self::$app->config['app']['salt'];
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
        return $this->getConnection()->executeQuery($query);
    }

    /**
     * Get instance of DataMapper.
     *
     * @return \Friday\Model\DataMapper
     */
    private function getDataMapper()
    {
        if ($this->dataMapper == null) {
            $this->dataMapper = new \Friday\Model\DataMapper(self::$app->config);
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
        $called_class = get_called_class();
        $table = self::$instance->parseTable($called_class);
        self::$instance->table($table);

        if (!method_exists(self::$instance->getConnection(), $method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
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
        return $this->getDataMapper()->getConnection();
    }

    /**
     * Get Table from Class name.
     *
     * @param string $class
     *
     * @return string
     */
    private function parseTable($class)
    {
        return Inflector::pluralize(strtolower(basename($class)));
    }
}
