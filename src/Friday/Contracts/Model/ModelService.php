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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com
 */

namespace Friday\Contracts\Model;

interface ModelService
{
    /**
     * Initialize ModelService instance.
     *
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function initialize($app);

    /**
     * Create Instance of Table.
     *
     * @param string $tableName
     *
     * @return \Friday\Model\Table
     */
    public function table($tableName);

    /**
     * Get Instance of Pagination.
     *
     * @return \Friday\Helper\Pagination
     */
    private function getPagination();

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
    protected function getPaginationHtml($url = '?', $style = 0, $cssClass = null, $replaceClass = false);

    /**
     * Is user logged or not.
     *
     * @return bool
     */
    protected function isLogged();

    /**
     * Is user superadmin or not.
     *
     * @return bool
     */
    protected function isAdmin();

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param mixed $string
     *
     * @return mixed
     */
    protected function sanitizeFormValue($string);

    /**
     * Get instance of Request.
     *
     * @return \Friday\Http\Request
     */
    protected function request();

    /**
     * Get APP_KEY value.
     *
     * @return string|false
     */
    protected function getAppKey();

    /**
     * Get hash salt value.
     *
     * @return string
     */
    protected function getSalt();

    /**
     * Execute SQL Query.
     *
     * @param string $query
     *
     * @return bool|\mysqli_result
     */
    protected function runQuery($query);

    /**
     * Get instance of DataMapper.
     *
     * @return \Friday\Model\DataMapper
     */
    private function getDataMapper();
}
