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

    'default' => env('DB_CONNECTION', 'mysql'),

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
            'host'     =>  env('DB_HOST', '127.0.0.1'),
            'port'     =>  env('DB_PORT', '3306'),
            'database' =>  env('DB_DATABASE', 'ironman'),
            'username' =>  env('DB_USERNAME', 'ironman'),
            'password' =>  env('DB_PASSWORD', 'secret'),
            'prefix'   =>  env('DB_PREFIX', '')
        ],

    ],

];
