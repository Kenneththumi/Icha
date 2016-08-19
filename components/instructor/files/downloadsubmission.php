<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$id = $_GET['id'];

$tests = $this->runquery("SELECT * FROM student_tests_assignments WHERE student_tests_assignments_id = '".$id."'",'single');
$docid = $tests['students_documents_id'];

//get the document
$document = $this->runquery("SELECT documents.docname AS docname "
        . "FROM students_documents "
        . "INNER JOIN documents ON students_documents.documents_id = documents.docid "
        . "WHERE students_documents.students_documents_id='".$docid."'",'single');
//get the duedate
$row=$this -> runquery("SELECT * FROM  ichadb.tests_assignments 
                       INNER JOIN ichadb.student_tests_assignments 
                       ON student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id 
                       WHERE student_tests_assignments.student_tests_assignments_id = '".$_GET['id']."'",'single');


 
      if(time()>=$row['duedate']){
             if($this->url_exists(MEDIA_PATH.'training/submissions/'.$document['docname'])){
                redirect(MEDIA_PATH.'training/submissions/'.$document['docname']);
            }
            else{
               $this->inlinemessage('Document not found', 'error');
            }
        }
        else{
            $this->inlinemessage('Due date is yet', 'error');
        }
 

   
