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
 * @since         1.0.12
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Model;

use Friday\Model\ModelService;
use mysqli;

class DB extends ModelService
{

    /**
     * Instance of the DB.
     *
     * @var \Friday\Model\DB|null
     */
    private static $instance = null;

    /**
     * Create a new Table instance.
     *
     * @param array $config
     *
     * @return mysqli
     */
    public function __construct()
    {
        $this->connection();
    }

    /**
     * Sets the database table name.
     *
     * @return \Friday\Model\DB|$this
     */
    public static function connection()
    {
        if(is_null(static::$instance)) {
            return new DB;
        }
        return $this;
    }

    /**
     * Execute a raw query.
     *
     * @param string $query
     *
     * @return bool
     */
    public static function query($query)
    {
        return static::connection()->runQuery($query);
    }
}
