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
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Model;

use mysqli;

interface Table
{
    /**
     * Create a new Table instance.
     *
     * @param array $config
     *
     * @return mysqli
     */
    public function __construct(array $config = []);

    /**
     * Sets the database table name.
     *
     * @param string                    $table
     * @param \Friday\Helper\Pagination $pagination
     *
     * @return $this
     */
    public function setTable($table, $pagination);

    /**
     * Returns the database table name.
     *
     * @return string|bool
     */
    public function getTable();

    /**
     * Get field from table.
     *
     * @param bool $sqlQuery
     *
     * @return int|string
     */
    public function num_rows($sqlQuery = false);

    /**
     * Get field from table.
     *
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array|string
     */
    public function get($fields = null, $sqlQuery = false);

    /**
     * Get all fields from table.
     *
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array|string
     */
    public function getAll($fields = null, $sqlQuery = false);

    /**
     * Get paginated fields from table.
     *
     * @param int        $limit
     * @param array|null $fields
     * @param bool       $sqlQuery
     *
     * @return array
     */
    public function getPaginated($limit = 1, $fields = null, $sqlQuery = false);

    /**
     * Add data to table.
     *
     * @param array $data
     * @param bool  $sqlQuery
     *
     * @return bool|string
     */
    public function add($data, $sqlQuery = false);

    /**
     * Update data to table.
     *
     * @param string|array|null $field
     * @param bool              $sqlQuery
     *
     * @return bool|string
     */
    public function update($field = null, $sqlQuery = false);

    /**
     * Delete data from table.
     *
     * @param bool $sqlQuery
     *
     * @return bool|string
     */
    public function delete($sqlQuery = false);

    /**
     * Create WHERE clause.
     *
     * @param array  $where
     * @param string $glue
     *
     * @return $this
     */
    public function where($where, $glue = 'AND');

    /**
     * Create WHERE LIKE clause.
     *
     * @param array  $where
     * @param string $glue
     *
     * @return $this
     */
    public function like($where, $glue = 'AND');

    /**
     * Create ORDER BY clause.
     *
     * @param string $field
     * @param string $order
     *
     * @return $this
     */
    public function orderBy($field, $order = 'ASC');

    /**
     * Create LIMIT clause.
     *
     * @param int $start
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit, $start = null);

    /**
     * Create ON DUPLICATE clause.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function onDuplicateUpdate($fields);

    /**
     * Create sql query.
     *
     * @param string      $type
     * @param string|null $field
     *
     * @return string
     */
    public function buildQuery($type, $field = null, $extra = null);

    /**
     * Run sql query.
     *
     * @param string|null $query
     *
     * @return \mysqli_result|bool
     */
    public function executeQuery($query = null);

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param mixed $str
     *
     * @return mixed
     */
    public function sanitizeFormValue($str);

    /**
     * Create sql query.
     *
     * @param string $value
     *
     * @return string
     *
     * @since 1.0.5
     */
    public function sqlValue($value);

    /**
     * Get quoted string.
     *
     * @param string $value
     *
     * @return string
     *
     * @since 1.0.5
     */
    public function getQuotedString($value);

    /**
     * Get all of the models from the database.
     *
     * @return array
     *
     * @since 1.0.7
     */
    public function all($columns = null);
}
