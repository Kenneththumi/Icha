<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//show test files fancybox
$this->wrapscript("$(document).ready(function(){
                        $('a.showupload').fancybox({
                                    'width': 700,
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

//processing of article deletion
if($_GET['task']=='del')
{
    if(isset($_GET['ids']))
    {
        $ids = explode(',',$_GET['ids']);

        foreach($ids as $id)
        {
            if($id!='')
            {
                $this->deleterow('tests_assignments','tests_assignments_id',$id);                
                $this->array_delete($ids,$id);
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('My Tests and Assignments','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('My Tests and Assignments','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('students','&msgvalid=Please_select_records_to_delete'));
    }
}
$studentid = $_SESSION['sourceid'];
/*
 * "SELECT ".
    "tests_assignments.* ,".
    "tests_assignments.name AS name, ".
    "tests_assignments_grades.grade AS grade ".
    
    "FROM tests_assignments ".
    
    "INNER JOIN student_courses ON student_courses.courseid = tests_assignments.courseid ".
    "INNER JOIN students ON student_courses.students_id = students.students_id ".
    "INNER JOIN tests_assignments_grades ON tests_assignments.tests_assignments_id = tests_assignments_grades.tests_assignments_id ".
    
    "WHERE tests_assignments.courseid = '".$_SESSION['courseid']."'"
 */

$tableparameters = array('querydata' => array(
                                        'query' => "SELECT DISTINCT ".
    "tests_assignments.tests_assignments_id, ".
    "tests_assignments.document_id, ".
    "tests_assignments.creationdate, ".
    "tests_assignments.duedate, ".
    "tests_assignments.courseid, ".
    "tests_assignments.tatype, ".
    "tests_assignments.name, ".
    "tests_assignments.description ".
   // "student_tests_assignments.grade ".
    //"student_tests_assignments.students_id ".
    
    "FROM tests_assignments ".
    
    "LEFT JOIN student_tests_assignments "
    . "ON tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id ".
    
    "WHERE tests_assignments.courseid = '".$_SESSION['courseid']."' ",
                                        'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                        'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                        'pgno' => $_GET['pageno'], //gets the specific page no
                                        'action' => 'read'
                                        ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                        'search' => '',
                                        //'add' => $link->urlreplace('showtests','addtestassignment'),
                                        'export' => '',
                                        'print' => '',
                                        //'delete' => $link->geturl().'&task=del'
                                        ),
    /*
                         //the edit details
                         'edit' => array(
                                            'columns' => array('Name' => array('class' => 'edit',
                                                                                      'link' => $link->urlreplace('showassignments','addtestassignment').'&task=edit'
                                                                                        )
                                                              ),

                                        ),
     * 
     */
                        'image' => array('columns'=> array('Download' => array(
                            'src' => DEFAULT_TEMPLATE_PATH.'/student/images/downloadicon.png',
                            'target' => '_blank',
                            'link' => '?content=com_students&folder=tests_assignments&file=processdownloads&alert=yes'),
                            'Upload' => array('src' => DEFAULT_TEMPLATE_PATH.'/student/images/uploadtesticon.png',
                            'class' => 'showupload',
                            'target' => '_blank',
                            'link' => '?content=com_students&folder=tests_assignments&file=processupload&alert=yes'))
                            ),
                        'pagetitle' => 'Test & Assignment Management',
                        'printtitle' => SITENAME.' - Test and Assignments',
                        'exceltitle' => 'ICHA Tests and Assignments Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('ID','Name','Description','Type'/*,'Grade %'*/,'Date Created','Due Date','Download','Upload'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('tests_assignments'),
                        'displaydbfields' => array('tests_assignments_id.int','name.text','description.longtext','tatype.text'/*,'grade.int'*/,'creationdate.date','duedate.date'),

                        //the page search parameters
                        'formtitle' => 'Search Tests and Assignments',
                        'searchmap' => array(1,3),
                        'searchfields' => array('Name','Creation Date','Due Date'),
                        'searchdbcolumns' => array('name','creationdate','duedate'),
                        'searchfieldtypes' => array('textbox','date','date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
