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
 * @since         1.0.8
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Helper;

class UrlGenerator
{
    /**
     * The Route instance.
     *
     * @var \Friday\Http\Route
     */
    protected $routes;

    /**
     * The UrlGenerator instance.
     *
     * @var \Friday\Helper\UrlGenerator|null
     */
    protected static $instance = null;

    /**
     * Create a new URL Generator instance.
     *
     * @param array                $routes
     * @param \Friday\Http\Request $request
     * @param string|null          $assetRoot
     *
     * @return void
     */
    public function __construct(/*$routes, Request $request, $assetRoot = null*/)
    {
        static::$instance = $this;
        /*$this->routes = $routes;
        $this->assetRoot = $assetRoot;

        $this->setRequest($request);*/
    }

    /**
     * Generate the URL to an application asset.
     *
     * @param string    $path
     * @param bool|null $secure
     *
     * @return string
     */
    public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) {
            return $path;
        }

        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->formatRoot($this->formatScheme($secure));

        return $this->removeIndex($root).trim($path, '/');
    }

    /**
     * Determine if the given path is a valid URL.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (!preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    /**
     * Get the base URL for the request.
     *
     * @param string      $scheme
     * @param string|null $root
     *
     * @return string
     */
    public function formatRoot($scheme, $root = null)
    {
        if (is_null($root)) {
            $root = SERVER_ROOT;
        }

        $start = starts_with($root, 'http://') ? 'http://' : 'https://';

        return preg_replace('~'.$start.'~', $scheme, $root, 1);
    }

    /**
     * Get the default scheme for a raw URL.
     *
     * @param bool|null $secure
     *
     * @return string
     */
    public function formatScheme($secure = null)
    {
        return $secure ? 'https://' : 'http://';
    }

    /**
     * Remove the index.php file from a path.
     *
     * @param string $root
     *
     * @return string
     */
    protected function removeIndex($root)
    {
        $i = 'index.php';

        return contains($root, $i) ? str_replace('/'.$i, '', $root) : $root;
    }

    /**
     * Get UrlGenerator instance.
     *
     * @return \Friday\Helper\UrlGenerator
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
