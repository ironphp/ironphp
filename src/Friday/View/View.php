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
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\View;

use Exception;

class View
{
    /**
     * Instance of the Friday Application.
     *
     * @var \Friday\Foundation\Application
     */
    private $app;

    /**
     * All Tags used for rendering html page.
     *
     * @var array
     */
    private $tags = [
        '!doctype' => false,
        'html' => true,
        'meta' => false,
        'head' => true,
        'body' => true,
        'title' => true,
        'p' => true,
        'pre' => true,
        'span' => true,
        'div' => true,
        'header' => true,
        'footer' => true,
        'script' => true,
        'style' => true,
        'link' => false,
        'base' => false,
        'a' => true,
        'i' => true,
        'nav' => true,
        'button' => true,
        'img' => false,
        'ul' => true,
        'ol' => true,
        'li' => true
    ];

    /**
     * Attribute of Tags with default value used for rendering html page.
     *
     * @var array
     */
    private $attr = [
        '!doctype' => ['html' => NULL],
        'html' => ['lang' => 'en'],
        'meta' => ['charset' => 'utf-8', 'name' => [
                'content' => [
                    "description" => "IronPHP Application",
                    "keywords" => "IronPHP, framework",
                    "author" => "Gaurang Kumar",
                    "viewport" => "width=device-width, initial-scale=1.0"
                ]
            ]
        ],
        'base' => ['target' => '_blank'],
        'link' => ['rel' => "stylesheet", 'type' => "text/css"]
    ];

    /**
     * Temperory View.
     *
     * @var string
     */
    public $tempView = NULL;

    /**
     * Data for html rendering.
     *
     * @var array
     */
    public $data;

    /**
     * Name of the controller that created the View if any.
     *
     * @var string
     */
    public $name;

    /**
     * Current passed params. Passed to View from the creating Controller for convenience.
     *
     * @var array
     * @deprecated 0.0.0 Use `$this->request->getParam('pass')` instead.
     */
    public $passedArgs = [];

    /**
     * The name of the subfolder containing templates for this View.
     *
     * @var string
     */
    public $templatePath;

    /**
     * The name of the template file to render. The name specified
     * is the filename in /src/Template/<SubFolder> without the .ctp extension.
     *
     * @var string
     */
    public $template;

    /**
     * The view theme to use.
     *
     * @var string|null
     */
    private $theme;

