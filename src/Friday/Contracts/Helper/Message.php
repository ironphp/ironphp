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
 * @since         1.0.10
 *
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        Gaurang Parmar <gaurangkumarp@gmail.com>
 */

namespace Friday\Contracts\Helper;

interface Message extends Arrayable
{
    /**
     * Get the keys present in the message bag.
     *
     * @return array
     */
    public function keys();

    /**
     * Add a message to the bag.
     *
     * @param  string  $key
     * @param  string  $message
     * @return $this
     */
    public function add($key, $message);

    /**
     * Merge a new array of messages into the bag.
     *
     * @param  array  $messages
     * @return $this
     */
    public function merge($messages);

    /**
     * Determine if messages exist for a given key.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key);

    /**
     * Get the first message from the bag for a given key.
     *
     * @param  string|null  $key
     * @param  string|null  $format
     * @return string
     */
    public function first($key = null, $format = null);

    /**
     * Get all of the messages from the bag for a given key.
     *
     * @param  string  $key
     * @param  string|null  $format
     * @return array
     */
    public function get($key, $format = null);

    /**
     * Get all of the messages for every key in the bag.
     *
     * @param  string|null  $format
     * @return array
     */
    public function all($format = null);

    /**
     * Get the raw messages in the container.
     *
     * @return array
     */
    public function getMessages();

    /**
     * Get the default message format.
     *
     * @return string
     */
    public function getFormat();

    /**
     * Set the default message format.
     *
     * @param  string  $format
     * @return $this
     */
    public function setFormat($format = ':message');

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isNotEmpty();

    /**
     * Get the number of messages in the container.
     *
     * @return int
     */
    public function count();
}
