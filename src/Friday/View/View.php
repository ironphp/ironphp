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
     * Instance of the Application.
     *
     * @var \Friday\Foundation\Application
     */
    private $app;

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
     * Create a new View instance.
     *
     * @param  \Friday\Foundation\Application  $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
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
     *
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
     *
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
     *
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
     * @param   array  $tags
     *
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
     *
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
     *
     * @return  $viewData.
     */
    public function renderHtml($viewData = null, $data = [], $layout = null)
    {
        $doctype = $this->createTag('!doctype'); //['HTML', 'PUBLIC', '"-//W3C//DTD HTML 4.01//EN"', '"http://www.w3.org/TR/html4/strict.dtd"']);
        $charset = $this->createTag('meta', 'charset');
        $xuaComp = $this->createTag('meta', ['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);
        $viewport = $this->createTag('meta', ['name' => 'viewport']);
        if(isset($this->data['meta']['title']) && $this->data['meta']['title'] != null) {
            $title = $this->createTag('title', null, $this->data['meta']['title']);
        }
        else {
            $title = $this->createTag('title', null, 'IronPHP App');
        }
        if(isset($this->data['meta']['description']) && $this->data['meta']['description'] != null) {
            $description = $this->createTag('meta', ['name' => 'description', 'content' => $this->data['meta']['description']]);
        }
        else {
            $description = $this->createTag('meta', ['name' => 'description']);
        }
        $keywords = $this->createTag('meta', ['name' => 'keywords']);
        $author = $this->createTag('meta', ['name' => 'author']);
        $canonical = $this->createTag('link', ['rel' => 'canonical', 'href' => $this->app->request->getUrl()]);
        $robot = $this->createTag('meta', ['name' => "robots", 'content' => "index, follow"]);
        $style = '';/*'<style type="text/css">
.sk-spinner-double-bounce.sk-spinner {
  width: 40px;
  height: 40px;
  position: relative;
  margin: 0 auto;
}
.sk-spinner-double-bounce .sk-double-bounce1,
.sk-spinner-double-bounce .sk-double-bounce2 {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #1ab394;
  opacity: 0.6;
  position: absolute;
  top: 0;
  left: 0;
  -webkit-animation: sk-doubleBounce 2s infinite ease-in-out;
  animation: sk-doubleBounce 2s infinite ease-in-out;
}
.sk-spinner-double-bounce .sk-double-bounce2 {
  -webkit-animation-delay: -1s;
  animation-delay: -1s;
}
@-webkit-keyframes sk-doubleBounce {
  0%,
  100% {
    -webkit-transform: scale(0);
    transform: scale(0);
  }
  50% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}
@keyframes sk-doubleBounce {
  0%,
  100% {
    -webkit-transform: scale(0);
    transform: scale(0);
  }
  50% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}
		#sp-bottom{background-color:#3c3c3c;padding:70px 0px 0px;}#sp-footer{background-color:#3c3c3c;}
		@media (max-width: 384px) {
			.image-thumb{
				min-width:100%;
			}
			.img-footer{
				margin-bottom:-1px;
				margin-top:-35px;
			}
			.download-now{
				display:none;
			}
		}
		@media (max-width: 450px) {
			.img-footer{
				margin-bottom:-1px;
				margin-top:-15px;
			}
		}
		@media (max-width: 250px) {
			.resbar{
				display:none;
			}
			.img-footer{
				margin-bottom:-5px;
				margin-top:-5px;
			}
		}
		.hot{
			color:#F29CB2 !important;
		}
		.hot:hover{
			color:#E7476F !important;
		}
		.hot-o{
			color:#E5456E !important;
			background-color:#F2A2B6 !important;
		}
		.hot-o:hover{
			color:#ED7C99 !important;
			background-color:#F7C4D1 !important;
		}
.download-button {margin-right:50px}
	</style>';*/
        $linkCss = '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">';
        $linkCss .= "\n".'<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/v4-shims.css">';
        $linkCss .= "\n".'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
        //$linkCss .= "\n".'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">';
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/style.css', 'rel' => 'stylesheet']);
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/dropdown.css', 'rel' => 'stylesheet']);
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/main.css', 'rel' => 'stylesheet']);
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/girls.css', 'rel' => 'stylesheet']);
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/new_style.css', 'rel' => 'stylesheet']);
        $linkCss .= "\n".$body = $this->createTag('link', ['href' => $this->app->request->getUrl().'css/tx3-tag-cloud.css', 'rel' => 'stylesheet', 'media' => "screen", 'type' => "text/css"]);
        $linkCss .= "\n".'<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
';
/*<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>*/
        $style2 =
	'<style>
	ul.tx3-tag-cloud li a {
		color: #7B7B7B;
	}
	ul.tx3-tag-cloud li a:hover {
		color: #E152C5 !important;
		font-weight:100 !important;
	}
	</style>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->';
        $head = $this->createTag('head', null, [$charset, $xuaComp, $viewport, $title, $description, $keywords, $author, $canonical, $robot, $style, $linkCss]);//, $style2
        //$i = $this->createTag('i', ['class' => 'fa fa-2x fa-github', 'aria-hidden' => "true"]);
        //$a = $this->createTag('a', ['href' => 'https://github.com/ironphp/ironphp', 'title' => 'IronPHP on GitHub', 'style' => 'align-self:center'], [$i]);
        $button = $this->createTag('button', ['type' => 'button', 'class' => 'navbar-toggle', 'data-toggle' => "collapse", 'data-target' => '#bs-example-navbar-collapse-1'], [
            $this->createTag('span', ['class' => 'sr-only'], 'Toggle navigation'),
            $this->createTag('span', ['class' => 'icon-bar']),
            $this->createTag('span', ['class' => 'icon-bar']),
            $this->createTag('span', ['class' => 'icon-bar'])
        ]);
        $a1 = $this->createTag('a', ['class' => 'navbar-brand', 'href' => $this->app->request->getHost(), 'title' => 'Title'], [
            $this->createTag('img', ['src' => $this->app->request->getUrl().'img/logo.png', 'class' => 'container-fluid', 'width' => '35', 'style' => 'margin-top:-6px'])
        ]);
        $a2 = $this->createTag('a', ['class' => 'navbar-brand', 'href' => $this->app->request->getHost(), 'title' => 'Title'], [
            '<strong style="color:rgba(255,61,121,1.00)">G</strong>irl<strong style="color:rgba(255,61,121,1.00)">HD</strong>Wall.<strong style="color:rgba(255,61,121,1.00)">TK</strong>'
        ]);
        $div1 = $this->createTag('div', ['class' => 'container-fluid'], [
            $this->createTag('div', ['class' => 'navbar-header'], [$button, $a1, $a2])
        ]);
        
        $li1 = $this->createTag('li', ['class' => 'dropdown tablet mini-tablet'], [
					'<a href="#" class="dropdown-toggle" data-toggle="dropdown">Top Category <b class="caret"></b></a>
<ul class="dropdown-menu menu-style no-underline" style="width:300px;">
</ul>'
        ]);
        $ul = $this->createTag('ul', ['class' => 'nav navbar-nav navbar-top-links1'], [$li1, '<li>
    <a href="'.$this->app->request->getUrl().'about" title="About Us">About</a>
</li>
<li>
    <a href="'.$this->app->request->getUrl().'contact" title="Contact Us">Contact</a>
</li>
<li class="mobile">
    <a href="'.$this->app->request->getUrl().'search" title="Search Image">Search</a>
</li>']);
        $div2 = $this->createTag('div', ['class' => 'collapse navbar-collapse', 'id' => 'bs-example-navbar-collapse-1'], [$ul]);
        $nav = $this->createTag('nav', ['class' => 'navbar navbar-default navbar-static-top navbar_panel', 'role' => 'navigation'], [$div1, $div2]);
        $body = $this->createTag('body', null, [$nav, $viewData]);
//print_r($description);exit;
        $html = $this->createTag('html', null, [$head, $body]);
        $dom = $doctype."\n".$html;
        return $dom;
?>


        

<?php
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
     * Renders View for given data, template file and layout.
     *
     * @param string|null  $viewPath
     * @param string       $data
     * @param string|null  $layout
     *
     * @return  $viewData.
     */
    public function renderView($viewPath = null, $data = [], $layout = null)
    {
        $viewData = file_get_contents($viewPath);
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

    /**
     * Renders Template for given data, template file.
     *
     * @param string|null  $templatePath
     * @param string       $data
     *
     * @return  $templateData.
     */
    public function renderTemplate($templatePath = null, $data = [])
    {
        $templateData = $this->readTemplate($templatePath);
        if($data === null) {
            throw new \Exception("template(): expects parameter 2 to be array, null given");
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
                        throw new \Exception('Error in template : loop has not closed properely');
                        exit;
                    }
                }
            }
            else {
                $templateData = str_replace('{{'.$key.'}}', $val, $templateData);
            }
        }
        /*
        if($layout == null) {
            $viewData = $this->renderHtml($viewData);
        }
        */
        return $templateData;
    }

    /**
     * Renders Template for given data, template file.
     *
     * @param string|null  $viewPath
     * @return  $viewData.
     */
    public function readTemplate($templatePath)
    {
        if(!is_readable($templatePath)) {
            throw new \Exception("No permissons to read template: ".$templatePath);
        }
        if($templateData = file_get_contents($templatePath)) {
            return $templateData;
        }
        else {
            throw new \Exception("Can not read template: ".$templatePath);
        }
    }
}
