<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//show test files fancybox
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

$tableparameters = array('querydata' => array(
                                        'query' => 'SELECT 
                                                            * 
                                                    FROM 
                                                            tests_assignments 
                                                    INNER JOIN 
                                                            courses
                                                    ON
                                                          tests_assignments.courseid = courses.courseid
                                                    WHERE 
                                                            tests_assignments.instructor_id = "'.$_SESSION['sourceid'].'"
                                                    AND 
                                                            courses.expirydate >"'.time().'"
                                                     
                                                    ORDER BY 
                                                            duedate 
                                                    DESC',
                                        'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                        'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                        'pgno' => $_GET['pageno'], //gets the specific page no
                                        'action' => 'read' 
                                        ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                        'search' => '',
                                        'add' => $link->urlreplace('showtests','addtestassignment'),
                                        'export' => '',
                                        'print' => '',
                                        'delete' => $link->geturl().'&task=del'
                                        ),
                         //the edit details
                         'edit' => array(
                                            'columns' => array('Name' => array('class' => 'edit',
                                                                                      'link' => $link->urlreplace('showtests','addtestassignment').'&task=edit'
                                                                                        )
                                                              ),

                                        ),
                        'image' => array('columns'=> array('Files' => array(
                            'src' => DEFAULT_TEMPLATE_PATH.'/instructor/images/folder-files.png',
                            'class' => 'showfolder',
                            'link' => '?content=com_instructor&folder=tests_assignments&file=showassignmentdoc&alert=yes'))
                            ),
                        'pagetitle' => 'Test & Assignment Management',
                        'printtitle' => SITENAME.' - Students',
                        'exceltitle' => 'ICHA Tests and Assignments Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('ID','Name','Description','Linked Course','Type','Date Created','Due Date','Files'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('tests_assignments','courses'),
                        'displaydbfields' => array('tests_assignments_id.int','name.text','description.longtext','coursename.text.(courses)','tatype.text','creationdate.date','duedate.date'),

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
?>