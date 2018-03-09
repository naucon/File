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
 * File Writer Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface FileWriterInterface
{
    /**
     * write string to file
     *
     * @param       string      $string     file content
     * @return      FileWriterInterface
     */
    public function write($string);

    /**
     * add string to file
     *
     * @param       string      $string     file content
     * @return      FileWriterInterface
     */
    public function writeLine($string);

    /**
     * clear file
     */
    public function clear();

    /**
     * truncates file to a given length (in bytes)
     *
     * @param       int         $bytes      length in bytes
     */
    public function truncates($bytes);
}