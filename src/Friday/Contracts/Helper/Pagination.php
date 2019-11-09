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
 * @link          https://github.com/IronPHP/IronPHP
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Helper;

interface Pagination
{
    /**
     * Create a new Pagination instance.
     *
     * @return void
     */
    public function __construct();

    /**
     * Initialize Pagination instance.
     *
     * @param int $limit
     * @param int $total
     *
     * @return void
     */
    public function initialize($limit, $total, $qry_url = '?');

    /**
     * Get a start point for query.
     *
     * @return int
     */
    public function getStartPoint();

    /**
     * Get pagination html.
     *
     * @param string     $url
     * @param int        $style
     * @param array|null $cssClass
     * @param bool       $replaceClass
     *
     * @return string|null|bool
     */
    public function getPaginationHtml($url, $style = 0, $cssClass = null, $replaceClass = false);

    /**
     * Parse css classes.
     *
     * @param array|null $cssClass
     * @param bool       $replaceClass
     *
     * @return array
     */
    public function parseData($cssClass, $replaceClass);

    /**
     * Get li tag filled with data.
     *
     * @param string      $li_class
     * @param string      $a_class
     * @param string|null $href
     * @param int         $counter
     * @param bool        $span
     *
     * @return string
     */
    public function getListItem($li_class, $a_class, $href, $counter, $span = false);

    /**
     * Get Pagination Fraction.
     *
     * @param int         $page
     * @param array       $li_class
     * @param string      $a_class
     * @param string|null $href
     * @param int         $counter
     *
     * @return string
     */
    public function getPaginationFraction($page, $li_class, $a_class, $href, $counter);
}
