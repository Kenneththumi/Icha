<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$student = new user;

$student_details = $student->returnUserSourceDetails($_SESSION['sourceid'],'student');
$login_details = $student->returnUserSourceDetails($_SESSION['userid'], 'login');

$course = $this->runquery("SELECT ".
        "courses.courseid AS courseid, ".
        "courses.coursename AS coursename ".
        "FROM student_courses ".
        "INNER JOIN courses ON student_courses.courseid = courses.courseid ".
        "WHERE student_courses.students_id = '".$_SESSION['sourceid']."'",'single');

//set the courseid in the session
$_SESSION['courseid'] = $course['courseid'];

//load the cover photo
echo '<div id="coverphoto">';

//remember to add the photo upload functionality
echo '<div id="studentpic">';
//echo '<img src="'.STYLES_PATH.$this->set_template.'/student/images/studentpic.png" />';

if($student_details['filename']=='')
{
    $img = '<img src="'.STYLES_PATH.$this->set_template.'/student/images/studentpic.png" >';
}
 else {
     //$img=MEDIA_PATH.'profilepics/'.$student_details['filename'];
    $img = '<img src="'.MEDIA_PATH.'profilepics/'.$student_details['filename'].'" >';
}
echo $img;

echo '</div>';

echo '<div id="studentdetails">';
echo '<h1>'.$student_details['name'].'</h1>';
echo '<p><strong>Registration No:</strong> '.$student_details['registrationid'].'</p>';
echo '<p><strong>Course registered:</strong> '.$course['coursename'].'</p>';
echo '<p><strong>Last Login:</strong> '.date('l, d M y',$login_details['lastlogin']).'</p>';
echo '</div>';
echo '</div>';

//the latest courses
echo '<div class="full_" id="latestcourses">';
    echo '<div class="headings">
          
                <h4 >Registered <strong>Course</strong></h4>
      
            <!--<div >
                <a href="'.$link->urlreturn('My Courses').'" >
                    <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/student/images/grid_white.png" />
                </a>
            </div>-->
        </div>';
    
    //get the courses 
    $courselist = $this->runquery("SELECT * FROM courses INNER JOIN student_courses "
            . "ON student_courses.courseid = courses.courseid "
            . "INNER JOIN students "
            . "ON student_courses.students_id = students.students_id "
            . "WHERE courses.parentid = '0' AND students.students_id ='".$_SESSION['sourceid']."'",'multiple','4');
    
    $coursecount = $this->getcount($courselist);
    
    if($coursecount>='1')
    {
        $list = '<ul>';
        for($r=1; $r<=$coursecount; $r++)
        {
            $course = $this->fetcharray($courselist);

            $list .= '<li class="list-group-item"><p class="list-group-item-text">
                <a href="'.$link->urlreturn('My Courses').'">'.$course['coursename'].'</a>
                    </p>';
            
            $list .= '</li>';
            
            $units = $this->runquery("SELECT * FROM courses WHERE parentid = '".$course['courseid']."'",'multiple','4');
            $unitcount = $this->getcount($units);
            
            if($unitcount>='1')
            {
                $list .= '<ul>';
                
                for($s=1; $s<=$unitcount; $s++)
                {
                    $unit = $this->fetcharray($units);
                    
                    $list .= '<li class="list-group-item">
                                <p class="list-group-item-text">
                                <a href="'.$link->urlreturn('My Courses').'">
                                <img src="'.SITE_PATH.'styles/ichatemplate/images/arrow.png" width="15" />
                                '.$this->shortentxt($unit['coursename'],30).'
                                </a>
                                </p>
                              </li>';
                }
                
                $list .= '</ul>';
            }
        }
        $list .= '</ul>';
        
        echo $list;
    }
    else
    {
        $this->inlinemessage('No Courses listed','valid');
    }
    
    echo '</ul>';
    
echo '</div>';

//the latest assignments
echo '<div class="full_" id="latestasignments">';
    echo '<div class="headings">
        <h4>Latest <strong>Assignments</strong></h4>
        <!--<a href="'.$link->urlreturn('My Tests and Assignments').'">
            <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/student/images/grid_white.png" />
        </a>-->
        </div>';
    
 //get the tests and assignments
             $tests = $this->runquery("SELECT * FROM tests_assignments WHERE tests_assignments.courseid = '".$_SESSION['courseid']."' ",'multiple');
             $testcount = $this->getcount($tests);

             if($testcount>='1')
             {
                 echo '<ul>';
                 for($w=1; $w<=$testcount; $w++)
                 {
                     $test = $this->fetcharray($tests);
                     $datecreated= date('d/M/Y @ H:i',$test['creationdate']);
                     $duedate= date('d/M/Y',$test['duedate']);
                     $tatype =   strtolower($test['tatype']);     
                     
                     echo '<li class="list-group-item">
                             
                                    <p class="list-group-item-text"> 
                                             '.$w.'. <strong>'.$test['name'].'</strong> '.$tatype.' was uploaded on <strong>'.$datecreated.' Hrs</strong> '
                                             . 'and is due on <strong>'.$duedate.'.</strong>
                                              <a href="?content=com_students&folder=tests_assignments&file=processdownloads&alert=yes&id='.$test['tests_assignments_id'].'" target="_blank">
                                                  <strong class="link_download">Click Here to download.</strong>
                                              </a>
                                            <!--<a href="'.$link->urlreturn('My Tests and Assignments').'">
                                                <strong class="link_download">Click Here to download.</strong>
                                             </a>-->

                                    </p>
                             
                            </li>';
                 }
                echo '</ul>';
             }
             else
             {
                 $this->inlinemessage('No Tests or Assigments Listed','valid');
             }
 
 echo '</div>';
 
      //the latest Course Documents
