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
 * File Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface FileInterface
{
    /**
     * return a basename without extension
     *
     * @return      string
     */
    public function getName();

    /**
     * return if file path is absolute
     *
     * @return      bool
     */
    public function isAbsolute();

    /**
     * return absolute file path
     *
     * @return      string
     */
    public function getAbsolutePath();

    /**
     * return a instance of FileInterface of the parent directory or null
     *
     * @return      File
     */
    public function getParent();

    /**
     * return if fil e path is a directory
     *
     * @return      bool
     * @see         FileInfoInterface::isDir()
     */
    public function isDirectory();

    /**
     * returns if the file or directory of the current file path exists
     *
     * @return      bool
     */
    public function exists();

    /**
     * return if file or directory is hidden, for example .htaccess, .svn
     *
     * @return      bool
     */
    public function isHidden();

    /**
     * return a time of when last modified
     *
     * @return      \DateTime             date time object
     * @see         FileInfoInterface::getMTime()
     */
    public function lastModified();

    /**
     * return a time of when last accessed
     *
     * @return      \DateTime             date time object
     * @see         FileInfoInterface::getATime()
     */
    public function lastAccessed();

    /**
     * return a time of when last accessed
     *
     * @return      \DateTime             date time object
     * @see         FileInfoInterface::getCTime()
     */
    public function lastChanged();

    /**
     * touch set access and modification time to file
     *
     * @param       int         optional - unix timestamp of modification time, default is current time
     * @param       int         optional - unix timestamp of access time. default is the modification time
     * @return      bool
     */
    public function touch($modificationTime=null, $accessTime=null);

    /**
     * create a empty file, named by the pathname
     *
     * @param       int                 optional file permission
     * @param       bool                return true if the file was successfully created
     */
    public function createNewFile($mode=null);

    /**
     * create a directory (but not the parent directories)
     *
     * @param       int                     optional - file permission
     * @return      bool
     */
    public function mkdir($mode=null);

    /**
     * create a directory recursive (with parent directories)
     *
     * @param       int                     optional - file permission
     * @return      bool
     */
    public function mkdirs($mode=null);

    /**
     * delete the file or directory of the current file path
     *
     * @return      bool                    true if file was deleted
     */
    public function delete();

    /**
     * delete the file or directory with its content of the current file path
     *
     * @return      bool                    true if file was deleted
     */
    public function deleteAll();

    /**
     * delete files of the current directory
     *
     * @return      bool                    true if files were deleted
     */
    public function deleteFiles();

    /**
     * delete files of the current directory recursive
     *
     * @return      bool                    true if files were deleted
     */
    public function deleteAllFiles();

    /**
     * rename file
     *
     * @param       string                  file name
     * @return      bool
     */
    public function rename($filename);

    /**
     * move file to a given directory
     *
     * @param       FileInterface|string
     * @return      bool
     */
    public function move($filepath);

    /**
     * copy file to a given directory
     *
     * @param       FileInterface|string
     * @return      bool
     */
    public function copy($filepath);

    /**
     * return user name of the file or directory
     *
     * @return      string         user name of file owner
     */
    public function getOwnerName();

    /**
     * return user group name of the file or directory
     *
     * @return      string         user group name of file group
     */
    public function getGroupName();

    /**
     * return permission of file or directory
     *
     * @return      int         file permission
     */
    public function getPermission();

    /**
     * @param       mixed       user group name or id
     * @return      bool
     */
    public function chgrp($usergroup);

    /**
     * change owner
     *
     * @param       mixed       user name or id
     * @return      bool
     */
    public function chown($user);

    /**
     * change permission
     *
     * @param       int         file permission
     * @return      bool
     */
    public function chmod($fileMode);

    /**
     * clear file status cache
     *
     * @return      void
     */
    public function flush();

    /**
     * returns files and directories
     *
     * @return      \Iterator
     */
    public function listAll();

    /**
     * returns files
     *
     * @return      \Iterator
     */
    public function listFiles();
}