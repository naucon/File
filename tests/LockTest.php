<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\File\Tests;

use Naucon\File\Lock;
use Naucon\File\LockInterface;
use Naucon\File\LockHandler;
use Naucon\File\LockManager;
use Naucon\File\Exception\LockException;
use Naucon\File\Exception\LockHandlerException;

class LockTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $lockfile = __DIR__ . '/lock/~foo.lock';
        if (is_file($lockfile)) {
            unlink($lockfile);
        }
    }

    public static function tearDownAfterClass()
    {

    }

    public function testInit()
    {
        $lockPath = __DIR__ . '/lock/';
        LockManager::init(new LockHandler($lockPath));
    }

    /**
     * @depends     testInit
     * @expectedException \Naucon\File\Exception\LockException
     */
    public function testInvalidLockId()
    {
        new Lock(' ');
    }

    /**
     * @depends     testInit
     * @return      LockInterface
     */
    public function testLock()
    {
        $lockObject = new Lock('foo');
        $this->assertFalse($lockObject->isLocked());

        // create lock file "~foo.lock"
        $this->assertTrue($lockObject->lock());

        $this->assertTrue($lockObject->isLocked());
        return $lockObject;
    }

    /**
     * @depends     testLock
     * @param       LockInterface       $lockObject
     */
    public function testUnlock(LockInterface $lockObject)
    {
        // delete lock file "~foo.lock"
        $this->assertTrue($lockObject->unlock());
    }

    /**
     * @depends     testUnlock
     * @expectedException \Naucon\File\Exception\LockHandlerException
     */
    public function testAlreadyLocked()
    {
        $firstLock = new Lock('foo');
        // create lock file "~foo.lock"
        $this->assertTrue($firstLock->lock());

        $secondLock = new Lock('foo');
        // try to lock file "~foo.lock" again
        $this->assertFalse($secondLock->lock());

        $this->assertTrue($firstLock->unlock()); // delete lock file "~foo.lock"

        $this->assertTrue($secondLock->lock()); // create lock file "~foo.lock" again
        $this->assertTrue($secondLock->unlock()); // delete lock file "~foo.lock" again
    }
}