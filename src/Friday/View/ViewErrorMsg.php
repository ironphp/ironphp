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

namespace Friday\View;

use Countable;
use Friday\Contracts\Helper\Message as MessageContract;

class ViewErrorMsg implements Countable
{
    /**
     * The array of the view error bags.
     *
     * @var array
     */
    protected $bags = [];

    /**
     * Checks if a named MessageBag exists in the bags.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasBag($key = 'default')
    {
        return isset($this->bags[$key]);
    }

    /**
     * Get a Message instance from the bags.
     *
     * @param string $key
     *
     * @return \Friday\Contracts\Helper\Message
     */
    public function getBag($key)
    {
        return Arr::get($this->bags, $key) ?: new MessageBag();
    }

    /**
     * Get all the bags.
     *
     * @return array
     */
    public function getBags()
    {
        return $this->bags;
    }

    /**
     * Add a new MessageBag instance to the bags.
     *
     * @param string                           $key
     * @param \Friday\Contracts\Helper\Message $bag
     *
     * @return $this
     */
    public function put($key, MessageContract $bag)
    {
        $this->bags[$key] = $bag;

        return $this;
    }

    /**
     * Determine if the default message bag has any messages.
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }

    /**
     * Get the number of messages in the default bag.
     *
     * @return int
     */
    public function count()
    {
        return $this->getBag('default')->count();
    }

    /**
     * Dynamically call methods on the default bag.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getBag('default')->$method(...$parameters);
    }

    /**
     * Dynamically access a view error bag.
     *
     * @param string $key
     *
     * @return \Friday\Contracts\Helper\Message
     */
    public function __get($key)
    {
        return $this->getBag($key);
    }

    /**
     * Dynamically set a view error bag.
     *
     * @param string                           $key
     * @param \Friday\Contracts\Helper\Message $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->put($key, $value);
    }

    /**
     * Convert the default bag to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getBag('default');
    }
}
