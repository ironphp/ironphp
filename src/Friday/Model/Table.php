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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Model;

use Exception;
use Friday\Helper\Inflector;
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
     * @var \Friday\Helper\Pagination|null
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
     * Model fully classified name.
     *
     * @var string|null
     */
    private $model = null;

    /**
     * Create a new Table instance.
     *
     * @param array $config
     *
     * @return mysqli
     */
    public function __construct(array $config = [])
    {
        try {
            $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        } catch (Exception $e) {
            exit('Error : '.$e->getMessage());
        }

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

        $this->connect_errno = (int) $mysqli->connect_errno;
        if ($this->connect_errno) {
            exit('Connect Error ['.$this->connect_errno.']: '.$this->connect_error);
        }
        $this->connection = $mysqli;
    }

    /**
     * Sets the database table name.
     *
     * @param string                         $table
     * @param \Friday\Helper\Pagination|null $pagination
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
     * @param string|array|null $fields
     * @param bool              $sqlQuery
     *
     * @return array|string
     */
    public function get($fields = null, $sqlQuery = false)
    {
        if ($fields = '*' || (is_array($fields) && count($fields) == 1 && $fields[0] == '*')) {
            $fields = null;
        }

        $this->buildQuery('select', $fields);
        if ($sqlQuery === true) {
            return $this->getQuery();
        }

        $result = $this->executeQuery();
        if ($result == false) {
            $this->num_rows = null;

            return false;
        } else {
            $this->num_rows = $result->num_rows;

            return $result->fetch_array(MYSQLI_ASSOC);
        }
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
        if ($this->pagination === null) {
            // TODO
        }

        $this->buildQuery('select', $fields, ['count'=>null, 'field'=>'num']);
        $result = $this->executeQuery();
        $this->num_rows = $result->num_rows;
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $total = $row['num'];

        if ($total == 0 && $sqlQuery == false) {
            return [];
        }
        $this->pagination->initialize($limit, $total);

        $data = $this->limit($limit, $this->pagination->getStartPoint())->getAll($fields, $sqlQuery);

        // should return normal array instead of array of objects, should be use paginate() for array of objects
        if (is_array($data)) {
            foreach ($data as $i => $item) {
                $data[$i] = (object) $item;
            }
        }

        return $data;
    }

    /**
     * Add data to table.
     *
     * @param array $data
     * @param bool  $sqlQuery
     *
     * @return \mysqli_result|bool|string
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
     * @return \mysqli_result|bool|string
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
        $this->num_rows = null;

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
     * @param mix    $value
     *
     * @return $this
     */
    public function where($where, $glue = 'AND', $value = null)
    {
        if (is_array($where) && count($where) != 0) {
            $array = [];
            foreach ($where as $field => $value) {
                $array[] = " `$field` = ".(is_string($value) ? "'$value'" : $value);
            }
            $this->where = ' WHERE'.implode(" $glue", $array);
        } elseif (is_string($where) && trim($where) != '') {
            $where = trim($where);
            if ($value == null) {
                $where = trim($where, 'WHERE ');
                $where = rtrim($where);
                $this->where = ' WHERE '.$where;
            } else {
                $value = is_string($value) ? "\"$value\"" : $value;
                $this->where = ' WHERE `'.$where.'` '.$glue.' '.$value;
            }
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
                $array[] = " `$field` LIKE ".(is_string($value) ? "'$value'" : $value);
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
                $array[] = " `$field` = ".(is_string($value) ? "'$value'" : $value);
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
                    $field[$i] = $this->sqlValue($value);
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
                        $keys[$i] = ' '.$this->sqlValue($key);
                    }
                    $keys = implode(',', $keys);
                }
                $values = array_values($field);
                foreach ($values as $i => $val) {
                    $values[$i] = $this->getQuotedString($val);
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
                    $values[] = " `$key` = ".$this->getQuotedString($value);
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
     * @return \mysqli_result|bool
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
            echo 'Query Error <strong>['.$this->errno.']</strong> : <strong>'.$this->error.'</strong> : '.$this->query;
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
        /*
        Function get_magic_quotes_gpc() is deprecated in PHP 7.4
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        */

        return $this->connection->real_escape_string($str);
    }

    /**
     * Create sql query.
     *
     * @param string $value
     *
     * @return string
     *
     * @since 1.0.5
     */
    public function sqlValue($value)
    {
        return '`'.trim($value).'`';
    }

    /**
     * Get quoted string.
     *
     * @param string $value
     *
     * @return string
     *
     * @since 1.0.5
     */
    public function getQuotedString($value)
    {
        return is_string($value) ? "'$value'" : $value;
    }

    /**
     * Get all of the models from the database.
     *
     * @param array|mixed|null $columns
     *
     * @return array
     *
     * @since 1.0.7
     */
    public function all($columns = null)
    {
        $data = $this->getAll();
        if (is_array($data)) {
            foreach ($data as $i => $item) {
                $data[$i] = (object) $item;
            }
        }

        return $data;
        /*
        return static::query()->get(
            is_array($columns) ? $columns : func_get_args()
        );
        */
    }

    /**
     * Execute the query and get the first related model.
     *
     * @param array $columns
     *
     * @return mixed
     *
     * @since 1.0.11
     */
    public function first($columns = ['*'])
    {
        $data = $this->get($columns);
        if ($data) {
            $data = (object) $data;
        }

        return $data;
    }

    /**
     * Execute the query and add data to related table.
     *
     * @param array $data
     *
     * @return mixed
     *
     * @since 1.0.11
     */
    public function create($data)
    {
        return $this->add($data);
    }

    /**
     * Get paginated fields from table.
     *
     * @param int        $limit
     * @param array|null $fields
     *
     * @return array
     *
     * @since 1.0.11
     */
    public function paginate($limit = 1, $fields = null)
    {
        return $this->getPaginated($limit, $fields);
    }

    /**
     * Add WHERE clause with AND conjuction.
     *
     * @param array  $where
     * @param string $glue
     * @param mix    $value
     *
     * @return $this
     *
     * @since 1.0.11
     */
    public function andWhere($where, $glue = 'AND', $value = null)
    {
        $prev_where = $this->where;
        $this->where($where, $glue, $value);
        $next_where = trim(str_replace('WHERE', '', $this->where));
        $this->where = $prev_where.' AND '.$next_where;

        return $this;
    }

    /**
     * Add WHERE clause with OR conjuction.
     *
     * @param array  $where
     * @param string $glue
     * @param mix    $value
     *
     * @return $this
     *
     * @since 1.0.11
     */
    public function orWhere($where, $glue = 'AND', $value = null)
    {
        $prev_where = $this->where;
        $this->where($where, $glue, $value);
        $next_where = trim(str_replace('WHERE', '', $this->where));
        $this->where = $prev_where.' OR '.$next_where;

        return $this;
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
     *
     * @moved 1.0.11
     */
    public function getPaginationHtml($url = '?', $style = 0, $cssClass = null, $replaceClass = false)
    {
        if ($this->pagination) {
            return $this->pagination->getPaginationHtml($url, $style, $cssClass, $replaceClass);
        }

        return false;

        // TODO: in case pagination not exist
        //return self::$instance->pagination->getPaginationHtml($url, $style, $cssClass, $replaceClass);
    }

    /**
     * Get record by id.
     *
     * @param int $id
     *
     * @return \App\Model
     *
     * @since 1.0.12
     */
    public function find($id)
    {
        $row = $this->where('id', '=', $id)->get();
        $model = new $this->model();
        foreach ($row as $key => $val) {
            $model->$key = $val;
        }

        return $model;
    }

    /**
     * Parse and Get Table from Class name.
     *
     * @param string                    $class
     * @param \Friday\Helper\Pagination $pagination
     *
     * @return string
     *
     * @copied 1.0.12
     */
    public function parseAndAddTable($class, $pagination)
    {
        $this->model = $class;
        $table = Inflector::pluralize(
            strtolower(
                (new \ReflectionClass($class))->getShortName()
            )
        );

        return $this->setTable($table, $pagination);
    }
}
