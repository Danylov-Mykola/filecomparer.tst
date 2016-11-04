<?php
/**
 * This file is a part of filecomparer.tst project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 02.11.2016
 * Time: 19:07
 */

namespace MykolaDanylov\FileComparer;

class Main
{
    const READ_BLOCK_SIZE = 4096;

    const DIFFERENT_0 = 0;
    const DIFFERENT_1 = 1;
    const DIFFERENT_2 = 2;
    const SAME_CONTENT = 100;

    private $readBlockSize = self::READ_BLOCK_SIZE;

    private $folderPath = null;
    private $ckFileSize = true;
    private $ckFullFileMd5 = false;
    private $firstBlockCompare = true;
    private $fullContentCompare = true;

    private $resultArray = [];
    private $resultArrayItemId = 0;

    private $objectsList = null;

    private static $static;


    /**
     * @param $folderPath - path to folder where we need to scan files
     */
    public function __construct($folderPath)
    {
        $this->initFolder($folderPath);
    }

    /**
     * Returns an object of this class.
     * Use this method to create functionality, if you need create only one object of functionality.
     * If you need several objects, use "new Main(..)" instead.
     * DO NOT use static AND dynamic initialization in your application at the same time! Use one of them only!
     * @param $folderPath - as it described in the constructor
     * @return Main
     */
    public static function getStatic($folderPath)
    {
        if(is_null(static::$static)){
            static::$static = new static($folderPath);
        }
        return static::$static;
    }

    /**
     * Returns result of scanning
     * @return array
     */
    public function getResultArray()
    {
        return $this->resultArray;
    }

    /**
     * @param string $folderPath - path in local filesystem where we need to looking for files
     * @return Main $this
     */
    private function initFolder($folderPath)
    {
        /*@todo: check if the param is string AND folder readability */
        $this->folderPath = $folderPath;
        return $this;
    }

    /**
     * @param array $params - array with certain keys. Parameters of wrong type will be ignored.
     * Available keys with types:
     *      $ckFileSize         => boolean;
     *      $ckPartialFileMd5   => boolean;
     *      $ckPartOfFileSize   => integer positive;
     *      $ckFullFileMd5      => boolean.
     * @return Main $this
     */
    public function setParams(array $params)
    {
        $ckFileSize = $this->ckFileSize;
        $readBlockSize= $this->readBlockSize;
        $fullContentCompare = $this->fullContentCompare;
        $firstBlockCompare = $this->firstBlockCompare;
        $ckFullFileMd5 = $this->ckFullFileMd5;

        extract($params, EXTR_IF_EXISTS);

        if(is_bool($ckFileSize)){
            $this->ckFileSize = $ckFileSize;
        }
        if(is_integer($readBlockSize && $readBlockSize > 0)){
            $this->readBlockSize = $readBlockSize;
        }
        if(is_bool($firstBlockCompare)){
            $this->firstBlockCompare = $firstBlockCompare;
        }
        if(is_bool($fullContentCompare)){
            $this->fullContentCompare = $fullContentCompare;
        }
        if(is_bool($ckFullFileMd5)){
            $this->ckFullFileMd5 = $ckFullFileMd5;
        }
        return $this;
    }

    /**
     * Make list of files from start folder, recursively
     */
    private function collectFiles()
    {
        $dirtyFilesList = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->folderPath));
        $this->objectsList = new ObjectsList();
        foreach($dirtyFilesList as $filePath){
            if(is_file($filePath)) {
                $piece = new File($filePath);
                $this->objectsList->addElement($piece);
            }
        }
    }

    /**
     * Comparing files with each other
     */
    public function compareFiles()
    {
        $this->collectFiles();
        $iterator = new Iterator();
        $iterator->loadList($this->objectsList);
//        $firstCursorId = 0; // it already is by default
        $secondCursorId = $iterator->addCursor();
        /** @var File $file1 */
        $file1 = null;
        $this->resultArrayItemId = -1;
        while(false !== ($firstCursorPosition = $iterator->current($file1))){
            $iterator->setCursorPosition($firstCursorPosition + 1, $secondCursorId);
            /** @var File $file2 */
            $file2 = null;
            while(false !== ($iterator->current($file2, $secondCursorId))){
                $this->resultArrayItemId++;
//                Uncomment next two lines to see comparing process (debug)
//                echo $file1->getFullFilePath() . " ... ";
//                echo $file2->getFullFilePath() . "\n";
                $this->resultArray[$this->resultArrayItemId]['files']
                    = $file1->getFullFilePath() . " ... "
                    . $file2->getFullFilePath();
                if($this->ckFileSize
                   && $file1->getFileSize() !== $file2->getFileSize())
                {
                    $this->resultArray[$this->resultArrayItemId]['result']
                        = 'Different 1 (by size).';
                    $this->resultArray[$this->resultArrayItemId]['code'] = self::DIFFERENT_0;
                    continue;
                }
                if($this->firstBlockCompare &&
                    $file1->readPart($this->readBlockSize) !== $file2->readPart($this->readBlockSize))
                {
                    $this->resultArray[$this->resultArrayItemId]['result']
                        = 'Different 2 (by first part of contents).';
                    $this->resultArray[$this->resultArrayItemId]['code'] = self::DIFFERENT_1;
                    continue;
                }
                if($this->fullContentCompare) {
                    do {
                        $partOfFile1 = $file1->readPart($this->readBlockSize);
                        $partOfFile2 = $file2->readPart($this->readBlockSize);
                        if ($partOfFile1 !== $partOfFile2) {
                            $this->resultArray[$this->resultArrayItemId]['result']
                                = 'Different (by contents).';
                            $this->resultArray[$this->resultArrayItemId]['code'] = self::DIFFERENT_2;
                            continue 2;
                        }
                    } while ($partOfFile1);
                }
                if($this->ckFullFileMd5){
                    if ($file1->getMd5File() !== $file2->getMd5File()) {
                        $this->resultArray[$this->resultArrayItemId]['result']
                            = 'Different (by md5).';
                        $this->resultArray[$this->resultArrayItemId]['code'] = self::DIFFERENT_2;
                        continue;
                    }
                }
                $this->resultArray[$this->resultArrayItemId]['result']
                    = ' WARNING! Copy of file detected!';
                $this->resultArray[$this->resultArrayItemId]['code'] = self::SAME_CONTENT;
                $file2->setMark();
            }
        }
        return $this;
    }
}