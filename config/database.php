<?php
/**
 * The Front Controller for handling every request
 *
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
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

return [

    /**
     *--------------------------------------------------------------------------
     * Default Database Connection Name
     *--------------------------------------------------------------------------
     *
     * Here you may specify which of the database connections below you wish
     * to use as your default connection for all database work.
     *
     */

    'default' => 'mysql',

    /**
     *--------------------------------------------------------------------------
     * Database Connections
     *--------------------------------------------------------------------------
     *
     * Here are each of the database connections setup for your application.
     *
     */

    'connections' => [

        'mysql' => [
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'ironman',
            'username' => 'ironman',
            'password' => '',
            'prefix' => ''
        ],

    ],

];
