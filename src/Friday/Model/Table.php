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

namespace Friday\Model;

use mysqli;

class Table
{
    /**
     * Instance of mysqli.
     *
     * @var \mysqli
     */
    private $connection;

    /**
     * Connection error no.
     *
     * @var int
     */
    private $connect_errno;

    /**
     * Connection error name.
     *
     * @var string
     */
    private $connect_error;

    /**
     * Query error no.
     *
     * @var int
     */
    private $errno;

    /**
     * Query error name.
     *
     * @var string
     */
    private $error;

    /**
     * Table name.
     *
     * @var string
     */
    private $table;

    /**
     * WHERE clause.
     *
     * @var string|null
     */
    private $where;

    /**
     * ORDER BY clause.
     *
     * @var string|null
     */
    private $order;

    /**
     * Instance of the Pagination.
     *
     * @var \Friday\Helper\Pagination
     */
    private $pagination = null;

    /**
     * LIMIT clause.
     *
     * @var string|null
     */
    private $limit = null;

    /**
     * ON DUPLICATE KEY UPDATE clause.
     *
     * @var string|null
     */
    private $duplicateUpdate = null;

    /**
     * Full builded query.
     *
     * @var string|null
     */
    private $query = null;

    /**
     * Number of affected rows.
     *
     * @var int|null
     */
    private $num_rows = null;

    /**
     * Create a new Table instance.
     *
     * @param array $config
     *
     * @return mysqli
     */
    public function __construct(array $config = [])
    {
        $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

        if (version_compare(PHP_VERSION, '5.2.9', '<=') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
            /*
             * This is the "official" OO way to do it,
             * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
             * Works as of PHP 5.2.9 and 5.3.0.
             */
            $this->connect_error = $mysqli->connect_error;
        } else {
            /*
             * Use this instead of $connect_error if you need to ensure
             * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
             */
            $this->connect_error = mysqli_connect_error();
        }

        $this->connect_errno = $mysqli->connect_errno;
        if ($this->connect_errno) {
            die('Connect Error ['.$this->connect_errno.']: '.$this->connect_error);
        }
        $this->connection = $mysqli;
    }

    /**
     * Sets the database table name.
     *
     * @param string                    $table
     * @param \Friday\Helper\Pagination $pagination
     *
     * @return $this
     */
    public function setTable($table, $pagination)
    {
        $this->where = null;
        $this->limit = null;
        $this->order = null;
        $this->duplicateUpdate = null;
        $this->query = null;
        $this->pagination = $pagination;
        $this->table = $table;
        $this->num_rows = null;

        return $this;
    }

    /**
     * Returns the database table name.
     *
     * @return string|bool
     */
    public function getTable()
    {
        if ($this->table === null) {
            return false;
        }

        return $this->table;
    }

    /**
     * Get field from table.
     *
     * @param bool $sqlQuery
     *
     * @return int|string
     */
    public function num_rows($sqlQuery = false)
    {
        $this->buildQuery('select');
        if ($sqlQuery === true) {
            return $this->getQuery();
        }

        return $this->executeQuery()->num_rows;
    }