echo '<div class="full_" id="latestasignments">';
    echo '<div class="headings">
        <h4>Latest <strong>Course Materials</strong></h4>
        <!--<a href="'.$link->urlreturn('My Tests and Assignments').'">
            <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/student/images/grid_white.png" />
        </a>-->
        </div>';
    
      $courseid=$_SESSION['courseid'];
     $userid = $_SESSION['userid'];
     
     $last_login = $this->runquery ("SELECT * FROM users WHERE users.userid = '$userid'"); 
     $login = $this->fetcharray($last_login);
    
     $loginlast = $login['lastlogin'];
  
     //var_dump($loginlast);
     
     $files=$this->runquery("SELECT * FROM documents JOIN courses_document ON courses_document.documents_id = documents.docid WHERE documents.uploaddate > '$loginlast' AND courses_document.courses_id ='$courseid' ",'multiple');
            
           
         $files_count=$this->getcount($files);
            if($files_count > 0){               
               
                 $coursename = $this->runquery("SELECT * FROM courses WHERE courses.courseid = '$courseid'");
                $name = $this->fetcharray($coursename);
               $count = $this->getcount($coursename);
                    if($count > 0){
//                        echo $coursename['coursename'];
                        $path = ABSOLUTE_PATH.'media/training/courses/'.$name['coursename'].'/';
                        $path2 = SITE_PATH.'media/training/courses/'.$name['coursename'].'/';
                                     echo '<ul>';
                            //while($file=  mysql_fetch_assoc($files)){
                            for($i=1;$i <= $files_count;$i++){
                                $file = $this->fetcharray($files);
                                  $fname = $file['filename'];
                                  $docname = $file['docname'];
                                  $type = $file['documenttype'];
                                  $ftime =  $file['uploaddate'];
                                  $filepath = $path.$fname;
                                  $getFilePath = $path2.$fname;
                                  //if(file_exists($filepath)){
                                //Since your last log in, document was uploaded on time. Click Here to Download.
                                         echo "<li class='list-group-item'>"
                                                  . "<p class='list-group-item-text'>"
                                                 . $i.'. Since your last log in, <strong>'.substr($docname,0,40).'</strong> '
                                                 . ' document was uploaded on <strong>'.(date('d/M/Y @ H:i',$ftime)).' Hrs</strong>.'
                                                 . "<a href= '$getFilePath' target='_blank'>"
                                                 . ' <strong class="link_download">Click Here to Download</strong>'
                                                 . '</a>'
                                                 . '</p>'
                                                 . '</li>';
                  
                                           
//                                  }else{
//                                      $this->inlinemessage('Wrong file path ','valid');  
//                                  }
                             }
                             //} 
                            
                            echo "</ul>";
                            
                     }else{
                          $this->inlinemessage('No Course Materials Listed','valid');  
                     }
                
            }
             else
            {
                   $this->inlinemessage('No New Course Materials','valid');  
            }
 
 
echo '</div>';

//the current grades
echo '<div class="full_" id="currentgrades">';
    echo '<div class="headings">
        <h4>Current <strong>Grades</strong></h4>
            <!--<a href="'.$link->urlreturn('My Graded Papers').'">
                <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/student/images/grid_black.png" />
            </a>-->
        </div>';
    
$assignments = $this->runquery("SELECT DISTINCT ".
    "tests_assignments.*, ".
    "student_tests_assignments.* ".
    "FROM tests_assignments ".
    "LEFT JOIN student_courses ON tests_assignments.courseid = student_courses.courseid ".
    "LEFT JOIN student_tests_assignments ON tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id ".
    "WHERE student_tests_assignments.students_id = '".$_SESSION['sourceid']."' AND grade != '0' AND grade IS NOT NULL",'multiple','all');

$assigncount = $this->getcount($assignments);

if($assigncount>='1')
{
    $this->loadplugin('charts/highcharts','js');
    $this->loadplugin('charts/modules/exporting','js');

    for($r=1; $r<=$assigncount; $r++)
    {
        $assign = $this->fetcharray($assignments);
        $assigntxt .= "['".$assign['name']." / ".$assign['tatype']."', ".($assign['grade']=='' ? '0' : $assign['grade'])." ]";

        if($r!=$assigncount)
        {
            $assigntxt .= ',';
        }

       $graphtitle .= "'".substr($assign['name'],0,11)." / ".$assign['tatype']."'".($r!=$assigncount ? ',' : '');
    }
}
else
{
    $this->inlinemessage('No Grades posted','valid');
}


//var_dump($graphtitle);
$this->wrapscript("$(function () { 
    $('#graph2').highcharts({
        chart: {
            type: 'bar',
            showAxes: true
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [".$graphtitle."]
        },
        yAxis: [{
            title: {
                text: 'Percentage'
            }
        }],
        plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
        series: [{
                name: 'Grade (Percentage)',
                data: [
                    ".$assigntxt."
                ],
                dataLabels: {
                    enabled: true,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -8,
                    y: 5,
                    style: {
                        fontSize: '17px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }]
            
    });
});");

echo '<div id="graph2" style="width:100%; height:205px;"></div>';
    
echo '</div>';

//manage assignments and grades
//echo '<div class="full_" id="managefiles">';
//    echo '<div class="headings">
//        <h2>Your <strong>Assignments Files & Marked Papers</strong></h2>
//        <a href="'.$link->urlreturn('Your Assignments & Files').'">
//        <img class="viewall" src="'.STYLES_PATH.$this->set_template.'/student/images/grid_black.png" />
//        </a>
//        </div>';
//    //var_dump($_SESSION['subfolder']);
//include_once ABSOLUTE_PATH.'components/students/files/mod_showallfiles.php';
//    
//echo '</div>';
//<i class='fa fa-download fa-lg' aria-hidden='true'></i>Download
?>