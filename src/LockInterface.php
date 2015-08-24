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
 * Lock Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface LockInterface
{
    /**
     * filter iterator element
     *
     * @return      string                  lock id
     */
    public function getLockId();

    /**
     * @return      bool
     */
    public function isLocked();

    /**
     * @return      bool
     */
    public function lock();

    /**
     * @return      bool
     */
    public function unlock();
}