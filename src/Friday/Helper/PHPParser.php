<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP).
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) IronPHP
 *
 * @link		  https://github.com/IronPHP/IronPHP
 * @since         1.0.8
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Helper;

use Exception;

class PHPParser
{
    /**
     * Track of parens nesting.
     *
     * @var null|array
     */
    protected $stack = null;

    /**
     * Current nesting level.
     *
     * @var null|array
     */
    protected $current = null;

    /**
     * String to parse.
     *
     * @var null|string
     */
    protected $string = null;

    /**
     * current character offset in string.
     *
     * @var null|int
     */
    protected $position = null;

    /**
     * start of text-buffer.
     *
     * @var null|string
     */
    protected $buffer_start = null;

    /**
     * count paran.
     *
     * @var int
     */
    protected $count = 0;

    /**
     * Length of string.
     *
     * @var int
     */
    protected $length = 0;

    /**
     * Create a new PHPParser instance.
     *
     * @param string $code
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->string = $code;
        $this->addComment();
    }

    /**
     * Run PHPParser.
     *
     * @return string
     */
    public function run()
    {
        $this->parseForEach();
        $this->parseIf();
        $this->parseCsrf();

        return $this->string;
    }

    /**
     * Parse a string into an array.
     *
     * @return array|bool
     */
    public function parse()
    {
        if (empty($this->string)) {
            return [];
        }

        $this->current = [];
        $this->stack = [];
        $this->length = strlen($this->string);

        // look at each character
        for ($this->position = 0; $this->position < $this->length; $this->position++) {
            switch ($this->string[$this->position]) {
                case '(':
                    $this->count++;
                    $this->push();
                    // push current scope to the stack an begin a new scope
                    array_push($this->stack, $this->current);
                    $this->current = [];
                    break;
                case ')':
                    $this->count--;
                    $this->push();
                    // save current scope
                    $t = $this->current;
                    // get the last scope from stack
                    $this->current = array_pop($this->stack);
                    // add just saved scope to current scope
                    $this->current[] = $t;
                    break;
                /*
                case ' ':
                    // make each word its own token
                    $this->push();
                    break;
                */
                default:
                    // remember the offset to do a string capture later
                    // could've also done $buffer .= $string[$position]
                    // but that would just be wasting resources…
                    if ($this->buffer_start === null) {
                        $this->buffer_start = $this->position;
                    }
            }
        }
        print_r($this);
        exit;

        return $this->current;
    }

    /**
     * Add data into buffer_start.
     *
     * @return void
     */
    protected function push()
    {
        if ($this->buffer_start !== null) {
            // extract string from buffer start to current position
            $buffer = substr($this->string, $this->buffer_start, $this->position - $this->buffer_start);
            // clean buffer
            $this->buffer_start = null;
            // throw token into current scope
            $this->current[] = $buffer;
        }
    }

