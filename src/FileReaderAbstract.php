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

use Naucon\File\FileAbstract;
use Naucon\File\FileReaderInterface;

/**
 * Abstract File Reader Class
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
abstract class FileReaderAbstract extends FileAbstract implements FileReaderInterface
{
    /**
     * @access      protected
     * @var         \SplFileObject          file object
     */
    protected $fileObject = null;

    /**
     * @access      protected
     * @var         bool                    skip empty line flag
     */
    protected $skipEmptyLines = false;




    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->fileObject);
    }



    /**
     * @access      protected
     * @return      \SplFileObject                      file object
     */
    protected function getFileObject()
    {
        return $this->fileObject;
    }

    /**
     * @access      protected
     * @param       string                              file mode
     * @param       bool                                true = skip empty lines, false = contains empty lines
     * @return      void
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
    protected function openFileObject($mode, $skipEmptyLines)
    {
        if (!$this->getParent()->isDir()) {
            $this->getParent()->mkdirs();
        }

        $newFile = false;
        if (!$this->isFile()) {
            switch ($mode)
            {
                case 'w':
                case 'w+':
                case 'a':
                case 'a+':
                case 'x':
                case 'x+':
                    $newFile = true;
                    break;
            }
        }

        $this->fileObject = $this->openFile($mode);

        if ($skipEmptyLines) {
            $this->skipEmptyLines = true;
            $this->fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        } else {
            $this->fileObject->setFlags(\SplFileObject::DROP_NEW_LINE);
        }

        if ($newFile) {
            $this->chmod($this->defaul_permission);
        }
    }

    /**
     * @return      string
     */
    public function read()
    {
        if ($this->getFileObject()->isFile()) {
            return file_get_contents($this->getFileObject()->getPathname());
        }
        return null;
    }

    /**
     * return a specified line of the file
     *
     * @param       int             line number
     * @return      string|array    file line, or false is line do not exist
     */
    public function readLine($line=0)
    {
        // // seek line
        $this->getFileObject()->seek($line);
        return $this->current();
    }

    /**
     * return an array with all lines of a file
     *
     * @return      array          file lines
     */
    public function readLines()
    {
        if ($this->getFileObject()->isFile()) {
            if ($this->skipEmptyLines) {
                // skip empty lines
                $lines = file($this->getFileObject()->getPathname(), FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            } else {
                $lines = file($this->getFileObject()->getPathname(), FILE_IGNORE_NEW_LINES);
            }

            if (is_array($lines)) {
                return $lines;
            }
        }
        return array();
    }

    /**
     * return first line
     *
     * @return      string|array
     */
    public function firstLine()
    {
        $this->first();
        return $this->getFileObject()->current();
    }

    /**
     * return next line of file
     *
     * @return      string|array    file line
     */
    public function nextLine()
    {
        $this->next();
        return $this->current();
    }

    /**
     * @return      bool            current line is first
     */
    public function isFirst()
    {
        if ($this->key()==0) {
            return true;
        }
        return false;
    }

    /**
     * @return      bool            current line is last
     */
    public function isLast()
    {
        return $this->getFileObject()->eof();
    }

    /**
     * return true if there is a next line
     *
     * @return      bool            has next line
     */
    public function hasNext()
    {
        if (!$this->isLast()) {
            return true;
        }
        return false;
    }

    /**
     * set first line as current line
     *
     * @return      void
     * @see         FileReader::rewind()
     */
    public function first()
    {
        $this->getFileObject()->rewind();
    }

    /**
     * return current line
     *
     * @return      mixed            current item
     */
    public function current()
    {
        return $this->getFileObject()->current();
    }

    /**
     * set point to next line
     *
     * @return      void
     */
    public function next()
    {
        $this->getFileObject()->next();
    }

    /**
     * return index of the current line
     *
     * @return      mixed            index of current line
     */
    public function key()
    {
        return $this->getFileObject()->key();
    }

    /**
     * return true if current line is valid
     *
     * @return      bool            current line is valid
     */
    public function valid()
    {
        return $this->getFileObject()->valid();
    }

    /**
     * rewind to the first line
     *
     * @return      void
     */
    public function rewind()
    {
        $this->getFileObject()->rewind();
    }
}