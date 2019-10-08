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
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Helper;

class Pagination
{
    /**
     * Limit of content shown on page.
     *
     * @var int
     */
    public $limit;

    /**
     * Current Page number.
     *
     * @var int
     */
    public $page;

    /**
     * Start point for query.
     *
     * @var int
     */
    public $startpoint;

    /**
     * Total rows of table from query.
     *
     * @var int
     */
    public $total;

    /**
     * Create a new Pagination instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Initialize Pagination instance.
     *
     * @param int $limit
     * @param int $total
     *
     * @return void
     */
    public function initialize($limit, $total, $qry_url = '?')
    {
        $this->limit = $limit;
        $this->total = $total;
        $this->page = !isset($_GET['page']) ? 1 : (int) ($_GET['page']);
        $this->startpoint = ($this->page * $this->limit) - $this->limit;
    }

    /**
     * Get a start point for query.
     *
     * @return int
     */
    public function getStartPoint()
    {
        return $this->startpoint;
    }

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
    public function getPaginationHtml($url, $style = 0, $cssClass = null, $replaceClass = false)
    {
        if ($this->total == null) {
            return false;
        }

        list($ul_class, $li_class, $a_class) = $this->parseData($cssClass, $replaceClass);

        $adjacents = '2';

        $page = ($this->page == 0 ? 1 : $this->page);
        $start = ($page - 1) * $this->limit;

        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($this->total / $this->limit);
        $lpm1 = $lastpage - 1;

        $pagination = '';
        if ($lastpage >= 1) {
            $pagination .= "<ul class=\"$ul_class\">\n";
            if ($style == 0) {
                $pagination .= "\t<li class=\"$li_class[0]\"><span class=\"$a_class\">$page/$lastpage</span></li>\n";
            }

            if ($lastpage < 2 + ($adjacents * 2)) { // $lastpage < 6
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "\t".$this->getListItem($li_class[2], $a_class, null, $counter)."\n";
                    } else {
                        $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$counter", $counter)."\n";
                    }
                }
            } elseif ($lastpage > 2 + ($adjacents * 2)) { // $lastpage > 6
                if ($page < 0 + ($adjacents * 2)) { // $page < 4
                    for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $pagination .= "\t".$this->getListItem($li_class[2], $a_class, null, $counter)."\n";
                        } else {
                            $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$counter", $counter)."\n";
                        }
                    }
                    $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$lastpage", '&raquo;')."\n";
                } elseif ($lastpage - ($adjacents * 1) > $page && $page > ($adjacents * 1)) { // $lastpage - 2 > $page && $page > 3
                    $pagination .= $this->getListItem($li_class[1], $a_class, "{$url}page=1", '&laquo;')."\n";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "\t".$this->getListItem($li_class[2], $a_class, null, $counter)."\n";
                        } else {
                            $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$counter", $counter)."\n";
                        }
                    }
                    $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$lastpage", '&raquo;')."\n";
                } else {
                    $pagination .= "\t<li class=\"$li_class[1]\"><a class=\"$a_class\" href='{$url}page=1'>&laquo;</a></li>\n";
                    for ($counter = $lastpage - ($adjacents * 2); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "\t".$this->getListItem($li_class[2], $a_class, null, $counter)."\n";
                        } else {
                            $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$counter", $counter)."\n";
                        }
                    }
                }
            } else {
                if ($page <= 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $pagination .= "\t".$this->getListItem($li_class[2], $a_class, null, $counter)."\n";
                        } else {
                            $pagination .= "\t".$this->getListItem($li_class[1], $a_class, "{$url}page=$counter", $counter)."\n";
                        }
                    }
                    //$pagination.= "<li class=\"$li_class[1]\"><a class=\"$a_class\" href='{$url}page=$lastpage'>&raquo;</a></li>";
                }
            }
            $pagination .= "</ul>\n";
        }

        return $pagination;
    }

    /**
     * Parse css classes.
     *
     * @param array|null $cssClass
     * @param bool       $replaceClass
     *
     * @return array
     *
     * @since 1.0.6
     */
    public function parseData($cssClass, $replaceClass)
    {
        $ul_class = $li_class = $a_class = null;

        if ($cssClass == null) {
            $ul_class = 'pagination';
            $li_class = ['page-item', 'page-item', 'page-item active'];
            $a_class = 'page-link';
        } else {
            if (isset($cssClass['ul']) && trim($cssClass['ul']) != '') {
                $ul_class = $replaceClass ? $cssClass['ul'] : $ul_class.' '.$cssClass['ul'];
            }
            if (isset($cssClass['li']) && is_array($cssClass['li'])) {
                foreach ($cssClass['li'] as $i => $li) {
                    if (isset($li) && trim($li) != '') {
                        $li_class[$i] = $replaceClass ? $li : $li_class[$i].' '.$li;
                    }
                }
            }
            if (isset($cssClass['a']) && trim($cssClass['a']) != '') {
                $a_class = $replaceClass ? $cssClass['a'] : $a_class.' '.$cssClass['a'];
            }
        }

        return [$ul_class, $li_class, $a_class];
    }

    /**
     * Get li tag filled with data.
     *
     * @param string $li_class
     * @param string $a_class
     * @param string|null $href
     * @param int    $counter
     *
     * @return string
     *
     * @since 1.0.6
     */
    public function getListItem($li_class, $a_class, $href, $counter)
    {
        return "<li class=\"$li_class\"><a class=\"$a_class\" ".($href == null ? "" : "href='{$href}'").">$counter</a></li>";
    }
}
