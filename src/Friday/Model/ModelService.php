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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Model;

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
     * Create a new ModelService instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Initialize ModelService instance.
     *
     * @param  \Friday\Foundation\Application  $app
     * @return void
     */
    public function initialize($app)
    {
        if(self::$app === NULL) {
            self::$app = $app;
        }
    }

    /**
     * Create Instance of Table.
     *
     * @param  string  $tableName
     * @return \Friday\Model\Table
     */
    public function table($tableName)
    {
        return $this->getDataMapper()->getTable($tableName, $this->getPagination());
    }

    /**
     * Get Instance of Pagination.
     *
     * @return \Friday\Helper\Pagination
     */
    private function getPagination()
    {
        if($this->pagination == null) {
            $this->pagination = new \Friday\Helper\Pagination();
        }
        return $this->pagination;
    }

    /**
     * Get pagination html.
     *
     * @param  string  $url
     * @param  int     $style
     * @param  array   $cssClass
     * @param  bool    $replaceClass
     * @return string|null
     */
    protected function getPaginationHtml($url = '?', $style = 0, $cssClass = null, $replaceClass = false)
    {
        return $this->pagination->getPaginationHtml($url, $style, $cssClass, $replaceClass);
    }

    /**
     * Is user logged or not.
     *
     * @return string
     */
    protected function isLogged()
    {
        if(self::$app->session->get('SESS_MEMBER_ID')) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Is user superadmin or not.
     *
     * @return string
     */
    protected function isAdmin()
    {
        $usertype = self::$app->session->get('SESS_USER_TYPE');
        if($usertype === 'Master') {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param   mixed   $string
     * @return  mixed
     */
    protected function sanitizeFormValue($string)
    {
        return $this->getDataMapper()->getConnection()->sanitizeFormValue($string);
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
     * @return string
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
     * @return mysqli_result
     */
    protected function runQuery($query)
    {
        return $this->getDataMapper()->getConnection()->executeQuery($query);
    }

    /**
     * Get instance of DataMapper.
     *
     * @return \Friday\Model\DataMapper
     */
    private function getDataMapper()
    {
        if($this->dataMapper == null) {
            $this->dataMapper = new \Friday\Model\DataMapper(self::$app->config);
        }
        return $this->dataMapper;
    }
}