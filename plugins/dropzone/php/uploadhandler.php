<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$ds = DIRECTORY_SEPARATOR; 
 
$storeFolder = 'courseunits';  

if (!empty($_FILES)) {
 
    $tempFile = $_FILES['file']['tmp_name'];         
 
    $targetPath = ABSOLUTE_MEDIA_PATH . $ds. $storeFolder . $ds; 
 
    $targetFile =  $targetPath. $_FILES['file']['name']; 
 
    if(move_uploaded_file($tempFile,$targetFile))
    {
        echo $targetFile;
    }
    else
    {
        echo 'Failure';
    }
    
} else {                                                           
    $result  = array();
 
    $files = scandir($storeFolder);                 //1
    if ( false!==$files ) {
        foreach ( $files as $file ) {
            if ( '.'!=$file && '..'!=$file) {       //2
                $obj['name'] = $file;
                $obj['size'] = filesize($storeFolder.$ds.$file);
                $result[] = $obj;
            }
        }
    }
     
    header('Content-type: text/json');              //3
    header('Content-type: application/json');
    echo json_encode($result);
}
?>
