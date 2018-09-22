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

namespace Friday\View;

class View
{

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
        'i' => true
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
     * Name of the controller that created the View if any.
     *
     * @var string
     */
    public $name;

    /**
     * Current passed params. Passed to View from the creating Controller for convenience.
     *
     * @var array
     * @deprecated 3.1.0 Use `$this->request->getParam('pass')` instead.
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
    public $theme;

    /**
     * An instance of a \Cake\Http\ServerRequest object that contains information about the current request.
     * This object contains all the information about a request and several methods for reading
     * additional information about the request.
     *
     * @var \Cake\Http\ServerRequest
     */
    public $request;

    /**
     * Reference to the Response object
     *
     * @var \Cake\Http\Response
     */
    public $response;

    /**
     * Constructor
     *
     * @param \Cake\Http\ServerRequest|null $request Request instance.
     * @param \Cake\Http\Response|null $response Response instance.
     * @param \Cake\Event\EventManager|null $eventManager Event manager instance.
     * @param array $viewOptions View options. See View::$_passedVars for list of
     *   options which get set as class properties.
     */
    public function __construct()
    {
        /*
        if (isset($viewOptions['view'])) {
            $this->setTemplate($viewOptions['view']);
        }
        if (isset($viewOptions['viewPath'])) {
            $this->setTemplatePath($viewOptions['viewPath']);
        }
        foreach ($this->_passedVars as $var) {
            if (isset($viewOptions[$var])) {
                $this->{$var} = $viewOptions[$var];
            }
        }
        if ($eventManager !== null) {
            $this->setEventManager($eventManager);
        }
        $this->request = $request ?: Router::getRequest(true);
        $this->response = $response ?: new Response();
        if (!$this->request) {
            $this->request = new ServerRequest([
                'base' => '',
                'url' => '',
                'webroot' => '/'
            ]);
        }
        $this->Blocks = new $this->_viewBlockClass();
        $this->initialize();
        $this->loadHelpers();
        */
    }

