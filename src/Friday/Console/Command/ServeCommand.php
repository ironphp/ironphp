<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * @link
 * @since         1.0.0
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console\Command;

use Friday\Console\Colors;
use Friday\Console\Command;

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
        $host = '127.0.0.1';
        $port = '8000';
        if (isset($this->option[0])) {
            if ($this->option[0] == 'help') {
                return $this->help();
            }
            if (strpos($this->option[0], '--host=') === 0) {
                $host = trim(str_replace('--host=', '', $this->option[0]));
            } elseif (strpos($this->option[0], '--port=') === 0) {
                $port = trim(str_replace('--port=', '', $this->option[0]));
            }
        }
        if (isset($this->option[1])) {
            if (strpos($this->option[1], '--host=') === 0) {
                $host = trim(str_replace('--host=', '', $this->option[1]));
            } elseif (strpos($this->option[1], '--port=') === 0) {
                $port = trim(str_replace('--port=', '', $this->option[1]));
            }
        }
        echo $this->getWelInfo();
        $this->serve($host, $port);
    }

    /**
     * Run app in development server.
     *
     * @param string $host
     * @param string $port
     * @param string $public_html
     *
     * @return void
     */
    public function serve($host = '127.0.0.1', $port = '8000', $public_html = 'public')
    {
        echo Colors::LIGHT_CYAN.'Built-in development server started [/public]: '.Colors::YELLOW."<http://$host:$port>".Colors::WHITE.PHP_EOL.
        'You can exit with `CTRL+C`'.PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        exec("php -S $host:$port -t $public_html");
    }

    /**
     * Help for this Commands.
     *
     * @return string
     */
    public function help()
    {
        $output = $this->getInfo().
        Colors::LIGHT_BLUE.str_repeat('-', 63).PHP_EOL.
        Colors::YELLOW.'Description:'.PHP_EOL.
        Colors::WHITE.'  Serve the application on the PHP development server'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Usage:'.PHP_EOL.
        Colors::WHITE.'  serve [options]'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Arguments:'.PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Options:'.PHP_EOL.
        Colors::GREEN.'      --host[=HOST]'.Colors::WHITE."\tThe host address to serve the application on ".Colors::YELLOW.'[default: "127.0.0.1"]'.PHP_EOL.
        Colors::GREEN.'      --port[=PORT]'.Colors::WHITE."\tThe port to serve the application on ".Colors::YELLOW.'[default: 8000]'.PHP_EOL.
        Colors::GREEN.'  -h, --help'.Colors::WHITE."\t\tDisplay this help message".PHP_EOL.
        Colors::GREEN.'  -V, --version'.Colors::WHITE."\t\tDisplay this application version".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW.'Help:'.PHP_EOL.
        Colors::WHITE.'  The '.Colors::GREEN.'serve'.Colors::WHITE.' command serves application on the PHP development server:'.PHP_EOL.
        PHP_EOL.
        Colors::GREEN.'    php jarvis serve'.PHP_EOL.
        PHP_EOL.
        Colors::WHITE.'  To serve application on other than default port and host.'.PHP_EOL.
        PHP_EOL.
        Colors::GREEN.'    php jarvis serve --host[=localhost] --port[=8080]'.PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;

        return $output;
    }
}
