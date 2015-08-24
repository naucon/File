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

use Naucon\File\FileFilterInterface;
use Naucon\File\Exception\FileFilterException;

/**
 * File Type Filter Class
 *
 * @package    File
 * @author     Sven Sanzenbacher
 */
class FileFilterType extends \FilterIterator implements FileFilterInterface
{
    /**
     * define file type for files
     */
    const TYPE_FILE = 'file';

    /**
     * define file type for directories
     */
    const TYPE_DIR = 'dir';

    /**
     * @var         string                  file type
     */
    protected $type = null;


    /**
     * Constructor
     *
     * @param       \Iterator               iterator
     * @param       string                  file type
     */
    public function __construct(\Iterator $iterator, $type)
    {
        switch ((string)$type)
        {
            case self::TYPE_DIR:
            case self::TYPE_FILE:
                $this->type = (string)$type;
                break;
            default:
                throw new FileFilterException('File type filter failed because given file type is unkown.');
        }
        parent::__construct($iterator);
    }

    /**
     * @return      string                  file type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * filter iterator element
     *
     * @return      bool
     */
    public function accept()
    {
        /**
         * @var     \SplFileInfo        $file
         */
        $file = $this->current();
        if ($file->getType()==$this->getType()) {
            return true;
        }
        return false;
    }
}