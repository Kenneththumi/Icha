<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$tid = $_GET['id'];

//check for previous entry
$docs = $this->runquery("SELECT * FROM student_tests_assignments WHERE tests_assignments_id = '$tid' AND students_id = '".$_SESSION['sourceid']."'",'multiple','all');
$doc_count = $this->getcount($docs);

if($doc_count >= 1){
    $doc = $this->fetcharray($docs);
    $doctestid = $doc['tests_assignments_id'];
}
else{
    $doctestid = $tid;
}

//get the assignment file
$workdoc = $this->runquery(
        "SELECT "
        . "documents.docname AS docname, "
        . "courses.coursename AS coursename FROM documents ".
        "INNER JOIN tests_assignments ON tests_assignments.document_id = documents.docid ".
        "INNER JOIN contributors ON tests_assignments.instructor_id = contributors.contributorid ".
        "INNER JOIN courses ON tests_assignments.courseid = courses.courseid ".
        "WHERE tests_assignments.tests_assignments_id = '".$doctestid."'",'single');

//var_dump($workdoc);
//$this->print_last_error(); exit();

if(file_exists(ABSOLUTE_MEDIA_PATH.'training/tests_and_assignments/'.$workdoc['docname']))
{
    if($doc_count=='0')
    {
        //save the download entry
        $savedownload = array(
            'students_id' => $_SESSION['sourceid'],
            'tests_assignments_id' => $tid,
            'downloaddate' => time(),
            'uploaddate' => '0'
        );

        $this->dbinsert('student_tests_assignments',$savedownload);
    }
    else
    {
        $savedownload = array(
            'downloaddate' => time()
        );

        $this->dbupdate('student_tests_assignments',$savedownload,"student_tests_assignments_id='".$tid."'");
    }
    
    redirect(MEDIA_PATH.'training/tests_and_assignments/'.$workdoc['docname']);
}
else{
    $this->inlinemessage('The assignment file has not been uploaded','error');
}
