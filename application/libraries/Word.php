<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'/libraries/PhpWord/Autoloader.php';
require_once APPPATH.'/libraries/PhpWord/IOFactory.php';
use PhpOffice\PhpWord\Autoloader as Autoloader;
Autoloader::register();
class Word extends Autoloader {
    
}
?>