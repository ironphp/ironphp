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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

use Friday\Foundation\Application;

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
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
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
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

if (! function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
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

if (! function_exists('is_session_started')) {
    /**
     * Check if session have been started.
     *
     * @return bool
     */
    function is_session_started()
    {
	    if ( PHP_SAPI !== 'cli' ) {
		    if ( version_compare(PHP_VERSION, '5.4.0', '>=') ) {
			    return (session_status() === PHP_SESSION_ACTIVE) ? true : false;
            }
            else {
			    return (session_id() === '') ? false : true;
            }
	    }
	    return false;
    }
}

if (! function_exists('is_bot')) {
    /**
     * Check whether the visitor is a search engine robot.
     *
     * @return  bool
     */
    function is_bot() {
	    $botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
	    "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
	    "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
	    "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
	    "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
	    "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
	    "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
	    "Butterfly","Twitturls","Me.dium","Twiceler");

    	foreach($botlist as $bot){
	    	if(strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
		        return true;	// Is a bot
            }
	    }
	    return false;	// Not a bot
    }
}

if (! function_exists('exception_error_handler')) {
    /**
     * Exception handler callable.
     *
 	 * @since  1.0.1
     * @param  int      $errno (severity)
     * @param  string   $errstr (message)
     * @param  string   $file
     * @param  int      $line
     * @return void
     * @throws \ErrorException
     */
	function exception_error_handler($errno, $errstr, $file, $line) {
    	if (!(error_reporting() & $errno)) {
        	// This error code is not included in error_reporting, so let it fall
        	// through to the standard PHP error handler
        	return false;
    	}
    	switch ($errno) {
			case E_USER_ERROR:
        		echo "<b>My ERROR</b> [$errno] $errstr<br>\n";
        		echo "  Fatal error on line $errline in file $errfile";
        		echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br>\n";
        		echo "Aborting...<br>\n";
        		exit(1);
        		break;

    		case E_USER_WARNING:
        		echo "<b>My WARNING</b> [$errno] $errstr<br>\n";
        		break;

    		case E_USER_NOTICE:
        		echo "<b>My NOTICE</b> [$errno] $errstr<br>\n";
        		break;

    		default:
        		echo "<b>Error</b>: [$errno] $errstr<br>\n";
        		break;
    	}

    	/* Don't execute PHP internal error handler */
    	return true;
		throw new ErrorException($errstr, 0, $errno, $file, $line);
    }
}

if (! function_exists('log_error')) {
    /**
     * Error handler, passes flow over the
	 * exception logger with new ErrorException.
     *
 	 * @since  1.0.1
     * @param  int      $errno (severity)
     * @param  string   $errstr (message)
     * @param  string   $errfile
     * @param  int      $errline
     * @return void
     */
	function log_error( $errno, $errstr, $errfile, $errline, $context = null ) {
		//print_r(func_get_args());exit;
    	log_exception( new ErrorException( $errstr, 0, $errno, $errfile, $errline ) );
	}
}

