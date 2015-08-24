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

use Naucon\File\FileReaderAbstract;

/**
 * File Reader Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 */
class FileReader extends FileReaderAbstract
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
    public function __construct($pathname, $mode='r', $skipEmptyLines=false)
    {
        parent::__construct($pathname);

        $this->openFileObject($mode, $skipEmptyLines);
    }
}