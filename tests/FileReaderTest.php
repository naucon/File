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

use Naucon\File\FileReader;
use Naucon\File\Exception\FileReaderException;

class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return      void
     */
    public function testInit()
    {
        $filePath = __DIR__ . '/example_read.txt';
        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->isReadable());

        $fileObject = new \SplFileObject($filePath, 'r');
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->isReadable());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testIterate()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $lines = array(
            'Line01', 'Line02', 'Line03', 'Line04', 'Line05', 'Line06', 'Line07', 'Line08', 'Line09',
        );


        $fileObject = new FileReader($filePath, 'r', true);
        $i = 0;
        foreach($fileObject as $line) {
            $this->assertContains($line, $lines);
            $i++;
        }
        $this->assertEquals(9, $i);


        $fileObject = new \SplFileObject($filePath, 'r');
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        $i = 0;
        foreach($fileObject as $line) {
            $this->assertContains($line, $lines);
            $i++;
        }
        $this->assertEquals(9, $i);
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testNextLine()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertEquals('Line01', $fileObject->firstLine());
        $this->assertEquals('Line02', $fileObject->nextLine());
        $this->assertEquals('Line03', $fileObject->nextLine());
        $this->assertEquals('Line01', $fileObject->firstLine());

        $fileObject = new \SplFileObject($filePath, 'r');
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        $this->assertEquals('Line01', $fileObject->current());
        $fileObject->next();
        $this->assertEquals('Line02', $fileObject->current());
        $fileObject->next();
        $this->assertEquals('Line03', $fileObject->current());
        $fileObject->rewind();
        $this->assertEquals('Line01', $fileObject->current());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testIsFirst()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertTrue($fileObject->isFirst());
        $this->assertEquals('Line01', $fileObject->firstLine());
        $this->assertEquals('Line02', $fileObject->nextLine());
        $this->assertEquals('Line03', $fileObject->nextLine());
        $this->assertFalse($fileObject->isFirst());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testReadLine()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertEquals('Line04', $fileObject->readLine(3));

        $fileObject = new \SplFileObject($filePath, 'r');
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        $fileObject->seek(3);   // seek line
        $this->assertEquals('Line04', $fileObject->current());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testReadInvalidLine()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertFalse($fileObject->readLine(20));

        $fileObject = new \SplFileObject($filePath, 'r');
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        $fileObject->seek(20);   // seek line
        $this->assertFalse($fileObject->current());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testIsLast()
    {
        $filePath = __DIR__ . '/example_read.txt';

        $fileObject = new FileReader($filePath, 'r', true);
        $this->assertEquals('Line08', $fileObject->readLine(7));
        $this->assertFalse($fileObject->isLast());
        $this->assertEquals('Line09', $fileObject->nextLine());
        $this->assertEquals('', $fileObject->nextLine());
        $this->assertTrue($fileObject->isLast());

        $fileObject = new \SplFileObject($filePath, 'r');
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::READ_AHEAD| \SplFileObject::SKIP_EMPTY);
        $fileObject->seek(7);
        $this->assertEquals('Line08', $fileObject->current());
        $this->assertFalse($fileObject->eof());
        $fileObject->next();
        $this->assertEquals('Line09', $fileObject->current());
        $fileObject->next();
        $this->assertEquals('', $fileObject->current());
        $this->assertTrue($fileObject->eof());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testRead()
    {
        $filePath = __DIR__ . '/example_read.txt';
        $fileObject = new FileReader($filePath, 'r', true);
        $expectedString = 'Line01' . PHP_EOL
                        . 'Line02' . PHP_EOL
                        . 'Line03' . PHP_EOL
                        . 'Line04' . PHP_EOL
                        . 'Line05' . PHP_EOL
                        . 'Line06' . PHP_EOL
                        . 'Line07' . PHP_EOL
                        . 'Line08' . PHP_EOL
                        . 'Line09' . PHP_EOL;
        $this->assertEquals($expectedString, $fileObject->read());
    }

    /**
     * @depends     testInit
     * @return      void
     */
    public function testReadLines()
    {
        $filePath = __DIR__ . '/example_read.txt';
        $fileObject = new FileReader($filePath, 'r', true);
        $expectedArray = array('Line01', 'Line02', 'Line03', 'Line04', 'Line05', 'Line06', 'Line07', 'Line08', 'Line09');
        $this->assertEquals($expectedArray, $fileObject->readLines());
    }
}