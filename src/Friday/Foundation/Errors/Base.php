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
 * @since         1.0.1
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 *
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Foundation\Errors;

/**
 * Error wrapper that mimics Exception API. For internal usage.
 */
class Base
{
    private $message;
    private $trace;

    public function __construct($message, $trace = [])
    {
        $this->message = $message;
        $this->trace = $trace;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFile()
    {
        return '';
    }

    public function getLine()
    {
        return 0;
    }

    public function getTrace()
    {
        return $this->trace;
    }
}
