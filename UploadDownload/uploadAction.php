<?php
header("Content-type: text/html; charset=utf-8");
require_once 'Upload.class.php';

$upload = new Upload('myFile1');
$dest = $upload->uploadFile();
echo $dest;