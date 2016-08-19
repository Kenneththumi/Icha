<?php
require_once('../../config.php'); 
include_once(ABSOLUTE_PATH.'plugins/fileUpload/getextension.php');

include_once(ABSOLUTE_PATH.'classes/db.class.php');
$dbase = new database;
 
//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
//and it will be changed to 1 if an errro occures.  
//If the error occures the file will not be uploaded.
$errors=0;

//reads the name of the file the user submitted for uploading
$file=$_FILES['file']['name'];

//if it is not empty
if ($file){
    
        $error = 0;
        
        //get the original name of the file from the clients machine
	$docname = $_FILES['file']['name'];
        $courseid = $_POST['courseid'];
	$key = $_POST['randomkey'];
	
	//get the extension of the file in a lower case format
	$extension = strtolower(getExtension($docname));
	
	//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
	//otherwise we will do more tests
	 
         //get the size of the file in bytes
         //$_FILES['userfile']['tmp_name'] is the temporary filename of the file
         //in which the uploaded file was stored on the server
         //$size = filesize($_FILES['userfile']['tmp_name']);

        //we will give an unique name, for example the time in unix time format
        $file_name = $key.'_'.$docname;
        
        //the new name will be containing the full path where will be stored (files folder)
        if(isset($_POST['saveto'])){                     
            $newname = ABSOLUTE_PATH.$_POST['saveto']."/".$file_name;
        }
        
        //we verify if the file has been uploaded, and print error instead
        $copied = copy($_FILES['file']['tmp_name'], $newname);
        
	//get the original name of the file from the clients machine
	$docname = $_FILES['file']['name'];
        //$courseid = $_POST['courseid'];
         $document = [
            'filename' => $file_name,
            'uploaddate' => time(),
            'docname' => $docname,
            'documenttype' => $extension
        ];
        
        $dbase->dbinsert('documents', $document);
        //get doc_id and save it into courses_documents table
        $docid = mysql_insert_id();
        
        $save_doc = [   
                        'documents_id'  =>  $docid,
                        'courses_id' => $courseid,                   
                        'time_created' => time()
                     ];
        $dbase ->dbinsert('courses_document', $save_doc) ;
        
}

if($error==0)
{
	return true;
}
else
{
	echo 'false';
}