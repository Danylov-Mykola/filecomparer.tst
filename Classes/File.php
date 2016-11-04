<?php
/**
 * This file is a part of filecomparer.tst project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 03.11.2016
 * Time: 13:09
 */

namespace MykolaDanylov\FileComparer;

/**
 * Class File
 * A object for a piece of list. In case there is a local file.
 * @package MykolaDanylov\FileComparer
 */
class File implements PieceInterface
{
    private $fullFilePath = null;
    private $fileSize = null;
    private $mark = false;
    private $fileHandler = null;
    private $md5 = null;

    /**
     * returns true if the file marked and false elsewhere
     * @return boolean
     */
    public function isMarked()
    {
        return $this->mark;
    }

    /**
     * Set the mark to object (file)
     */
    public function setMark()
    {
        $this->mark = true;
    }

    /**
     * @param $fullFilePath - path to a file
     * @throws \Exception
     */
    public function __construct($fullFilePath)
    {
        if(!is_readable($fullFilePath)){
            throw new \Exception('File not readable');
        }
        $this->fullFilePath = $fullFilePath;
        $this->fileSize = filesize($fullFilePath);
    }

    /**
     * We have to close file handler manually if it has been created
     */
    public function __destruct()
    {
        $this->closeHandler();
    }

    /**
     * See description in PieceInterface
     * @param int $bytesCount
     * @return string
     */
    public function readPart($bytesCount = 1)
    {
        if(is_null($this->fileHandler)) {
            $this->fileHandler = fopen($this->fullFilePath, 'rb');
        }
        $readPart = fread($this->fileHandler, $bytesCount);
        return $readPart;
    }

    /**
     * Close file
     */
    public function closeHandler()
    {
        if(!is_null($this->fileHandler)) {
            fclose($this->fileHandler);
        }
    }

    /**
     * Returns file path
     * @return string
     */
    public function getFullFilePath()
    {
        return $this->fullFilePath;
    }

    /**
     * Returns file size or null (if file has not been initialized)
     * @return int|null
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Returns md5 string of file or null (see md5_file function description)
     * @return null|string
     */
    public function getMd5File()
    {
        if(is_null($this->md5)){
            $this->md5 = md5_file($this->getFullFilePath());
        }
        return $this->md5;
    }
}