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
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

/**
 * You can empty out this file, if you are certain that you match all requirements.
 *
 * You can remove this if you are confident that your PHP version is sufficient.
 */
if (version_compare(PHP_VERSION, '5.5.0') < 0) {
    trigger_error('Your PHP version must be equal or higher than 5.5.0 to use CakePHP.' . PHP_EOL, E_USER_ERROR);
}
