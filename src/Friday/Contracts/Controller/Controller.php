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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Controller;

use Friday\View\View;

interface Controller
{
    /**
     * Initialization hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @param \Friday\Foundation\Application $app
     *
     * @return void
     */
    public function initialize($app);

    /**
     * Returns the controller name.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the controller name.
     *
     * @param string $name Controller name.
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $viewPath
     * @param array       $data
     * @param string|null $layout
     *
     * @return string
     */
    public function renderView($viewPath = null, $data = [], $layout = null);

    /**
     * Renders view for given data, template file and layout.
     *
     * @param string|null $templatePath
     * @param array       $data
     *
     * @return string|bool
     */
    public function renderTemplate($templatePath = null, $data = []);

    /**
     * Create Instance of Model.
     *
     * @param string $model View to use for rendering
     *
     * @return \Friday\Model\ModelService|bool
     */
    public function model($model);

    /**
     * Display View.
     *
     * @param string $view View to use for rendering
     * @param array  $data Arguments to use
     *
     * @return void|bool
     */
    public function view($view, $data = []);

    /**
     * Display Template.
     *
     * @param string $template Template to use for rendering
     * @param array  $data     Arguments to use
     *
     * @return void|bool
     */
    public function template($template, $data = []);

    /**
     * Handle new controller@method from route.
     *
     * @param string $controller
     * @param string $method
     *
     * @return null|string
     */
    public function handleController($controller, $method);

    /**
     * Renders theme for given data.
     *
     * @param array $themeInfo
     * @param array $data
     *
     * @return string|bool
     */
    public function renderTheme($themeInfo, $data = []);
}
