<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if($_GET['task']=='del')
{
    $ids = $_GET['ids'];
    
    if(strpos($ids,',') == FALSE)
    {
        $student = $this->runquery("SELECT foldername FROM students WHERE students_id = '$ids'",'single');
        
        //delete files and folders
        unlink(ABSOLUTE_MEDIA_PATH.'training/students/'.$student['foldername']);
        
        $this->deleterow('students','students_id',$ids);
    }
    else
    {
        $idarray = explode(',',$ids);
        
        foreach($idarray as $id)
        {
            $student = $this->runquery("SELECT foldername FROM students WHERE students_id = '$id'",'single');
        
            //delete files and folders
            unlink(ABSOLUTE_MEDIA_PATH.'training/students/'.$student['foldername']);
        
            $this->deleterow('students','students_id',$id);
        }
    }
    //$this->print_last_query();    exit();
    redirect($link->urlreturn('Students').'&msgvalid=The_selected_records_have_been_deleted');
}

//the image fancybox settings
$this->wrapscript("$(document).ready(function(){
                        $('a.imglink').fancybox({
                                        'width': 700,
                                        'height': 510,
                                        'autoDimensions': false,
                                        'autoScale': false,
                                        'transitionIn': 'elastic',
                                        'transitionOut': 'elastic',
                                        'enableEscapeButton' : true,
                                        'overlayShow' : true,
                                        'overlayColor' : '#FFFFFF',
                                        'overlayOpacity' : 0.3,
                                        'scrolling': 'auto',
                                        'hideOnOverlayClick': false,
                                        'type':'iframe'
                                });
                        $('a.approvelink').fancybox({
                                        'width': 500,
                                        'height': 250,
                                        'autoDimensions': false,
                                        'autoScale': false,
                                        'transitionIn': 'elastic',
                                        'transitionOut': 'elastic',
                                        'enableEscapeButton' : true,
                                        'overlayShow' : true,
                                        'overlayColor' : '#FFFFFF',
                                        'overlayOpacity' : 0.3,
                                        'scrolling': 'auto',
                                        'hideOnOverlayClick': false,
                                        'type':'iframe'
                                });
                         });");

$this->wrapscript("$(document).ready(function(){
                        $('a.loginlink').fancybox({
                                        'width': 550,
                                        'height': 480,
                                        'autoDimensions': false,
                                        'autoScale': false,
                                        'transitionIn': 'elastic',
                                        'transitionOut': 'elastic',
                                        'enableEscapeButton' : true,
                                        'overlayShow' : true,
                                        'overlayColor' : '#FFFFFF',
                                        'overlayOpacity' : 0.8,
                                        'scrolling': 'auto',
                                        'hideOnOverlayClick': false
                                });
                         });");

//selects all course from courses table
$courses = $this->runquery("SELECT * FROM courses ORDER BY coursename ASC",'multiple','all');
$coursecount = $this->getcount($courses);

$courseslist['0'] = 'Select Course';

for($r=1; $r<=$coursecount; $r++){
    $course = $this->fetcharray($courses);
    $courseslist[$course['courseid']] = $course['coursename'];
}

//$courseslist = array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)');

$tableparameters = array(
                            'querydata' => array(
                                'query' => "SELECT students.*, student_courses.* FROM students "
                                            . "INNER JOIN student_courses "
                                            . "ON students.students_id = student_courses.students_id "
                                            . "ORDER BY students.students_id DESC",
                                'querytype' => 'multiple',
                                'rpg' => '10',
                                'pgno' => $_GET['pageno'],
                                'action' => 'read'
                            ),
                            'tools' => array(
                                'search' => '',
                                'add' => $link->urlreplace('showstudents','addstudent'),
                                'export' => '',
                                'print' => '',
                                'delete' => $link->geturl().'&task=del'
                            ),
                            //the edit parameter indicates which columns to include the edit link
                            'edit' => array(
                                                'columns' => array('Student Name' => array('class' => 'edit',
                                                                                          'link' => $link->urlreplace('showstudents','addstudent').'&task=edit'
                                                                                         ),
                                                                     'Approved' => array('class'=>'approvelink',
                                                                                        'link' => $link->urlreplace('showstudents','studentapproval').'&alert=yes'),
                                                                     'Paid' => array('class'=>'imglink',
                                                                                        'link' => $link->urlreplace('showstudents','showstudentfinances').'&alert=yes')
                                                                  )
                                            ),
                            //the images parameter indicates which columns to be replaced by an image, link and the image source url
                            'image' => array('columns' => array('Linked Documents' => array('src' => DEFAULT_TEMPLATE_PATH.'/admin/images/viewdoc.png',
                                                                                  'class' => 'imglink',
                                                                                  'link' => '?admin=com_admin&folder=students&file=showstudentsdocs&alert=yes'
                                                                                ),
                                                                  'Login Details' => array('src'=>DEFAULT_TEMPLATE_PATH.'/admin/images/login.png',
                                                                                          'class' => 'loginlink',
                                                                                          'link' => '?admin=com_admin&folder=students&file=studentlogin&alert=yes')
                                                )
                                            ),
                            /**
                            'functions' => array(
                                    'Paid' => array(
                                        'name' => 'checkFinances',
                                        'paramtype' => array('id','cid'),
                                        'paramcolumn' => array('students_id','courseid')
                                    )
                                ),
                             * 
                             */
                            'pagetitle' => 'Student Management',
                            'printtitle' => SITENAME.' - Students',
                            'exceltitle' => 'ICHA Students Excel Document for '.date('d-m-Y',time()),
                            'tablecolumns' => array('Registration ID','Student Name','Email Address','Registration Date','Course Applied','Approved','Paid','Linked Documents','Login Details'),
                            'dbtables' => array('students','student_courses','courses'),
                            'displaydbfields' => array('registrationid.text','name.text','emailaddress.text','registrationdate.date','coursename.text.{student_courses,courses}','approved.text','paid.text'),
                            
                            //page search parameters
                            'formtitle' => 'Search Courses',
                            'searchmap' => array(1,1,2,2),
                            'searchfields' => array('Student Name','Email','CourseName','From Date','To Date'),
                            'searchdbcolumns' => array('name','emailaddress','student_courses.courseid.students_id','registrationdate','registrationdate'),
                            'searchfieldtypes' => array('textbox','textbox','select','start date','end date'),
                            'CourseName_values' => $courseslist
);

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
