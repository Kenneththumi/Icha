<?php
    //prevents direct access of these files
    defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

    $link = new navigation();
    $this->loadscripts();

    //get the test assignment details
    $testid = $_GET['id'];

    $test = $this->runquery("SELECT ".
            "student_tests_assignments.comments AS comments, ".
            
             "student_tests_assignments.grade AS grade, ".
            
            "tests_assignments.name AS name, ".
            
            "tests_assignments.tatype AS type ".
            
            " FROM student_tests_assignments ".

            "INNER JOIN tests_assignments ON student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id ".

            "INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".

            "INNER JOIN courses ON courses.courseid = tests_assignments.courseid ".

            "INNER JOIN contributors ON courses.contributorid = contributors.contributorid ".

            "WHERE student_tests_assignments.student_tests_assignments_id = '$testid'",'single');

    //the save function
   
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

    $gradeform->addHTML('<h1>Grade & Comments for '.$test['name'].' '.$test['type'].'</h1>');

    $gradeform->addHTML('Grade assigned for '.$test['name'].' '.$test['type'].'(as percentage):');
    $gradeform->addTextbox('', 'grade',$test['grade'],array('disabled'=>true));
    $gradeform->addHTML('<br/>');
    $gradeform->addHTML('Comments from your Instructor:');
    $gradeform->addTextarea('', 'comments',$test['comments'],array('disabled'=>true));
    $gradeform->addHTML('');

   

    $gradeform->render();

   
