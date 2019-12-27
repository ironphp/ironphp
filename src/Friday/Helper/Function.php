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
use Friday\Foundation\Application;
use Friday\Helper\Env;

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        return Env::get($key, $default);
        /*
        if(func_num_args() == 1) {
            $key = func_get_arg(0);
            if(isset(Application::$env[$key])) {
                $value = Application::$env[$key];
            }
            else {
                echo "$key not exist in env()";
                exit;
            }
        }
        elseif(func_num_args() == 2) {
            $key = func_get_arg(0);
            $val = func_get_arg(1);
            $value = Application::$env[$key] = $val;
        }
        else {
            echo "invalid num of args in env()";
            exit;
        }
        if($value === 'true') {
            $value = true;
        }
        if($value === 'false') {
            $value = false;
        }
        return $value;
        */

        /*
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && starts_with($value, '"') && ends_with($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
        */
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('is_session_started')) {
    /**
     * Check if session have been started.
     *
     * @return bool
     */
    function is_session_started()
    {
        if (PHP_SAPI !== 'cli') {
            if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
                return (session_status() === PHP_SESSION_ACTIVE) ? true : false;
            } else {
                return (session_id() === '') ? false : true;
            }
        }

        return false;
    }
}

if (!function_exists('is_bot')) {
    /**
     * Check whether the visitor is a search engine robot.
     *
     * @return bool
     */
    function is_bot()
    {
        $botlist = ['Teoma', 'alexa', 'froogle', 'Gigabot', 'inktomi',
        'looksmart', 'URL_Spider_SQL', 'Firefly', 'NationalDirectory',
        'Ask Jeeves', 'TECNOSEEK', 'InfoSeek', 'WebFindBot', 'girafabot',
        'crawler', 'www.galaxy.com', 'Googlebot', 'Scooter', 'Slurp',
        'msnbot', 'appie', 'FAST', 'WebBug', 'Spade', 'ZyBorg', 'rabaz',
        'Baiduspider', 'Feedfetcher-Google', 'TechnoratiSnoop', 'Rankivabot',
        'Mediapartners-Google', 'Sogou web spider', 'WebAlta Crawler', 'TweetmemeBot',
        'Butterfly', 'Twitturls', 'Me.dium', 'Twiceler', ];

        foreach ($botlist as $bot) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
                return true;	// Is a bot
            }
        }

        return false;	// Not a bot
    }
}

if (!function_exists('sqldate_to_timestamp')) {
    /**
     * Get timestamp from SQL Format Date (yyyy-mm-dd hh:ii:ss).
     *
     * @param string $d yyyy-mm-dd
     *
     * @return int
     */
    function sqldate_to_timestamp($d)
    {
        $date_ary = date_parse($d);

        return mktime($date_ary['hour'], $date_ary['minute'], $date_ary['second'], $date_ary['month'], $date_ary['day'], $date_ary['year']);
    }
}

/*
function moneyInWords($m){
    if($m>=1000){
        if($m>=100000){
            if($m>=10000000){
                $m = $m/10000000;
                $money = $m.' Cr.';
            }else{
                $m = $m/100000;
                $money = $m.' Lacs';
            }
        }else{
            $m = $m/1000;
            $money = $m.' Th.';
        }
    }
    else{
        $money = $m;
    }
    return 'Rs. '.$money;
}
function sqlDateMDY($d){//$d = mm/dd/yyyy
    $dt = explode('/',$d);
    $d = "{$dt[2]}/{$dt[0]}/{$dt[1]}"; //yyyy/mm/dd
    return date_create($d);
    $date = date_format($datetime, DATE_ATOM);
    return substr($date, 0, 10); //yyyy-mm-dd
}
function sqlDateDMY($d){//$d = dd/mm/yyyy
    $dt=explode('/',$d);
    $d="{$dt[2]}/{$dt[1]}/{$dt[0]}"; //yyyy/mm/dd
    $datetime = date_create($d);
    $date = date_format($datetime, DATE_ATOM);
    return substr($date, 0, 10); //yyyy-mm-dd
}
function DateYMD($d){
    //$d = yyyy-mm-dd
    $date_ary = date_parse($d);
    $date = date('d/m/Y', mktime(0, 0, 0, $date_ary['month'], $date_ary['day'], $date_ary['year']));
    return $date; //dd/mm/yyyy
}
function formatDate($d){
    $d=strtotime($d);
    return date('F j, Y',$d);
}
function formatBudget($b){
    $a=explode('-',$b);
    $a['min']=(int)$a[0];
    $a['words_min']=moneyInWords($a['min']);
    if(!empty($a[1])){
        if($a[1]=='∞' || $a[1]=='&infin;'){
            $a['max']=$a['min']*2;
            $a['words']='More than '.$a['words_min'];
        }
        else{
            $a['max']=(int)$a[1];
            $a['words_max']=moneyInWords($a['max']);
            if($a['min']==0)
                $a['words']='Less than '.$a['words_max'];
            else
                $a['words']=$a['words_min'].' to '.$a['words_max'];
        }
    }
    else{
        $a['words']=$a['words_min'];
    }
    return $a;
}
function qry_arg($arg,$conj){
    //$conj='AND';
    $conj=' '.$conj.' ';
    //$arg=array(1,2,3);
    $num_arg=count($arg);
    if($num_arg==0){
        $args='';
    }
    else{
        $args=' WHERE';
        if($num_arg==1){
            $args.=$arg[0];
        }
        else{
            $args.=' '.implode($conj,$arg);
        }
    }
    return $args;
}
?>
<script>
function setCookie(cookieName, cookieValue, expdays) {
    var d = new Date();
    d.setTime(d.getTime() + (expdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + "; " + expires;
}
function delCookie(cookieName) {
    var cookieValue="";
    var d = new Date();
    d.setTime(d.getTime() - (1*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + "; " + expires;
}
function getCookie(cookieName) {
    var name = cookieName + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}
</script>
<?php
*/
