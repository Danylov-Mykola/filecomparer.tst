# filecomparer.tst
Composer package for finding same files in a certain folder recursively.
You can add it to your own app or test it as is.
--
  Project includes example runable file 'example.php' which
uses example folder 'example_files'.

#  How to add functionality to you own project:
  - go into dir where your project is;
  - run: "composer require mykola-danylov/filecomparer"
  - you can run example using console:
    ->  php vendor/mykola-danylov/filecomparer/example.php

#  The simplest way to test the functionality of the package:
  - use console under you computer with PHP and the composer installed;
  - go into a directory where you want to create this project;
  - run 'composer create-project  mykola-danylov/filecomparer';
  - run 'php filecomparer/example.php' to see output of the example.
  
  After that you could modify example.php by your own way.
There are several algorithms available during comparing files.
All of them you could set with setParams() method (see example.php)
