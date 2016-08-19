<?php
require_once('../../config.php'); 
include_once(ABSOLUTE_PATH.'plugins/fileUpload/FileUploadClass.php');
 
$upload_dir = ABSOLUTE_PATH.'/media/'.$_POST['saveto'].'/';
$valid_extensions = array('gif', 'png', 'jpeg', 'jpg');

$Upload = new FileUpload('userfile');
$result = $Upload->handleUpload($upload_dir, $valid_extensions);

if (!$result) {
    echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));   
} else {
    echo json_encode(array('success' => true, 'file' => $Upload->getFileName()));
}