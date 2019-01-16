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
 * @since         1.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Console\Command;

use Friday\Console\Colors;
use Friday\Console\Command;
use Friday\Foundation\Application;

class VersionCommand extends Command
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
        $output = Colors::RED.Colors::BG_BLACK."\
  _______                  ____  __   ______
 /__  __/_____ ___  ____  / __ \/ /  / / __ \
   / /  / ___/ __ \/ __ \/ /_/ / /__/ / /_/ /
 _/ /__/ /  / /_/ / / / / .___/ ___  / .___/
/_____/_/   \____/_/ /_/_/   /_/  /_/_/
".
        $this->getWelInfo().
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }

    /**
     * Check application update.
     *
     * @return bool
     */
    public function checkUpdate()
    {
        $url = 'https://repo.packagist.org/p/ironphp/ironphp.json';
        $packagistJson = @file_get_contents($url);
        if($packagistJson === true) {
            $packagistArray = json_decode($packagistJson,true);
            $packagistData = $packagistArray['packages']['ironphp/ironphp']['dev-master'];
            $time = $packagistData['time'];
            $version = $packagistData['version']; //dev-master
            $branchAlias = $packagistData['extra']['branch-alias']['dev-master']; // 0.0.1-dev
            date_default_timezone_set('Asia/Kolkata');
            $parsedTime = date_parse($time);
            $timeStamp = mktime($parsedTime['hour'], $parsedTime['minute'], $parsedTime['second'], $parsedTime['month'], $parsedTime['day'], $parsedTime['year']);
        }
        $installData = $this->app->getIntallTime();

        if($packagistJson === true) {
            echo Colors::WHITE." ".str_replace('T', ' ', substr(date(DATE_ATOM, $timeStamp), 0, 19));
            echo Colors::GREEN."\nChecking updates... ".Colors::WHITE;
            if($timeStamp > $installData->time) {
                if($branchAlias != $this->app->version()) {
                    print "Package have an update ".$branchAlias;
                }
                else {
                    print "Package have an update ".$version;
                }
            }
            else {
                print "There is no update.";
            }
        }
        else {
            echo Colors::GREEN."\nChecking updates... ".Colors::WHITE." No internet!";
        }
    }

    /**
     * Help for this Commands.
     *
     * @return void
     */
    public function help()
    {
        $output = Colors::LIGHT_BLUE.str_repeat("-", 63).PHP_EOL.
        Colors::GREEN."IronPHP ".Colors::WHITE."Framework ".Colors::YELLOW.Application::VERSION.Colors::WHITE." (kernel: src, env: ".env('APP_ENV').", debug: ".env('APP_DEBUG').")".PHP_EOL.
        Colors::LIGHT_BLUE.str_repeat("-", 63).PHP_EOL.
        Colors::YELLOW."Description:".PHP_EOL.
        Colors::WHITE."  Displays version of the ironphp framework".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Usage:".PHP_EOL.
        Colors::WHITE."  version".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Arguments:".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Options:".PHP_EOL.
        PHP_EOL.
        Colors::YELLOW."Help:".PHP_EOL.
        Colors::WHITE."  The ".Colors::GREEN."version".Colors::WHITE." display framework version:".PHP_EOL.
        PHP_EOL.
        Colors::GREEN."    php jarvis version".PHP_EOL.
        PHP_EOL.
        Colors::BG_BLACK.Colors::WHITE;
        return $output;
    }
}
