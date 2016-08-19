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
                $this->deleterow('courses','courseid',$id);                
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
            redirect($link->urlreturn('courseunits','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('courseunits','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('courseunits','&msgvalid=Please_select_records_to_delete'));
    }
}

$tableparameters = array('querydata' => array(
                                            'query' => 'SELECT * FROM courses WHERE  contributorid=\''.$_SESSION['sourceid'].'\' AND parentid != \'0\' ORDER BY courseid ASC',
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read' 
                                            ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            'add' => $link->urlreplace('showcourseunits','addcourseunit'),
                                            'export' => '',
                                            'print' => '',
                                            'delete' => $link->geturl().'&task=del'
                                        ),
                         //the edit details
                         'edit' => array(
                                            'columns' => array('Unit Name' => array('class' => 'edit',
                                                                                      'link' => $link->urlreplace('showcourseunits','addcourseunit').'&task=edit'
                                                                                        )
                                                              ),

                                        ),
                        'pagetitle' => 'Course Unit Management',
                        'printtitle' => SITENAME.' - Course Units',
                        'exceltitle' => 'ICHA Course Units Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Date','Unit Name','Description','Created On','Linked Course'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('courses'),

                        //dbfields - if foreign table exists (table_name.table_column.foreign_key)
                        //'dbfields' => array('publishdate','coursename','description','enabled','startdate','enddate','contributors.name.cid'),

                        //displaydbfields - this is for display of the records as per the above stated columns (dbfield.fieldtype.dbtable)
                        //NOTE: the (dbfield.fieldtype.*dbtable) format works only when the primary table's primary key is a foreign column in the other *dbtable
                        //NOTE: the (dbfield.fieldtype.(dbtable)) format indicates the reverse of the above ie its when the other table's primary key is a foreign column on the primery table
                        //NOTE: the (dbfield.fieldtype|[specified_field_type].dbtable) format now includes an additional square [] feature which allows for more specification of the field type, the pipe symbol separates the field type from the specifications
                        'displaydbfields' => array('publishdate.date','coursename.text','description.longtext','startdate.date','coursename.text'),

                        //the page search parameters
                        'formtitle' => 'Search Course Units',
                        'searchmap' => array(1,2,1,1),
                        'searchfields' => array('Unit Name','Creation Date','Start Date','End Date'),
                        'searchdbcolumns' => array('unitname','creationdate'),
                        'searchfieldtypes' => array('textbox','date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>
