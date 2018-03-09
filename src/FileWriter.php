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
     *
     * @param       string|\SplFileInfo|\SplFileObject $pathname pathname
     * @param       string $mode file mode
     * @param       bool $skipEmptyLines true = skip empty lines, false = contains empty lines
     * @throws Exception\FileException
     */
    public function __construct($pathname, $mode = 'r+', $skipEmptyLines = false)
    {
        parent::__construct($pathname);

        $this->openFileObject($mode, $skipEmptyLines);
    }

    /**
     * write string to file
     *
     * @param       string      $string     file content
     * @return      FileWriterInterface
     * @throws      FileWriterException
     */
    public function write($string)
    {
        if ($this->getFileObject()->fwrite($string) === false) {
            // return written bytes or null on error
            throw new FileWriterException('write to file failed.');
        }
        return $this;
    }

    /**
     * add string to file
     *
     * @param       string      $string     file content
     * @return      FileWriterInterface
     * @throws      FileWriterException
     */
    public function writeLine($string)
    {
        $string = rtrim($string, "\n\r") . PHP_EOL;
        if ($this->getFileObject()->fwrite($string) === false) {
            // return written bytes or null on error
            throw new FileWriterException('write line to file failed.');
        }
        return $this;
    }

    /**
     * clear file
     */
    public function clear()
    {
        $this->getFileObject()->ftruncate(0);
    }

    /**
     * truncates file to a given length (in bytes)
     *
     * @param       int         $bytes      length in bytes
     */
    public function truncates($bytes)
    {
        $this->getFileObject()->ftruncate($bytes);
    }
}