<?php
header("Content-type: text/html; charset=utf-8");
require_once 'upload.func.php';

$files = getFiles();

foreach($files as $file) {
    $result = uploadMutipleFile($file);
    echo $result['msg'].'<br/><br/>';
    if(isset($result['destination'])) {
        $uploadFiles[] = $result['destination'];
    }
}
//$uploadFiles = array_values(array_filter($uploadFiles));
print_r($uploadFiles);