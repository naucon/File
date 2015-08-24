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
 * File Info Interface
 * interface for SplFileInfo class
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface FileInfoInterface
{
    /**
     * return file path
     *
     * @return      string
     * @see         \SplFileInfo::__toString()
     */
    public function __toString();

    /**
     * return a basename with extension
     *
     * @param       string                  optional suffix
     * @return      string
     * @see         \SplFileInfo::getBasename()
     */
    public function getBasename($suffix=null);

    /**
     * return filename with extension
     *
     * @return      string
     * @see         \SplFileInfo::getFilename()
     */
    public function getFilename();

    /**
     * return file info object for filename with extension
     *
     * @param       string                      class name
     * @return      string
     * @see         \SplFileInfo::getFileInfo()
     */
    public function getFileInfo($class_name=null);

    /**
     * return inode number of the file or directory
     *
     * @return      int
     * @see         \SplFileInfo::getInode()
     */
    public function getInode();

    /**
     * return file extension
     *
     * @return      string
     * @see         \SplFileInfo::getExtension()
     */
    public function getExtension();

    /**
     * return file path with basename
     *
     * @return      string
     * @see         \SplFileInfo::getPathname()
     */
    public function getPathname();

    /**
     * return file path without basename
     *
     * @return      string
     * @see         \SplFileInfo::getPath()
     */
    public function getPath();

    /**
     * return file info object for path without basename
     *
     * @param       string                      class name
     * @return      \SplFileInfo
     * @see         \SplFileInfo::getPathInfo()
     */
    public function getPathInfo($class_name=null);

    /**
     * return absolute file path
     *
     * @return      string
     * @see         \SplFileInfo::getRealPath()
     */
    public function getRealPath();

    /**
     * return file type (file|dir)
     *
     * @return      string
     * @see         \SplFileInfo::getType()
     */
    public function getType();

    /**
     * return if file path is a directory
     *
     * @return      bool
     * @see         \SplFileInfo::isDir()
     */
    public function isDir();

    /**
     * return if file path is a file
     *
     * @return      bool
     * @see         \SplFileInfo::isFile()
     */
    public function isFile();

    /**
     * return if file path is a symlink
     *
     * @return      bool
     * @see         \SplFileInfo::isLink()
     */
    public function isLink();

    /**
     * return symlink target
     * is not necessary a absolute path, could also be a relative path
     *
     * @return      string
     * @see         \SplFileInfo::getLinkTarget()
     */
    public function getLinkTarget();

    /**
     * return if file is executable
     *
     * @return      bool
     * @see         \SplFileInfo::isExecutable()
     */
    public function isExecutable();

    /**
     * return if file is readable
     *
     * @return      bool
     * @see         \SplFileInfo::isReadable()
     */
    public function isReadable();

    /**
     * return if file is writeable
     *
     * @return      bool
     * @see         \SplFileInfo::isWritable()
     */
    public function isWritable();

    /**
     * return a time of when last modified
     *
     * @return      int             unix timestamp
     * @see         \SplFileInfo::getMTime()
     */
    public function getMTime();

    /**
     * return a time of when last accessed
     *
     * @return      int             unix timestamp
     * @see         \SplFileInfo::getATime()
     */
    public function getATime();

    /**
     * return a time of when last changed
     *
     * @return      int             unix timestamp
     * @see         \SplFileInfo::getCTime()
     */
    public function getCTime();

    /**
     * return user id of the file or directory
     *
     * @return      int         user id of file owner
     * @see         \SplFileInfo::getOwner()
     */
    public function getOwner();

    /**
     * return user group id of the file or directory
     *
     * @return      int         user group id  of file group
     * @see         \SplFileInfo::getGroup()
     */
    public function getGroup();

    /**
     * return permission of file or directory
     *
     * @return      int         file permission
     * @see         \SplFileInfo::getPerms()
     */
    public function getPerms();

    /**
     * return file size in bytes
     *
     * @return      int
     * @see         \SplFileInfo::getSize()
     */
    public function getSize();

    /**
     * return a instance of SplFileObject to open and modify a file
     *
     * @param       string                      mode to open a file
     * @param       bool                        true = to search file within include path
     * @param       resource                    context
     * @return      \SplFileObject              instance of SplFileObject
     * @see         \SplFileInfo::openFile()
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
    public function openFile($open_mode='r', $use_include_path=false, $context=null);

    /**
     * sets the class which is return when calling SplFileInfo::openFile()
     *
     * @param       string                      class name
     * @return      void
     * @see         \SplFileInfo::setFileClass()
     */
    public function setFileClass($class_name);

    /**
     * sets the class which is return when calling SplFileInfo::getFileInfo() or SplFileInfo::getPathInfo()
     *
     * @param       string                      class name
     * @return      void
     * @see         \SplFileInfo::setInfoClass()
     */
    public function setInfoClass($class_name);
}