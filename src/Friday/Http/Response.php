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

namespace Friday\Http;

/*-----------------------------------------------------
 * Having a slim HTTP request wrapper living happily on its own is all well and fine sure, but ultimately useless if not coupled to the counterpart that mimics the data and behavior of a typical HTTP response. Let’s fix and build up this complementary component:
 *-----------------------------------------------------
 * The Response class is unquestionably a more active creature than its partner Request. It acts like a basic container which allows you to stack up HTTP headers at will and is capable of sending them out to the client too.
 *-----------------------------------------------------
 * With these classes doing their thing in isolation, it’s time to tackle the next part in the construction of a front controller. In a typical implementation, the routing/dispatching processes are most of the time encapsulated inside the same method, which frankly speaking isn’t that bad at all. In this case, however, it’d be nice to break down the processes in question and delegate them to different classes. This way, things are balanced a little more in the equally of their responsibilities.
 *-----------------------------------------------------
 */

class Response implements ResponseInterface
{
    public function __construct($version) {
        $this->version = $version;
    }
 
    public function getVersion() {
        return $this->version;
    }
 
    public function addHeader($header) {
        $this->headers[] = $header;
        return $this;
    }
 
    public function addHeaders(array $headers) {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }
        return $this;
    }
 
    public function getHeaders() {
        return $this->headers;
    }
 
    public function send() {
        if (!headers_sent()) {
            foreach($this->headers as $header) {
                header("$this->version $header", true);
            }
        } 
    }
}