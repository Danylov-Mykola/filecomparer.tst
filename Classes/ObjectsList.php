<?php
/**
 * This file is a part of filecomparer.package project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 03.11.2016
 * Time: 13:09
 */

namespace MykolaDanylov\FileComparer;

/**
 * Class ObjectsList
 * This is a simple list from array of any type.
 * But it can be used as example, if we will decided to do more complex one.
 * First of all, if we need OOP hierarchy of lists.
 * @package MykolaDanylov\FileComparer
 */
class ObjectsList
{
    private $list = [];

    /**
     * Add element to a list
     * @param PieceInterface $element
     * @return int - index (id) of new element
     */
    public function addElement(PieceInterface $element)
    {
        $this->list[] = $element;
        return count($this->list) - 1;
    }

    /**
     * @param $key - ID of record
     * @return mixed|null - element from the list. Null if an element not found.
     */
    public function getElementByKey($key)
    {
        if(!@is_null($this->list[$key])){
            return $this->list[$key];
        } else {
            return null;
        }
    }

    /**
     * Returns count of elements in a list
     * @return int
     */
    public function getCount()
    {
        return count($this->list);
    }
}