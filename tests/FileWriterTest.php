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

use Naucon\File\FileWriter;
use Naucon\File\Exception\FileWriterException;

class FileWriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return      void
     */
    public function testInit()
    {
        $filePath = __DIR__ . '/example_write1.txt';
        $fileObject = new FileWriter($filePath);
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->isWritable());

        $filePath = __DIR__ . '/example_write2.txt';
        $fileObject = new \SplFileObject($filePath, 'r+');
        $this->assertEquals($filePath, $fileObject->getPathname());
        $this->assertTrue($fileObject->isWritable());
    }

    /**
     * @return      array
     */
    public function testWrite()
    {
        $string = 'Line01'.PHP_EOL;
        $string.= 'Line02'.PHP_EOL;
        $string.= 'Line03'.PHP_EOL;
        $string.= 'Line04'.PHP_EOL;


        $filePath = __DIR__ . '/example_write1.txt';
        $fileObject1 = new FileWriter($filePath,'w+');
        $fileObject1->write($string);

        $filePath = __DIR__ . '/example_write2.txt';
        $fileObject2 = new \SplFileObject($filePath, 'w+');
        $fileObject2->fwrite($string);

        return $fileObject1;
    }

    /**
     * @depends     testWrite
     * @param       $fileObject1
     * @return      void
     */
    public function testIterate($fileObject1)
    {
        $lines = array(
            'Line01', 'Line02', 'Line03', 'Line04', ''
        );


        $i = 0;
        foreach($fileObject1 as $line) {
            $this->assertContains($line, $lines);
            $i++;
        }
        $this->assertEquals(5, $i);
    }

    /**
     * @return      void
     */
    public function testWriteLine()
    {
        $filePath = __DIR__ . '/example_write1.txt';
        $fileObject = new FileWriter($filePath,'a+');
        $fileObject->writeLine("foo");
        $fileObject->writeLine("bar");

        $fileObject->writeLine(" some string " . PHP_EOL . "with line breaks \n\r".PHP_EOL);

        $filePath = __DIR__ . '/example_write2.txt';
        $fileObject = new \SplFileObject($filePath, 'a+');
        $fileObject->fwrite("foo" . PHP_EOL);
        $fileObject->fwrite("bar" . PHP_EOL);
    }

    /**
     * @return      void
     */
    public function testTruncates()
    {
        $filePath = __DIR__ . '/example_write1.txt';
        $fileObject = new FileWriter($filePath,'a+');
        $fileObject->truncates(13);

        $filePath = __DIR__ . '/example_write2.txt';
        $fileObject = new \SplFileObject($filePath, 'a+');
        $fileObject->ftruncate(13);
    }

    /**
     * @return      void
     */
    public function testClear()
    {
        $filePath = __DIR__ . '/example_write1.txt';
        $fileObject = new FileWriter($filePath,'a+');
        $fileObject->clear();

        $filePath = __DIR__ . '/example_write2.txt';
        $fileObject = new \SplFileObject($filePath, 'a+');
        $fileObject->ftruncate(0);
    }
}