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

class Language
{
    /**
     * The default locale.
     *
     * @var string
     */
    protected static $locale;

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public static function getLocale()
    {
        return self::$locale;
    }

    /**
     * Set the default locale.
     *
     * @param string $locale
     *
     * @return void
     */
    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }
}
