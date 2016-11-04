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
     * @author Mykola Danylov (n.danylov@gmail.com)
     */
    public function setMark()
    {
        $this->mark = true;
    }



    public function __construct($fullFilePath)
    {
        if(!is_readable($fullFilePath)){
            throw new \Exception('File not readable');
        }
        $this->fullFilePath = $fullFilePath;
        $this->fileSize = filesize($fullFilePath);
    }

    public function __destruct()
    {
        $this->closeHandler();
    }

    public function readPart($bytesCount = 1)
    {
        if(is_null($this->fileHandler)) {
            $this->fileHandler = fopen($this->fullFilePath, 'rb');
        }
        $readPart = fread($this->fileHandler, $bytesCount);
        return $readPart;
    }

    public function closeHandler()
    {
        if(!is_null($this->fileHandler)) {
            fclose($this->fileHandler);
        }
    }

    /**
     * @return null
     */
    public function getFullFilePath()
    {
        return $this->fullFilePath;
    }

    /**
     * @return int|null
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function getMd5File()
    {
        if(is_null($this->md5)){
            $this->md5 = md5_file($this->getFullFilePath());
        }
        return $this->md5;
    }
}