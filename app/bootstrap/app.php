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
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

/**
 *--------------------------------------------------------------------------
 * Create The Application
 *--------------------------------------------------------------------------
 *
 * The first thing we will do is create a new IronPHP application instance
 * which serves as the "glue" for all the components of IronPHP.
 *
 */

$app = new Friday\Foundation\Application(
    realpath(__DIR__.'/../../')
);

return $app;
