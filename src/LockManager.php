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

use Naucon\File\Exception\LockManagerException;

/**
 * Lock Manager Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 *
 * @example    LockManagerExample.php
 */
class LockManager
{
    /**
     * @static
     * @access      private
     * @var         LockManager             lock manager object
     */
    static private $singletonObject = null;

    /**
     * @access      private
     * @var         LockHandlerInterface    lock handler object
     */
    private $lockHandler = null;



    /**
     * Constructor
     *
     * @access      private
     * @param       LockHandlerInterface        $lockHandler
     */
    private function __construct(LockHandlerInterface $lockHandler)
    {
        $this->lockHandler = $lockHandler;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->lockHandler);
    }

    /**
     * Clone
     *
     * @access      private
     */
    private function __clone()
    {
    }



    /**
     * @return      LockHandlerInterface        lock handler object
     */
    public function getLockHandler()
    {
        return $this->lockHandler;
    }

    /**
     * singleton
     *
     * @static
     * @param       LockHandlerInterface        $lockHandler
     * @return      LockManager
     */
    static public function init(LockHandlerInterface $lockHandler)
    {
        if (is_null(self::$singletonObject)) {
            self::$singletonObject = new self($lockHandler);
        }
        return self::$singletonObject;
    }

    /**
     * @static
     * @param       LockInterface       $lockObject
     * @return      bool
     * @throws      LockManagerException
     */
    static public function isLocked(LockInterface $lockObject)
    {
        if (!is_null(self::$singletonObject)) {
            return self::$singletonObject->getLockHandler()->isLocked($lockObject);
        } else {
            throw new LockManagerException('IsLocked failed. LockManager is not initialized.');
        }
    }

    /**
     * @static
     * @param       LockInterface       $lockObject
     * @return      bool
     * @throws      LockManagerException
     */
    static public function lock(LockInterface $lockObject)
    {
        if (!is_null(self::$singletonObject)) {
            return self::$singletonObject->getLockHandler()->lock($lockObject);
        } else {
            throw new LockManagerException('Lock failed. LockManager is not initialized.');
        }
    }

    /**
     * @static
     * @param       LockInterface       $lockObject
     * @return      bool
     * @throws      LockManagerException
     */
    static public function unlock(LockInterface $lockObject)
    {
        if (!is_null(self::$singletonObject)) {
            return self::$singletonObject->getLockHandler()->unlock($lockObject);
        } else {
            throw new LockManagerException('Unlock failed. LockManager is not initialized.');
        }
    }
}