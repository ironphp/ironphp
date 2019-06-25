<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2018 (c) IronPHP (GaurangKumar Parmar)
 *
 * @link          https://github.com/IronPHP/IronPHP
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console;

class Colors
{
    const ESC = "\x1b";  // \033
    const CSI = "\x1b["; // \033[

    /*
     * Foreground Colors with their Light colors
     */
    const BLACK = self::CSI.'0;30m';
    const DARK_GRAY = self::CSI.'1;30m';
    const RED = self::CSI.'0;31m';
    const LIGHT_RED = self::CSI.'1;31m';
    const GREEN = self::CSI.'0;32m';
    const LIGHT_GREEN = self::CSI.'1;32m';
    const BROWN = self::CSI.'0;33m';
    const YELLOW = self::CSI.'1;33m';
    const BLUE = self::CSI.'0;34m';
    const LIGHT_BLUE = self::CSI.'1;34m';
    const PURPLE = self::CSI.'0;35m';
    const LIGHT_PURPLE = self::CSI.'1;35m';
    const CYAN = self::CSI.'0;36m';
    const LIGHT_CYAN = self::CSI.'1;36m';
    const LIGHT_GRAY = self::CSI.'0;37m';
    const WHITE = self::CSI.'1;37m';

    /*
     * Background Colors
     */
    const BG_BLACK = self::CSI.'40m';
    const BG_RED = self::CSI.'41m';
    const BG_GREEN = self::CSI.'42m';
    const BG_YELLOW = self::CSI.'43m';
    const BG_BLUE = self::CSI.'44m';
    const BG_MAGENTA = self::CSI.'45m';
    const BG_CYAN = self::CSI.'46m';
    const BG_LIGHT_GRAY = self::CSI.'47m';
}
