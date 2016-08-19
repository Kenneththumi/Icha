<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

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
                $this->deleterow('students','courseid',$id);                
                $this->array_delete($ids,$id);
                
                /*
                //get the file, delete it and delete the publication
                $publication = $this->runquery("SELECT * FROM publications WHERE courseid='$id'",'single');
                $document = $this->runquery("SELECT * FROM documents WHERE publicationid='".$publication['publicationid']."'",'single');

                unlink(ABSOLUTE_MEDIA_PATH.'pdf/'.$document['filename']);

                $this->deleterow('publications','courseid',$id);
                $this->deleterow('documents','publicationid',$publication['publicationid']);                
                 * 
                 */
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('students','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('students','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('students','&msgvalid=Please_select_records_to_delete'));
    }
}

$tableparameters = array('querydata' => array(
                                            'query' => "SELECT ".
    "DISTINCT registrationdate, registrationid, name, emailaddress, mobile, courses.coursename AS course ".
    "FROM students ".
    "INNER JOIN student_courses ON student_courses.students_id = students.students_id ".
    "LEFT JOIN courses ON student_courses.courseid = courses.courseid ".
    "WHERE   expirydate >'".time()."' AND contributorid = '".$_SESSION['sourceid']."' AND students.approved = 'Yes' ".
    "ORDER BY student_courses.students_id ASC",
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read'
                                            ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            //'add' => $link->urlreplace('showstudents','addcourse'),
                                            'export' => '',
                                            'print' => ''
                                            //'delete' => $link->geturl().'&task=del'
                                        ),
                         //the edit details
                         'edit' => array(
                                            'columns' => array('Course Name' => array('class' => 'edit',
                                                                                      'link' => $link->urlreplace('showstudents','addcourse').'&task=edit'
                                                                                        )
                                                              ),

                                        ),
                        'image' => array('columns'=>array(
                            'Assignments' => array('src'=>DEFAULT_TEMPLATE_PATH.'/instructor/images/assignments.png','class'=>'assignmets','link'=>'#'),
                            'Grades' => array('src'=>DEFAULT_TEMPLATE_PATH.'/instructor/images/grades.png','class'=>'assignmets','link'=>'#')
                        )),
                        'pagetitle' => 'Student Management',
                        'printtitle' => SITENAME.' - students',
                        'exceltitle' => 'ICHA Students Excel Document for '.date('d-m-Y',time()),
                        //'tablecolumns' => array('Date','Student Name','Date of Birth','Email Address','Mobile','Assignments','Grades'),
    'tablecolumns' => array('Date','Registration No.','Student Name','Email Address','Mobile','Course'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('students'),

                        //dbfields - if foreign table exists (table_name.table_column.foreign_key)
                        //'dbfields' => array('publishdate','coursename','description','enabled','startdate','enddate','contributors.name.cid'),

                        //displaydbfields - this is for display of the records as per the above stated columns (dbfield.fieldtype.dbtable)
                        //NOTE: the (dbfield.fieldtype.*dbtable) format works only when the primary table's primary key is a foreign column in the other *dbtable
                        //NOTE: the (dbfield.fieldtype.(dbtable)) format indicates the reverse of the above ie its when the other table's primary key is a foreign column on the primery table
                        //NOTE: the (dbfield.fieldtype|[specified_field_type].dbtable) format now includes an additional square [] feature which allows for more specification of the field type, the pipe symbol separates the field type from the specifications
                        'displaydbfields' => array('registrationdate.date','registrationid.text','name.text','emailaddress.text','mobile.text','course.text'),

                        //the page search parameters
                        'formtitle' => 'Search students',
                        'searchmap' => array(1,2,2),
                        'searchfields' => array('Student Name','Registration Date'),
                        'searchdbcolumns' => array('coursename','publishdate','startdate','enddate'),
                        'searchfieldtypes' => array('textbox','date','start date','end date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>