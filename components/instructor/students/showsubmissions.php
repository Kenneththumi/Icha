<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$db = new database;

if($_GET['task']=='del')
{
    if(isset($_GET['ids']))
    {
        $ids = explode(',',$_GET['ids']);

        foreach($ids as $id)
        {
            if($id!='')
            {
                $this->deleterow('student_tests_assignments','student_tests_assignments_id',$id);                
                $this->array_delete($ids,$id);
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('Student Submissions','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('Student Submissions','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('Student Submissions','&msgvalid=Please_select_records_to_delete'));
    }
}

//show course files fancybox
$this->wrapscript("$(document).ready(function(){
                        $('a.showfolder').fancybox({
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

//$lastmonth = strtotime(date('l, d M y',$login_details['lastlogin']).' -1 month');
$query_run=$this -> runquery("SELECT * FROM tests_assignments WHERE courseid ='".$_SESSION['sourceid']."'");
$row = $this->fetcharray($query_run);
if(time()>=$row['duedate']){
    $downloadarray = array(
                        'src' => DEFAULT_TEMPLATE_PATH.'/student/images/downloadicon.png',
                        'link' => '?instructor=com_instructor&folder=files&file=downloadsubmission&show=submissions',
                        'target' => '_blank');
}
else{
    $downloadarray = array(
                        'src' => DEFAULT_TEMPLATE_PATH.'/student/images/downloadicon.png',
                        'link' => '#',
                        'target' => '_blank');
}
echo $_GET['ids'];
$tableparameters = array('querydata' => array(
                                            'query' => "SELECT ".
    //the variables
    "student_tests_assignments.student_tests_assignments_id AS student_tests_assignments_id, ".
    "students.registrationid AS registrationid, ".
    "students.name AS student, ".
    "tests_assignments.name AS docname, ".
    "tests_assignments.description AS description, ".
    "tests_assignments.tatype AS type, ".
    "student_tests_assignments.uploaddate AS uploaddate, ".
    "student_tests_assignments.grade AS grade, ".
    "courses.coursename AS coursename ".
    
    //main table
    "FROM tests_assignments".
    
    //student_tests_assignments join
    " INNER JOIN student_tests_assignments ON student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id ".
    //courses join
    " INNER JOIN courses ON tests_assignments.courseid = courses.courseid ".
    //students join
    " INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".
    
    "WHERE student_tests_assignments.uploaddate != 0 AND courses.expirydate >'".time()."' AND tests_assignments.instructor_id='".$_SESSION['sourceid']."' ".
    "ORDER BY student_tests_assignments.uploaddate DESC",
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read' 
                                            ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            'export' => '',
                                            'print' => '',
                                            'delete' => $link->geturl().'&task=del'
                                        ),
                        'image' => array('columns'=> array(
                                'Download' => array(
                                                    'src' => DEFAULT_TEMPLATE_PATH.'/student/images/downloadicon.png',
                                                    'link' => '?instructor=com_instructor&folder=files&file=downloadsubmission&show=submissions',
                                                    'target' => '_blank'
                                                 )/*,
                                'Marked Papers' => array(
                                    'src' => DEFAULT_TEMPLATE_PATH.'/instructor/images/grades.png',
                                    'class' => 'showfolder',
                                    'link' => '?instructor=com_instructor&folder=files&file=showfolder&show=markedpaper&alert=yes')*/
                                )
                            ),
                        'edit' => array(
                                                'columns' => array('Grade % <br/><span style="font-size:10px; color: red;">(click number to add grade)</span>' => array('class' => 'showfolder',
                                                                                          'link' => $link->urlreplace('showsubmissions','addgrade').'&alert=yes'
                                                                                         )
                                                                  )
                                            ),
                        'pagetitle' => 'Submission Management',
                        'printtitle' => SITENAME.' - Submissions',
                        'exceltitle' => 'ICHA Submissions Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Submission Name','No.','Student Name','Course','Upload Date','Grade % <br/><span style="font-size:10px; color: red;">(click number to add grade)</span>','Download'/*,'Marked Papers'*/),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('student_tests_assignments'),
                        'displaydbfields' => array('docname.text','registrationid.text','student.text','coursename.text','uploaddate.date','grade.text'),

                        //the page search parameters
                        'formtitle' => 'Search Submissions',
                        'searchmap' => array(1,2,2),
                        'searchfields' => array('Course Name','Publish Date','Start Date','End Date'),
                        'searchdbcolumns' => array('coursename','publishdate','startdate','enddate'),
                        'searchfieldtypes' => array('textbox','date','start date','end date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>
