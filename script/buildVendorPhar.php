<?php 
//set phar is writeable
//x ini_set("phar.readonly", 0);

//Run this script to build Hx phar
$rootDir = dirname(dirname(__FILE__));

$outputPath = $rootDir . DIRECTORY_SEPARATOR . 'build' .DIRECTORY_SEPARATOR . 'vendor.phar';

$sourcePath = $rootDir . DIRECTORY_SEPARATOR .'vendor' . DIRECTORY_SEPARATOR;

//remove old phar file
if(file_exists($outputPath))
	unlink($outputPath);

//remove old phar GZ file
if(file_exists($outputPath . '.gz'))
	unlink($outputPath . '.gz');

//rebuild phar file
$phar = new \Phar($outputPath);

$phar->setDefaultStub('include.php');

$phar->buildFromDirectory($sourcePath);

$phar->compress(Phar::GZ);

//external library is excluded from  phar file, developer need to explicitly includ it accordingly
?>