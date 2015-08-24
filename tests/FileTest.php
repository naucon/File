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

use Naucon\File\File;
use Naucon\File\FileInterface;
use Naucon\File\Exception\FileException;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // remove directories
        $filePath =  __DIR__ . '/tmp/foo';
        $fileObject = new File($filePath);
        $fileObject->deleteAll();

        // remove directories
        $filePath =  __DIR__ . '/tmp/bar';
        $fileObject = new File($filePath);
        $fileObject->deleteAll();

        // remove directories
        $filePath =  __DIR__ . '/tmp/foo2';
        $fileObject = new File($filePath);
        $fileObject->deleteAll();
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass()
    {

    }

    /**
     * @expectedException Naucon\File\Exception\FileException
     * @return    void
     */
    public function testEmptyInit()
    {
        $fileObject = new File('');
    }

    /**
     * @return    void
     */
    public function testInit()
    {
        $fileObject = new File('example.txt');
        $this->assertEquals('example.txt', $fileObject->getPathname());
        $this->assertEquals('example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new File('./example.txt');
        $this->assertEquals('./example.txt', $fileObject->getPathname());
        $this->assertEquals('./example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('.', $fileObject->getPath());

        $fileObject = new File('../example.txt');
        $this->assertEquals('../example.txt', $fileObject->getPathname());
        $this->assertEquals('../example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('..', $fileObject->getPath());

        $fileObject = new File('tmp/example.txt');
        $this->assertEquals('tmp/example.txt', $fileObject->getPathname());
        $this->assertEquals('tmp/example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('tmp', $fileObject->getPath());

        $fileObject = new File('/example.txt');
        $this->assertEquals('/example.txt', $fileObject->getPathname());
        $this->assertEquals('/example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new File('tmp/');
        $this->assertEquals('tmp', $fileObject->getPathname());
        $this->assertEquals('tmp', (string)$fileObject);
        $this->assertEquals('tmp', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new File('tmp');
        $this->assertEquals('tmp', $fileObject->getPathname());
        $this->assertEquals('tmp', (string)$fileObject);
        $this->assertEquals('tmp', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new File('/foo/bar/');
        $this->assertEquals('/foo/bar', $fileObject->getPathname());
        $this->assertEquals('/foo/bar', (string)$fileObject);
        $this->assertEquals('bar', $fileObject->getBasename());
        $this->assertEquals('/foo', $fileObject->getPath());

        $fileObject = new File('/foo\bar/');
        $this->assertEquals('/foo/bar', $fileObject->getPathname());    // deviation to SplFileInfo '/foo\bar'
        $this->assertEquals('/foo/bar', (string)$fileObject);           // deviation to SplFileInfo '/foo\bar'
        $this->assertEquals('bar', $fileObject->getBasename());         // deviation to SplFileInfo 'foo\bar'
        $this->assertEquals('/foo', $fileObject->getPath());            // deviation to SplFileInfo ''


        $fileObject = new \SplFileInfo('example.txt');
        $this->assertEquals('example.txt', $fileObject->getPathname());
        $this->assertEquals('example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new \SplFileInfo('./example.txt');
        $this->assertEquals('./example.txt', $fileObject->getPathname());
        $this->assertEquals('./example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('.', $fileObject->getPath());

        $fileObject = new \SplFileInfo('../example.txt');
        $this->assertEquals('../example.txt', $fileObject->getPathname());
        $this->assertEquals('../example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('..', $fileObject->getPath());

        $fileObject = new \SplFileInfo('tmp/example.txt');
        $this->assertEquals('tmp/example.txt', $fileObject->getPathname());
        $this->assertEquals('tmp/example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('tmp', $fileObject->getPath());

        $fileObject = new \SplFileInfo('/example.txt');
        $this->assertEquals('/example.txt', $fileObject->getPathname());
        $this->assertEquals('/example.txt', (string)$fileObject);
        $this->assertEquals('example.txt', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());                // deviation to File '/'

        $fileObject = new \SplFileInfo('tmp/');
        $this->assertEquals('tmp', $fileObject->getPathname());
        $this->assertEquals('tmp', (string)$fileObject);
        $this->assertEquals('tmp', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new \SplFileInfo('tmp');
        $this->assertEquals('tmp', $fileObject->getPathname());
        $this->assertEquals('tmp', (string)$fileObject);
        $this->assertEquals('tmp', $fileObject->getBasename());
        $this->assertEquals('', $fileObject->getPath());

        $fileObject = new \SplFileInfo('/foo/bar/');
        $this->assertEquals('/foo/bar', $fileObject->getPathname());
        $this->assertEquals('/foo/bar', (string)$fileObject);
        $this->assertEquals('bar', $fileObject->getBasename());
        $this->assertEquals('/foo', $fileObject->getPath());

        $fileObject = new \SplFileInfo('/foo\bar/');
        $this->assertEquals('/foo\bar', $fileObject->getPathname());    // deviation to File '/foo/bar'
        $this->assertEquals('/foo\bar', (string)$fileObject);           // deviation to File '/foo/bar'
        $this->assertEquals('foo\bar', $fileObject->getBasename());     // deviation to File 'bar'
        $this->assertEquals('', $fileObject->getPath());                // deviation to File 'foo'
    }

    /**
     * @return    void
     */
    public function testGetExtension()
    {
        $fileObject = new File('example.txt');
        $this->assertEquals('txt', $fileObject->getExtension());

        $fileObject = new File('image.jpeg');
        $this->assertEquals('jpeg', $fileObject->getExtension());

        $fileObject = new File('.htaccess');
        $this->assertEquals('htaccess', $fileObject->getExtension());

        $fileObject = new File('/tmp');
        $this->assertEquals('', $fileObject->getExtension());

        $fileObject = new File('/tmp/example.txt.txt');
        $this->assertEquals('txt', $fileObject->getExtension());


        $fileObject = new \SplFileInfo('example.txt');
        $this->assertEquals('txt', $fileObject->getExtension());

        $fileObject = new \SplFileInfo('image.jpeg');
        $this->assertEquals('jpeg', $fileObject->getExtension());

        $fileObject = new \SplFileInfo('.htaccess');
        $this->assertEquals('htaccess', $fileObject->getExtension());

        $fileObject = new \SplFileInfo('/tmp');
        $this->assertEquals('', $fileObject->getExtension());

        $fileObject = new \SplFileInfo('/tmp/example.txt.txt');
        $this->assertEquals('txt', $fileObject->getExtension());
    }

    /**
     * @return    void
     */
    public function testGetFilename()
    {
        $fileObject = new File('example.txt');
        $this->assertEquals('example.txt', $fileObject->getFilename());

        $fileObject = new File('image.jpeg');
        $this->assertEquals('image.jpeg', $fileObject->getFilename());

        $fileObject = new File('.htaccess');
        $this->assertEquals('.htaccess', $fileObject->getFilename());

        $fileObject = new File('/tmp');
        $this->assertEquals('/tmp', $fileObject->getFilename());


        $fileObject = new \SplFileInfo('example.txt');
        $this->assertEquals('example.txt', $fileObject->getFilename());

        $fileObject = new \SplFileInfo('image.jpeg');
        $this->assertEquals('image.jpeg', $fileObject->getFilename());

        $fileObject = new \SplFileInfo('.htaccess');
        $this->assertEquals('.htaccess', $fileObject->getFilename());

        $fileObject = new \SplFileInfo('/tmp');
        $this->assertEquals('/tmp', $fileObject->getFilename());
    }

    /**
     * @return    void
     */
    public function testGetName()
    {
        $fileObject = new File('example.txt');
        $this->assertEquals('example', $fileObject->getName());

        $fileObject = new File('image.jpeg');
        $this->assertEquals('image', $fileObject->getName());

        $fileObject = new File('.htaccess');
        $this->assertEquals('.htaccess', $fileObject->getName());

        $fileObject = new File('/tmp');
        $this->assertEquals('tmp', $fileObject->getName());

        $fileObject = new File('/tmp/example.txt.txt');
        $this->assertEquals('example.txt', $fileObject->getName());



        $fileObject = new \SplFileInfo('example.txt');
        $this->assertEquals('example', $fileObject->getBasename('.' . $fileObject->getExtension()));

        $fileObject = new \SplFileInfo('image.jpeg');
        $this->assertEquals('image', $fileObject->getBasename('.' . $fileObject->getExtension()));

        $fileObject = new \SplFileInfo('.htaccess');
        $this->assertEquals('.htaccess', $fileObject->getBasename('.' . $fileObject->getExtension()));

        $fileObject = new \SplFileInfo('/tmp');
        $this->assertEquals('tmp', $fileObject->getBasename('.' . $fileObject->getExtension()));

        $fileObject = new \SplFileInfo('/tmp/example.txt.txt');
        $this->assertEquals('example.txt', $fileObject->getBasename('.' . $fileObject->getExtension()));
    }

    /**
     * @return    void
     */
    public function testAbsolute()
    {
        // require to that phpunit is executed in vendor/naucon directory

        $fileObject = new File('File/tests/example.txt');
        $this->assertFalse($fileObject->isAbsolute());
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getAbsolutePath()));

        $fileObject = new File(__DIR__ . '/example.txt');
        $this->assertTrue($fileObject->isAbsolute());
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getAbsolutePath()));

        $fileObject = new File('File/../File/tests/example.txt');
        $this->assertFalse($fileObject->isAbsolute());
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getAbsolutePath()));

        $fileObject = new File('File/../File/tests/');
        $this->assertFalse($fileObject->isAbsolute());
        $this->assertEquals(strtolower(__DIR__), strtolower($fileObject->getAbsolutePath()));



        $fileObject = new \SplFileInfo('File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo(__DIR__ . '/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo('File/../File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo('File/../File/tests/');
        $this->assertEquals(strtolower(__DIR__), strtolower($fileObject->getRealPath()));
    }

    /**
     * @return    void
     */
    public function testGetRealPath()
    {
        // require to that phpunit is executed in vendor/naucon directory

        $fileObject = new File('File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new File(__DIR__ . '/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new File('File/../File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new File('File/../File/tests/');
        $this->assertEquals(strtolower(__DIR__), strtolower($fileObject->getRealPath()));



        $fileObject = new \SplFileInfo('File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo(__DIR__ . '/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo('File/../File/tests/example.txt');
        $this->assertEquals(strtolower(__DIR__) . '/example.txt', strtolower($fileObject->getRealPath()));

        $fileObject = new \SplFileInfo('File/../File/tests/');
        $this->assertEquals(strtolower(__DIR__), strtolower($fileObject->getRealPath()));
    }

    /**
     * @return    void
     */
    public function testParent()
    {
        $fileObject = new File('File/tests/example.txt');
        $parentFileObject = $fileObject->getParent();
        $this->assertInstanceOf('Naucon\File\FileInterface', $parentFileObject);
        $this->assertEquals(strtolower(__DIR__), strtolower($parentFileObject->getAbsolutePath()));

        $fileObject = new File(__DIR__ . '/example.txt');
        $parentFileObject = $fileObject->getParent();
        $this->assertInstanceOf('Naucon\File\FileInterface', $parentFileObject);
        $this->assertEquals(strtolower(__DIR__), strtolower($parentFileObject->getAbsolutePath()));

        $fileObject = new File('File/../File/tests/example.txt');
        $parentFileObject = $fileObject->getParent();
        $this->assertInstanceOf('Naucon\File\FileInterface', $parentFileObject);
        $this->assertEquals(strtolower(__DIR__), strtolower($parentFileObject->getAbsolutePath()));

        $fileObject = new File('example.txt');
        $parentFileObject = $fileObject->getParent();
        $this->assertEquals('.', $parentFileObject->getPathname());

        $fileObject = new File('File/../File/tests/');
        $parentFileObject = $fileObject->getParent();
        $this->assertInstanceOf('Naucon\File\FileInterface', $parentFileObject);
        $this->assertEquals(strtolower(realpath(__DIR__.'/../')), strtolower($parentFileObject->getAbsolutePath()));

        $fileObject = new File('File/../File/tests/');
        $parentFileObject = $fileObject->getParent();
        $this->assertInstanceOf('Naucon\File\FileInterface', $parentFileObject);
        $this->assertEquals(strtolower(realpath(__DIR__.'/../')), strtolower($parentFileObject->getAbsolutePath()));
    }

    /**
     * @return    void
     */
    public function testExist()
    {
        $path =  __DIR__;
        $filePath = __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->exists());

        $fileObject = new File($path);
        $this->assertEquals($path, $fileObject->getPathname());
        $this->assertTrue($fileObject->exists());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->isFile());  // SplFileInfo has no exist method

        $fileObject = new \SplFileInfo($path);
        $this->assertEquals($path, $fileObject->getPathname());
        $this->assertTrue($fileObject->isDir());  // SplFileInfo has no exist method
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsExecutable()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $result = $fileObject->isExecutable();


        $fileObject = new \SplFileInfo($filePath);
        $this->assertEquals($result, $fileObject->isExecutable());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsReadable()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isReadable());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertTrue($fileObject->isReadable());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsWritable()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isWritable());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertTrue($fileObject->isWritable());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsFile()
    {
        $path = __DIR__;
        $filePath =  $path . '/example.txt';

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());

        $fileObject = new File($path);
        $this->assertFalse($fileObject->isFile());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertTrue($fileObject->isFile());

        $fileObject = new \SplFileInfo($path);
        $this->assertFalse($fileObject->isFile());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsDir()
    {
        $path = __DIR__;
        $filePath =  $path . '/example.txt';

        $fileObject = new File($filePath);
        $this->assertFalse($fileObject->isDirectory());
        $this->assertFalse($fileObject->isDir());

        $fileObject = new File($path);
        $this->assertTrue($fileObject->isDirectory());
        $this->assertTrue($fileObject->isDir());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertFalse($fileObject->isDir());

        $fileObject = new \SplFileInfo($path);
        $this->assertTrue($fileObject->isDir());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testIsLink()
    {
        $path = __DIR__;
        $filePath =  $path . '/example.txt';

        $fileObject = new File($filePath);
        $this->assertFalse($fileObject->isLink());

        $fileObject = new File($path);
        $this->assertFalse($fileObject->isLink());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertFalse($fileObject->isLink());

        $fileObject = new \SplFileInfo($path);
        $this->assertFalse($fileObject->isLink());
    }

    /**
     * @return      void
     */
    public function testIsHidden()
    {
        $fileObject = new File('example.txt');
        $this->assertEquals('txt', $fileObject->getExtension());
        $this->assertFalse($fileObject->isHidden());

        $fileObject = new File('.htaccess');
        $this->assertEquals('htaccess', $fileObject->getExtension());
        $this->assertTrue($fileObject->isHidden());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testLastModified()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
        $this->assertInstanceOf('DateTime', $fileObject->lastModified());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testLastAccessed()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());
        $this->assertInstanceOf('DateTime', $fileObject->lastAccessed());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testLastChanged()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getCTime());
        $this->assertInstanceOf('DateTime', $fileObject->lastChanged());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getCTime());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testTouch()
    {
        // I did not compare access and modification time to defined values
        // because it looks like that this file attributes are cached in php
        // because they did not change until you call the script again.
        // beyond that i feared problem with windows.
        // TODO try clearstatcache()

        // create file
        $createFiles = array(
            __DIR__ . '/tmp/example_touch1.txt',
            __DIR__ . '/tmp/example_touch2.txt',
            __DIR__ . '/tmp/example_touch3.txt',
            __DIR__ . '/tmp/example_touch4.txt'
        );
        foreach ($createFiles as $createFile) {
            if (!is_file($createFile)) {
                fclose(fopen($createFile, 'x'));
            }
        }

        $filePath =  __DIR__ . '/tmp/example_touch1.txt';
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->touch());
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());

        $filePath =  __DIR__ . '/tmp/example_touch2.txt';
        $modificationTime = time()-3600; // current time -1 hour
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->touch($modificationTime));
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());

        $filePath =  __DIR__ . '/tmp/example_touch3.txt';
        $accessTime = time()-7200; // current time -2 hour
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->touch(null,$accessTime));
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());

        $filePath =  __DIR__ . '/tmp/example_touch4.txt';
        $modificationTime = time()-42200; // current time -12 hour
        $accessTime = time()-36000; // current time -10 hour
        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->touch($modificationTime,$accessTime));
        $this->assertGreaterThanOrEqual(0, $fileObject->getMTime());
        $this->assertGreaterThanOrEqual(0, $fileObject->getATime());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testCreateNewFile()
    {
        $pathname =  __DIR__ . '/tmp/new_file.txt';

        if (is_file($pathname)) {
            unlink($pathname);
        }
        $fileObject = new File($pathname);
        $this->assertTrue($fileObject->createNewFile());
        $this->assertTrue($fileObject->isFile());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testMkdir()
    {
        $filePath =  __DIR__ . '/tmp/foo';

        if (is_dir($filePath)) {
            $this->markTestSkipped();
        } else {
            $fileObject = new File($filePath);
            $this->assertTrue($fileObject->mkdir());
            $this->assertTrue($fileObject->isReadable());
            $this->assertTrue($fileObject->isWritable());

            $mode = 0777;
            $filePath =  __DIR__ . '/tmp/foo/lv1/';
            $fileObject = new File($filePath);
            $this->assertTrue($fileObject->mkdir($mode));
            $this->assertTrue($fileObject->isReadable());
            $this->assertTrue($fileObject->isWritable());
        }
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testMkdirs()
    {
        $filePath =  __DIR__ . '/tmp/bar/lv1/';
        if (is_dir($filePath)) {
            $this->markTestSkipped();
        } else {
            $fileObject = new File($filePath);
            $this->assertTrue($fileObject->mkdirs());
            $this->assertTrue($fileObject->isReadable());
            $this->assertTrue($fileObject->isWritable());

            $mode = 0777;
            $filePath =  __DIR__ . '/tmp/bar/lv1/lv2/lv3/';
            $fileObject = new File($filePath);
            $this->assertTrue($fileObject->mkdirs($mode));
            $this->assertTrue($fileObject->isReadable());
            $this->assertTrue($fileObject->isWritable());
        }
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testDelete()
    {
        $filePath =  __DIR__ . '/tmp/example_delete.txt';

        if (!is_file($filePath)) {
            // create file
            fclose(fopen($filePath, 'x'));
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->delete());


        $filePath =  __DIR__ . '/tmp/example_delete/';

        if (!is_dir($filePath)) {
            // create directory
            mkdir($filePath,0777,true);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->delete());
    }

    /**
     * @depends     testExist
     * @depends     testMkdir
     * @depends     testMkdirs
     * @return      void
     */
    public function testDeleteAll()
    {
        $filePath =  __DIR__ . '/tmp/example_delete.txt';

        if (!is_file($filePath)) {
            // create file
            fclose(fopen($filePath, 'x'));
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->deleteAll());

        $filePath =  __DIR__ . '/tmp/foo/';

        if (!is_dir($filePath)) {
            // create directories
            mkdir($filePath,0777,true);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->deleteAll());

        $filePath =  __DIR__ . '/tmp/bar/';

        if (!is_dir($filePath)) {
            // create directories
            mkdir($filePath,0777,true);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->deleteAll());
    }

    /**
     * @depends     testDeleteAll
     * @return      void
     */
    public function testDeleteFiles()
    {
        $filePath =  __DIR__ . '/tmp/foo2';

        // create directories
        $createDirs = array(
            $filePath,
            $filePath . '/bar2',
            $filePath . '/bar3',
        );
        foreach ($createDirs as $createDir) {
            if (!is_dir($createDir)) {
                mkdir($createDir,0777,true);
            }
        }

        // create file
        $createFiles = array(
            $filePath . '/example_file_delete1.txt',
            $filePath . '/example_file_delete2.txt',
            $filePath . '/bar2/example_file_delete3.txt',
            $filePath . '/bar2/example_file_delete4.txt',
            $filePath . '/bar3/example_file_delete5.txt',
            $filePath . '/bar3/example_file_delete6.txt'
        );
        foreach ($createFiles as $createFile) {
            if (!is_file($createFile)) {
                fclose(fopen($createFile, 'x'));
            }
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->deleteFiles());

        $subPaths = array_diff(scandir($filePath), array('..', '.'));
        $this->assertEquals(2, count($subPaths));
    }

    /**
     * @depends     testDeleteFiles
     * @return      void
     */
    public function testDeleteAllFiles()
    {
        $filePath =  __DIR__ . '/tmp/foo2';

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->deleteAllFiles());

        $subPaths = array_diff(scandir($filePath.'/bar2'), array('..', '.'));
        $this->assertEquals(0, count($subPaths));
        $subPaths = array_diff(scandir($filePath.'/bar3'), array('..', '.'));
        $this->assertEquals(0, count($subPaths));
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testRename()
    {
        // create file
        $filePath =  __DIR__ . '/tmp/example_rename_old.txt';
        if (!is_file($filePath)) {
            fclose(fopen($filePath, 'x'));
        }
        // remove renamed file
        $filePathNew = __DIR__ . '/tmp/example_rename_new.txt';
        if (is_file($filePathNew)) {
            unlink($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->rename('example_rename_new.txt'));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isFile());


        // create dir
        $filePath =  __DIR__ . '/tmp/ExampleRenameOld';
        if (!is_dir($filePath)) {
            mkdir($filePath,0777);
        }
        // remove renamed file
        $filePathNew = __DIR__ . '/tmp/ExampleRenameNew';
        if (is_dir($filePathNew)) {
            rmdir($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->rename('ExampleRenameNew'));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isDir());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testMove()
    {
        // create file
        $filePath =  __DIR__ . '/tmp/example_move.txt';
        if (!is_file($filePath)) {
            fclose(fopen($filePath, 'x'));
        }
        // create target dir
        $newPath = __DIR__ . '/tmp/target';
        if (!is_dir($newPath)) {
            mkdir($newPath,0777);
        }
        // remove moved file
        $filePathNew = $newPath . '/example_move.txt';
        if (is_file($filePathNew)) {
            unlink($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->move($newPath));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isFile());


        // create dir
        $filePath =  __DIR__ . '/tmp/ExampleMove';
        if (!is_dir($filePath)) {
            mkdir($filePath,0777);
        }
        // remove moved file
        $filePathNew = $newPath . '/ExampleMove';
        if (is_dir($filePathNew)) {
            rmdir($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->move($newPath));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isDir());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testCopy()
    {
        // create file
        $filePath =  __DIR__ . '/tmp/example_copy.txt';
        if (!is_file($filePath)) {
            fclose(fopen($filePath, 'x'));
        }
        // create target dir
        $newPath = __DIR__ . '/tmp/target';
        if (!is_dir($newPath)) {
            mkdir($newPath,0777);
        }
        // remove copied file
        $filePathNew = $newPath . '/example_copy.txt';
        if (is_file($filePathNew)) {
            unlink($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isFile());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->copy($newPath));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isFile());


        // create dir
        $filePath =  __DIR__ . '/tmp/ExampleCopy';
        if (!is_dir($filePath)) {
            mkdir($filePath,0777);
        }
        // remove copied file
        $filePathNew = $newPath . '/ExampleCopy';
        if (is_dir($filePathNew)) {
            rmdir($filePathNew);
        }

        $fileObject = new File($filePath);
        $this->assertTrue($fileObject->isDir());
        $this->assertTrue($fileObject->isWritable());
        $this->assertTrue($fileObject->copy($newPath));
        $this->assertEquals($filePathNew, $fileObject->getPathname());
        $this->assertTrue($fileObject->isDir());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testOwner()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getOwner());
        $this->assertGreaterThan(0, strlen($fileObject->getOwnerName()));


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getOwner());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testGroup()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getGroup());
        $this->assertGreaterThanOrEqual(0, strlen($fileObject->getGroupName()));

        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThanOrEqual(0, $fileObject->getGroup());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testPermission()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThan(0, $fileObject->getPermission());
        $this->assertEquals(4, strlen($fileObject->getPermission()));


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThan(0, $fileObject->getPerms());
        $this->assertEquals(4, strlen( substr(decoct($fileObject->getPerms()),2) ) ); // deviation
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testChgrp()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $userGroupId = $fileObject->getGroup());
        $this->assertGreaterThanOrEqual(0, strlen($userGroup = $fileObject->getGroupName()));

        if ($userGroupId > 0) {
            $this->assertTrue($fileObject->chgrp($userGroupId));
            $this->assertTrue($fileObject->chgrp($userGroup));

            $this->assertEquals($userGroupId, $fileObject->getGroup());
            $this->assertEquals($userGroup, $fileObject->getGroupName());
        }
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testChown()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThanOrEqual(0, $userId = $fileObject->getOwner());
        $this->assertGreaterThanOrEqual(0, strlen($user = $fileObject->getOwnerName()));

        $this->assertTrue($fileObject->chown($userId));
        $this->assertTrue($fileObject->chown($user));

        $this->assertEquals($userId, $fileObject->getOwner());
        $this->assertEquals($user, $fileObject->getOwnerName());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testChmod()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThan(0, $mode = $fileObject->getPermission());
        $this->assertEquals(4, strlen($mode));
        $this->assertTrue($fileObject->chmod($mode));
        $fileObject->flush();   // clear file status cache
        $this->assertEquals($mode, $fileObject->getPermission());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testChmodWithAllModes()
    {
        $modes = array(
            '0600', // file owner read and write;
            '0640', // file owner read and write; owner group read
            '0660', // file owner read and write; owner group read and write
            '0604', // file owner read and write; everbody read
            '0606', // file owner read and write; everbody read and write
            '0664', // file owner read and write; owner group read and write; everbody read
            '0666', // file owner read and write; owner group read and write; everbody read and write
            '0700', // file owner read, execute and write;
            '0740', // file owner read, execute and write; owner group read
            '0760', // file owner read, execute and write; owner group read and write
            '0770', // file owner read, execute and write; owner group read, execute and write
            '0704', // file owner read, execute and write; everbody read
            '0706', // file owner read, execute and write; everbody read and write
            '0707', // file owner read, execute and write; everbody read, execute and write
            '0744', // file owner read, execute and write; owner group read; everbody read
            '0746', // file owner read, execute and write; owner group read; everbody read and write
            '0747', // file owner read, execute and write; owner group read; everbody read, execute and write
            '0754', // file owner read, execute and write; owner group read and execute; everbody read
            '0755', // file owner read, execute and write; owner group read and execute; everbody read and execute
            '0756', // file owner read, execute and write; owner group read and execute; everbody read and write
            '0757', // file owner read, execute and write; owner group read and execute; everbody read, execute and write
            '0764', // file owner read, execute and write; owner group read and write; everbody read
            '0766', // file owner read, execute and write; owner group read and write; everbody read and write
            '0767', // file owner read, execute and write; owner group read and write; everbody read, execute and write
            '0774', // file owner read, execute and write; owner group read, execute and write; everbody read
            '0775', // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
            '0776', // file owner read, execute and write; owner group read, execute and write; everbody read and write
            '0777', // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
        );

        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        foreach ($modes as $mode) {
            $this->assertTrue($fileObject->chmod($mode));
            clearstatcache();
            $this->assertEquals($mode, $fileObject->getPermission());
        }
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testSize()
    {
        $filePath =  __DIR__ . '/example.txt';
        $fileObject = new File($filePath);
        $this->assertGreaterThan(0, $size = $fileObject->getSize());


        $fileObject = new \SplFileInfo($filePath);
        $this->assertGreaterThan(0, $fileObject->getSize());
        $this->assertEquals($size, $fileObject->getSize());
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testListAll()
    {
        $examplePath = __DIR__ . '/ExampleDir';

        $filesObject = new File($examplePath);

        $expectedFiles = array(
            $examplePath . '/ExampleChildDir',
            $examplePath . '/testFile1.txt',
            $examplePath . '/testFile2.txt'
        );

        $i = 0;
        foreach ($filesObject->listAll() as $fileObject) {
            $this->assertInstanceOf('Naucon\File\File', $fileObject);
            $this->assertContains($fileObject->getPathname(), $expectedFiles);
            $i++;
        }

        $this->assertEquals(3, $i);
    }

    /**
     * @depends     testExist
     * @return      void
     */
    public function testListFiles()
    {
        $examplePath = __DIR__ . '/ExampleDir';

        $filesObject = new File($examplePath);

        $expectedFiles = array(
            $examplePath . '/testFile1.txt',
            $examplePath . '/testFile2.txt'
        );

        $i = 0;
        foreach ($filesObject->listFiles() as $fileObject) {
            $this->assertInstanceOf('Naucon\File\File', $fileObject);
            $this->assertContains($fileObject->getPathname(), $expectedFiles);
            $i++;
        }

        $this->assertEquals(2, $i);
    }
}