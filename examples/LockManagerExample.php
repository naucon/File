<?php
$lockPath = __DIR__ . '/lock/';

use Naucon\File\Lock;
use Naucon\File\LockHandler;
use Naucon\File\LockManager;

LockManager::init(new LockHandler($lockPath));

$lockObject = new Lock('foo');
if ($lockObject->isLocked()) {
    $lockObject->unlock();  // make sure that file is not locked
}

if ($lockObject->lock()) {
    echo "Lock<br/>";
} else {
    echo "Lock not possible<br/>";
}

$lockObject->unlock();
echo "Unlock<br/>";


$lockObject1 = new Lock('foo');
$lockObject1->lock(); // create lock file "~foo.lock"

$lockObject2 = new Lock('foo');
try {
    $lockObject2->lock(); // throw exception - lock file lock file "~foo.lock" already there
} catch (\Exception $e) {
    echo 'Already Locked<br/>';
}

$lockObject1->unlock(); // delete lock file "~foo.lock"

$lockObject2->lock(); // create lock file "~foo.lock" again
$lockObject2->unlock(); // delete lock file "~foo.lock" again