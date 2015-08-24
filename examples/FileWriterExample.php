<?php
$filePath = __DIR__ . '/example_write.txt';

use Naucon\File\FileWriter;
$fileObject = new FileWriter($filePath,'w+');   // file point at the beginning of the file, truncate existing content

echo 'File: ' . $fileObject->getPathname();
echo '<br/>';
echo '<br/>';

$string = 'Line01'.PHP_EOL;
$string.= 'Line02'.PHP_EOL;
$string.= 'Line03'.PHP_EOL;
$string.= 'Line04'.PHP_EOL;

$fileObject->write($string);

foreach($fileObject as $line) {
    echo $line . '<br/>';
}
echo '<br/>';

//Line01
//Line02
//Line03
//Line04


$filePath = __DIR__ . '/example_write.txt';
$fileObject = new FileWriter($filePath,'a+');   // file point at the end of the file
$fileObject->writeLine("foo");
$fileObject->writeLine("bar");

foreach($fileObject as $line) {
    echo $line . '<br/>';
}
echo '<br/>';

//Line01
//Line02
//Line03
//Line04
//foo
//bar

$fileObject->clear();   // remove all content
