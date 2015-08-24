# naucon File Package

## About

This package contains php classes to access, change, copy, delete, move, rename files and directories.


### Features

* File
    * Extending SplFileInfo
    * change Privileges
    * create file or directories
    * copy files and directories
    * move files and directories
    * delete files and directories (recursive)
    * iterate files an directories
* FileReader
    * read file content
    * iterate lines
* FileWriter
    * write file content
    * write lines to file
    * remove file content (clear)
    * truncat file content
* Lock (file lock mechanism)


### Compatibility

* PHP5.3


## Installation

install the latest version via composer 

    composer require naucon/file


## Basic Usage

### File

The `File` class provides methods to access and change file attributes as well as basic file operations like copy, delete, move, rename to a given absolute file or directory path.
Since PHP 5.1.2 the Standard PHP Library (SPL) contains a class `SplFileInfo` that can access file attributes but do not change or perform any basic file operations.
The `File` class inherit from the `SplFileInfo` class to ensure compatibility.

Create a instance of `File` class with a absolute file path string.

    $examplePath = __DIR__ . '/example.txt';

    use Naucon\File\File;
    $fileObject = new File($examplePath);

Example:

    echo 'File ' . $fileObject->getPathname() . ' do' . (($fileObject->exists()) ? '' : ' not') . ' exist.';
    echo '<br/>';
    echo 'File is' . (($fileObject->isReadable()) ? '' : ' not') . ' readable.';
    echo '<br/>';
    echo 'File is' . (($fileObject->isWritable()) ? '' : ' not') . ' writeable.';
    echo '<br/>';
    echo '<br/>';

    echo 'File size: ' . $fileObject->getSize() . ' bytes';
    echo '<br/>';
    echo 'Access Time: ' . $fileObject->lastAccessed()->format('d.m.Y H:s');
    echo '<br/>';
    echo 'Change Time: ' . $fileObject->lastChanged()->format('d.m.Y H:s');
    echo '<br/>';
    echo 'Modification Time: ' . $fileObject->lastModified()->format('d.m.Y H:s');
    echo '<br/>';
    echo '<br/>';

    echo 'File Owner: ' . $fileObject->getOwnerName() . ' (' . $fileObject->getOwner() . ')';
    echo '<br/>';
    echo 'File Group: ' . $fileObject->getGroupName() . ' (' . $fileObject->getGroup() . ')';
    echo '<br/>';
    echo 'File permission: ' . $fileObject->getPermission();
    echo '<br/>';
    echo '<br/>';


#### create directory

To create a directory, make a instance of `File` with a absolute path of the new directory and call the method `mkdir()` or `mkdirs()`.

    $newDirectoryPath = __DIR__ . '/tmp';

    use Naucon\File\File;
    $fileObject = new File($newDirectoryPath);
    $fileObject->mkdir();

The method `mkdirs()` will not only create the given directory instead it create every directories of the path recursive.

    $newDirectoryPath = __DIR__ . '/tmp/foo/bar';

    use Naucon\File\File;
    $fileObject = new File($newDirectoryPath);
    $fileObject->mkdirs();


#### rename

To rename a file or directory, first create a instance of `File` with the absolute path of the file or directory.
Afterward call the method `rename()` with the new file or directory name (file with extension).

    $fileObject->rename('example_foo.txt');


#### copy

To copy a file or directory, first create a instance of `File` with the absolute path of the source file or directory.
Afterward call the method `copy()` with a absolute path of the target directory.

    $sourcePath = __DIR__ . '/example.txt';
    $targetPath = __DIR__ . '/tmp/target/';

    use Naucon\File\File;
    $fileObject = new File($sourcePath);
    $fileObject->copy($targetPath);


#### move

To move a file or directory, first create a instance of `File` with the absolute path of the source file or directory.
Afterward call the method `move()` with a absolute path of the target directory.

    $sourcePath = __DIR__ . '/example.txt';
    $targetPath = __DIR__ . '/tmp/target/move/';

    use Naucon\File\File;
    $fileObject = new File($sourcePath);
    $fileObject->move($targetPath);


#### delete

To delete a file or directory, first create a instance of `File` with the absolute path of the file or directory.
Afterward call the method `delete()` or `deleteAll()`.

    $sourcePath = __DIR__ . '/tmp/example.txt';

    use Naucon\File\File;
    $fileObject = new File($sourcePath);
    $fileObject->delete();

The method `delete()` will delete the file or directory. It can only remove empty directories.
To delete a directory recursive with its files and sub directories call `deleteAll()`.

    $sourcePath = __DIR__ . '/tmp/';

    use Naucon\File\File;
    $fileObject = new File($sourcePath);
    $fileObject->deleteAll();


#### iterate

The `File` class provides the methods `listAll()` and `listFiles()` to access the files an directories of a given directory.
The methods return a instance of `FilesystemIterator` class (SPL). The instance can be iterated with the `foreach()` command to retrieve the files and directories.

    $path = __DIR__ . '/ExampleDir';

    use Naucon\File\File;
    $fileObject = new File($path);
    $iteratorObject = $fileObject->listAll();

    foreach ($iteratorObject as $subFileObject) {
        $subFileObject->getBasename() . '<br/>';
        if ($subFileObject->isDir()) {
            foreach ($subFileObject->listAll() as $subChildFileObject) {
                echo $subChildFileObject->getBasename() . '<br/>';
            }
        }
    }

