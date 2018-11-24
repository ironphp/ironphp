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

use Friday\Http\Route;

/**
 *--------------------------------------------------------------------------
 * Web Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register web routes for your application. Now
 * create something great!
 *
 */

//route with controller@method
Route::get('/', 'IndexController@Index');
Route::get('/page1', 'IndexController@Index');

//route with optional multiple arguments - must pass default argument to all optioanl arguments
Route::get('/member/{name}/{?id}', function ($name, $id = 1) {
    echo "Name: [$id] $name";
    echo '<br>Num of Ars: '.func_num_args();
    echo '<br>Ars: ';print_r(func_get_args());
});

//route with optional arguments - must pass default  argument
Route::get('/user/{?name}/', function ($name = 'GK') {
    echo "Name: $name";
    echo '<br>Num of Ars: '.func_num_args();
    echo '<br>Ars: ';print_r(func_get_args());
});

//route with multiple arguments - must be in sequence
Route::get('/names/{id}/{name}', function ($id, $name) {
    echo "Name: [$id] $name";
});

//route with arguments
Route::get('/name/{name}', function ($name) {
    echo 'Name: '.$name;
});

//route with view only (always GET method)
Route::view('/view', 'index', ['name' => 'IronPHP']);

//route with callable
Route::get('/callable', function () {
    echo 'callable';
});
