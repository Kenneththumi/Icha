<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');


//show course files fancybox
$this->wrapscript("$(document).ready(function(){
                        $('a.showtaprofile').fancybox({
                                        'width': 900,
                                        'height': 400,
                                        'autoDimensions': false,
                                        'autoScale': false,
                                        'transitionIn': 'elastic',
                                        'transitionOut': 'elastic',
                                        'enableEscapeButton' : true,
                                        'overlayShow' : true,
                                        'overlayColor' : '#FFFFFF',
                                        'overlayOpacity' : 0.8,
                                        'scrolling': 'auto',
                                        'hideOnOverlayClick': false,
                                        'centerOnScroll': true,
                                        'type':'iframe'
                                });
                         });");


$link = new navigation;
$instructor = new user;

$instructor_details = $instructor->returnUserSourceDetails($_SESSION['sourceid'],'instructor');
$login_details = $instructor->returnUserSourceDetails($_SESSION['userid'], 'login');

//load the cover photo
echo '<div id="coverphoto">';

//remember to add the photo upload functionality
echo '<div id="instructorpic">';
echo '<img src="'.STYLES_PATH.$this->set_template.'/instructor/images/instructorpic.png" />';
echo '</div>';

echo '<div id="instructordetails">';
echo '<h1>'.$instructor_details['name'].'</h1>';
echo '<p><strong>Email Address:</strong> '.$instructor_details['emailaddress'].'</p>';
echo '<p><strong>Last Login:</strong> '.date('l, d M y',$login_details['lastlogin']).'</p>';
echo '</div>';
echo '</div>';

//the students
echo '<div class="full_" id="latestasignments">';
    echo '<div class="headings">
        <h4>Registered <strong>Students</strong></h4>
        <!--<a href="'.$link->urlreturn('My Students').'">
        <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/instructor/images/grid_white.png" />
        </a>-->
        </div>';
    
 //get the registered students
 $tests = $this->runquery('SELECT * FROM '
                            . 'student_courses LEFT JOIN '
                            . 'students ON student_courses.students_id = students.students_id '
                            . 'LEFT JOIN courses ON student_courses.courseid=courses.courseid '
                            . 'WHERE contributorid =\''.$_SESSION['sourceid'].'\' '
                            . 'AND courses.expirydate >'.time()
                            . ' AND students.approved = \'yes\' ORDER BY student_courses.students_id ASC','multiple','4');
 $testcount = $this->getcount($tests);
 
 if($testcount>='1')
 {
     for($f=1; $f<=$testcount; $f++)
     {
        $test = $this->fetcharray($tests);
        $uri = '?instructor=com_instructor&folder=frontpage&file=studenttaprofile&alert=yes&id='. $test['students_id'].'&name='.$test['name'];
//        echo '<li class="list-group-item">
//                <a href="'.$link->urlreturn('My Students').'">
//                    <p class="list-group-item-text">
//                    '.$f.'. '.$test['name'].'
//                    </p>
//                   
//                </a>
//            </li>';
        echo '<li class="list-group-item">
                <a class="showtaprofile" href="'.$uri.'">
                    <p class="list-group-item-text">
                    '.$f.'. '.$test['name'].'
                    </p>
                   
                </a>
            </li>';
     }
 }
 else
 {
     $this->inlinemessage('No registered students','valid');
 }
    
echo '</div>';

