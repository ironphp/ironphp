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

use Friday\Helper\Language;
use Friday\Helper\UrlGenerator;

class App
{
    /**
     * The App instance.
     *
     * @var \Friday\Helper\App|null
     */
    protected static $instance = null;

    /**
     * The helper instance list.
     *
     * @var array
     */
    protected $app = [];

	/**
     * Create a new App instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->app = [
			'url' => UrlGenerator::class
		];
    }

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale()
    {
		return Language::getLocale();
    }

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale)
    {
		Language::setLocale($locale);
    }

    /**
     * Get App instance.
     *
     * @param  string|null  $abstract
     * @return void
     * @exception \Exception
     */
    public static function getInstance($abstract = null)
    {
		if(self::$instance === null) {
			self::setInstance();
		}
		if($abstract === null) {
			return self::$instance;
		} else {
			if(!isset(self::$instance->app[$abstract])) {
				throw Exception("Invalid app usage.");
			}
			$app = self::$instance->app[$abstract];
			return new $app();
		}
    }

    /**
     * Set new App instance.
     *
     * @return void
     */
    public static function setInstance()
    {
		static::$instance = new static;
    }
}