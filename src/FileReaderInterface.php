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

/**
 * File Reader Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface FileReaderInterface extends \Iterator
{
    /**
     * return content of file
     *
     * @return      string          file content
     */
    public function read();

    /**
     * return a specified line of the file
     *
     * @param       int             line number
     * @return      string|array    file line, or false is line do not exist
     */
    public function readLine($line=0);

    /**
     * return an array with all lines of a file
     *
     * @return      array          file lines
     */
    public function readLines();

    /**
     * return first line
     *
     * @return      string|array
     */
    public function firstLine();

    /**
     * return next line of file
     *
     * @return      string|array    file line
     */
    public function nextLine();

    /**
     * @return      bool            current line is first
     */
    public function isFirst();

    /**
     * @return      bool            current line is last
     */
    public function isLast();

    /**
     * return true if there is a next line
     *
     * @return      bool            has next line
     */
    public function hasNext();

    /**
     * set first line as current line
     *
     * @return      void
     */
    public function first();
}