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

use Naucon\File\File;
use Naucon\File\FileInterface;
use Naucon\File\FileInfoInterface;
use Naucon\File\FileFilterType;
use Naucon\File\Exception\FileException;

/**
 * Abstract File Class
 * provides a utility to access and change the files and directories on the filesystem.
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
abstract class FileAbstract extends \SplFileInfo implements FileInterface, FileInfoInterface
{
    /**
     * define path separator
     */
    const PATH_SEPARATOR = '/';


    /**
     * @var         int                     default file permission
     */
    protected $defaul_permission = 0777;


    /**
     * Constructor
     *
     * @param       string                  relative or absolut pathname
     */
    public function __construct($pathname)
    {
        if (is_string($pathname) && !empty($pathname)) {
            if (DIRECTORY_SEPARATOR === '/') {
                $pathname = str_replace('\\', '/', $pathname);
            }

            parent::__construct($pathname);
        } elseif ($pathname instanceof \SplFileInfo) {
            parent::__construct($pathname->getPathname());
        } else {
            throw new FileException('No pathname was ' . __CLASS__ . ' given.', E_ERROR);
        }

        $this->setInfoClass('Naucon\File\File');
    }


    /**
     * return a basename without extension
     *
     * @return      string
     */
    public function getName()
    {
        return $this->getBasename('.' . $this->getExtension());
    }

    /**
     * return if file path is absolute
     *
     * @return      bool
     */
    public function isAbsolute()
    {
        $filepath = $this->getPathname();
        if (isset($filepath[0]) && $filepath[0] == self::PATH_SEPARATOR) {
            return true;
        }
        return false;
    }

    /**
     * return absolute file path
     *
     * @return      string
     */
    public function getAbsolutePath()
    {
        if (!$this->isAbsolute()) {
            $filepath = stream_resolve_include_path($this->getPathname());
        } else {
            $filepath = realpath($this->getPathname());
        }
        return $filepath;
    }

    /**
     * return a instance of FileInterface of the parent directory or null
     *
     * @return      File
     */
    public function getParent()
    {
        return $this->getPathInfo();
    }

    /**
     * return if fil e path is a directory
     *
     * @return      bool
     * @see         FileInfoInterface::isDir()
     */
    public function isDirectory()
    {
        return $this->isDir();
    }

    /**
     * returns if the file or directory of the current file path exists
     *
     * @return      bool
     */
    public function exists()
    {
        $return = false;
        if ($this->isFile()
            || $this->isDir()
        ) {
            $return = true;
        }
        return $return;
    }

    /**
     * return if file or directory is hidden, for example .htaccess, .svn
     *
     * @return      bool
     */
    public function isHidden()
    {
        $basename = $this->getBasename();

        if (isset($basename[0]) && $basename[0] == '.') {
            return true;
        }
        return false;
    }

    /**
     * return a time of when last modified
     *
     * @return      \DateTime             date time object
     */
    public function lastModified()
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($this->getMTime());
        return $dateTime;
    }

    /**
     * return a time of when last accessed
     *
     * @return      \DateTime             date time object
     */
    public function lastAccessed()
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($this->getATime());
        return $dateTime;
    }

    /**
     * return a time of when last accessed
     *
     * @return      \DateTime             date time object
     */
    public function lastChanged()
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($this->getCTime());
        return $dateTime;
    }

    /**
     * touch set access and modification time to file
     *
     * @param       int         optional unix timestamp of modification time, default is current time
     * @param       int         optional unix timestamp of access time. default is the modification time
     * @return      bool
     */
    public function touch($modificationTime=null, $accessTime=null)
    {
        if ($this->isFile()) {
            if (is_null($modificationTime)) {
                $modificationTime = time();
            }

            if (is_null($accessTime)) {
                $accessTime = $modificationTime;
            }

            return touch($this->getPathname(), (int)$modificationTime, (int)$accessTime);
        } else {
            throw new FileException('File can not be touched, because it is no file or do not exist.', E_NOTICE);
        }
    }

    /**
     * create a empty file, named by the pathname
     *
     * @param       int                 optional file permission
     * @param       bool                return true if the file was successfully created
     */
    public function createNewFile($mode=null)
    {
        if ($this->isAbsolute()) {
            if (is_null($mode)) {
                $mode = $this->defaul_permission;
            }

            if ($resource = fopen($this->getPathname(), 'x')) {
                fclose($resource);
                $this->chmod($mode);
                return true;
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * create a directory (but not the parent directories)
     *
     * @param       int                 optional file permission
     * @return      bool
     */
    public function mkdir($mode=null)
    {
        if ($this->isAbsolute()) {
            if (is_null($mode)) {
                $mode = $this->defaul_permission;
            }

            if (mkdir($this->getPathname())) {
                $this->chmod($mode);
                return true;
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * create a directory recursive (with parent directories)
     *
     * @return      bool
     */
    public function mkdirs($mode=null)
    {
        if ($this->isAbsolute()) {
            if (is_null($mode)) {
                $mode = $this->defaul_permission;
            }

            if (mkdir($this->getPathname(), 0777, true)) {
                $this->chmod($mode);
                return true;
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * delete the file or empty directory of the current file path
     *
     * @return      bool            true if file or directory was deleted, false if file or directory was not found
     */
    public function delete()
    {
        if ($this->isAbsolute()) {
            if ($this->isFile() || $this->isLink()) {
                // delete file and symlink
                return unlink($this->getPathname());
            } elseif ($this->isDir()) {
                // delete directory
                return rmdir($this->getPathname());
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * delete the file or directory with its content of the current file path
     *
     * @return      bool            true if file or directory was deleted, false if file or directory was not found
     */
    public function deleteAll()
    {
        if ($this->isAbsolute()) {
            if ($this->isFile() || $this->isLink()) {
                // delete file and symlink
                return unlink($this->getPathname());
            } elseif ($this->isDir()) {
                // delete directories and its content
                return $this->deleteAction($this->getPathname(), true);
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * @access      protected
     * @param       string          pathname
     * @param       bool            delete files recursive
     * @return      bool
     */
    protected function deleteAction($pathname, $recursive=false)
    {
        if ($recursive) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pathname, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        } else {
            $files = new \FilesystemIterator($pathname, \FilesystemIterator::SKIP_DOTS);
        }

        foreach ($files as $file) {
            if ($file->isDir()) {
                if (!rmdir($file->getRealPath())) {
                    return false;
                }
            } else {
                if (!unlink($file->getRealPath())) {
                    return false;
                }
            }
        }
        if (!rmdir($pathname)) {
            return false;
        }
        return true;
    }

    /**
     * delete files of the current directory
     *
     * @return      bool            true if files were deleted
     */
    public function deleteFiles()
    {
        if ($this->isAbsolute()) {
            if ($this->isDir()) {
                // delete files in the current directory
                return $this->deleteFilesAction($this->getPathname(), false);
            } else {
                throw new FileException('Given path is a file and not a directory.');
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * delete files of the current directory recursive
     *
     * @return      bool            true if files were deleted
     */
    public function deleteAllFiles()
    {
        if ($this->isAbsolute()) {
            if ($this->isDir()) {
                // delete files in the current directory and it's subdirectories
                return $this->deleteFilesAction($this->getPathname(), true);
            } else {
                throw new FileException('Given path is a file and not a directory.');
            }
            return false;
        } else {
            throw new FileException('Given file path is not a absolute path.');
        }
    }

    /**
     * @access      protected
     * @param       string          pathname
     * @param       bool            delete files recursive
     * @return      bool
     */
    public function deleteFilesAction($pathname, $recursive=false)
    {
        if ($recursive) {
            $files = new FileFilterType(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pathname, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST),FileFilterType::TYPE_FILE);
        } else {
            $files = new FileFilterType(new \FilesystemIterator($pathname, \FilesystemIterator::SKIP_DOTS),FileFilterType::TYPE_FILE);
        }

        foreach ($files as $file) {
            if (!unlink($file->getRealPath())) {
                return false;
            }
        }
        return true;
    }

    /**
     * rename file
     *
     * @param       string                  directory or file name with extension
     * @return      bool
     */
    public function rename($pathname)
    {
        if (!empty($pathname)) {
            if ($this->exists()) {
                $targetPathname = $this->getPath() . self::PATH_SEPARATOR . (string)$pathname;
                if (rename($this->getPathname(), $targetPathname)) {
                    parent::__construct($targetPathname);
                    return true;
                }
            } else {
                throw new FileException('Rename failed, file or directory do not exist.');
            }
        } else {
            throw new FileException('Rename failed, because given filename is empty.');
        }
        return false;
    }

    /**
     * move file to a given directory
     *
     * @param       FileInterface|string
     * @return      bool
     */
    public function move($pathname)
    {
        if (!empty($pathname)) {
            if ($pathname instanceof FileInterface) {
                $targetFile = $pathname;
            } else {
                $targetFile = new File($pathname);
            }

            if ($this->exists()) {
                if ($targetFile->isDir()) {
                    $targetPathname = $targetFile->getPathname() . self::PATH_SEPARATOR . $this->getBasename();
                    if (rename($this->getPathname(), $targetPathname)) {
                        parent::__construct($targetPathname);
                        return true;
                    }
                } else {
                    throw new FileException('Move failed, target directory do not exist.');
                }
            } else {
                throw new FileException('Move failed, source file or directory do not exist.');
            }
        } else {
            throw new FileException('Move failed, because given filepath is empty.');
        }
        return false;
    }

    /**
     * copy file to a given directory
     *
     * @param       FileInterface|string
     * @return      bool
     */
    public function copy($pathname)
    {
        if (!empty($pathname)) {
            if ($pathname instanceof FileInterface) {
                $targetFile = $pathname;
            } else {
                $targetFile = new File($pathname);
            }

            if ($targetFile->isDir()) {
                $targetPathname = $targetFile->getPathname() . self::PATH_SEPARATOR . $this->getBasename();
                if ($this->isFile()) {
                    if (copy($this->getPathname(), $targetPathname)) {
                        parent::__construct($targetPathname);
                        return true;
                    }
                } elseif ($this->isDir()) {
                    if ($this->copyAction($this->getPathname(), $targetPathname, true)) {
                        parent::__construct($targetPathname);
                        return true;
                    }
                } else {
                    throw new FileException('Copy failed, source file or directory do not exist.');
                }
            } else {
                throw new FileException('Copy failed, target directory do not exist.');
            }
        } else {
            throw new FileException('Copy failed, because given filepath is empty.');
        }
        return false;
    }

    /**
     * @access      protected
     * @param       string          source file path
     * @param       string          target file path
     * @param       bool            copy files recursive
     * @return      bool
     */
    protected function copyAction($sourcePathname, $targetPathname, $recursive=false)
    {
        if (!empty($sourcePathname) && !empty($targetPathname)) {
            if (is_file($sourcePathname)) {
                if (!copy($sourcePathname, $targetPathname)) {
                    return false;
                }
            } elseif (is_dir($sourcePathname)) {
                if (!is_dir($targetPathname)) {
                    if (!mkdir($targetPathname, 0777, true)) {
                        return false;
                    }
                }

                $sourceSubPaths = new \FilesystemIterator($sourcePathname, \FilesystemIterator::SKIP_DOTS);
                foreach ($sourceSubPaths as $sourceSubBasename) {
                    $sourceSubFilePath = $sourcePathname . self::PATH_SEPARATOR . $sourceSubBasename;
                    $targetSubFilePath = $targetPathname . self::PATH_SEPARATOR . $sourceSubBasename;
                    if (!$this->copyAction($sourceSubFilePath, $targetSubFilePath, $recursive)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * return user name of the file or directory
     *
     * @return      string         user name of file owner
     */
    public function getOwnerName()
    {
        $userId = $this->getOwner();
        if ($userId) {
            $userData = posix_getpwuid($userId);
            return ((isset($userData['name'])) ? $userData['name'] : null);
        }
        return false;
    }

    /**
     * return user group name of the file or directory
     *
     * @return      string         user group name of file group
     */
    public function getGroupName()
    {
        $userId = $this->getGroup();
        if ($userId) {
            $userData = posix_getgrgid($userId);
            return ((isset($userData['name'])) ? $userData['name'] : null);
        }
        return false;
    }

    /**
     * return permission of file or directory
     *
     * @return      int         file permission
     */
    public function getPermission()
    {
        return substr(decoct($this->getPerms()), 2);
    }

    /**
     * @param       mixed       user group name or id
     * @return      bool
     */
    public function chgrp($userGroup)
    {
        if ($this->exists()
            && (is_string($userGroup) || is_int($userGroup))
        ) {
            if (!empty($userGroup)) {
                return chgrp($this->getPathname(), $userGroup);
            } else {
                throw new FileException('Chgrp failed, because given usergroup is empty.');
            }
        }
        return false;
    }

    /**
     * change owner
     *
     * @param       mixed       user name or id
     * @return      bool
     */
    public function chown($user)
    {
        if ($this->exists()
            && (is_string($user) || is_int($user))
        ) {
            if (!empty($user)) {
                return chown($this->getPathname(), $user);
            } else {
                throw new FileException('Chown failed, because given user is empty.');
            }
        }
        return false;
    }

    /**
     * change permission
     *
     * @param       int         file permission, default permission is 0777
     * @return      bool
     */
    public function chmod($fileMode)
    {
        if ($this->exists()) {
            // file mode must be from type octal. through converting octal to decimal and the other way around
            // we going sure that the given value is a octal. Any non octal number will be detected.
            if (decoct(octdec($fileMode)) != $fileMode) {
                throw new FileException('Chmod failed, because given permission is not from type octal.');
            }

            // convert a given octal string to a octal integer
            if (is_string($fileMode)) {
                $fileMode = intval($fileMode, 8);
            }

            switch ($fileMode) {
                case 0600: // file owner read and write;
                case 0640: // file owner read and write; owner group read
                case 0660: // file owner read and write; owner group read and write
                case 0604: // file owner read and write; everbody read
                case 0606: // file owner read and write; everbody read and write
                case 0664: // file owner read and write; owner group read and write; everbody read
                case 0666: // file owner read and write; owner group read and write; everbody read and write
                case 0700: // file owner read, execute and write;
                case 0740: // file owner read, execute and write; owner group read
                case 0760: // file owner read, execute and write; owner group read and write
                case 0770: // file owner read, execute and write; owner group read, execute and write
                case 0704: // file owner read, execute and write; everbody read
                case 0706: // file owner read, execute and write; everbody read and write
                case 0707: // file owner read, execute and write; everbody read, execute and write
                case 0744: // file owner read, execute and write; owner group read; everbody read
                case 0746: // file owner read, execute and write; owner group read; everbody read and write
                case 0747: // file owner read, execute and write; owner group read; everbody read, execute and write
                case 0754: // file owner read, execute and write; owner group read and execute; everbody read
                case 0755: // file owner read, execute and write; owner group read and execute; everbody read and execute
                case 0756: // file owner read, execute and write; owner group read and execute; everbody read and write
                case 0757: // file owner read, execute and write; owner group read and execute; everbody read, execute and write
                case 0764: // file owner read, execute and write; owner group read and write; everbody read
                case 0766: // file owner read, execute and write; owner group read and write; everbody read and write
                case 0767: // file owner read, execute and write; owner group read and write; everbody read, execute and write
                case 0774: // file owner read, execute and write; owner group read, execute and write; everbody read
                case 0775: // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
                case 0776: // file owner read, execute and write; owner group read, execute and write; everbody read and write
                case 0777: // file owner read, execute and write; owner group read, execute and write; everbody read, execute and write
                    break;
                default:
                    $fileMode = 0777;
            }

            return chmod($this->getPathname(), $fileMode);
        }
        return false;
    }

    /**
     * clear file status cache
     *
     * @return      void
     */
    public function flush()
    {
        clearstatcache();
    }

    /**
     * return file size in bytes
     *
     * @return      int                 filesize in bytes or -1 if it fails
     */
    public function getSize()
    {
        $filePath = $this->getPathname();
        $size = -1;
        $isWin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
        $execWorks = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
        if ($isWin) {
            if ($execWorks) {
                $cmd = "for %F in (\"$filePath\") do @echo %~zF";
                @exec($cmd, $output);
                if (is_array($output)) {
                    $result = trim(implode("\n", $output));
                    if (ctype_digit($result)) {
                        $size = $result;
                    }
                }
            }
            // try the Windows COM interface if its fails
            if (class_exists('COM') && $size > -1) {
                $fsobj = new COM('Scripting.FileSystemObject');
                $file = $fsobj->GetFile($filePath);
                $result = $file->Size;
                if (ctype_digit($result)) {
                    $size = $result;
                }
            }
        } else {
            $result = trim("stat -c%s $filePath");
            if (ctype_digit($result)) {
                $size = $result;
            }
        }
        if ($size < 0) {
            $size = filesize($filePath);
        }
        return $size;
    }

    /**
     * returns files and directories
     *
     * @return      \Iterator
     */
    public function listAll()
    {
        $iterator = new \FilesystemIterator($this->getAbsolutePath());
        $iterator->setInfoClass(get_class($this));
        return $iterator;
    }

    /**
     * returns files
     *
     * @return      \Iterator
     */
    public function listFiles()
    {
        $iterator = new FileFilterType(new \FilesystemIterator($this->getAbsolutePath()), 'file');
        $iterator->setInfoClass(get_class($this));
        return $iterator;
    }
}