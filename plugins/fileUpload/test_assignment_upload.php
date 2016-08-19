<?php
require_once('../../config.php'); 
include_once(ABSOLUTE_PATH.'plugins/fileUpload/getextension.php');
 
//$dbase = new database;
 
//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
//and it will be changed to 1 if an errro occures.  
//If the error occures the file will not be uploaded.
$errors=0;

//reads the name of the file the user submitted for uploading
$file=$_FILES['userfile']['name'];

//if it is not empty
if ($file) 
{
	//get the original name of the file from the clients machine
	//$filename = str_replace(' ','',stripslashes($_FILES['userfile']['name']));
    
        $filename = $_FILES['userfile']['name'];
	$key = $_POST['randomkey'];
	
	//get the extension of the file in a lower case format
	$extension = getExtension($filename);
	$extension = strtolower($extension);
	
	//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
	//otherwise we will do more tests
	 
         //get the size of the file in bytes
         //$_FILES['userfile']['tmp_name'] is the temporary filename of the file
         //in which the uploaded file was stored on the server
         $size = filesize($_FILES['userfile']['tmp_name']);

        //we will give an unique name, for example the time in unix time format
        $file_name = $key.'_'.$filename;
        
        //the new name will be containing the full path where will be stored (files folder)
        if(!isset($_POST['saveto']))
        {
            $newname = ABSOLUTE_PATH."media/banners/".$file_name;
        }
        else {
            $newname = ABSOLUTE_PATH."media/".$_POST['saveto']."/".$file_name;
        }
        
        //we verify if the file has been uploaded, and print error instead
        $copied = copy($_FILES['userfile']['tmp_name'], $newname);

        if (!$copied||$error==1) 
        {
                $errors=1;
        }
}

if($error==0)
{
	return true;
}
else
{
	echo 'false';
}
