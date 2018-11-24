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

namespace Friday\Console\Command;

use Friday\Console\Colors;
use Friday\Console\Command;
use Friday\Foundation\Application;

class ServeCommand extends Command
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
     * @param array  $option
     * @return void
     */
    public function __construct($option = [])
    {
        $this->option = $option;
    }

    /**
     * Execute Commands.
     *
     * @return void
     */
    public function run()
    {
        print Colors::LIGHT_BLUE.str_repeat("-", 72).PHP_EOL.
        Colors::WHITE."Welcome to ".Colors::GREEN."IronPHP".Colors::WHITE." Framework ".
        Colors::YELLOW."".Application::VERSION.Colors::WHITE." (kernel: src, env: ".env('APP_ENV').", debug: ".env('APP_DEBUG').")".PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat("-", 72).PHP_EOL;
        $this->serve();
        return;
    }

    /**
     * Run app in development server.
     *
     * @param  string  $port
     * @param  string  $public_html
     * @return void
     */
    public function serve($port = '8000', $public_html = 'public')
    {
        print Colors::LIGHT_CYAN."Built-in development server started: ".Colors::YELLOW."<http://localhost:$port>".Colors::WHITE.PHP_EOL.
        "You can exit with `CTRL+C`".PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        exec("php -S localhost:$port -t $public_html", $output);
    }
}