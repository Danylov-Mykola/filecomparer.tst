<?php
/**
 * This file is a part of filecomparer.tst project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 03.11.2016
 * Time: 20:18
 */

namespace MykolaDanylov\FileComparer;

/**
 * Interface PieceInterface
 * You have ability to create list of pieces of any type, and use with the main functionality,
 * if the concrete piece is implements this Interface.
 * @package MykolaDanylov\FileComparer
 */
interface PieceInterface
{
    /**
     * Piece is the file or any resource, that can we read data from.
     * @param int $bytesCount - block size we read from piece
     * @return string - data has been read from piece
     */
    public function readPart($bytesCount = 1);

    /**
     * We need ability to set mark to a piece.
     * @return void
     */
    public function setMark();

    /**
     * It should returns false if a piece has not been marked. And returns true elsewhere.
     * @return bool
     */
    public function isMarked();
}