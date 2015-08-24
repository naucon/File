<?php
$filePath = __DIR__ . '/example_read.txt';

use Naucon\File\FileReader;
$fileObject = new FileReader($filePath, 'r', true);

echo 'File: ' . $fileObject->getPathname();
echo '<br/>';
echo '<br/>';

echo 'Iterate lines:<br/>';
// iterate
foreach($fileObject as $line) {
    echo $line . '<br/>';
}

echo '<br/>';
echo '<br/>';

// while
echo 'While:<br/>';
echo $fileObject->firstLine();
echo '<br/>';
while ( !$fileObject->isLast() ){
    echo $fileObject->nextLine();
    echo '<br/>';
}

echo '<br/>';
echo '<br/>';

echo 'Navigate:<br/>';
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

echo '<br/>';
echo '<br/>';

// read all
echo 'File Content:<br/>';
echo nl2br($fileObject->read());
echo '<br/>';

echo '<br/>';
echo '<br/>';

echo 'Array of lines:<br/>';
$lines = $fileObject->readLines();   // return array
foreach ($lines as $line) {
    echo $line . '<br/>';
}