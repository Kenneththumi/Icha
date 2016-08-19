<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$docsave = array(
                'filename' => $_POST['filename'],
                'docname' => $_POST['docname'],
                'documenttype' => 'pdf',
                'uploaddate' => time()
            );  

if($_POST['filename']!=''){
    
    if($_POST['docid']==''){
        
        $this->dbinsert('documents',$docsave);
        $docid = mysql_insert_id();
    }
    else{
        
        $docid = $_POST['docid'];
        $doc_chk = $this->runquery("SELECT * FROM documents WHERE docid='$docid'",'multiple','all');
        $doc_count = $this->getcount($doc_chk);
        
        if($doc_count>='1'){
        
            $this->dbupdate('documents',$docsave,"docid='$docid'");
        }
        else{
        
            $this->dbinsert('documents',$docsave);
            $docid = mysql_insert_id();
        }        
    }
}

//$this->print_last_error(); exit();
//get the random number

$bodytxt = 'pabstract_'.$_POST['randomvalue'];
$savestep2 = array(
                    'title'=>$_POST['publicationtitle'],
                    'body'=> $_POST[$bodytxt],
                    'category'=>$_POST['category'],
                    'publishdate'=>time(),
                    'author' => $_POST['createdby'],
                    'authoremail' => $_POST['authoremail'],
                    'ptype' => $_POST['ptype'],
                    'docid' => $docid
);
//var_dump($savestep2); 

if(!isset($_POST['publicationid'])||$_POST['publicationid']==''){
    
    $this->dbinsert('publications', $savestep2);
    $publicationid = mysql_insert_id();
    
    redirect($link->urlreturn('resources & tools').'&msgvalid=The_resource_has_been_added');
}
else{
    
    $this->dbupdate('publications', $savestep2,"publicationid='".$_POST['publicationid']."'");
    $publicationid = $_POST['publicationid'];
    
    redirect($link->urlreturn('resources & tools').'&msgvalid=The_resource_has_been_edited');
}