    /**
     * Get field from table.
     *
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array|string
     */
    public function get($fields = null, $sqlQuery = false)
    {
        $this->buildQuery('select', $fields);
        if ($sqlQuery === true) {
            return $this->getQuery();
        }
        $result = $this->executeQuery();
        $this->num_rows = $result->num_rows;

        return $result->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Get all fields from table.
     *
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array|string
     */
    public function getAll($fields = null, $sqlQuery = false)
    {
        $data = [];
        $this->buildQuery('select', $fields);
        if ($sqlQuery === true) {
            return $this->getQuery();
        }
        $result = $this->executeQuery();
        $this->num_rows = $result->num_rows;
        if ($result->num_rows == 0) {
            return [];
        }
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Get paginated fields from table.
     *
     * @param int        $limit
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array
     */
    public function getPaginated($limit = 1, $fields = null, $sqlQuery = false)
    {
        $this->buildQuery('select', $fields, ['count'=>null, 'field'=>'num']);
        $result = $this->executeQuery();
        $this->num_rows = $result->num_rows;
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $total = $row['num'];
        if ($total == 0 && $sqlQuery == false) {
            return [];
        }
        $this->pagination->initialize($limit, $total);

        return $this->limit($limit, $this->pagination->getStartPoint())->getAll($fields, $sqlQuery);
    }

    /**
     * Add data to table.
     *
     * @param array $data
     * @param bool  $sqlQuery
     *
     * @return bool|string
     */
    public function add($data, $sqlQuery = false)
    {
        if (!is_array($data) || count($data) == 0) {
            echo 'no data to save'; //no argument
            exit;
        }
        $this->buildQuery('insert', $data);
        $this->num_rows = 0;
        if ($sqlQuery === true) {
            return $this->getQuery();
        }

        return $this->executeQuery();
    }

    /**
     * Update data to table.
     *
     * @param string|array|null $field
     * @param bool              $sqlQuery
     *
     * @return bool|string
     */
    public function update($field = null, $sqlQuery = false)
    {
        if (empty($field)) {
            echo 'No data to save'; //no argument
            exit;
        }

        $this->buildQuery('update', $field);

        if ($sqlQuery === true) {
            return $this->getQuery();
        }
        $result = $this->executeQuery();
        $this->num_rows = $result->num_rows;

        return $result;
    }

    /**
     * Delete data from table.
     *
     * @param bool $sqlQuery
     *
     * @return bool|string
     */
    public function delete($sqlQuery = false)
    {
        if (func_num_args() != 0) {
            echo 'invalid';
            exit;
        }
        $this->buildQuery('delete');
        if ($sqlQuery === true) {
            return $this->getQuery();
        }
        $result = $this->executeQuery();
        $this->num_rows = 0;

        return $result;
    }

    /**
     * Create WHERE clause.
     *
     * @param array  $where
     * @param string $glue
     *
     * @return $this
     */
    public function where($where, $glue = 'AND')
    {
        if (is_array($where) && count($where) != 0) {
            $array = [];
            foreach ($where as $field => $value) {
                $array[] = " `$field` = ".((is_string($value) ? "'$value'" : $value));
            }
            $this->where = ' WHERE'.implode(" $glue", $array);
        } elseif (is_string($where) && trim($where) != '') {
            $where = trim($where);
            $where = trim($where, 'WHERE ');
            $where = rtrim($where);
            $this->where = ' WHERE '.$where;
        }

        return $this;
    }

    /**
     * Create WHERE LIKE clause.
     *
     * @param array  $where
     * @param string $glue
     *
     * @return $this
     */
    public function like($where, $glue = 'AND')
    {
        if (is_array($where) && count($where) != 0) {
            $array = [];
            foreach ($where as $field => $value) {
                $array[] = " `$field` LIKE ".((is_string($value) ? "'$value'" : $value));
            }
            $this->where = ' WHERE'.implode(" $glue", $array);
        } elseif (is_string($where) && trim($where) != '') {
            $where = trim($where);
            $where = trim($where, 'WHERE ');
            $where = rtrim($where);
            $this->where = ' WHERE '.$where;
        }

        return $this;
    }

    /**
     * Create ORDER BY clause.
     *
     * @param string $field
     * @param string $order
     *
     * @return $this
     */
    public function orderBy($field, $order = 'ASC')
    {
        if (is_string($field) && trim($field) != '') {
            $field = trim($field);
            $field = ltrim($field, 'ORDER BY ');
            $this->order = ' ORDER BY `'.$field.'`'.(($order == 'DESC') ? ' DESC' : ' ASC');
        }

        return $this;
    }

    /**
     * Create LIMIT clause.
     *
     * @param int $start
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit, $start = null)
    {
        if (is_int($limit)) {
            $build = $limit;
            if (is_int($start)) {
                $build = $start.', '.$limit;
            }
            $this->limit = ' LIMIT '.$build;
        }

        return $this;
    }

    /**
     * Create ON DUPLICATE clause.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function onDuplicateUpdate($fields)
    {
        if (is_array($fields) && count($fields) != 0) {
            $array = [];
            foreach ($fields as $field => $value) {
                $array[] = " `$field` = ".((is_string($value) ? "'$value'" : $value));
            }
            $this->duplicateUpdate = ' ON DUPLICATE KEY UPDATE '.implode(' ,', $array);
        } elseif (is_string($fields) && trim($fields) != '') {
            $fields = trim($fields);
            //$where = trim($where, 'WHERE ');
            //$where = rtrim($where);
            $this->duplicateUpdate = ' ON DUPLICATE KEY UPDATE '.$fields;
        }

        return $this;
    }

    /**
     * Create sql query.
     *
     * @param string      $type
     * @param string|null $field
     *
     * @return string
     */
    public function buildQuery($type, $field = null, $extra = null)
    {
        $values = [];
        $sql = null;
        if ($type == 'select') {
            if ($field == null) {
                $field = '*';
            } elseif (is_array($field)) {
                foreach ($field as $i => $value) {
                    $field[$i] = '`'.trim($value).'`';
                }
                $field = trim(implode(' ,', $field));
            }
            $ex = '';
            if ($extra != null && is_array($extra)) {
                foreach ($extra as $i => $v) {
                    if ($i == 'count') {
                        $ex .= 'COUNT';
                        if ($v == null) {
                            $ex .= '(*)';
                        }
                    }
                    if ($i == 'field') {
                        $ex .= ' as `'.$v.'`';
                    }
                }
                $sql = "SELECT $ex FROM `".$this->getTable().'` '.$this->where;
            } else {
                $sql = "SELECT $field FROM `".$this->getTable().'` '.$this->where.$this->order.$this->limit;
            }
        } elseif ($type == 'insert') {
            $values = [];
            if (is_array($field)) {
                $keys = array_keys($field);
                if ($keys[0] === 0) {
                    $keys = '';
                } else {
                    foreach ($keys as $i => $key) {
                        $keys[$i] = ' `'.trim($key).'`';
                    }
                    $keys = implode(',', $keys);
                }
                $values = array_values($field);
                foreach ($values as $i => $val) {
                    $values[$i] = is_string($val) ? "'$val'" : $val;
                }
                $values = implode(' ,', $values);
            } else {
                $keys = '';
                $values = trim($field);
            }
            $values = "($values)";
            $keys = ($keys !== '') ? "($keys)" : '';
            $sql = 'INSERT INTO `'.$this->getTable()."` $keys VALUES $values".$this->duplicateUpdate;
        } elseif ($type == 'update') {
            if (is_array($field)) {
                foreach ($field as $key => $value) {
                    $values[] = " `$key` = ".(is_string($value) ? "'$value'" : $value);
                }
                $values = implode(' ,', $values);
            } else {
                $values = trim($field);
            }
            $sql = 'UPDATE `'.$this->getTable()."` SET $values ".$this->where;
        } elseif ($type == 'delete') {
            $sql = 'DELETE FROM `'.$this->getTable().'` '.$this->where;
        }
        $this->query = $sql;

        return $sql;
    }

    /**
     * Run sql query.
     *
     * @param string|null $query
     *
     * @return bool|mysqli_result
     */
    public function executeQuery($query = null)
    {
        if ($query != null) {
            $this->query = $query;
        }
        $result = $this->connection->query($this->query);
        $this->errno = $this->connection->errno;
        $this->error = $this->connection->error;
        if ($this->connection->errno) {
            echo 'Query Error ['.$this->errno.'] : '.$this->error.' : '.$this->query;
        }
        if ($this->errno == 1054) {
            echo 'Table not set properly in query';
        }

        return $result;
    }

    /**
     * Get builded sql query.
     *
     * @return string
     */
    private function getQuery()
    {
        return $this->query;
    }

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param mixed $str
     *
     * @return mixed
     */
    public function sanitizeFormValue($str)
    {
        $str = trim($str);
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }

        return $this->connection->real_escape_string($str);
    }
}
