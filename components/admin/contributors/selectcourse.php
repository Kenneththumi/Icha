<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$this->loadplugin('classForm/class.form');

//get instructor
$instructorid = $_GET['id'];
$instructor = $this->runquery("SELECT * FROM contributors WHERE contributorid='".$instructorid."'",'single');

//save the new course contributorid
if(isset($_POST['instructorcoursesave'])){
    
    $courseid = $_POST['course'];
    
    $update = [
        'contributorid' => $_POST['insid']
    ];
    
    $this->dbupdate('courses', $update, "courseid = '".$courseid."'");
    $this->inlinemessage('The course has been added','valid');
}

//delete contributor from course
if($_GET['task'] == 'delete'){
    
    $delete = [
        'contributorid' => 0
    ];
    
    $this->dbupdate('courses', $delete, "courseid = '".$_GET['courseid']."'");
    $this->inlinemessage('The course entry has been deleted','valid');
}

//get all courses
$allcourses = $this->runquery("SELECT * FROM courses",'multiple','all');
$allcount = $this->getcount($allcourses);

$courselist[''] = 'Select Course';
for($t=1; $t<=$allcount; $t++){
    
    $allcourse = $this->fetcharray($allcourses);
    $courselist[$allcourse['courseid']] = $allcourse['coursename'];
}

//get courses under instructor
$courses = $this->runquery("SELECT * FROM courses WHERE contributorid='".$instructorid."'",'multiple','all');
$coursecount = $this->getcount($courses);

$table = '<table width="100%" border="0" cellpadding="5" class="tablelist">'
        . '<tr class="odd">
            <td><strong>Courses under '.$instructor['name'].'</strong></td>
            <td></td>
          </tr>';

for($r=1; $r<=$coursecount; $r++){
    
    $course = $this->fetcharray($courses);
    $table .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
                <td>'.$course['coursename'].'</td>
                <td>
                    <a href="'.$link->geturl().'&courseid='.$course['courseid'].'&task=delete">
                        <img src="'.STYLES_PATH.'ichatemplate/images/icons/delicon.png" width="20" height="20" />
                    </a>
                </td>
              </tr>';
}

$table .= '</table>';

$courseform = new form();
$courseform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'95%',
                                  "map"=>array(1,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => false,
                                  "action"=>''
                                  ));

$courseform->addHidden('instructorcoursesave','instructorcoursesave');
$courseform->addHidden('insid', $instructorid);

$courseform->addHTML('<h1>'.$instructor['name'].' : Course Selection</h1>');
$courseform->addSelect('Select Course','course','',$courselist);

$courseform->addButton('Add New Course', 'submit', 
    ['style'=>'float:right; margin-right: 15px; margin-botton:20px']);
$courseform->addHTML($table);

$courseform->render();