if (! function_exists('log_exception')) {
    /**
	 * Uncaught exception handler.
     *
 	 * @since  1.0.1
     * @param  \Exception  $e
     * @return void
     */
	function log_exception(Exception $e) {
		switch($e->getSeverity()) {
			case E_ERROR: // 1 //
				$severity = 'E_ERROR';
				break;
			case E_WARNING: // 2 //
				$severity = 'E_WARNING';
				break;
			case E_PARSE: // 4 //
				$severity = 'E_PARSE';
				break;
			case E_NOTICE: // 8 //
				$severity = 'E_NOTICE';
				break;
			case E_CORE_ERROR: // 16 //
				$severity = 'E_CORE_ERROR';
				break;
			case E_CORE_WARNING: // 32 //
				$severity = 'E_CORE_WARNING';
				break;
			case E_COMPILE_ERROR: // 64 //
				$severity = 'E_COMPILE_ERROR';
				break;
			case E_COMPILE_WARNING: // 128 //
				$severity = 'E_COMPILE_WARNING';
				break;
			case E_USER_ERROR: // 256 //
				$severity = 'E_USER_ERROR';
				break;
			case E_USER_WARNING: // 512 //
				$severity = 'E_USER_WARNING';
				break;
			case E_USER_NOTICE: // 1024 //
				$severity = 'E_USER_NOTICE';
				break;
			case E_STRICT: // 2048 //
				$severity = 'E_STRICT';
				break;
			case E_RECOVERABLE_ERROR: // 4096 //
				$severity = 'E_RECOVERABLE_ERROR';
				break;
			case E_DEPRECATED: // 8192 //
				$severity = 'E_DEPRECATED';
				break;
			case E_USER_DEPRECATED: // 16384 //
				$severity = 'E_USER_DEPRECATED';
				break;
			case E_ALL: // 32767 //
				$severity = 'E_ALL';
				break;
			default:
				$severity = 'UNKOWN';
				break;
		}
    	if ( env('APP_DEBUG') === true ) {
        	print '
				<!DOCTYPE html>
				<html lang="en">
					<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<title>Error</title>
						<!-- Fonts -->
						<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
						<style>html, body {background-color: #fff;color: #636b6f;font-family: \'Nunito\', sans-serif;font-weight: 100;height: 100vh;margin: 0;}.full-height {height: 90vh;}.flex-center {align-items: center;display: flex;justify-content: center;}.content {text-align: center;}.title {font-size: 36px;padding: 20px;}</style>
					</head>
					<body>
						<div class="flex-center position-ref full-height">
							<div class="content">
								<h2 class="title" style="color: rgb(190, 50, 50)">Exception Occured</h2>
        						<table style="width: 800px; display: inline-block;text-align:left">
        							<tr style="background-color:rgb(230,230,230)">
										<th style="width: 80px">Type</th>
										<td>'. get_class($e) .'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Code</th>
										<td>'. $e->getCode() .'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Trace</th>
										<td>'. trim(str_replace('#', '<br>#', $e->getTraceAsString()),'<br>') .'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Severity</th>
										<td>'. $e->getSeverity() .' - '.$severity.'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Message</th>
										<td>'. $e->getMessage() .'</td>
									</tr>
        							<tr style="background-color:rgb(230,230,230)">
										<th>File</th>
										<td>'. $e->getFile() .'</td>
									</tr>
        							<tr style="background-color:rgb(240,240,240)">
										<th>Line</th>
										<td>'. $e->getLine() .'</td>
									</tr>
        						</table>
							</div>
						</div>
					</body>
				</html>
			';
    	}
    	else {
        	$message = "Type: " . get_class($e) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";
        	if(!file_exists(LOGS . "/debug.log") || !is_file(LOGS . "/debug.log")) {
				file_put_contents( LOGS . "/debug.log", '');
			}
        	error_log("[".date('Y-y-d H:i:s')."] " . $message . PHP_EOL . PHP_EOL, 3, LOGS . "/debug.log");
			//header( "Location: {$config["error_page"]}" );
			echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Error</title><!-- Fonts --><link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
<style>html, body {background-color: #fff;color: #636b6f;font-family: \'Nunito\', sans-serif;font-weight: 100;height: 100vh;margin: 0;}.full-height {height: 90vh;}.flex-center {align-items: center;display: flex;justify-content: center;}.content {text-align: center;}.title {font-size: 36px;padding: 20px;}</style><body><div class="flex-center position-ref full-height"><div class="content"><div class="title">It looks like something went wrong.</div></div></div></body></html>';
    	}
    	exit();
	}
}

if (! function_exists('check_for_fatal')) {
    /**
	 * Checks for a fatal error, work around for
	 * set_error_handler not working on fatal errors.
     *
 	 * @since  1.0.1
     * @return void
     */
	function check_for_fatal() {
    	$error = error_get_last();
		if($error["type"] != 0) {
			log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
		}
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
	$dt=explode('/',$d);
	$d="{$dt[2]}/{$dt[0]}/{$dt[1]}"; //yyyy/mm/dd
	$datetime = date_create($d);
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
function DateYMD($d){//$d = yyyy-mm-dd
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
