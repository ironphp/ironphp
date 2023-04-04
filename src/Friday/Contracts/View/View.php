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
 * @since         1.0.6
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 *
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\View;

use Exception;

interface View
{
    /**
     * Create a new View instance.
     *
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app);

    /**
     * Renders view with HTML tags and given data, template file and layout.
     *
     * @param array $tags
     *
     * @return void
     */
    public function addRenderTags($tags);

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
    public function createTag($tag, $attr = null, $content = null, $isCloseTag = null);

    /**
     * Renders View for given data, template file and layout.
     *
     * @param string|null $viewPath
     * @param array       $data
     * @param string|null $layout
     *
     * @return string
     *
     * @exception \Exception
     */
    public function renderView($viewPath, $data = [], $layout = null);

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
    public function renderTemplate($templatePath, $data = []);

    /**
     * Renders Template for given data, template file.
     *
     * @param string $templatePath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function readTemplate($templatePath);

    /**
     * Get the current view theme.
     *
     * @return string|null
     */
    public function getTheme();

    /**
     * Set the view theme to use.
     *
     * @param string $theme Theme name.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setTheme($theme);

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
    public function renderTheme($themeInfo, $data = []);

    /**
     * Convert HTML tags string to array.
     *
     * @param string $html
     *
     * @return array
     */
    public function htmlToArray($html, $isFile = true, $first = true);

    /**
     * Get array from DOMDocument node data.
     *
     * @param \DOMElement $node
     *
     * @return array
     */
    public function nodeToArray($node);

    /**
     * Convert array to HTML tags string.
     *
     * @param array $tagArray
     *
     * @return string|bool
     */
    public function arrayToHtml($tagArray);

    /**
     * Get children of RecursiveIterator.
     *
     * @param array $tagArray
     *
     * @return \RecursiveArrayIterator
     */
    public function getChildrenIterator($tagArray);

    /**
     * Iterate children of RecursiveIterator.
     *
     * @param array  $tagArray
     * @param string $name
     *
     * @return array
     */
    public function childrenIterate($tagArray, $name = '');

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
    public function createElement($element, $attr = null, $content = null, $isCloseTag = null);

    /**
     * Put value from given data in template.
     *
     * @param string $templateData
     * @param array  $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function putData($templateData, $data = []);

    /**
     * Get the current view theme path.
     *
     * @return string|null
     *
     * @since 1.0.7
     */
    public function getThemePath();

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
    public function setThemePath($themePath);

    /**
     * Include file in template.
     *
     * @param string $templateData
     *
     * @return string
     *
     * @since 1.0.8
     */
    public function includeFile($templateData);
}