//the latest courses
//the latest courses
echo '<div class="full_" id="studentsubmissions">';
    echo '<div class="headings">
        <h4>Student <strong>Submissions</strong></h4>
            <!--<a href="'.$link->urlreturn('Student Submissions').'">
                <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/instructor/images/grid_white.png" />
            </a>-->
        </div>';
    
    //get the courses 
  
    $lastmonth = strtotime(date('l, d M y',$login_details['lastlogin']).' -1 month');
    
    
    
    $submitlist = $this->runquery("SELECT  
                                            tests_assignments.name AS testname,
                                            student_courses.students_id AS stdid,
                                            student_courses.courseid AS courseid,
                                            tests_assignments.tatype AS type,
                                            student_tests_assignments.uploaddate AS uploaddate 
                                        FROM
                                           student_tests_assignments 
                                        INNER JOIN 
                                            tests_assignments 
                                        ON 
                                            student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id 
                                        INNER JOIN 
                                           student_courses
                                        ON
                                             student_tests_assignments.students_id = student_courses.students_id
                                        WHERE 
                                             tests_assignments.instructor_id='".$_SESSION['sourceid']."' "
                                        . "AND student_tests_assignments.uploaddate >= '$lastmonth'",'multiple');
    $submitcount = $this->getcount($submitlist);
     $check = 0;
     
    if($submitcount>='1')
    {
        echo '<ul>';
        for($r=1; $r<=$submitcount; $r++)
        {
            $submit = $this->fetcharray($submitlist);
           
            $submit1 = $this->runquery("SELECT 
                                     students.name AS name,
                                     courses.enddate AS enddate
                                FROM 
                                    student_courses
                                INNER JOIN  
                                    courses 
                                ON
                                    courses.courseid = student_courses.courseid
                                INNER JOIN
                                    students
                                ON 
                                    student_courses.students_id = students.students_id
                                WHERE 
                                    student_courses.students_id = '".$submit['stdid']."'
                                AND 
                                    courses.expirydate > ".time()."
                                AND
                                   courses.courseid ='".$submit['courseid']."' ");
                               
            
            
       
             $count = $this->getcount($submit1);
             $submit1=$this->fetcharray($submit1);
             if($count>0){
                //
                echo '<li class="list-group-item">
                <a href="'.$link->urlreturn('Student Submissions').'">
                <p class="list-group-item-text">'.$r.'. '.$submit['testname'].' '.$submit['type'].' was submitted by '.$submit1['name'].' on '.date('d-M-Y @ H:i',$submit['uploaddate']).' Hrs</p>
                </a>
                </li>';
                //
                $check++;
             }else{
                 if($r == $count && $check = 0){
                     $this->inlinemessage('No submissions listd','valid');
                 }
             }
            
        }
    }
    else
    {
        $this->inlinemessage('No submissions listed','valid');
    }
    
    echo '</ul>';
    
echo '</div>';

//the student performance
echo '<div class="full_" id="currentgrades">';
    echo '<div class="headings">
        <h4>Student <strong>Performance</strong></h4>
          <!--<a href="'.$link->urlreturn('My Student Grades').'">
          <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/instructor/images/grid_black.png" />
        </a>-->
        </div>';
    
$this->loadplugin('charts/highcharts','js');
$this->loadplugin('charts/modules/exporting','js');
//changed from here
$querystr = "SELECT DISTINCT
                   student_tests_assignments.students_id AS id,
                   students.name AS student
            FROM
                    tests_assignments
            INNER JOIN 
                    student_tests_assignments 
            ON
                    tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id
            INNER JOIN 
                    contributors
            ON
                    contributors.contributorid = tests_assignments.instructor_id
            INNER JOIN 
                    students
            ON
                    students.students_id =    student_tests_assignments.students_id 
            INNER JOIN
                    courses
            ON  
                courses.courseid = tests_assignments.courseid
            WHERE 
                courses.expirydate > '".time()."'
            AND
                    contributors.contributorid='".$_SESSION['sourceid']."' ";
 
$queryrun = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queryrun);

for($l=1; $l<=$querycount; $l++)
{
    $getquery = $this->fetcharray($queryrun);
               $students .= "'".$getquery['student']."' ".($l!=$querycount? ', ' : '')."";
            $_SESSION['id'] = $getquery['id'];
                      
              $query = "SELECT 
                             tests_assignments_id AS testid,
                            name
                        FROM 
                               tests_assignments
                       WHERE
                               instructor_id ='".$_SESSION['sourceid']."' 
                       ORDER BY tests_assignments_id ASC";
                        
             $queryrun1 = $this -> runquery($query,'multiple','all');
          $querycount1 = $this -> getcount($queryrun1);
            $assigns_ids = $this -> fetcharray($queryrun1);
                       $_SESSION['testid']=$assigns_ids['testid'];
                    $assignments = "{
                                name : '".$getquery['student']."' ,
                                data : [ ";
                  
                    
                    $testnamess ="";
             for($i=1; $i <= $querycount1; $i++){
                  
               
                  $testnamess .= "'".substr($assigns_ids['name'],0,12)."' ".($i!=$querycount1? ', ' : '')."";
               
                
                 $assigns_ids = $this -> fetcharray($queryrun1);
                 
                    $std_id = $_SESSION['id']."<br/>";
                    $testid =$_SESSION['testid'];
                    $run2 = $this->runquery("select  
                                         *
                                from 
                                        student_tests_assignments
                     
                                where
                                       student_tests_assignments.tests_assignments_id = '".(isset($assigns_ids['testid'])?  $assigns_ids['testid'] :  $testid )."'
                                and 
                                      student_tests_assignments.students_id ='".$std_id."' ",'single');
                  
                    if(!isset($run2['grade'])){
                        $grade = 0;
                    }else{
                        $grade = $run2['grade'];
                    }                   
                       $marks = $grade.($i!=$querycount1 ? '' : '');
                      $assignments .= "".$marks." ".($i!=$querycount1 ? ','  : '')."";         
             }
             $assignments .="]} ".($l!=$querycount ? ', ' : '')."";
             $data .=$assignments;
             
              
           
              
}

$info = "<small>(click student name to disable/enable bar)</small>";

$this->wrapscript("$(function () {
    $('#graph2').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Student Performance ".$info."'
        },
        xAxis: {
            categories: [".$testnamess."],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Percentage (%)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Percent(%)'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [".$data."]
    });
});");

echo '<div id="graph2" style="width:100%; height:405px;"></div>';
    
echo '</div>';