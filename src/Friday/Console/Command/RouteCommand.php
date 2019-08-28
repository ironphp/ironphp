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
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console\Command;

use Friday\Console\Colors;
use Friday\Console\Command;

class RouteCommand extends Command
{
    /**
     * Command options.
     *
     * @var array
     */
    private $option;

    /**
     * Create a List Commands instance.
     *
     * @param \Friday\Foundation\Console $console
     * @param array                      $option
     *
     * @return void
     */
    public function __construct($console, $option = [])
    {
        parent::__construct($console);

        $this->option = $option;
    }

    /**
     * Execute Commands.
     *
     * @return string
     */
    public function run()
    {
        $output = Colors::WHITE.Colors::BG_BLACK.
        $this->getRoute().
        Colors::BG_BLACK.Colors::WHITE;

        return $output;
    }

    /**
     * Return list of routes.
     *
     * @param  string|null  $route
     *
     * @return string
     */
    public function getRoute($route = null)
    {
        $routes = $this->getConsole()->getRoutes($route);
        $l1 = 10;
        $l2 = 10;
        $l3 = 10;
        $list = '';
        foreach ($routes as $route) {
            if ($l1 < strlen($route[1])) {
                $l1 = strlen($route[1]);
            }
            if ($l2 < strlen($route[0])) {
                $l2 = strlen($route[0]);
            }
            if ($l3 < strlen($route[2])) {
                $l3 = strlen($route[2]);
            }
        }
        foreach ($routes as $route) {
            $list .=
"| $route[1]".str_repeat(' ', $l1 - strlen($route[1]))." | $route[0]".str_repeat(' ', $l2 - strlen($route[0]))." | $route[2]".str_repeat(' ', $l3 - strlen($route[2])).' |
+'.str_repeat('-', $l1 + 2).'+'.str_repeat('-', $l2 + 2).'+'.str_repeat('-', $l3 + 2).'+
';
        }
        $list = '
+'.str_repeat('-', $l1 + 2).'+'.str_repeat('-', $l2 + 2).'+'.str_repeat('-', $l3 + 2).'+
| '.Colors::GREEN.Colors::BG_BLACK.'URI'.Colors::WHITE.Colors::BG_BLACK.str_repeat(' ', $l1 - strlen('URI')).' | '.Colors::GREEN.Colors::BG_BLACK.'Method'.Colors::WHITE.Colors::BG_BLACK.str_repeat(' ', $l2 - strlen('Method')).' | '.Colors::GREEN.Colors::BG_BLACK.'Action'.Colors::WHITE.Colors::BG_BLACK.str_repeat(' ', $l3 - strlen('Action')).' |
+'.str_repeat('-', $l1 + 2).'+'.str_repeat('-', $l2 + 2).'+'.str_repeat('-', $l3 + 2).'+
'.$list;

        return $list;
    }

    /**
     * Help for this Commands.
     *
     * @return string
     */
    public function help()
    {
        $output = $this->getInfo().
        Colors::YELLOW.'Description:'.PHP_EOL.
        Colors::WHITE.'  List all registered routes'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Usage:'.PHP_EOL.
        Colors::WHITE.'  route [options]'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Arguments:'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Options:'.PHP_EOL.
        Colors::GREEN.'  -r, --reverse'.Colors::WHITE."\t\tReverse the ordering of the routes.".PHP_EOL.
        Colors::GREEN.'  -h, --help'.Colors::WHITE."\t\tDisplay this help message".PHP_EOL.
        Colors::GREEN.'  -V, --version'.Colors::WHITE."\t\tDisplay this application version".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Help:'.PHP_EOL.
        Colors::WHITE.'  The '.Colors::GREEN.'route'.Colors::WHITE.' display list of registered routes:'.PHP_EOL.
        PHP_EOL.
        Colors::GREEN.'    php jarvis route'.PHP_EOL.
        PHP_EOL.
        Colors::WHITE.'  To display list in reverse order use --reverse or -r'.PHP_EOL.
        PHP_EOL.
        Colors::GREEN.'    php jarvis route --reverse'.PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;

        return $output;
    }
}
