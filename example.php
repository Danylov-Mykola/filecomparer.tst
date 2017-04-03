<?php
/**
 * This file is a part of filecomparer.package project.
 * Author: Mykola Danylov (n.danylov@gmail.com)
 * Date: 21.10.2016
 * Time: 15:57
 */

use \MykolaDanylov\FileComparer\Main as FileComparer;

if (php_sapi_name() != "cli") {
    echo "<pre>";
    echo "Usage this example file in console mode is preferable.\n";
}
echo "Starting with \"filecomparer\" functionality...\n";

require_once "vendor/autoload.php";

$microtime = microtime(true);
$result =
    FileComparer::getStatic(dirname(__FILE__) . '/example_files') // Set the root folder name where files have been present.
    ->setParams([
        'ckFileSize' => true, // Compare files by size
        'readBlockSize' => 16386, // If files need compare by content, files will be read block-by-block
        'firstBlockCompare' => false, // Compare by content, block-by-block. It overrides fullContentCompare mode.
        'fullContentCompare' => false, // Compare by content using reading files content
        'ckFullFileMd5' => true, // Check files similarity by php native md5_file() function
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