    /**
     * Create a new View instance.
     *
     * @param  \Friday\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param   array  $tags
     * @return  void
     */
    public function addRenderTags($tags)
    {
        $i[0] = 0;
        foreach($tags as $tag => $text) {
            if(is_array($text)) {
                $i[1] = 0;
                foreach($text as $tag1 => $text1) {
                    $temp[$i[0]][$i[1]] = $this->createTag($tag1, $text1);
                }
                $temp[$i[0]] = implode("\n", $temp[$i[0]]);
            }
            else {
                $temp[$i[0]] = $this->createTag($tag, $text);
            }
            $i[0]++;
        }
        $this->tempView = implode("\n", $temp);
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param   string      $tag
     * @param   array|null  $attr
     * @param   string|null $content
     * @return  array
     * @throws  \Exception
     */
    public function createTag($tag, $attr = null, $content = null)
    {
        if(empty($tag) || !is_string($tag)) {
            throw new Exception("Tags name can not be null or non-string");
            exit;
        }
        $temp = '<'.$tag;
        if($attr != null) {
            $attr = (array) $attr;
            foreach($attr as $key => $val) {
                if(is_numeric($key)) {
                    $val = strtolower(trim($val));
                    if(isset($this->attr[$tag][$val])) {
                        $args[$val] = $this->attr[$tag][$val];
                        //$args[] = $val.($this->attr[$tag][$val] == NULL ? '' : "=\"{$this->attr[$tag][$val]}\"");
                    }
                    else {
                        $args[$val] = null;
                    }
                }
                else {
                    if(!isset($args[$key])) {
                        $args[$key] = $val;
                    }
                }
                if(isset($this->attr[$tag][$key])) {
                    if(is_array($this->attr[$tag][$key])) {
                        foreach($this->attr[$tag][$key] as $k => $v) {
                            if(isset($v[$val]) && !isset($args[$val])) {
                                $args[$k] = $v[$val];
                            }
                        }
                    }
                    else {
                        if(!isset($args[$val])) {
                            $args[$key] = $this->attr[$tag][$key];
                        }
                    }
                }
            }
            unset($attr);
            foreach($args as $key => $val) {
                $attr[] = $key.($val == null ? '' : "=\"$val\"");
            }
            unset($args);
            $args = array_unique($attr);
            $args = implode(' ', $args);
        }
        $temp .= isset($args) ? ' '.$args.'>' : '>'; 
        if($content != null) {
            if(!is_array($content)) {
                $content = explode("\n", $content);
            }
            array_walk($content, function(&$val, $key) { $val = trim($val); } );//5.3
            $content = implode("\n", $content);
            $content = explode("\n", $content);
            $content = preg_replace('/^/', "\t", $content);
            #$content = preg_filter('/^/', "@", $content);
            $content = rtrim("\n".implode("\n", $content), "\n")."\n";
            /*
            foreach($content as $i => $line) {
                $content[$i] = "\t".$line;
            }
            $content = implode("\n", $content);
            */
            $temp .= $content;
        }
        if($this->tags[$tag] === true) {
            $temp .= '</'.$tag.'>';
        }
        return $temp;
    }

    /**
     * Renders View for given data, template file and layout.
     *
     * @param   string       $viewPath
     * @param   string       $data
     * @param   string|null  $layout
     * @return  string
     */
    public function renderView($viewPath, $data = [], $layout = null)
    {
        ob_start();
        require($viewPath);
        $viewData = ob_get_contents();
        ob_end_clean();
        foreach($data as $key => $val) {
            if(is_array($val)) {
                if($key == 'meta') {
                    $this->data['meta'] = $val;
                }
            }
            else {
                $viewData = str_replace('{{'.$key.'}}', $val, $viewData);
            }
        }
        return $viewData;
    }

    /**
     * Renders Template for given data, template file.
     *
     * @param   string  $templatePath
     * @param   string  $data
     * @return  string.
     * @throws  \Exception
     */
    public function renderTemplate($templatePath, $data = [])
    {
        $templateData = $this->readTemplate($templatePath);
        if($data === null) {
            throw new Exception("template(): expects parameter 2 to be array, null given");
        }
        foreach($data as $key => $val) {
            if(is_array($val)) {
                $findStart = strpos($templateData, '{{'.$key.':}}');
                if($findStart !== false) {
                    $findEnd = strpos($templateData, '{{:'.$key.'}}');
                    if($findEnd !== false) {
                        $len = 5 + strlen($key);
                        $substr = substr($templateData, $findStart, ($findEnd - $findStart));
                        $substr = substr($substr, $len);
                        $loopstr = []; #fixed-for: Uncaught Error: [] operator not supported for strings $loopstr[]
                        foreach($val as $k => $v) {
                            $temp = $substr;
                            if(is_array($v)) {
                                //$temp = strtr($temp, $v); {{}} not replaced
                                foreach($v as $ks => $vs) {
                                    $temp = str_replace('{{'.$ks.'}}', $vs, $temp);
                                }
                            }
                            else {
                                $temp = str_replace('{{'.$key.'}}', $v, $temp);
                            }
                            $loopstr[] = $temp;
                        }
                        $loopstr = implode("\n", $loopstr);
                        $templateData = str_replace('{{'.$key.':}}'.$substr.'{{:'.$key.'}}', $loopstr, $templateData);
                    }
                    else {
                        throw new Exception('Error in template : loop has not closed properely');
                        exit;
                    }
                }
            }
            else {
                $templateData = str_replace('{{'.$key.'}}', $val, $templateData);
            }
        }
        return $templateData;
    }

    /**
     * Renders Template for given data, template file.
     *
     * @param   string  $templatePath
     * @return  string
     * @throws  \Exception
     */
    public function readTemplate($templatePath)
    {
        if(!is_readable($templatePath)) {
            throw new Exception("No permissons to read template: ".$templatePath);
        }
        if($templateData = file_get_contents($templatePath)) {
            return $templateData;
        }
        else {
            throw new Exception("Can not read template: ".$templatePath);
        }
    }

    /**
     * Get the current view theme.
     *
     * @return string|null
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set the view theme to use.
     *
     * @param   string  $theme Theme name.
     * @return  void
     * @throws  \Exception
     */
    public function setTheme($theme)
    {
        if(empty($theme)) {
            throw new Exception("Theme name can not be null");
            exit;
        }
        $this->theme = $theme;
    }

    /**
     * Renders Theme for given data.
     *
     * @param   array        $themeInfo
     * @param   string       $data
     * @return  $templateData
     * @throws  \Exception
     */
    public function renderTheme($themeInfo, $data = [])
    {
        $templateData = trim($this->readThemePage($themeInfo['themeFilePath']));
        $pos1 = stripos($templateData, '<!doctype');
        if($pos1 !== false) {
            $pos2 = stripos($templateData, '>');
            $doctype = substr($templateData, $pos1, $pos2+1);
        }
        $doctype = $doctype ?? $this->createTag('!doctype', 'html');
        /*
        $temp = substr($templateData, $pos+1);
        $pos = strpos($templateData, '</head>');
        $head = substr($temp, 0, $pos-4);
        $body = substr($temp, $pos+2);
        $pos = strpos($body, '</html>');
        $body = substr($body, 0, $pos);
        */
        $tagArray = $this->htmlToArray($themeInfo['themeFilePath'], true, true);
        print_r($doctype."\n".$this->arrayToHtml($tagArray));exit;
        if(!is_array($data) && $data !== null) {
            throw new Exception("template(): expects parameter 2 to be array, null given");
        }
        foreach($data as $key => $val) {
            if(is_array($val)) {
                $findStart = strpos($templateData, '{{'.$key.':}}');
                if($findStart !== false) {
                    $findEnd = strpos($templateData, '{{:'.$key.'}}');
                    if($findEnd !== false) {
                        $len = 5 + strlen($key);
                        $substr = substr($templateData, $findStart, ($findEnd - $findStart));
                        $substr = substr($substr, $len);
                        $loopstr = []; #fixed-for: Uncaught Error: [] operator not supported for strings $loopstr[]
                        foreach($val as $k => $v) {
                            $temp = $substr;
                            if(is_array($v)) {
                                //$temp = strtr($temp, $v); {{}} not replaced
                                foreach($v as $ks => $vs) {
                                    $temp = str_replace('{{'.$ks.'}}', $vs, $temp);
                                }
                            }
                            else {
                                $temp = str_replace('{{'.$key.'}}', $v, $temp);
                            }
                            $loopstr[] = $temp;
                        }
                        $loopstr = implode("\n", $loopstr);
                        $templateData = str_replace('{{'.$key.':}}'.$substr.'{{:'.$key.'}}', $loopstr, $templateData);
                    }
                    else {
                        throw new Exception('Error in template : loop has not closed properely');
                        exit;
                    }
                }
            }
            else {
                $templateData = str_replace('{{'.$key.'}}', $val, $templateData);
            }
        }
        return $templateData;
    }

    /**
     * Read Theme Page for given data, template file.
     *
     * @param   string  $themeFilePath
     * @return  string
     * @throws  \Exception
     */
    public function readThemePage($themeFilePath)
    {
        if(!is_readable($themeFilePath)) {
            throw new Exception("No permissons to read template: ".$themeFilePath);
        }
        if($themeFilePath = file_get_contents($themeFilePath)) {
            return $themeFilePath;
        }
        else {
            throw new Exception("Can not read template: ".$themeFilePath);
        }
    }

    /**
     * Convert HTML tags string to array.
     *
     * @param   string  html
     * @return  array
     */
    public function htmlToArray($html, $isFile = true, $first = true)
    {
        $tagArray = [];
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        if($isFile) {
            $dom->loadHTMLFile($html);
        } else {
            $dom->loadHTML($html); 
        }
        $dom->loadHTML('<!--CMD--><!--TXT-->');
        if(true) {
        $tag = '
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>PHP: RecursiveTreeIterator::__construct - Manual </title>

 <link rel="shortcut icon" href="https://www.php.net/favicon.ico">
 <link rel="search" type="application/opensearchdescription+xml" href="http://php.net/phpnetimprovedsearch.src" title="Add PHP.net search">
 <link rel="alternate" type="application/atom+xml" href="https://www.php.net/releases/feed.php" title="PHP Release feed">
 <link rel="alternate" type="application/atom+xml" href="https://www.php.net/feed.atom" title="PHP: Hypertext Preprocessor">

 <link rel="canonical" href="https://www.php.net/manual/en/recursivetreeiterator.construct.php">
 <link rel="shorturl" href="https://www.php.net/manual/en/recursivetreeiterator.construct.php">
 <link rel="alternate" href="https://www.php.net/manual/en/recursivetreeiterator.construct.php" hreflang="x-default">

 <link rel="contents" href="https://www.php.net/manual/en/index.php">
 <link rel="index" href="https://www.php.net/manual/en/class.recursivetreeiterator.php">
 <link rel="prev" href="https://www.php.net/manual/en/recursivetreeiterator.callhaschildren.php">
 <link rel="next" href="https://www.php.net/manual/en/recursivetreeiterator.current.php">

 <link rel="alternate" href="https://www.php.net/manual/en/recursivetreeiterator.construct.php" hreflang="en">
 <link rel="alternate" href="https://www.php.net/manual/pt_BR/recursivetreeiterator.construct.php" hreflang="pt_BR">
 <link rel="alternate" href="https://www.php.net/manual/zh/recursivetreeiterator.construct.php" hreflang="zh">
 <link rel="alternate" href="https://www.php.net/manual/fr/recursivetreeiterator.construct.php" hreflang="fr">
 <link rel="alternate" href="https://www.php.net/manual/de/recursivetreeiterator.construct.php" hreflang="de">
 <link rel="alternate" href="https://www.php.net/manual/ja/recursivetreeiterator.construct.php" hreflang="ja">
 <link rel="alternate" href="https://www.php.net/manual/ro/recursivetreeiterator.construct.php" hreflang="ro">
 <link rel="alternate" href="https://www.php.net/manual/ru/recursivetreeiterator.construct.php" hreflang="ru">
 <link rel="alternate" href="https://www.php.net/manual/es/recursivetreeiterator.construct.php" hreflang="es">
 <link rel="alternate" href="https://www.php.net/manual/tr/recursivetreeiterator.construct.php" hreflang="tr">

<link rel="stylesheet" type="text/css" href="/cached.php?t=1539771603&amp;f=/fonts/Fira/fira.css" media="screen">
<link rel="stylesheet" type="text/css" href="/cached.php?t=1539765004&amp;f=/fonts/Font-Awesome/css/fontello.css" media="screen">
<link rel="stylesheet" type="text/css" href="/cached.php?t=1540425603&amp;f=/styles/theme-base.css" media="screen">
<link rel="stylesheet" type="text/css" href="/cached.php?t=1540425603&amp;f=/styles/theme-medium.css" media="screen">

 <!--[if lte IE 7]>
 <link rel="stylesheet" type="text/css" href="https://www.php.net/styles/workarounds.ie7.css" media="screen">
 <![endif]-->

 <!--[if lte IE 8]>
 <script>
  window.brokenIE = true;
 </script>
 <![endif]-->

 <!--[if lte IE 9]>
 <link rel="stylesheet" type="text/css" href="https://www.php.net/styles/workarounds.ie9.css" media="screen">
 <![endif]-->

 <!--[if IE]>
 <script src="https://www.php.net/js/ext/html5.js"></script>
 <![endif]-->

 <base href="https://www.php.net/manual/en/recursivetreeiterator.construct.php">

</head>
<body class="docs ">

<nav id="head-nav" class="navbar navbar-fixed-top">
  <div class="navbar-inner clearfix">
    <a href="/" class="brand"><img src="/images/logos/php-logo.svg" width="48" height="24" alt="php"></a>
    <div id="mainmenu-toggle-overlay"></div>
    <input type="checkbox" id="mainmenu-toggle">
    <ul class="nav">
      <li class=""><a href="/downloads">Downloads</a></li>
      <li class="active"><a href="/docs.php">Documentation</a></li>
      <li class=""><a href="/get-involved" >Get Involved</a></li>
      <li class=""><a href="/support">Help</a></li>
    </ul>
    <form class="navbar-search" id="topsearch" action="/search.php">
      <input type="hidden" name="show" value="quickref">
      <input type="search" name="pattern" class="search-query" placeholder="Search" accesskey="s">
    </form>
  </div>
  <div id="flash-message"></div>
</nav>
<div class="headsup"><a href="/conferences/index.php#id2019-05-10-1">php[world] 2019 Call for Speakers</a></div>
<nav id="trick"><div><dl>
<dt><a href="/manual/en/getting-started.php">Getting Started</a></dt>
	<dd><a href="/manual/en/introduction.php">Introduction</a></dd>
	<dd><a href="/manual/en/tutorial.php">A simple tutorial</a></dd>
<dt><a href="/manual/en/langref.php">Language Reference</a></dt>
	<dd><a href="/manual/en/language.basic-syntax.php">Basic syntax</a></dd>
	<dd><a href="/manual/en/language.types.php">Types</a></dd>
	<dd><a href="/manual/en/language.variables.php">Variables</a></dd>
	<dd><a href="/manual/en/language.constants.php">Constants</a></dd>
	<dd><a href="/manual/en/language.expressions.php">Expressions</a></dd>
	<dd><a href="/manual/en/language.operators.php">Operators</a></dd>
	<dd><a href="/manual/en/language.control-structures.php">Control Structures</a></dd>
	<dd><a href="/manual/en/language.functions.php">Functions</a></dd>
	<dd><a href="/manual/en/language.oop5.php">Classes and Objects</a></dd>
	<dd><a href="/manual/en/language.namespaces.php">Namespaces</a></dd>
	<dd><a href="/manual/en/language.errors.php">Errors</a></dd>
	<dd><a href="/manual/en/language.exceptions.php">Exceptions</a></dd>
	<dd><a href="/manual/en/language.generators.php">Generators</a></dd>
	<dd><a href="/manual/en/language.references.php">References Explained</a></dd>
	<dd><a href="/manual/en/reserved.variables.php">Predefined Variables</a></dd>
	<dd><a href="/manual/en/reserved.exceptions.php">Predefined Exceptions</a></dd>
	<dd><a href="/manual/en/reserved.interfaces.php">Predefined Interfaces and Classes</a></dd>
	<dd><a href="/manual/en/context.php">Context options and parameters</a></dd>
	<dd><a href="/manual/en/wrappers.php">Supported Protocols and Wrappers</a></dd>
</dl>
<dl>
<dt><a href="/manual/en/security.php">Security</a></dt>
	<dd><a href="/manual/en/security.intro.php">Introduction</a></dd>
	<dd><a href="/manual/en/security.general.php">General considerations</a></dd>
	<dd><a href="/manual/en/security.cgi-bin.php">Installed as CGI binary</a></dd>
	<dd><a href="/manual/en/security.apache.php">Installed as an Apache module</a></dd>
	<dd><a href="/manual/en/security.sessions.php">Session Security</a></dd>
	<dd><a href="/manual/en/security.filesystem.php">Filesystem Security</a></dd>
	<dd><a href="/manual/en/security.database.php">Database Security</a></dd>
	<dd><a href="/manual/en/security.errors.php">Error Reporting</a></dd>
	<dd><a href="/manual/en/security.globals.php">Using Register Globals</a></dd>
	<dd><a href="/manual/en/security.variables.php">User Submitted Data</a></dd>
	<dd><a href="/manual/en/security.magicquotes.php">Magic Quotes</a></dd>
	<dd><a href="/manual/en/security.hiding.php">Hiding PHP</a></dd>
	<dd><a href="/manual/en/security.current.php">Keeping Current</a></dd>
<dt><a href="/manual/en/features.php">Features</a></dt>
	<dd><a href="/manual/en/features.http-auth.php">HTTP authentication with PHP</a></dd>
	<dd><a href="/manual/en/features.cookies.php">Cookies</a></dd>
	<dd><a href="/manual/en/features.sessions.php">Sessions</a></dd>
	<dd><a href="/manual/en/features.xforms.php">Dealing with XForms</a></dd>
	<dd><a href="/manual/en/features.file-upload.php">Handling file uploads</a></dd>
	<dd><a href="/manual/en/features.remote-files.php">Using remote files</a></dd>
	<dd><a href="/manual/en/features.connection-handling.php">Connection handling</a></dd>
	<dd><a href="/manual/en/features.persistent-connections.php">Persistent Database Connections</a></dd>
	<dd><a href="/manual/en/features.safe-mode.php">Safe Mode</a></dd>
	<dd><a href="/manual/en/features.commandline.php">Command line usage</a></dd>
	<dd><a href="/manual/en/features.gc.php">Garbage Collection</a></dd>
	<dd><a href="/manual/en/features.dtrace.php">DTrace Dynamic Tracing</a></dd>
</dl>
<dl>
<dt><a href="/manual/en/funcref.php">Function Reference</a></dt>
	<dd><a href="/manual/en/refs.basic.php.php">Affecting PHP"s Behaviour</a></dd>
	<dd><a href="/manual/en/refs.utilspec.audio.php">Audio Formats Manipulation</a></dd>
	<dd><a href="/manual/en/refs.remote.auth.php">Authentication Services</a></dd>
	<dd><a href="/manual/en/refs.utilspec.cmdline.php">Command Line Specific Extensions</a></dd>
	<dd><a href="/manual/en/refs.compression.php">Compression and Archive Extensions</a></dd>
	<dd><a href="/manual/en/refs.creditcard.php">Credit Card Processing</a></dd>
	<dd><a href="/manual/en/refs.crypto.php">Cryptography Extensions</a></dd>
	<dd><a href="/manual/en/refs.database.php">Database Extensions</a></dd>
	<dd><a href="/manual/en/refs.calendar.php">Date and Time Related Extensions</a></dd>
	<dd><a href="/manual/en/refs.fileprocess.file.php">File System Related Extensions</a></dd>
	<dd><a href="/manual/en/refs.international.php">Human Language and Character Encoding Support</a></dd>
	<dd><a href="/manual/en/refs.utilspec.image.php">Image Processing and Generation</a></dd>
	<dd><a href="/manual/en/refs.remote.mail.php">Mail Related Extensions</a></dd>
	<dd><a href="/manual/en/refs.math.php">Mathematical Extensions</a></dd>
	<dd><a href="/manual/en/refs.utilspec.nontext.php">Non-Text MIME Output</a></dd>
	<dd><a href="/manual/en/refs.fileprocess.process.php">Process Control Extensions</a></dd>
	<dd><a href="/manual/en/refs.basic.other.php">Other Basic Extensions</a></dd>
	<dd><a href="/manual/en/refs.remote.other.php">Other Services</a></dd>
	<dd><a href="/manual/en/refs.search.php">Search Engine Extensions</a></dd>
	<dd><a href="/manual/en/refs.utilspec.server.php">Server Specific Extensions</a></dd>
	<dd><a href="/manual/en/refs.basic.session.php">Session Extensions</a></dd>
	<dd><a href="/manual/en/refs.basic.text.php">Text Processing</a></dd>
	<dd><a href="/manual/en/refs.basic.vartype.php">Variable and Type Related Extensions</a></dd>
	<dd><a href="/manual/en/refs.webservice.php">Web Services</a></dd>
	<dd><a href="/manual/en/refs.utilspec.windows.php">Windows Only Extensions</a></dd>
	<dd><a href="/manual/en/refs.xml.php">XML Manipulation</a></dd>
	<dd><a href="/manual/en/refs.ui.php">GUI Extensions</a></dd>
</dl>
<dl>
<dt>Keyboard Shortcuts</dt><dt>?</dt>
<dd>This help</dd>
<dt>j</dt>
<dd>Next menu item</dd>
<dt>k</dt>
<dd>Previous menu item</dd>
<dt>g p</dt>
<dd>Previous man page</dd>
<dt>g n</dt>
<dd>Next man page</dd>
<dt>G</dt>
<dd>Scroll to bottom</dd>
<dt>g g</dt>
<dd>Scroll to top</dd>
<dt>g h</dt>
<dd>Goto homepage</dd>
<dt>g s</dt>
<dd>Goto search<br>(current page)</dd>
<dt>/</dt>
<dd>Focus search box</dd>
</dl></div></nav>
<div id="goto">
    <div class="search">
         <div class="text"></div>
         <div class="results"><ul></ul></div>
   </div>
</div>

  <div id="breadcrumbs" class="clearfix">
    <div id="breadcrumbs-inner">
          <div class="next">
        <a href="recursivetreeiterator.current.php">
          RecursiveTreeIterator::current &raquo;
        </a>
      </div>
              <div class="prev">
        <a href="recursivetreeiterator.callhaschildren.php">
          &laquo; RecursiveTreeIterator::callHasChildren        </a>
      </div>
          <ul>
            <li><a href="index.php">PHP Manual</a></li>      <li><a href="funcref.php">Function Reference</a></li>      <li><a href="refs.basic.other.php">Other Basic Extensions</a></li>      <li><a href="book.spl.php">SPL</a></li>      <li><a href="spl.iterators.php">Iterators</a></li>      <li><a href="class.recursivetreeiterator.php">RecursiveTreeIterator</a></li>      </ul>
    </div>
  </div>




<div id="layout" class="clearfix">
  <section id="layout-content">
  <div class="page-tools">
    <div class="change-language">
      <form action="/manual/change.php" method="get" id="changelang" name="changelang">
        <fieldset>
          <label for="changelang-langs">Change language:</label>
          <select onchange="document.changelang.submit()" name="page" id="changelang-langs">
            <option value="en/recursivetreeiterator.construct.php" selected="selected">English</option>
            <option value="pt_BR/recursivetreeiterator.construct.php">Brazilian Portuguese</option>
            <option value="zh/recursivetreeiterator.construct.php">Chinese (Simplified)</option>
            <option value="fr/recursivetreeiterator.construct.php">French</option>
            <option value="de/recursivetreeiterator.construct.php">German</option>
            <option value="ja/recursivetreeiterator.construct.php">Japanese</option>
            <option value="ro/recursivetreeiterator.construct.php">Romanian</option>
            <option value="ru/recursivetreeiterator.construct.php">Russian</option>
            <option value="es/recursivetreeiterator.construct.php">Spanish</option>
            <option value="tr/recursivetreeiterator.construct.php">Turkish</option>
            <option value="help-translate.php">Other</option>
          </select>
        </fieldset>
      </form>
    </div>
    <div class="edit-bug">
      <a href="https://edit.php.net/?project=PHP&amp;perm=en/recursivetreeiterator.construct.php">Edit</a>
      <a href="https://bugs.php.net/report.php?bug_type=Documentation+problem&amp;manpage=recursivetreeiterator.construct">Report a Bug</a>
    </div>
  </div><div id="recursivetreeiterator.construct" class="refentry">
 <div class="refnamediv">
  <h1 class="refname">RecursiveTreeIterator::__construct</h1>
  <p class="verinfo">(PHP 5 &gt;= 5.3.0, PHP 7)</p><p class="refpurpose"><span class="refname">RecursiveTreeIterator::__construct</span> &mdash; <span class="dc-title">Construct a RecursiveTreeIterator</span></p>

 </div>

 <div class="refsect1 description" id="refsect1-recursivetreeiterator.construct-description">
  <h3 class="title">Description</h3>
  <div class="methodsynopsis dc-description">
   <span class="modifier">public</span> <span class="methodname"><strong>RecursiveTreeIterator::__construct</strong></span>
    ( <span class="methodparam"><span class="type"><span class="type RecursiveIterator|IteratorAggregate">RecursiveIterator|IteratorAggregate</span></span> <code class="parameter">$it</code></span>
   [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$flags</code><span class="initializer"> = RecursiveTreeIterator::BYPASS_KEY</span></span>
   [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$cit_flags</code><span class="initializer"> = CachingIterator::CATCH_GET_CHILD</span></span>
   [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$mode</code><span class="initializer"> = RecursiveIteratorIterator::SELF_FIRST</span></span>
  ]]] )</div>

  <p class="para rdfs-comment">
   Constructs a new <a href="class.recursivetreeiterator.php" class="classname">RecursiveTreeIterator</a> from the supplied recursive iterator.
  </p>

  <div class="warning"><strong class="warning">Warning</strong><p class="simpara">This function is
currently not documented; only its argument list is available.
</p></div>

 </div>


 <div class="refsect1 parameters" id="refsect1-recursivetreeiterator.construct-parameters">
  <h3 class="title">Parameters</h3>
  <p class="para">
   <dl>

    
     <dt>
<code class="parameter">it</code></dt>

     <dd>

      <p class="para">
       The <a href="class.recursiveiterator.php" class="classname">RecursiveIterator</a> or <a href="class.iteratoraggregate.php" class="classname">IteratorAggregate</a> to iterate over.
      </p>
     </dd>

    
    
     <dt>
<code class="parameter">flags</code></dt>

     <dd>

      <p class="para">
       Flags may be provided which will affect the behavior of some methods. 
       A list of the flags can found under <a href="class.recursivetreeiterator.php#recursivetreeiterator.constants" class="link">RecursiveTreeIterator predefined constants</a>.
      </p>
     </dd>

    
    
     <dt>
<code class="parameter">caching_it_flags</code></dt>

     <dd>

      <p class="para">
       Flags to affect the behavior of the <a href="class.recursivecachingiterator.php" class="classname">RecursiveCachingIterator</a> used internally. 
      </p>
     </dd>

    
    
     <dt>
<code class="parameter">mode</code></dt>

     <dd>

      <p class="para">
       Flags to affect the behavior of the <a href="class.recursiveiteratoriterator.php" class="classname">RecursiveIteratorIterator</a> used internally. 
      </p>
     </dd>

    
   </dl>

  </p>
 </div>


 <div class="refsect1 returnvalues" id="refsect1-recursivetreeiterator.construct-returnvalues">
  <h3 class="title">Return Values</h3>
  <p class="para">
   No value is returned.
  </p>
 </div>



</div>
<section id="usernotes">
 <div class="head">
  <span class="action"><a href="/manual/add-note.php?sect=recursivetreeiterator.construct&amp;redirect=https://www.php.net/manual/en/recursivetreeiterator.construct.php"><img src="/images/notes-add@2x.png" alt="add a note" width="12" height="12"> <small>add a note</small></a></span>
  <h3 class="title">User Contributed Notes </h3>
 </div>
 <div class="note">There are no user contributed notes for this page.</div></section>    </section><!-- layout-content -->
        <aside class="layout-menu">

        <ul class="parent-menu-list">
                                    <li>
                <a href="class.recursivetreeiterator.php">RecursiveTreeIterator</a>

                                    <ul class="child-menu-list">

                                                <li class="">
                            <a href="recursivetreeiterator.beginchildren.php" title="beginChildren">beginChildren</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.beginiteration.php" title="beginIteration">beginIteration</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.callgetchildren.php" title="callGetChildren">callGetChildren</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.callhaschildren.php" title="callHasChildren">callHasChildren</a>
                        </li>
                                                <li class="current">
                            <a href="recursivetreeiterator.construct.php" title="_&#8203;_&#8203;construct">_&#8203;_&#8203;construct</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.current.php" title="current">current</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.endchildren.php" title="endChildren">endChildren</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.enditeration.php" title="endIteration">endIteration</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.getentry.php" title="getEntry">getEntry</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.getpostfix.php" title="getPostfix">getPostfix</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.getprefix.php" title="getPrefix">getPrefix</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.key.php" title="key">key</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.next.php" title="next">next</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.nextelement.php" title="nextElement">nextElement</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.rewind.php" title="rewind">rewind</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.setpostfix.php" title="setPostfix">setPostfix</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.setprefixpart.php" title="setPrefixPart">setPrefixPart</a>
                        </li>
                                                <li class="">
                            <a href="recursivetreeiterator.valid.php" title="valid">valid</a>
                        </li>
                        
                    </ul>
                
            </li>
                        
                    </ul>
    </aside>


  </div><!-- layout -->

  <footer>
    <div class="container footer-content">
      <div class="row-fluid">
      <ul class="footmenu">
        <li><a href="/copyright.php">Copyright &copy; 2001-2019 The PHP Group</a></li>
        <li><a href="/my.php">My PHP.net</a></li>
        <li><a href="/contact.php">Contact</a></li>
        <li><a href="/sites.php">Other PHP.net sites</a></li>
        <li><a href="/privacy.php">Privacy policy</a></li>
      </ul>
      </div>
    </div>
  </footer>

    
 <!-- External and third party libraries. -->
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/cached.php?t=1421837618&amp;f=/js/ext/modernizr.js"></script>
<script src="/cached.php?t=1421837618&amp;f=/js/ext/hogan-2.0.0.min.js"></script>
<script src="/cached.php?t=1421837618&amp;f=/js/ext/typeahead.min.js"></script>
<script src="/cached.php?t=1421837618&amp;f=/js/ext/mousetrap.min.js"></script>
<script src="/cached.php?t=1421837618&amp;f=/js/search.js"></script>
<script src="/cached.php?t=1539765004&amp;f=/js/common.js"></script>

<a id="toTop" href="javascript:;"><span id="toTopHover"></span><img width="40" height="40" alt="To Top" src="/images/to-top@2x.png"></a>

</body>
</html>
';
        }
        #print_r($dom->childNodes);exit;
        #foreach($dom->childNodes as $child) {
        if($dom->nodeType == XML_HTML_DOCUMENT_NODE) {
            $tagArray['#root'][] = $this->nodeToArray($dom);
        } else {
            $tagArray[$dom->nodeName][] = $this->nodeToArray($dom);
        }
        print_r($tagArray);//exit;
        #}
/*
        $tags = $dom->getElementsByTagName('*');
        foreach($tags as $key => $tag) {
            print_r($tag);
            $tagArray[$key][$tag->nodeName] = $this->nodeToArray($tag);
            if($first) {
                break;
            }
        }
*/
        return $tagArray;
    }

    /**
     * Get array from DOMDocument node data.
     *
     * @param   \DOMElement  $node
     * @return  array
     */
    public function nodeToArray($node) 
    {
        $array = false;
        print str_repeat('#',30)."\n";
        #print_r([$node->nodeName, $node->nodeType, $node->nodeValue]);exit;
        #print_r(['node'=>$node, 'nodeName'=>$node->nodeName, 'nodeValue'=>$node->nodeValue, 'nodeType'=>$node->nodeType, 'attributes'=>$node->attributes, 'childNodes'=>$node->childNodes, 'array'=>$array]);

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                $array[$childNode->nodeName] = array();
                print_r($array[$childNode->nodeName]);
                #$array[$node->nodeName] = array();
                #print_r(['childNode'=>$childNode, 'childNodeName'=>$childNode->nodeName, 'childNodeValue'=>$childNode->nodeValue, 'childNodeType'=>$childNode->nodeType, 'childNodeAttributes'=>$childNode->attributes, 'childNodeChildren'=>$childNode->childNodes]);
                if($childNode->nodeType == XML_DOCUMENT_TYPE_NODE ) {
                    $array[$node->nodeName][] = ["html", "PUBLIC", $node->doctype->publicId, $node->doctype->systemId];
#print_r([$childNode->nodeName, $childNode->nodeType, $childNode->nodeValue, $array]);
print str_repeat('-',30)."\n";
                } elseif($childNode->nodeType == XML_COMMENT_NODE ) {
                    $array[$childNode->nodeName] = $childNode->nodeValue;
                }
                elseif ($childNode->nodeType != XML_TEXT_NODE) {
                    if(!isset($array[$childNode->nodeName]) ||
                       !is_array($array[$childNode->nodeName])) {
                        $array[$childNode->nodeName] = [];
                    }
                    $array[$childNode->nodeName][] = $this->nodeToArray($childNode);
                } else {
                    if ($node->childNodes->length == 1) {
                        $array[$node->firstChild->nodeName] = $node->firstChild->nodeValue;//empty($node->firstChild->nodeValue) ? [] : [$node->firstChild->nodeValue];
                    }
                }
            }
        }

