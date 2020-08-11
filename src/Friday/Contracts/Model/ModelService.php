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
     * @param bool   $pagination
     *
     * @return \Friday\Model\Table
     */
    public function table($tableName, $pagination = false);

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
    public function getPaginationHtml($url = '?', $style = 0, $cssClass = null, $replaceClass = false);

    /**
     * Function to sanitize values received from the form. Prevents SQL injection.
     *
     * @param mixed $string
     *
     * @return mixed
     */
    public function sanitizeFormValue($string);
}
