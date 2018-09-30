<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       IronPHP
 * @copyright     Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 * @link          
 * @since         0.0.1
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
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
     * @param  int  $limit
     * @param  int  $total
     * @return void
     */
    public function initialize($limit, $total, $qry_url = '?')
    {
        $this->limit = $limit;
        $this->total = $total;
        $this->page = !isset($_GET["page"]) ? 1 : (int) ($_GET["page"]);
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
     * @param  string  $url
     * @return string
     */
    public function getPaginationHtml($url)
    {
	    $adjacents = "2"; 
    	
	    $page = ($this->page == 0 ? 1 : $this->page);  
        $start = ($page - 1) * $this->limit;								
	
        $prev = $page - 1;							
        $next = $page + 1;
        $lastpage = ceil($this->total/$this->limit);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if($lastpage >= 1) {	
		    $pagination .= "<ul class='pagination'>\n";
            $pagination .= "\t<li class='details'><a>$page/$lastpage</a></li>\n";

		    if($lastpage < 2 + ($adjacents * 2)) { // $lastpage < 6
			    for($counter = 1; $counter <= $lastpage; $counter++) {
				    if ($counter == $page) {
					    $pagination.= "\t<li class='active'><a>$counter</a></li>\n";
                    }
    			    else {
    				    $pagination.= "\t<li><a href='{$url}page=$counter'>$counter</a></li>\n";
                    }
    		    }
    	    }
            elseif($lastpage > 2 + ($adjacents * 2)) { // $lastpage > 6
			    if($page < 0 + ($adjacents * 2)) { // $page < 4
				    for($counter = 1; $counter < 2 + ($adjacents * 2); $counter++) {
					    if($counter == $page) {
    					    $pagination.= "<li class='active'><a>$counter</a></li>";
                        }
    				    else {
    					    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                        }
    			    }
				    $pagination.= "<li><a href='{$url}page=$lastpage'>&raquo;</a></li>";
    		    }
                elseif($lastpage - ($adjacents * 1) > $page && $page > ($adjacents * 1)) { // $lastpage - 2 > $page && $page > 3
    			    $pagination.= "<li><a href='{$url}page=1'>&laquo;</a></li>";
    			    for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
    				    if($counter == $page) {
    					    $pagination.= "<li class='active'><a>$counter</a></li>";
                        }
    				    else {
    					    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                        }
    			    }
    			    $pagination.= "<li><a href='{$url}page=$lastpage'>&raquo;</a></li>";	
    		    }
                else {
    			$pagination.= "<li><a href='{$url}page=1'>&laquo;</a></li>";
    			    for($counter = $lastpage - ($adjacents * 2); $counter <= $lastpage; $counter++) {
					    if($counter == $page) {
    					    $pagination.= "<li class='active'><a>$counter</a></li>";
                        }
    				    else {
    					    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                        }
    			    }
    		    }
    	    }
            else {
    		    if($page < 1 + ($adjacents * 2)) {
				    for($counter = 1; $counter < 2 + ($adjacents * 2); $counter++) {
					    if($counter == $page) {
    					    $pagination.= "<li class='active'><a>$counter</a></li>";
                        }
    				    else {
    					    $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                        }
				    }
				    $pagination.= "<li><a href='{$url}page=$lastpage'>&raquo;</a></li>";
			    }
		    }
		    $pagination.= "</ul>\n";		
	    }
	    return $pagination;
    }
}
