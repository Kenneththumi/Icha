<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$tid = $_GET['id'];

$student = $this->runquery("SELECT ".
        "students.foldername AS foldername, ".
        "documents.filename AS filename ".
        "FROM student_tests_assignments ".
        "INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".
        "INNER JOIN documents ON student_tests_assignments.marked_document_id = documents.docid ".
        "WHERE student_tests_assignments.student_tests_assignments_id = '$tid'",'single');

if(file_exists(ABSOLUTE_MEDIA_PATH.'training/students/'.$student['foldername'].'/marked_papers/'.$student['filename']))
{
    redirect(MEDIA_PATH.'training/students/'.$student['foldername'].'/marked_papers/'.$student['filename']);
}
else
{
    $this->inlinemessage('The graded paper has not been found','error');
}
?>
