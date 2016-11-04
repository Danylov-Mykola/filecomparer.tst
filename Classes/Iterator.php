<?php
/**
 * This file is a part of filecomparer.tst project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 03.11.2016
 * Time: 15:03
 */

namespace MykolaDanylov\FileComparer;

use MykolaDanylov\FileComparer\PieceInterface as PieceInterface;


class Iterator
{
    /** @var ObjectsList  */
    private $list;
    /** @var array of int. The iterator cah has any number of cursors,
     * and returns value from the $list by any of that cursors */
    private $cursors = [0];

    /**
     * @return int
     */
    /**
     * Returns position of certain cursor
     * @param int $cursorId - desired cursor id
     * @return int - position of cursor over the $list
     */
    public function getCursorPosition($cursorId = 0)
    {
        return $this->cursors[$cursorId];
    }

    /**
     * Create another cursor over list
     * @return int - new cursor index
     */
    public function addCursor()
    {
        $this->cursors[] = 0;
        return count($this->cursors) - 1;
    }

    /**
     * Set cursor position manually
     * @param $cursorPosition
     * @param int $cursorId
     * @return Iterator $this
     */
    public function setCursorPosition($cursorPosition, $cursorId = 0)
    {
        $this->cursors[$cursorId] = $cursorPosition;
        return $this;
    }

    /**
     * @author Mykola Danylov (n.danylov@gmail.com)
     * @return Iterator $this
     */
    protected function rewindAllCursorsPositions()
    {
        $this->cursors = array_fill(0, count($this->cursors), 0);
        return $this;
    }

    /**
     * Reset all cursors to 0 position
     * @param ObjectsList $list
     * @return Iterator $this
     */
    public function loadList(ObjectsList $list)
    {
        $this->list = $list;
        $this->rewindAllCursorsPositions();
        return $this;
    }

    /**
     * Returns cursors position/status and current pointer to &element
     * @param $element - the variable where function will returns current element
     * @param int $cursorId - which cursor we need use to get element from the list
     * @return bool|int - returns cursor position, or false if element could not reached
     */
    public function current(&$element, $cursorId = 0)
    {
        do {
            /** @var PieceInterface $listElement */
            $listElement = $this->list->getElementByKey($this->cursors[$cursorId]);
            if (!is_null($listElement)) {
                $result = $this->cursors[$cursorId]++;
            } else {
                $result = false; //end of list
            }
        } while($result && $listElement->isMarked());
        $element = $listElement;

        return $result;
    }
}