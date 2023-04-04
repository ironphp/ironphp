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
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Contracts\Model;

interface DataMapper
{
    /**
     * Create a new DataMapper instance.
     *
     * @return void
     */
    public function __construct($config = null);

    /**
     * Set Table name and get Table instance.
     *
     * @param string                    $table
     * @param \Friday\Helper\Pagination $pagination
     *
     * @return \Friday\Model\Table
     */
    public function getTable($table, $pagination);

    /**
     * Get Table instance.
     *
     * @return \Friday\Model\Table
     */
    public function getConnection();
}
