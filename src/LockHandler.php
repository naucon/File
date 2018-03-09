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

use Naucon\File\Exception\LockHandlerException;

/**
 * Lock Handler Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 *
 * @example    LockManagerExample.php
 */
class LockHandler implements LockHandlerInterface
{
    /**
     * @access      private
     * @var         \SplFileInfo
     */
    private $fileInfo = null;


    /**
     * Constructor
     *
     * @param       \SplFileInfo|string     $pathname       pathname
     */
    public function __construct($pathname)
    {
        $this->setFileInfo($pathname);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->fileInfo);
    }



    /**
     * @access      protected
     * @return      \SplFileInfo
     */
    protected function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * @access      protected
     * @param       \SplFileInfo|string     $pathname       pathname
     */
    protected function setFileInfo($pathname)
    {
        if ($pathname instanceof \SplFileInfo) {
            $this->fileInfo = $pathname;
        } else {
            $this->fileInfo = new \SplFileInfo($pathname);
        }
    }

    /**
     * @access      protected
     * @param       LockInterface $lockObject lock object
     * @return      File
     * @throws Exception\FileException
     */
    protected function getLockFile(LockInterface $lockObject)
    {
        $pathname = $this->getFileInfo()->getPathname() . '/~' . $lockObject->getLockId() . '.lock';
        return new File($pathname);
    }

    /**
     * @param       LockInterface $lockObject lock object
     * @return      bool
     * @throws Exception\FileException
     */
    public function isLocked(LockInterface $lockObject)
    {
        $fileObject = $this->getLockFile($lockObject);
        if ($fileObject->isFile()) {
            return true;
        }
        return false;
    }

    /**
     * @param       LockInterface $lockObject lock object
     * @return      bool
     * @throws      LockHandlerException
     * @throws Exception\FileException
     */
    public function lock(LockInterface $lockObject)
    {
        $fileObject = $this->getLockFile($lockObject);
        if (!$fileObject->isFile()) {
            $fileObject->createNewFile();
            return true;
        } else {
            // alarm
            throw new LockHandlerException('Lock ID ' . $lockObject->getLockId() . ' already locked.');
        }
    }

    /**
     * @param       LockInterface $lockObject lock object
     * @return      bool
     * @throws Exception\FileException
     */
    public function unlock(LockInterface $lockObject)
    {
        $fileObject = $this->getLockFile($lockObject);
        if ($fileObject->delete()) {
            return true;
        }
        return false;
    }
}