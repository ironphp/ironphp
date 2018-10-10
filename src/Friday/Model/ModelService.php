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
        self::$app = $app;
    }

    /**
     * Create Instance of Table.
     *
     * @param  string  $tableName
     * @return \App\Model\Table
     */
    public function table($tableName)
    {
        if($this->dataMapper == null) {
            $this->dataMapper = new \Friday\Model\DataMapper(self::$app->config);
        }
        return $this->dataMapper->getTable($tableName, $this->getPagination());
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
     * @param  string $urlstr
     * @return string
     */
    public function getPaginationHtml($urlstr = '?')
    {
        return $this->pagination->getPaginationHtml($urlstr);
    }

    /**
     * Is user logged or not.
     *
     * @return string
     */
    public function isLogged()
    {
        if(self::$app->session->get('user_id')) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Is user admin or not.
     *
     * @return string
     */
    public function isAdmin()
    {
        if(self::$app->session->get('master')) {
            return true;
        }
        else {
            return false;
        }
    }
}