        if($array === false) {
            $array = [];
        }

        return $array; 
    }

    /**
     * Convert array to HTML tags string.
     *
     * @param   array  $tagArray
     * @return  string
     */
    public function arrayToHtml($tagArray)
    {
        #print str_repeat('#',30)."\n";
        //$_SESSION['tags'] = $tagArray;
        #$tagArray = $_SESSION['tags'];
        #print_r($tagArray);exit;
        $html = '';
        if(!is_array($tagArray) && !is_object($tagArray)) {
            return false;
        }
        $iterator = $this->getChildrenIterator($tagArray);
        $children = $attr = [];
        $key = $key1 = $key2 = $key3 = null;
        $tag = null;
        foreach($iterator as $key => $val) {
            #print_r(['T'=>$tag, 'A'=>$attr, 'C'=>$children, 'K'=>"$key =>", 'V'=>$val]);
            if(!is_array($val)) {
                #nothing
                #$tag = is_int($key) ? $tag : $key;
            } else {
                foreach($val as $key1 => $val1) {
                    $tag = is_int($key1) ? $tag : $key1;
                    #print_r(['T'=>$tag, 'A'=>$attr, 'C'=>$children, 'K1'=>"$key => $key1 =>", 'V1'=>$val1]);
                    if(!is_array($val1)) {
                        $attr[$key1] = $val1;
                    } else {
                        foreach($val1 as $key2 => $val2) {
                            #print_r(['T'=>$tag, 'A'=>$attr, 'C'=>$children, 'K2'=>"$key => $key1 => $key2 =>", 'V2'=>$val2]);
                            if(!is_array($val2)) {
                                #$tag = $key;
                                if($key2 == 'body' || $key2 == '#text') {
                                    $children[] = $val2;
                                } else {
                                    $attr[$key2] = $val2;
                                }
                            } else {
                                //$children[] = $this->createTag($key2);
                                if(empty($val2) || (count($val2) == 1 && empty($val2[0])) ) {
                                    #print_r(['T'=>$tag, 'A'=>$attr, 'C'=>$children, 'K2'=>"$key => $key1 => $key2 =>", 'V2'=>$val2]);
                                    #echo $key2;exit;
                                    $children[] = $this->createTag($key2);
                                } else {
                                    #$children[] = $this->childrenIterate([$val2]);
                                    foreach($val2 as $key3 => $val3) {
                                        #print_r(['T'=>$tag, 'A'=>$attr, 'C'=>$children, 'K3'=>"$key => $key1 => $key2 => $key3 =>", 'V3'=>$val3]);
                                        $children[] = $this->arrayToHtml([ [$key2=>$val3] ]);
                                        if($key3 == '#text') {
                                            //$children[] = $val3;
                                        } else {
                                        }
/*
                                        if(!is_array($val3)) {
                                        } else {
                                            if(empty($val3)) {
                                                //$children[] = $this->createTag($key2);
                                            } else {
                                                //$children[] = $this->childrenIterate([$key3 => $val3]);
                                            }
                                        }
*/
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        #print_r(['tag'=>$tag, 'attr'=>$attr, 'children'=>$children, "$key => $key1 => $key2 => $key3"=>$val]);
        if($tag == '#comment') {
            #print_r(['tag'=>$tag, 'attr'=>$attr, 'children'=>$children, "$key => $key1 => $key2 => $key3"=>$val]);//exit;
            foreach($children as $key => $child) {
                $child[$key] = "<!--".$child."-->";
            }
            $html .= implode('', $child);
        } else {
            if($tag == '#document') {
                $tag = '!doctype';
            }
            #print_r([$tag, $attr, $children]);print"--------------";
            $html .= $this->createTag($tag, $attr, $children);echo $html;exit;
        }
        #print_r($html."\n");
        //print_r($tagArray);
        #return $html;
    }
    
    /**
     * Get children of RecursiveIterator.
     *
     * @param   array  $tagArray
     * @return  array
     */
    public function getChildrenIterator($tagArray)
    {
        return new \RecursiveArrayIterator($tagArray);
    }
    
    /**
     * Iterate children of RecursiveIterator.
     *
     * @param   string  $name
     * @param   array   $tagArray
     * @return  array
     */
    public function childrenIterate($tagArray, $name = null)
    {
        $tag = $name;
        $iterator = $this->getChildrenIterator($tagArray);
        $children = $attr = [];
        foreach ($iterator as $key => $val) {
        print_r([$key, $val]);//, $key, $key1, is_array($val1), $attr, $children]);
        exit;
            if(!is_array($val)) {
            print_r([$tag, $name, $children, $attr, $key, $val]);exit;
                $tag = $name;
                $attr[$key] = $val;
            } else {
                //$children[] = $this->childrenIterate($key, $val);
            }
            $childrenArray = $children == [] ? null : implode('', $children);
            $html = $this->createTag($tag, $attr);
            //return 'text';
            /*
            if($iterator->hasChildren()) {
                return $this->getChildrenIterator($file);
            }
            return $file;
            */
        }
    }
}