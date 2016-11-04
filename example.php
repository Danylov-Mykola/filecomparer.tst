<?php
/**
 * This file is a part of filecomparer.tst project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 21.10.2016
 * Time: 15:57
 */
require_once "vendor/autoload.php";

use \MykolaDanylov\FileComparer\Main as FileComparer;

$microtime = microtime(true);
$result =
    FileComparer::getStatic('files')
    ->setParams([
        'ckFileSize' => true,
        'readBlockSize' => 16386,
        'firstBlockCompare' => false,
        'fullContentCompare' => false,
        'ckFullFileMd5' => true,
    ])
    ->compareFiles()
    ->getResultArray();
$microtime = microtime(true) - $microtime;
//var_dump($result);

echo "----------" . "\n";
foreach($result as $line){
    echo "| ";
        echo $line['result'] . "\n";
//        echo " |" ;
//        echo $line['files'];
    if($line['code'] === FileComparer::SAME_CONTENT) {
        echo '        -->>  ' . $line['files'];
        echo "\n";
    }
}
echo "-----------" . "\n";
echo "TIME: " . $microtime . "\n";


