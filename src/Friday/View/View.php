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
     * @param   string   $tag
     * @return  array
     */
    public function createTag($tag, $attr = NULL, $content = NULL)
    {
        $temp = '<'.$tag;
        if($attr != NULL) {
            $attr = (array) $attr;
            foreach($attr as $key => $val) {
                if(is_numeric($key)) {
                    $val = strtolower(trim($val));
                    if(isset($this->attr[$tag][$val])) {
                        $args[$val] = $this->attr[$tag][$val];
                        //$args[] = $val.($this->attr[$tag][$val] == NULL ? '' : "=\"{$this->attr[$tag][$val]}\"");
                    }
                    else {
                        $args[$val] = NULL;
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
                $attr[] = $key.($val == NULL ? '' : "=\"$val\"");
            }
            unset($args);
            $args = array_unique($attr);
            $args = implode(' ', $args);
        }
        $temp .= isset($args) ? ' '.$args.'>' : '>'; 
        if($content != NULL) {
            if(is_array($content)) {
                array_walk($content, function(&$val, $key) { $val = trim($val); } );//5.3
                $content = implode("\n",$content);
                $content = explode("\n", $content);
                $content = preg_replace('/^/', "\t", $content);
                #$content = preg_filter('/^/', "@", $content);
                $content = rtrim("\n".implode("\n", $content), "\n")."\n";
            }
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
     * @param  string|null  $viewPath
     * @param  string       $data
     * @param  string|null  $layout
     * @return string
     */
    public function renderView($viewPath = null, $data = [], $layout = null)
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
     * @param string|null  $templatePath
     * @param string       $data
     * @return  $templateData.
     * @throws  \Exception
     */
    public function renderTemplate($templatePath = null, $data = [])
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
     * @param   string|null  $viewPath
     * @return  $viewData
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
}
