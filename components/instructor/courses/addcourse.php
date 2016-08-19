<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

echo '<h1 class="pagetitle">Course Management</h1>';

//process the steps
if(isset($_POST['savestep']))
{
    $step = $_POST['savestep'];
    
    switch($step)
    {
        case 'saveone':
            
            //check the start and end dates
            if(strtotime($_POST['startdate']) > strtotime($_POST['enddate']))
            {
                redirect($link->geturl().'&msgerror=Please_enter_the_course_right_start_and_end_dates');
            }
            
            $savestep = array(
                                'coursename' => $_POST['coursename'],
                                'description'=>$_POST['desc'],
                                'enabled' => ($_POST['enabled']=='1' ? 'Yes' : 'No'),
                                'publishdate' => strtotime($_POST['publishdate']),
                                'startdate' => strtotime($_POST['startdate']),
                                'enddate' => strtotime($_POST['enddate']),
                                'contributorid' => $_POST['createdby']
                            );
            
            if(isset($_POST['courseid']))
            {
                $courseid = $_POST['courseid'];
                    
                $this->dbupdate('courses',$savestep,"courseid='".$courseid."'");
                
            }
            else
            {
                $this->dbinsert('courses', $savestep);
                $courseid = mysql_insert_id();
            }
            
            /*
            //save the price
            $pricearray = array(
                'price' => $_POST['price'],
                'curid' => $_POST['cur'],
                'ptype' => 'course',
                'courseid' => $courseid,
                'publicationid' => '0'
            );
            
            $pricechk = $this->runquery("SELECT count(*) FROM prices WHERE courseid ='$courseid'",'single');
            
            if($pricechk['count(*)']>='1')
            {
                $this->dbupdate('prices', $pricearray, "courseid='".$courseid."'");                
                
            }
            else {
                $this->dbinsert('prices', $pricearray);
            }
             * 
             */
            
            if(isset($_POST['courseid']))
            {
                redirect($link->urlreturn('My Courses').'&msgvalid=The_course_has_been_edited');
            }
            else
            {
                redirect($link->urlreturn('My Courses').'&msgvalid=The_course_has_been_added');
            }
            
        break;
    }
}

echo '<div id="contentholder">';

$action  = $link->urlreplace('1', '2').'&task=edit';
include (ABSOLUTE_PATH.'components/instructor/courses/courseform.php');

echo '</div>';
?>
