<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\File;

use Naucon\File\Exception\FileWriterException;
use Naucon\File\FileReaderAbstract;
use Naucon\File\FileWriterInterface;

/**
 * File Writer Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 */
class FileWriter extends FileReaderAbstract implements FileWriterInterface
{
    /**
     * Constructor
     *
     * @param       string|SplFileInfo|SplFileObject    pathname
     * @param       string                  file mode
     * @param       bool                    true = skip empty lines, false = contains empty lines
     *
     * file mode
     * r     = read only, beginning of file
     * r+    = read and write, beginning of file
     * w     = write only, beginning of file, empty file, create file if necessary
     * w+    = read and write, beginning of file, empty file, create file if nesessary
     * a     = write only, end of file, create file if necessary
     * a+    = read and write, end of file, create file if necessary
     * x     = write only, beginning of file, only create file
     * x+    = read and write, beginning of file, only create file
     *
     * +----+----+-----+-----+---+------+
     * |mode|read|write|start|end|create|
     * +----+----+-----+-----+---+------+
     * | r  | x  |     |  x  |   |      |
     * +----+----+-----+-----+---+------+
     * | r+ | x  |  x  |  x  |   |      |
     * +----+----+-----+-----+---+------+
     * | w  |    |  x  |  x  |   |  opt |
     * +----+----+-----+-----+---+------+
     * | w+ | x  |  x  |  x  |   |  opt |
     * +----+----+-----+-----+---+------+
     * | a  |    |  x  |     | x |  opt |
     * +----+----+-----+-----+---+------+
     * | a+ | x  |  x  |     | x |  opt |
     * +----+----+-----+-----+---+------+
     * | x  |    |  x  |  x  |   | only |
     * +----+----+-----+-----+---+------+
     * | x+ | x  |  x  |  x  |   | only |
     * +----+----+-----+-----+---+------+
     */
    public function __construct($pathname, $mode='r+', $skipEmptyLines=false)
    {
        parent::__construct($pathname);

        $this->openFileObject($mode, $skipEmptyLines);
    }

    /**
     * write string to file
     *
     * @param       string                  file content
     * @return      FileWriterInterface
     */
    public function write($string)
    {
        if(is_null($this->getFileObject()->fwrite($string))) {
            // return written bytes or null on error
            throw new FileWriterException('write to file failed.');
        }
        return $this;
    }

    /**
     * add string to file
     *
     * @param       string                  file content
     * @return      FileWriterInterface
     */
    public function writeLine($string)
    {
        $string = rtrim($string, "\n\r") . PHP_EOL;
        if(is_null($this->getFileObject()->fwrite($string))) {
            // return written bytes or null on error
            throw new FileWriterException('write line to file failed.');
        }
        return $this;
    }

    /**
     * clear file
     *
     * @return      bool                    true = when successful
     */
    public function clear()
    {
        $this->getFileObject()->ftruncate(0);
    }

    /**
     * truncates file to a given length (in bytes)
     *
     * @param       int                     length in bytes
     * @return      bool                    true = when successful
     */
    public function truncates($bytes)
    {
        $this->getFileObject()->ftruncate($bytes);
    }
}