    /**
     * Initialization hook method.
     *
     * Properties like $helpers etc. cannot be initialized statically in your custom
     * view class as they are overwritten by values from controller in constructor.
     * So this method allows you to manipulate them as required after view instance
     * is constructed.
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Get path for templates files.
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set path for templates files.
     *
     * @param string $path Path for template files.
     * @return $this
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;

        return $this;
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
     * @param string|null $theme Theme name.
     * @return $this
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get the name of the template file to render. The name specified is the
     * filename in /src/Template/<SubFolder> without the .ctp extension.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the name of the template file to render. The name specified is the
     * filename in /src/Template/<SubFolder> without the .ctp extension.
     *
     * @param string $name Template file name to set.
     * @return $this
     */
    public function setTemplate($name)
    {
        $this->template = $name;

        return $this;
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param   array        $tags
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
        /*
        {
        $tags, $text = NULL, $parent = NULL, $child = NULL, $before = NULL, $after = NULL
        $tempView = '<'.$tags;
        if(isset($this->attr[$tags]) && $this->attr[$tags] != NULL) {
            foreach($this->attr[$tags] as $attr => $val) {
                $args[] = $attr.($val == NULL ? '' : "=\"$val\"");
            }
            $args = implode(' ', $args);
        }
        $tempView .= isset($args) ? ' '.$args.'>' : '>'; 
        if($child != NULL) {
            $tempView .= $text;
        }
        if($child != NULL) {
            $tempView .= '{'.$child.'}';
        }
        if($this->tags[$tags] === true) {
            $tempView .= "\n".'</'.$tags.'>';
        }
        if($after != NULL) {
            $tempView = $tempView."\n".'@'.$after.'@';
        }
        if($parent != NULL) {
            $tempView = str_replace('{'.$parent.'}', "\n".$tempView, $this->tempView);
        }
        if($before != NULL) {
            $tempView = str_replace('@'.$before.'@', $tempView, $this->tempView);
        }
        $this->tempView = $tempView;
        }
        */
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
                        $args[] = $val.($this->attr[$tag][$val] == NULL ? '' : "=\"{$this->attr[$tag][$val]}\"");
                    }
                    else {
                        $args[] = $val;
                    }
                }
                else {
                    $args[] = $key.($val == NULL ? '' : "=\"$val\"");
                    if(isset($this->attr[$tag][$key])) {
                        foreach($this->attr[$tag][$key] as $k => $v) {
                            if(isset($v[$val])) {
                                $args[] = $k.($v[$val] == NULL ? '' : "=\"{$v[$val]}\"");
                            }
                        }
                    }
                }
            }
            $args = array_unique($args);
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

        {
        /*
        $tags, $text = NULL, $parent = NULL, $child = NULL, $before = NULL, $after = NULL
        $tempView = '<'.$tags;
        if(isset($this->attr[$tags]) && $this->attr[$tags] != NULL) {
            foreach($this->attr[$tags] as $attr => $val) {
                $args[] = $attr.($val == NULL ? '' : "=\"$val\"");
            }
            $args = implode(' ', $args);
        }
        $tempView .= isset($args) ? ' '.$args.'>' : '>'; 
        if($child != NULL) {
            $tempView .= $text;
        }
        if($child != NULL) {
            $tempView .= '{'.$child.'}';
        }
        if($this->tags[$tags] === true) {
            $tempView .= "\n".'</'.$tags.'>';
        }
        if($after != NULL) {
            $tempView = $tempView."\n".'@'.$after.'@';
        }
        if($parent != NULL) {
            $tempView = str_replace('{'.$parent.'}', "\n".$tempView, $this->tempView);
        }
        if($before != NULL) {
            $tempView = str_replace('@'.$before.'@', $tempView, $this->tempView);
        }
        $this->tempView = $tempView;
        */
        }
    }

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param string|null  $viewPath
     * @param string       $data
     * @param string|null  $layout
     * @return  $viewData.
     */
    public function renderHtml($viewData = null, $data = [], $layout = null)
    {
                /*
                <div class="links">
                </div>
                */
        $doctype = $this->createTag('!doctype'); //['HTML', 'PUBLIC', '"-//W3C//DTD HTML 4.01//EN"', '"http://www.w3.org/TR/html4/strict.dtd"']);
        $charset = $this->createTag('meta', 'charset');
        $viewport = $this->createTag('meta', ['name' => 'viewport']);
        $title = $this->createTag('title', null, 'IronPHP');
        $description = $this->createTag('meta', ['name' => 'description']);
        $keywords = $this->createTag('meta', ['name' => 'keywords']);
        $author = $this->createTag('meta', ['name' => 'author']);
        $linkCss = '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">';
        $linkCss .= "\n".'<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/v4-shims.css">';
        $head = $this->createTag('head', null, [$charset, $viewport, $title, $description, $keywords, $author, $linkCss]);
        //$i = $this->createTag('i', ['class' => 'fa fa-2x fa-github', 'aria-hidden' => "true"]);
        //$a = $this->createTag('a', ['href' => 'https://github.com/ironphp/ironphp', 'title' => 'IronPHP on GitHub', 'style' => 'align-self:center'], [$i]);
        $body = $this->createTag('body', null, [$viewData]);
//print_r($head);exit;
        $html = $this->createTag('html', null, [$head, $body]);
        $dom = $doctype."\n".$html;
        return $dom;

        {
        /*
        $p1 = $this->createTag('p', null, 'Text');
        $p2 = $this->createTag('p', null, 'Text');
        $span1 = $this->createTag('span', null, [$p1, $p2]);
        $span2 = $this->createTag('span', null, [$p1, $p2]);
        $div1 = $this->createTag('div', null, [$span1, $span2]);
        $div2 = $this->createTag('div', null, [$span1, $span2]);
        $header = $this->createTag('header', null, [$div1, $div2]);
        */

        #$html = ['!doctype' => null, 'html' => ['head' => null]];
        #$this->addRenderTags($html);

        /*
        $this->addRenderTags('!doctype', NULL, NULL, false, NULL, 'doc');
        $this->addRenderTags('html', NULL, NULL, 'html', 'doc', NULL);
        $this->addRenderTags('head', NULL, 'html', 'head', NULL, 'head');
        $this->addRenderTags('body', NULL, NULL, NULL, 'head', NULL);
        $this->addRenderTags('title', 'IronPHP', 'head', NULL, NULL, NULL);
        */
        }
    }

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null  $viewPath
     * @param string       $data
     * @param string|null  $layout
     * @return  $viewData.
     */
    public function render($viewPath = null, $data = [], $layout = null)
    {
        $viewData = file_get_contents($viewPath);
        foreach($data as $key => $val) {
            $viewData = str_replace('{{'.$key.'}}', $val, $viewData);
        }
        if($layout == null) {
            $viewData = $this->renderHtml($viewData);
        }
        return $viewData;
        /*
        if ($this->hasRendered) {
            return null;
        }

        $defaultLayout = null;
        if ($layout !== null) {
            $defaultLayout = $this->layout;
            $this->layout = $layout;
        }

        $viewFileName = $view !== false ? $this->_getViewFileName($view) : null;
        if ($viewFileName) {
            $this->_currentType = static::TYPE_TEMPLATE;
            $this->dispatchEvent('View.beforeRender', [$viewFileName]);
            $this->Blocks->set('content', $this->_render($viewFileName));
            $this->dispatchEvent('View.afterRender', [$viewFileName]);
        }

        if ($this->layout && $this->autoLayout) {
            $this->Blocks->set('content', $this->renderLayout('', $this->layout));
        }
        if ($layout !== null) {
            $this->layout = $defaultLayout;
        }

        $this->hasRendered = true;

        return $this->Blocks->get('content');
        */
    }

}
