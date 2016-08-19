<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();
$this->loadscripts();

//get the test assignment details
$testid = $_GET['id'];

$test = $this->runquery("SELECT ".
        
        "tests_assignments.name AS name, ".
        "student_tests_assignments.*, ".
        "student_tests_assignments.students_id AS students_id, ".
        "student_tests_assignments.tests_assignments_id AS testid, ".
        "student_tests_assignments.grade AS grade, ".
        "student_tests_assignments.comments AS comments, ".
        "students.foldername AS studentfolder, ".
        "tests_assignments.courseid AS courseid, ".
        "tests_assignments.duedate AS duedate, ".
        "contributors.foldername AS instructorfolder, ".
        "students.name AS studentname, ".
        "students.emailaddress AS studentemail, ".
        "courses.coursename AS coursename ".
        
        " FROM student_tests_assignments ".
        
        "INNER JOIN tests_assignments ON student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id ".
        
        "INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".
        
        "INNER JOIN courses ON courses.courseid = tests_assignments.courseid ".
        
        "INNER JOIN contributors ON courses.contributorid = contributors.contributorid ".
        
        "WHERE student_tests_assignments.student_tests_assignments_id = '$testid'",'single');

//the save function
if(isset($_POST['grade'])&&isset($_POST['comments']))
{    
    $gradesave = array(
        'grade' => $_POST['grade'],
        'uploaddate' => time(),
        'comments' => $_POST['comments']
    );
    
    $this->dbupdate('student_tests_assignments',$gradesave,"student_tests_assignments_id='".$_POST['staid']."'");
    
    

    $this->inlinemessage('The grade & comments has been saved. Please refresh browser to see new entry','valid');
    exit;
}
elseif(isset($_POST['grade'])&&!isset($_POST['comments']))
{
    $this->inlinemessage('Please Insert a grade and Comments','error');
}
//'training/".$test['instructorfolder']."/students/".$test['studentfolder']."/marked_papers'

$this->loadplugin('classForm/class.form');
$gradeform = new form();

$gradeform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'98%',
                                  "map" => array(1,3,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action"=>'',
                                  ));

$gradeform->addHidden('cmd', 'save');
$gradeform->addHidden('staid',$_GET['id']);
$gradeform->addHidden('testid', $test['testid']);
$gradeform->addHidden('studentid', $test['students_id']);
$gradeform->addHidden('courseid', $test['courseid']);
$gradeform->addHidden('url', $link->geturl());

$gradeform->addHTML('<h1>Grade for '.$test['name'].'</h1>');



$gradeform->addHTML('Assign the grade for the assignment (as percentage):');
$gradeform->addTextbox('', 'grade',$test['grade'],array('required'=>true) );
$gradeform->addHTML('<br/>');
$gradeform->addHTML('Please comment on the assignment:');
$gradeform->addTextarea('', 'comments',$test['comments'],array('required'=>true));
$gradeform->addHTML('');
$gradeform->addButton('Save Grade and Comments');

$gradeform->render();

?>