The method `listFiles()` filters the result to file only. There for it implements the `FileFilterType` filter class.

    $iteratorObject = $fileObject->listFiles();

    foreach ($iteratorObject as $subFileObject) {
        echo $subFileObject->getBasename() . '<br/>';
    }


To filter the result use a implementation of `FilterIterator`. The package contains already the filter class `FileFilterType` to filter after the file type (dir|file.

    $iterator = new FileFilterType($fileObject->listAll(), 'dir');
    foreach ($iteratorObject as $subFileObject) {
        echo $subFileObject->getBasename() . '<br/>';
    }

### FileReader

The `FileReader` class extends the `File` class to read the content of a given file in different ways.

Since PHP 5.1 the Standard PHP Library (SPL) contains a class `SplFileObject` that can access and change file content.
The `FileReader` class nowadays use a instance of `SplFileObject` to perform read operations but do not inherit from it and is also not compatible.

Create a instance of `FileReader` class with a absolute file path string.

    $filePath = __DIR__ . '/example_read.txt';

    use Naucon\File\FileReader;
    $fileObject = new FileReader($filePath, 'r', true);


#### Iterate lines

The `FileReader` class implements the iterater interface. The instance can be iterated with the `foreach()` command to retrieve lines of file content.

    // iterate
    foreach($fileObject as $line) {
        echo $line . '<br/>';
    }

To navigate between line the following methods are provided `isFirst()`, `firstLine()`, `isLast()`, `nextLine()`, `readLine($pointer)`

    // while
    echo $fileObject->firstLine();
    echo '<br/>';
    while ( !$fileObject->isLast() ){
        echo $fileObject->nextLine();
        echo '<br/>';
    }

    echo $fileObject->firstLine();
    echo '<br/>';
    echo $fileObject->nextLine();
    echo '<br/>';
    echo $fileObject->nextLine();
    echo '<br/>';
    echo $fileObject->firstLine();
    echo '<br/>';

    echo $fileObject->readLine(3);
    echo '<br/>';

    echo $fileObject->readLine(7);
    echo '<br/>';

When calling `read()` the file content is returned at once.

    // read all
    echo nl2br($fileObject->read());
    echo '<br/>';

When calling `readLines()` the lines of file content are returned in a array at once.

    $lines = $fileObject->readLines();   // return array
    foreach ($lines as $line) {
        echo $line . '<br/>';
    }


### FileWriter

The `FileWriter` class extends the `FileReader` class to write content of a given file in different ways.

Since PHP 5.1 the Standard PHP Library (SPL) contains a class `SplFileObject` that can access and change file content.
The `FileWriter` class nowadays use a instance of `SplFileObject` to perform read operations but do not inherit from it and is also not compatible.

Create a instance of `FileWriter` class with a absolute file path string.

    $filePath = __DIR__ . '/example_write.txt';

    use Naucon\File\FileWriter;
    $fileObject = new FileWriter($filePath,'w+');   // file point at the beginning of the file, truncate existing content

Afterwards call `write($sting)` or `writeLine($string)` to write a given string to the file.

    $string = 'Line01'.PHP_EOL;
    $string.= 'Line02'.PHP_EOL;
    $string.= 'Line03'.PHP_EOL;
    $string.= 'Line04'.PHP_EOL;

    $fileObject->write($string);

    // iterate file lines
    foreach($fileObject as $line) {
        echo $line . '<br/>';
    }
    echo '<br/>';

    //Output:
    //Line01
    //Line02
    //Line03
    //Line04

The method `writeLine($string)` will add a line break a the given string.

    $filePath = __DIR__ . '/example_write.txt';
    $fileObject = new FileWriter($filePath,'a+');   // file point at the end of the file
    $fileObject->writeLine("foo");
    $fileObject->writeLine("bar");

    foreach($fileObject as $line) {
        echo $line . '<br/>';
    }
    echo '<br/>';

    //Output:
    //Line01
    //Line02
    //Line03
    //Line04
    //foo
    //bar

When calling `clear()` the file content will be removed

    $fileObject->clear();   // remove all content


### Lock

The `Lock` classes is a mechanism to lock processes by writing a lock file at the beginning of the process and deleting it at the end of the process.
When the process is executed and the lock file already exists it will aborted. Thereby multiple processes can not be executed at the same time.
You may have seen it when opening word document on windows.

First create a instance of `LockHandler` class with a absolute path where the lock file are written.
Afterward create a instance of `LockManager` class with the LockHandler` instance. The `LockManager` implements the singleton patter and is there for accessible everywhere.

    $lockPath = __DIR__ . '/lock/';

    use Naucon\File\LockHandler;
    use Naucon\File\LockManager;
    LockManager::init(new LockHandler($lockPath));

To perform locking create a instance of `Lock` with a unique identifier. Then call `lock()` to write the lock file.

    use Naucon\File\Lock;
    $lockObject = new Lock('foo');

    $lockObject->lock();    // create lock file "~foo.lock"

When calling `unlock()` the lock file will be removed;

    $lockObject->unlock(); // delete lock file "~foo.lock"

To verify if a a lock file exist call `isLocked()`.

    if ($lockObject->isLocked()) {
        $lockObject->unlock();  // make sure that file is not locked (deadlock)
    }

Example:

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



## License

The MIT License (MIT)

Copyright (c) 2015 Sven Sanzenbacher

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.











