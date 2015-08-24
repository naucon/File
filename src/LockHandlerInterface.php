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
 * Lock Handler Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface LockHandlerInterface
{
    /**
     * @param       LockInterface                    lock object
     * @return      bool
     */
    public function isLocked(LockInterface $lockObject);

    /**
     * @param       LockInterface                    lock object
     * @return      bool
     */
    public function lock(LockInterface $lockObject);

    /**
     * @param       LockInterface                    lock object
     * @return      bool
     */
    public function unlock(LockInterface $lockObject);
}