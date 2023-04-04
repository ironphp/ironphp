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
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Model;

class DataMapper
{
    /**
     * The IronPHP configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The Database connection.
     *
     * @var \Friday\Model\Table
     */
    protected $connection;

    /**
     * Create a new DataMapper instance.
     *
     * @return void
     */
    public function __construct($config = null)
    {
        if ($config) {
            $this->config = $config;
        }
        $this->connection = new \Friday\Model\Table(
            $this->config['db']['connections']['mysql']
        );
        //$this->connection->__debug() //return array if it is defined and returning an array, otherwise blenk array
        //$this->connection->__debugInfo() // same as __debug()

        //self::$instance->modelService->initialize(self::$instance->app);
    }

    /**
     * Set Table name and get Table instance.
     *
     * @param string                         $table
     * @param \Friday\Helper\Pagination|null $pagination
     *
     * @return \Friday\Model\Table
     */
    public function getTable($table, $pagination)
    {
        return $this->connection->setTable($table, $pagination);
    }

    /**
     * Get Table instance.
     *
     * @return \Friday\Model\Table
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
