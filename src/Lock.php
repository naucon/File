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

use Naucon\File\Exception\LockException;

/**
 * Lock Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 *
 * @example    LockManagerExample.php
 */
class Lock implements LockInterface
{
    /**
     * @access      protected
     * @var         string                  lock id
     */
    protected $lockId = null;


    /**
     * Constructor
     *
     * @param       string $lockId lock id
     * @throws LockException
     */
    public function __construct($lockId)
    {
        $this->setLockId($lockId);
    }


    /**
     * @return      string                  lock id
     */
    public function getLockId()
    {
        return $this->lockId;
    }

    /**
     * @access      protected
     * @param       string      $lockId     lock id
     * @return      void
     * @throws      LockException
     */
    protected function setLockId($lockId)
    {
        if (strlen(trim((string)$lockId)) > 0) {
            $this->lockId = (string)$lockId;
        } else {
            throw new LockException('Lock ID is not valid.');
        }
    }

    /**
     * @return      bool
     * @throws Exception\LockManagerException
     */
    public function isLocked()
    {
        return LockManager::isLocked($this);
    }

    /**
     * @return      bool
     * @throws Exception\LockManagerException
     */
    public function lock()
    {
        return LockManager::lock($this);
    }

    /**
     * @return      bool
     * @throws Exception\LockManagerException
     */
    public function unlock()
    {
        return LockManager::unlock($this);
    }
}