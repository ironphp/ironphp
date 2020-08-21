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
use Friday\Contracts\View\Factory as ViewFactory;
use Friday\Foundation\Application;
use Friday\Helper\App;
use Friday\Helper\Env;
use Friday\View\View;

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
                return true; // Is a bot
            }
        }

        return false; // Not a bot
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

if (!function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null $view
     * @param array       $data
     *
     * @return \Friday\View\View|\Friday\Contracts\View\Factory
     */
    function view($view = null, $data = [])
    {
        return View::template($view, $data);
        /*
                $factory = app(ViewFactory::class);

                if (func_num_args() === 0) {
                    return $factory;
                }

                return $factory->make($view, $data, $mergeData);
        */
    }
}

if (!function_exists('e')) {
    /**
     * Encode HTML special characters in a string.
     *
     * @param string $value
     * @param bool   $doubleEncode
     *
     * @return string
     */
    function e($value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}

if (!function_exists('app')) {
    /**
     * Get the App instance.
     *
     * @param string|null $abstract
     *
     * @return \Friday\Helper\App
     */
    function app($abstract = null)
    {
        return App::getInstance($abstract);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string    $path
     * @param bool|null $secure
     *
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="'.csrf_token().'">';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (!function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param mixed        $parameters
     * @param bool         $absolute
     * @param string|null  $id
     *
     * @return string
     */
    function route($name, $parameters = [], $absolute = true, $id = null)
    {
        return app('url')->route($name, $parameters, $absolute, $id);
    }
}

if (!function_exists('config')) {
    /**
     * Get / Set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed             $default
     *
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
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

if (!function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('session')->old($key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed             $value
     *
     * @return mixed
     */
    function session($key = null, $value = null)
    {
        if (is_null($key)) {
            return app('session')->all();
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        if (!is_null($value)) {
            return app('session')->set($key, $value);
        }

        return app('session')->get($key);
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param string|null $to
     * @param int         $status
     * @param array       $headers
     * @param bool|null   $secure
     *
     * @return \-\Routing\Redirector|\-\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        header('Location: '.$to, true, $status);
        exit;
        /*
                if (is_null($to)) {
                    return app('redirect');
                }

                return app('redirect')->to($to, $status, $headers, $secure);
        */
    }
}

if (!function_exists('mime2ext')) {
    /**
     * Get an extension from MIME content type.
     *
     * @param string $mime
     *
     * @return string
     */
    function mime2ext($mime)
    {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'font/otf'                                                                  => 'otf',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'font/ttf'                                                                  => 'ttf',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'image/webp'                                                                => 'webp',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'font/woff'                                                                 => 'woff',
            'font/woff2'                                                                => 'woff2',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }
}

if (!function_exists('str_slug')) {
    /**
     * Create slug for string.
     *
     * @param string $str
     * @param string $glue
     *
     * @return string
     */
    function str_slug($str, $glue = '-')
    {
		return str_replace([' ', '_', '-'], $glue, strtolower($str));
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
