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

/**
 * File Filter Interface
 *
 * @abstract
 * @package    File
 * @author     Sven Sanzenbacher
 */
interface FileFilterInterface
{
    /**
     * filter iterator element
     *
     * @return      bool
     */
    public function accept();
}