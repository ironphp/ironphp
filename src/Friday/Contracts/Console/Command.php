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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Console;

use Friday\Foundation\Application;

interface Command
{
    /**
     * Create a new Command instance.
     *
     * @param \Friday\Foundation\Console $console
     *
     * @return void
     */
    public function __construct($console);

    /**
     * Get instance of Console.
     *
     * @return \Friday\Foundation\Console
     */
    public function getConsole();

    /**
     * Get info of Console Application.
     *
     * @return string
     */
    public function getInfo();

    /**
     * Get welcome info of Console Application.
     *
     * @return string
     */
    public function getWelInfo();

    /**
     * Execute Help command.
     *
     * @param string $command
     * @param array  $option
     *
     * @return string
     */
    public function executeHelp($command, $option = []);
}
