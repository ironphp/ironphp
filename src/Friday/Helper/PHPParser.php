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

class PHPParser
{
    /**
     * Track of parens nesting
     *
     * @var null|array
     */
    protected $stack = null;

    /**
     * Current nesting level
     *
     * @var null|array
     */
    protected $current = null;

    /**
     * String to parse
     *
     * @var null|string
     */
    protected $string = null;

    /**
     * current character offset in string
     *
     * @var null|int
     */
    protected $position = null;

    /**
     * start of text-buffer
     *
     * @var null|string
     */
    protected $buffer_start = null;

    /**
     * count paran
     *
     * @var int
     */
    protected $count = 0;

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
	}

    /**
     * Run PHPParser.
     *
     * @return string
     */
    public function run()
	{
		$result = $this->parse();
		print_r($result);exit;
	}

	/**
     * Parse a string into an array.
     *
     * @return array|bool
     */
	public function parse()
    {
        if (empty($this->string)) {
            return array();
        }

		$this->current = array();
        $this->stack = array();
        $this->length = strlen($this->string);

		// look at each character
        for ($this->position = 0; $this->position < $this->length; $this->position++) {
            switch ($this->string[$this->position]) {
                case '(':
					$this->count++;
                    $this->push();
                    // push current scope to the stack an begin a new scope
                    array_push($this->stack, $this->current);
                    $this->current = array();
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
print_r($this);exit;
        return $this->current;
    }
	
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
}

