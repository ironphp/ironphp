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
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\View;

use Exception;
use Friday\Contracts\View\View as ViewInterface;

class View implements ViewInterface
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
        'html'     => true,
        'meta'     => false,
        'head'     => true,
        'body'     => true,
        'title'    => true,
        'p'        => true,
        'pre'      => true,
        'span'     => true,
        'div'      => true,
        'header'   => true,
        'footer'   => true,
        'script'   => true,
        'style'    => true,
        'link'     => false,
        'base'     => false,
        'a'        => true,
        'i'        => true,
        'nav'      => true,
        'button'   => true,
        'img'      => false,
        'ul'       => true,
        'ol'       => true,
        'li'       => true,
        'input'    => false,
        'form'     => true,
        'dt'       => true,
        'dd'       => true,
        'br'       => false,
        'dl'       => true,
        'label'    => true,
        'option'   => true,
        'select'   => true,
        'fieldset' => true,
        'h1'       => true,
        'h2'       => true,
        'h3'       => true,
        'h4'       => true,
        'h5'       => true,
        'h6'       => true,
        'strong'   => true,
        'code'     => true,
        'small'    => true,
        'section'  => true,
        'aside'    => true,
    ];

    /**
     * Attribute of Tags with default value used for rendering html page.
     *
     * @var array
     */
    private $attr = [
        '!doctype' => ['html' => null],
        'html'     => ['lang' => 'en'],
        'meta'     => ['charset' => 'utf-8', 'name' => [
            'content' => [
                'description' => 'IronPHP Application',
                'keywords'    => 'IronPHP, framework',
                'author'      => 'Gaurang Kumar',
                'viewport'    => 'width=device-width, initial-scale=1.0',
            ],
        ],
        ],
        'base' => ['target' => '_blank'],
        'link' => ['rel' => 'stylesheet', 'type' => 'text/css'],
    ];

    /**
     * Temperory View.
     *
     * @var string
     */
    public $tempView = null;

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
     * Data that should be available to all templates for a controller.
     *
     * @var array
     */
    protected $shared = [];

    /**
     * Instance of the View.
     *
     * @var \Friday\View\View|null
     */
    private static $instance;

    /**
     * The view factory instance.
     *
     * @var \Friday\View\Factory
     */
    private $factory;

    /**
     * Create a new View instance.
     *
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->factory = new Factory;
		self::$instance = $this;
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param array $tags
     *
     * @return void
     */
    public function addRenderTags($tags)
    {
        $i[0] = 0;
        $temp = [];
        foreach ($tags as $tag => $text) {
            if (is_array($text)) {
                $i[1] = 0;
                foreach ($text as $tag1 => $text1) {
                    $temp[$i[0]][$i[1]] = $this->createTag($tag1, $text1);
                }
                $temp[$i[0]] = implode("\n", $temp[$i[0]]);
            } else {
                $temp[$i[0]] = $this->createTag($tag, $text);
            }
            $i[0]++;
        }
        $this->tempView = implode("\n", $temp);
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param string      $tag
     * @param array|null  $attr
     * @param string|null $content
     * @param bool|null   $isCloseTag
     *
     * @throws \Exception
     *
     * @return string
     */
    public function createTag($tag, $attr = null, $content = null, $isCloseTag = null)
    {
        $args = [];
        if (empty($tag) || !is_string($tag)) {
            throw new Exception('Tags name can not be null or non-string');
            exit;
        }
        $temp = '<'.$tag;
        if ($attr != null) {
            $attr = (array) $attr;
            foreach ($attr as $key => $val) {
                if (is_numeric($key)) {
                    $val = strtolower(trim($val));
                    if (isset($this->attr[$tag][$val])) {
                        $args[$val] = $this->attr[$tag][$val];
                    //$args[] = $val.($this->attr[$tag][$val] == NULL ? '' : "=\"{$this->attr[$tag][$val]}\"");
                    } else {
                        $args[$val] = null;
                    }
                } else {
                    if (!isset($args[$key])) {
                        $args[$key] = $val;
                    }
                }
                if (isset($this->attr[$tag][$key])) {
                    if (is_array($this->attr[$tag][$key])) {
                        foreach ($this->attr[$tag][$key] as $k => $v) {
                            if (isset($v[$val]) && !isset($args[$val])) {
                                $args[$k] = $v[$val];
                            }
                        }
                    } else {
                        if (!isset($args[$val])) {
                            $args[$key] = $this->attr[$tag][$key];
                        }
                    }
                }
            }
            // unset($attr); // ???
            foreach ($args as $key => $val) {
                $attr[] = $key.($val == null ? '' : "=\"$val\"");
            }
            unset($args);
            $args = array_unique($attr);
            $args = implode(' ', $args);
        }
        $temp .= !empty($args) ? ' '.$args.'>' : '>';
        if ($content != null) {
            $content = explode("\n", $content);

            array_walk($content, function (&$val, $key) {
                $val = trim($val);
            }); //5.3
            $content = implode("\n", $content);
            $content = explode("\n", $content);
            $content = preg_replace('/^/', "\t", $content);
            //$content = preg_filter('/^/', "@", $content);
            $content = rtrim("\n".implode("\n", $content), "\n")."\n";
            /*
            foreach($content as $i => $line) {
                $content[$i] = "\t".$line;
            }
            $content = implode("\n", $content);
            */
            $temp .= $content;
        }
        if (!is_bool($isCloseTag)) {
            $isCloseTag = $this->tags[$tag] ?? true;
        }
        if ($isCloseTag) {
            $temp .= '</'.$tag.'>';
        }

        return $temp;
    }

    /**
     * Renders View for given data, template file and layout.
     *
     * @param string|null $viewPath
     * @param array       $data
     * @param string|null $layout
     *
     * @return string
     * @exception \Exception
     */
    public function renderView($viewPath, $data = [], $layout = null)
    {
        $data[''] = null;
        ob_start();
        require $viewPath;
        $viewData = ob_get_contents();
        ob_end_clean();
        if ($viewData === false) {
            throw new Exception('Output buffering is not active.');
            exit;
        }
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                if ($key == 'meta') {
                    $this->data['meta'] = $val;
                }
            } else {
                $viewData = str_replace('{{'.$key.'}}', $val, $viewData);
            }
        }

        return $viewData;
    }

    /**
     * Renders Template for given data, template file.
     *
     * @param string|null $templatePath
     * @param array       $data
     *
     * @throws \Exception
     *
     * @return string.
     */
    public function renderTemplate($templatePath, $data = [])
    {
        $templateData = $this->readTemplate($templatePath);
        if ($data === null) {
            throw new Exception('template(): expects parameter 2 to be array, null given');
        }
        $data[''] = null;

        return $this->putData($templateData, $data);
    }

    /**
     * Renders Template for given data, template file.
     *
     * @param string $templatePath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function readTemplate($templatePath)
    {
        if (!is_readable($templatePath)) {
            throw new Exception('No permissons to read template: '.$templatePath);
        }
        if ($templateData = file_get_contents($templatePath)) {
            return $templateData;
        } else {
            throw new Exception('Can not read template: '.$templatePath);
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
     * @param string $theme Theme name.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setTheme($theme)
    {
        if (empty($theme)) {
            throw new Exception('Theme name can not be null');
            exit;
        }
        $this->theme = $theme;
    }

    /**
     * Renders Theme for given data.
     *
     * @param array $themeInfo
     * @param array $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function renderTheme($themeInfo, $data = [])
    {
        $data = $data ?: [];

        return $this->renderTemplate($themeInfo['themeFilePath'], $data);
        $templateData = trim($this->readTemplate($themeInfo['themeFilePath']));
        $pos1 = stripos($templateData, '<!doctype');
        if ($pos1 !== false) {
            $pos2 = stripos($templateData, '>');
            $doctype = substr($templateData, $pos1, $pos2 + 1);
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
        $tagArray = (array) $this->htmlToArray($themeInfo['themeFilePath'], true, true);
        $html = $this->arrayToHtml($tagArray);

        if (!is_array($data) && $data !== null) {
            throw new Exception('template(): expects parameter 2 to be array, null given');
        }

        return $this->putData($templateData, $data);
    }

    /**
     * Convert HTML tags string to array.
     *
     * @param string $html
     *
     * @return array
     */
    public function htmlToArray($html, $isFile = true, $first = true)
    {
        $tagArray = [];
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        if ($isFile) {
            $dom->loadHTMLFile($html);
        } else {
            $dom->loadHTML($html);
        }
        $dom->loadHTML('<html>x</html> <html>y</html>');
        if ($dom->nodeType == XML_HTML_DOCUMENT_NODE) {
            $tagArray['#root'] = $this->nodeToArray($dom);
        } else {
            $tagArray[$dom->nodeName] = $this->nodeToArray($dom);
        }
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
     * @param \DOMElement $node
     *
     * @return array
     */
    public function nodeToArray($node)
    {
        $array = []; //false;
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType == XML_DOCUMENT_TYPE_NODE) {
                    $doctype[] = $childNode->name;
                    if ($node->doctype->publicId != null || $node->doctype->systemId != null) {
                        $doctype[] = 'PUBLIC';
                        $doctype[] = '"'.$node->doctype->publicId.'"';
                        $doctype[] = '"'.$node->doctype->systemId.'"';
                    }
                    $array[] = [$node->nodeName => $doctype];
                } elseif ($childNode->nodeType == XML_COMMENT_NODE) {
                    $array[] = [$childNode->nodeName => ['#text' => $childNode->nodeValue]];
                } elseif ($childNode->nodeType != XML_TEXT_NODE) {
                    $array[] = [$childNode->nodeName => $this->nodeToArray($childNode)];
                } elseif ($node->childNodes->length == 1) {
                    $array = [$node->firstChild->nodeName => $node->firstChild->nodeValue];
                }
            }
        }

        if ($array === false) {
            $array = [];
        }

        return $array;
    }

    /**
     * Convert array to HTML tags string.
     *
     * @param array $tagArray
     *
     * @return string|bool
     */
    public function arrayToHtml($tagArray)
    {
        $html = '';
        /*
                if (!is_array($tagArray) && !is_object($tagArray)) {
                    return false;
                }
        */

        $children = $attr = [];
        $key = $key1 = $key2 = $key3 = null;
        $tag = null;

        foreach ($tagArray as $key => $val) {
            $tag = is_int($key) ? $tag : $key;
            if (!is_array($val)) {
                /*
                $attr[$key] = $val;
                if($key === 'body' || $key === '#text') {
                    $children[] = $val;
                } else {
                    $attr[$key1] = $val;
                }
                */
            } else {
                foreach ($val as $key1 => $val1) {
                    if (!is_array($val1)) {
                        if ($key1 === 'body' || $key1 === '#text') {
                            $children[] = $val1;
                        } else {
                            $attr[$key1] = $val1;
                        }
                    } else {
                        if (empty($val1) || (count($val1) == 1 && empty($val1[0]))) {
                            //$children[] = $this->createElement($key1);
                        }
                        foreach ($val1 as $key2 => $val2) {
                            $children[] = $this->arrayToHtml([$key2=>$val2]);
                            /*
                            if (!is_array($val2)) {
                                //$tag = $key;
                                if ($key2 === 'body' || $key2 === '#text') {
                                    //$children[] = $val2;
                                } else {
                                    //$attr[$key2] = $val2;
                                }
                            } else {
                                if (empty($val2) || (count($val2) == 1 && empty($val2[0]))) {
                                    //$children[] = $this->createElement($key2);
                                } else {
                                    foreach ($val2 as $key3 => $val3) {
                                        if ($key3 == '#text') {
                                            //$children[] = $val3;
                                        } else {
                                        }
                                        if (false) {
                                            if (!is_array($val3)) {
                                            } else {
                                                if (empty($val3)) {
                                                    //$children[] = $this->createTag($key2);
                                                } else {
                                                    //$children[] = $this->childrenIterate([$key3 => $val3]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            */
                        }
                    }
                }
            }
        }
        if ($tag == '#comment') {
            foreach ($children as $key => $child) {
                $children[$key] = '<!--'.$child."-->\n";
            }
            $html = "\n".implode('', $children);
        } elseif ($tag == '#root') {
            $html = implode("\n", $children);
        } else {
            if ($tag == '#document') {
                $tag = '!doctype';
            }
            if ($tag !== null) {
                $html = $this->createTag($tag, $attr, implode('', $children));
            }
        }

        return $html;
    }

    /**
     * Get children of RecursiveIterator.
     *
     * @param array $tagArray
     *
     * @return \RecursiveArrayIterator
     */
    public function getChildrenIterator($tagArray)
    {
        return new \RecursiveArrayIterator($tagArray);
    }

    /**
     * Iterate children of RecursiveIterator.
     *
     * @param array  $tagArray
     * @param string $name
     *
     * @return array
     */
    public function childrenIterate($tagArray, $name = '')
    {
        $tag = $name;
        $iterator = $this->getChildrenIterator($tagArray);
        $children = $attr = [];
        foreach ($iterator as $key => $val) {
            if (!is_array($val)) {
                $tag = $name;
                $attr[$key] = $val;
            } else {
                //$children[] = $this->childrenIterate($key, $val);
            }
            $childrenArray = $children == [] ? null : implode('', $children);
            $html = $this->createTag($tag, $attr);
            /*
            if($iterator->hasChildren()) {
                return $this->getChildrenIterator($file);
            }
            return $file;
            */
        }
    }

    /**
     * Renders HTML element.
     *
     * @param string      $element
     * @param array|null  $attr
     * @param string|null $content
     * @param bool|null   $isCloseTag
     *
     * @throws \Exception
     *
     * @return string
     */
    public function createElement($element, $attr = null, $content = null, $isCloseTag = null)
    {
        if (empty($element) || !is_string($element)) {
            throw new Exception('Element name can not be null or non-string');
            exit;
        }
        $html = '';
        switch ($element) {
            case '#comment':
                //foreach($children as $key => $child) {
                //    $children[$key] = "<!--".$child."-->\n";
                //}
                //$html = "\n".implode('', $children);
                $html = '<!--'.$content."-->\n";
                break;
            case '#root':
                break;
            case '#document':
                $tag = '!doctype';
                break;
            default:
                $html = $this->createTag($element, $attr, $content, $isCloseTag);
                break;
        }

        return $html;
    }

    /**
     * Put value from given data in template.
     *
     * @param string $templateData
     * @param array  $data
     *
     * @throws \Exception
     *
     * @return string.
     */
    public function putData($templateData, $data = [])
    {
        $start = 0;
        while (true) {
            $findStart = strpos($templateData, '@include(', $start);
            if ($findStart !== false) {
                $findStart = $findStart + 9;
                $findEnd = strpos($templateData, ')', $findStart);
                if ($findEnd !== false) {
                    $substr = substr($templateData, $findStart, ($findEnd - $findStart));
                    $file = $this->getThemePath()."layout\\$substr.html";
                    if (file_exists($file) && is_file($file)) {
                        $templateData = str_replace("@include($substr)", file_get_contents($file), $templateData);
                    } else {
                        $templateData = str_replace("@include($substr)", '', $templateData);
                    }
                }
                $start = $findEnd;
            } else {
                break;
            }
        }
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $findStart = strpos($templateData, '{{'.$key.':}}');
                if ($findStart !== false) {
                    $findEnd = strpos($templateData, '{{:'.$key.'}}');
                    if ($findEnd !== false) {
                        $len = 5 + strlen($key);
                        $substr = substr($templateData, $findStart, ($findEnd - $findStart));
                        $substr = substr($substr, $len);
                        $loopstr = []; //fixed-for: Uncaught Error: [] operator not supported for strings $loopstr[]
                        foreach ($val as $k => $v) {
                            $temp = $substr;
                            if (is_array($v)) {
                                //$temp = strtr($temp, $v); {{}} not replaced
                                foreach ($v as $ks => $vs) {
                                    $temp = str_replace('{{'.$ks.'}}', $vs, $temp);
                                }
                            } else {
                                $temp = str_replace('{{'.$key.'}}', $v, $temp);
                            }
                            $loopstr[] = $temp;
                        }
                        $loopstr = implode("\n", $loopstr);
                        $templateData = str_replace('{{'.$key.':}}'.$substr.'{{:'.$key.'}}', $loopstr, $templateData);
                    } else {
                        throw new Exception('Error in template : loop has not closed properely');
                        exit;
                    }
                }
            } else {
                $templateData = str_replace('{{'.$key.'}}', $val, $templateData);
            }
        }

        return $templateData;
    }

    /**
     * Get the current view theme path.
     *
     * @return string|null
     *
     * @since 1.0.7
     */
    public function getThemePath()
    {
        return $this->templatePath;
    }

    /**
     * Set the view theme path.
     *
     * @param string $theme Theme name.
     *
     * @throws \Exception
     *
     * @return void
     *
     * @since 1.0.7
     */
    public function setThemePath($themePath)
    {
        if (empty($themePath)) {
            throw new Exception('ThemePath can not be null');
            exit;
        }
        $this->templatePath = $themePath;
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Illuminate\View\View
     *
     * @throws \BadMethodCallException
     * @since 1.0.7
     */
    public static function __callStatic($method, $parameters)
    {
		if(!method_exists(self::$instance->factory, $method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
		}

		call_user_func_array(array(self::$instance->factory, $method), $parameters);
    }
}
