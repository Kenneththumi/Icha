<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

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


$tableparameters = array('querydata' => array(
                                            'query' => 'SELECT * FROM courses ',
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read'
                                            ),
                            //put in the field parameters for nested table
                            'children' => array(
                                'checkjoin' => 'INNER JOIN student_courses ON student_courses.courseid = courses.courseid INNER JOIN students ON student_courses.students_id = students.students_id ',
                                'checkcondition' => "courses.parentid = '0' AND students.students_id ='".$_SESSION['sourceid']."'",
                                'checkdbcolumn' => 'parentid',                          
                                'childcolumns' => array('Course Name','Description','Enabled','Instructor','Course Files')),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            'export' => '',
                                            'print' => ''
                                            //'delete' => $link->geturl().'&task=del'
                                        ),
                        'image' => array('columns'=> array('Course Files' => array(
                            'src' => DEFAULT_TEMPLATE_PATH.'/instructor/images/folder-files.png',
                            'class' => 'showfolder',
                            'link' => '?content=com_students&folder=courses&file=showcoursedocs&alert=yes'))
                            ),
                        'pagetitle' => 'Course Management',
                        'printtitle' => SITENAME.' - Courses',
                        'exceltitle' => 'ICHA Courses Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Course Name','Enabled','Instructor','Course Files'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('courses','contributors'),

                        //dbfields - if foreign table exists (table_name.table_column.foreign_key)
                        //'dbfields' => array('publishdate','coursename','description','enabled','startdate','enddate','contributors.name.cid'),

                        //displaydbfields - this is for display of the records as per the above stated columns (dbfield.fieldtype.dbtable)
                        //NOTE: the (dbfield.fieldtype.*dbtable) format works only when the primary table's primary key is a foreign column in the other *dbtable
                        //NOTE: the (dbfield.fieldtype.(dbtable)) format indicates the reverse of the above ie its when the other table's primary key is a foreign column on the primery table
                        //NOTE: the (dbfield.fieldtype|[specified_field_type].dbtable) format now includes an additional square [] feature which allows for more specification of the field type, the pipe symbol separates the field type from the specifications
                        'displaydbfields' => array('coursename.text','enabled.text','name.text.(contributors)'),

                        //the page search parameters
                        'formtitle' => 'Search Courses',
                        'searchmap' => array(1,2,2),
                        'searchfields' => array('Course Name','Publish Date','Start Date','End Date'),
                        'searchdbcolumns' => array('coursename','publishdate','startdate','enddate'),
                        'searchfieldtypes' => array('textbox','date','start date','end date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>