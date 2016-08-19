<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

echo '<h1 class="pagetitle">Add New Course</h1>';

if(isset($_POST['coursesave'])){
    
    $selectcourses = $_POST['courses'];
    
    foreach ($selectcourses as $selectcourse) {
        
        $coursechk = $this->runquery("SELECT * "
            . "FROM student_courses "
            . "WHERE students_id='".$_POST['studentid']."' AND courseid='".$selectcourse."'",'multiple','all');
        
        $chkcount = $this->getcount($coursechk);
        
        if($chkcount == 0){
            
            $addcourse = [
              'students_id' => $_POST['studentid'],
               'courseid' => $selectcourse
            ];

            $save = $this->dbinsert('student_courses', $addcourse);
        }
    }
    
    $this->inlinemessage('The new courses have been added','valid');
}

$courses = $this->runquery("SELECT * FROM courses", 'multiple', 'all');
$coursecount = $this->getcount($courses);

//get the courses
$coursestable = '<table width="100%" border="0" cellpadding="10" class="tablelist">
          <tr>
            <td></td>
            <td class="tabletitle"><strong>Course Name</strong></td>
          </tr>';

for($r=1; $r<=$coursecount; $r++){
    
    $course = $this->fetcharray($courses);
    
    $coursechk = $this->runquery("SELECT * "
            . "FROM student_courses "
            . "WHERE students_id='".$_GET['id']."' AND courseid='".$course['courseid']."'",'multiple','all');
    $chkcount = $this->getcount($coursechk);
    
    $coursestable .= '<tr>'
                        . '<td><input type="checkbox" '
                        . ($chkcount >= 1 ? 'checked' : '')
                        . ' name="courses[]" value="'.$course['courseid'].'"></td>'
                        . '<td>'.ucwords(strtolower($course['coursename'])).'</td>'
                    . '</tr>';
}

$coursestable .= '</table>';

$courseform = new form;
$courseform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'97%',
                                  "map" => array(1,3,3,1,2,2,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action" => ''
                                  ));


$courseform->renderHead();

$courseform->addHidden('coursesave', 'coursesave');

if(isset($_GET['id'])){
    $courseform->addHidden('studentid', $_GET['id']);
}
elseif(isset($_POST['studentid'])){
    $courseform->addHidden('studentid', $_POST['studentid']);
}

$courseform->addHTML($coursestable);
$courseform->addButton('Save New Course');

$courseform->render();