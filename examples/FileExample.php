<?php
use Naucon\File\File;

$examplePath = __DIR__ . '/example.txt';
$fileObject = new File($examplePath);

echo 'File ' . $fileObject->getPathname() . ' do' . (($fileObject->exists()) ? '' : ' not') . ' exist.';
echo '<br/>';
echo 'File is' . (($fileObject->isReadable()) ? '' : ' not') . ' readable.';
echo '<br/>';
echo 'File is' . (($fileObject->isWritable()) ? '' : ' not') . ' writeable.';
echo '<br/>';
echo '<br/>';

echo 'Filesize: ' . $fileObject->getSize() . ' bytes';
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


echo 'Create new directory';
echo '<br/>';

$newDirectoryPath = __DIR__ . '/tmp/target/move/';
$newDirectoryFileObject = new File($newDirectoryPath);
if (!$newDirectoryFileObject->isDir()) {
    $newDirectoryFileObject->mkdirs();
}

echo 'Copy file to target directory';
echo '<br/>';

$examplePath = __DIR__ . '/example.txt';
$exampleCopyPath = __DIR__ . '/tmp/target/';
$fileObject = new File($examplePath);
$fileObject->copy($exampleCopyPath);
$fileObject->rename('example_copy.txt');


echo 'Move copied file to a new directory';
echo '<br/>';

$exampleMovePath = __DIR__ . '/tmp/target/move/';
$fileObject->move($exampleMovePath);
$fileObject->rename('example_move.txt');

echo 'Moved File ' . $fileObject->getPathname() . ' do' . (($fileObject->exists()) ? '' : ' not') . ' exist.';
echo '<br/>';
echo 'File is' . (($fileObject->isReadable()) ? '' : ' not') . ' readable.';
echo '<br/>';
echo 'File is' . (($fileObject->isWritable()) ? '' : ' not') . ' writeable.';
echo '<br/>';
echo 'File permission: ' . $fileObject->getPermission();
echo '<br/>';
echo '<br/>';


echo 'Remove file';
echo '<br/>';
$deletePath = __DIR__ . '/tmp/example.txt';
$fileObject = new File($deletePath);
$fileObject->delete();

echo 'Remove directories';
echo '<br/>';
$deletePath = __DIR__ . '/tmp/target/';
$fileObject = new File($deletePath);
$fileObject->deleteAll();

$examplePath = __DIR__ . '/ExampleDir';
$fileObject = new File($examplePath);

echo '<br/>';

$iteratorObject = $fileObject->listAll();
echo 'Iterate * <strong>' . $fileObject->getBasename() . '</strong>';
echo '<br/>';

echo '<ul>';
echo '<li>' . $fileObject->getBasename();
    echo '<ul>';
    foreach ($iteratorObject as $subFileObject) {
        echo '<li>' . $subFileObject->getBasename() . '</li>';
        if ($subFileObject->isDir()) {
            echo '<ul>';
            foreach ($subFileObject->listAll() as $subChildFileObject) {
                echo '<li>' . $subChildFileObject->getBasename() . '</li>';
            }
            echo '</ul>';
        }
    }
    echo '</ul>';
echo '</li>';
echo '</ul>';

$iteratorObject = $fileObject->listFiles();
echo 'Iterate files <strong>' . $fileObject->getBasename() . '</strong>';
echo '<br/>';

echo '<ul>';
echo '<li>' . $fileObject->getBasename();
echo '<ul>';
foreach ($iteratorObject as $subFileObject) {
    echo '<li>' . $subFileObject->getBasename() . '</li>';
}
echo '</ul>';
echo '</li>';
echo '</ul>';