    /**
     * Add value in key.
     *
     * @param array $data
     *
     * @return string
     */
    public function addKeyVal($data)
    {
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $findStart = strpos($this->string, '{{'.$key.':}}');
                if ($findStart !== false) {
                    $findEnd = strpos($this->string, '{{:'.$key.'}}');
                    if ($findEnd !== false) {
                        $len = 5 + strlen($key);
                        $substr = substr($this->string, $findStart, ($findEnd - $findStart));
                        $substr = substr($substr, $len);
                        $loopstr = []; //fixed-for: Uncaught Error: [] operator not supported for strings $loopstr[]
                        foreach ($val as $k => $v) {
                            $temp = $substr;
                            if (is_array($v)) {
                                //$temp = strtr($temp, $v); {{}} not replaced
                                foreach ($v as $ks => $vs) {
                                    $temp = str_replace('{{'.$ks.'}}', $vs, $temp);
                                }
                            } else {
                                $temp = str_replace('{{'.$key.'}}', $v, $temp);
                            }
                            $loopstr[] = $temp;
                        }
                        $loopstr = implode("\n", $loopstr);
                        $this->string = str_replace('{{'.$key.':}}'.$substr.'{{:'.$key.'}}', $loopstr, $this->string);
                    } else {
                        throw new Exception('Error in template : loop has not closed properely');
                        exit;
                    }
                }
            } else {
                $this->string = str_replace('{{'.$key.'}}', $val, $this->string);
            }
        }

        return $this->string;
    }

    /**
     * Replace all {{}} to PHP code.
     *
     * @return string
     */
    public function replaceBraces()
    {
        $start = 0;
        while (true) {
            $findStart = strpos($this->string, '{{', $start);
            if ($findStart !== false) {
                $findStart += 2;
                $findEnd = strpos($this->string, '}}', $findStart);
                if ($findEnd !== false) {
                    $substr = substr($this->string, $findStart, ($findEnd - $findStart));
                    if (empty(trim($substr))) {
                        $this->string = str_replace('{{'.$substr.'}}', '', $this->string);
                    } else {
                        $this->string = str_replace('{{'.$substr.'}}', '<?=e('.trim($substr).')?>', $this->string);
                    }
                }
                $start = $findEnd;
            } else {
                break;
            }
        }

        return $this->string;
    }

    /**
     * Replace all @foreach to PHP code.
     *
     * @return void
     */
    public function parseForEach()
    {
        $start = 0;
        while (true) {
            $findStart = strpos($this->string, '@foreach', $start);
            if ($findStart !== false) {
                $findStart += 8;
                $countParan = 1;
                $loop = true;
                $start_loop = false;
                $i = $findStart;

                while ($countParan && $loop) {
                    if ($this->string[$i] === '(') {
                        $start_loop = true;
                        $countParan++;
                    }
                    if ($this->string[$i] === ')') {
                        $countParan--;
                    }
                    if ($start_loop && $countParan == 1) {
                        $loop = false;
                    }
                    $i++;
                }

                //$findEnd = strpos($this->string, ')', $findStart);
                $findEnd = $i + 1;
                if ($findEnd !== false) {
                    $substr = substr($this->string, $findStart, ($findEnd - $findStart));
                    $this->string = str_replace('@foreach'.$substr, '<?php foreach'.$substr.': ?>', $this->string);
                    $this->string = str_replace('@endforeach', '<?php endforeach; ?>', $this->string);
                }
                $start = $findEnd;
            } else {
                break;
            }
        }
    }

    /**
     * Replace all @if to PHP code.
     *
     * @return void
     */
    public function parseIf()
    {
        $start = 0;
        while (true) {
            $findStart = strpos($this->string, '@if', $start);
            if ($findStart !== false) {
                $findStart += 3;
                $countParan = 1;
                $loop = true;
                $start_loop = false;
                $i = $findStart;

                while ($countParan && $loop) {
                    if ($this->string[$i] === '(') {
                        $start_loop = true;
                        $countParan++;
                    }
                    if ($this->string[$i] === ')') {
                        $countParan--;
                    }
                    if ($start_loop && $countParan == 1) {
                        $loop = false;
                    }
                    $i++;
                }

                $findEnd = $i + 1;
                if ($findEnd !== false) {
                    $substr = substr($this->string, $findStart, ($findEnd - $findStart));
                    $this->string = str_replace('@if'.$substr, '<?php if'.trim($substr).': ?>', $this->string);
                    $this->string = str_replace('@else', '<?php else: ?>', $this->string);
                    $this->string = str_replace('@endif', '<?php endif; ?>', $this->string);
                }
                $start = $findEnd;
            } else {
                break;
            }
        }
    }

    /**
     * Replace all @if to PHP code.
     *
     * @return void
     */
    public function addComment()
    {
        $this->string = str_replace('{{--', '<!--', $this->string);
        $this->string = str_replace('--}}', '-->', $this->string);
    }

    /**
     * Replace all @csrf to CSRF tag.
     *
     * @return void
     */
    public function parseCsrf()
    {
        $this->string = str_replace('@csrf', csrf_field(), $this->string);
    }
}
