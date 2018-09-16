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

namespace Friday\Environment;

use Friday\Environment\Exception\InvalidPathException;

/**
 * This is the dotenv class.
 *
 * It's responsible for loading a `.env` file in the given directory and
 * setting the environment vars.
 */
class GetEnv
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * The loader instance.
     *
     * @var \Dotenv\Loader|null
     */
    protected $loader;

    /**
     * Create a new dotenv instance.
     *
     * @param string $path
     * @param string $file
     *
     * @return void
     */
    public function __construct($path, $file = '.env')
    {
        $this->filePath = $this->getFilePath($path, $file);
        $this->loader = new Loader($this->filePath, true);
    }

    /**
     * Load environment file in given directory.
     *
     * @return array
     */
    public function load()
    {
        return $this->loadData();
    }

    /**
     * Load environment file in given directory, suppress InvalidPathException.
     *
     * @return array
     */
    public function safeLoad()
    {
        try {
            return $this->loadData();
        } catch (InvalidPathException $e) {
            // suppressing exception
            return array();
        }
    }

    /**
     * Load environment file in given directory.
     *
     * @return array
     */
    public function overload()
    {
        return $this->loadData(true);
    }

    /**
     * Returns the full path to the file.
     *
     * @param string $path
     * @param string $file
     *
     * @return string
     */
    protected function getFilePath($path, $file)
    {
        if (!is_string($file)) {
            $file = '.env';
        }

        $filePath = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$file;

        return $filePath;
    }

    /**
     * Actually load the data.
     *
     * @param bool $overload
     *
     * @return array
     */
    protected function loadData($overload = false)
    {
        return $this->loader->setImmutable(!$overload)->load();
    }

    /**
     * Required ensures that the specified variables exist, and returns a new validator object.
     *
     * @param string|string[] $variable
     *
     * @return \Dotenv\Validator
     */
    public function required($variable)
    {
        return new Validator((array) $variable, $this->loader);
    }

    /**
     * Get the list of environment variables declared inside the 'env' file.
     *
     * @return array
     */
    public function getEnvironmentVariableNames()
    {
        return $this->loader->variableNames;
    }
}
