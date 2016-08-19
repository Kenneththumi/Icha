<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if(isset($_POST['docname']))
{
    //save the document id first
    $docsave = array(
        'filename' => $_POST['docname'],
        'uploaddate' => time(),
        'docname' => $_POST['docname'],
        'documenttype' => $_POST['tatype']
    );
    
    $docfetch = $this->runquery("SELECT * FROM documents INNER JOIN tests_assignments ON tests_assignments.document_id = documents.docid WHERE tests_assignments_id = '".$_POST['id']."'",'multiple','all');
    $doccount = $this->getcount($docfetch);
    
    if($doccount=='0')
    {
        $this->dbinsert('documents',$docsave);
        
        //get the document id from the above query
        $docid = mysql_insert_id();
    }
    else {
        //get the document id 
        $doc = $this->fetcharray($docfetch);
        $this->dbupdate('documents',$docsave,"docid = '".$doc['docid']."'");
        //$this->print_last_error();
        //exit;
        $docid = $doc['docid'];
    }
    
    $tasave = array(
        'name' => $_POST['tatitle'],
        'courseid' => $_POST['courseid'],
        'tatype' => $_POST['tatype'],
        'creationdate' => time(),
        'duedate' => strtotime($_POST['duedate']),
        'description' => $_POST['description'],
        'instructor_id' => $_SESSION['sourceid'],
        'document_id' => $docid
    );
    
    //notify selected students of the new assignment
    require ABSOLUTE_PATH.'components/instructor/tests_assignments/notifystudents.php';
        
    if(!isset($_POST['id']))
    {
        $this->dbinsert('tests_assignments',$tasave);
        
        
        //add the ID variable into the $_POST['url']
        $taid = mysql_insert_id();
        $_POST['url'] = $_POST['url'].'&id='.$taid;
    }
    else {
        $this->dbupdate('tests_assignments',$tasave,"tests_assignments_id = '".$_POST['id']."'");
    }
    
    //check date
    if(strtotime($_POST['duedate']) < time())
    {
        redirect($_POST['url'].'&msgerror=You_cannot_pick_a_date_in_the_past');
    }
    
    redirect($link->urlreturn('Tests and Assignments').'&msgvalid=The_'.$_POST['tatype'].'_has_been_'.(isset($_POST['id']) ? 'edited' : 'added').'_and_sent');
}
else
{
    redirect($_POST['url'].'&msgerror=Please_upload_the_document');
}
?>
