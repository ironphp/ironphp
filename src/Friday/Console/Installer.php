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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console;

class Installer
{
    /**
     * Create a new Installer instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * postInstall.
     *
     * @return void
     */
    static public function postInstall()
    {
        print('postInstall');
    }

    /**
     * postUpdate.
     *
     * @return void
     */
    static public function postUpdate()
    {
        print('postUpdate');
    }

    /**
     * postInstall.
     *
     * @return void
     */
    static public function postInstall()
    {
        print('postInstall');
    }

    /**
     * postAutoloadDump.
     *
     * @return void
     */
    static public function postAutoloadDump()
    {
        print('postAutoloadDump');
    }
}
