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

namespace Friday\Foundation\Exceptions;

use ErrorException;

class Handler
{
    /**
     * Create a new exception handler instance.
     *
     * @return void
     */
    public function __construct()
    {
		register_shutdown_function( "check_for_fatal" );
		set_error_handler( "log_error" );
		set_exception_handler( "log_exception" );
    	if ( env('APP_DEBUG') === true ) {
			ini_set( "display_errors", "on" );
			error_reporting( E_ALL );
		}
		else {
			ini_set( "display_errors", "off" );
			error_reporting( 0 );
		}
    }
}
