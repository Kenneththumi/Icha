<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$ins = new user();

//processing of article deletion
if($_GET['task']=='del'){
    
    if(isset($_GET['ids'])){
        
        $ids = explode(',',$_GET['ids']);
        $ds = DIRECTORY_SEPARATOR;

        foreach($ids as $id){
            
            if($id!=''){
                
                //delete the course folder
                $delcourse = $this->runquery("SELECT * FROM courses WHERE courseid = '$id'",'single');
                $contributor = $ins->returnUserSourceDetails($delcourse['contributorid'], 'instructor');
                
                rmdir(ABSOLUTE_MEDIA_PATH . 'training' .$ds. $contributor['foldername'] .$ds. 'courses' .$ds. $delcourse['coursename']);
                
                $this->deleterow('courses','courseid',$id);                
                $this->array_delete($ids,$id);
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('courses & units','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('courses & units','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('courses & units','&msgvalid=Please_select_records_to_delete'));
    }
}

//brocure script
$this->wrapscript("$(document).ready(function(){
                        if($('.imglink').length){
                        
                                $('.imglink').fancybox({
                                        'width': 500,
                                        'height': 310,
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
                            }
                         });");

//the table parameters
$tableparameters = array('querydata' => array(
                                            'query' => "SELECT * FROM courses",
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read',
                                            'orderby' => ['courseid','desc']
                                            ),
    
                        //put in the field parameters for nested table
                        'children' => array(
                            'checkcondition' => "parentid = '0'",
                            'checkdbcolumn' => 'parentid',                          
                            'childcolumns' => array('Course Name','Description','Price','Enabled','Start Date','End Date','Parent Course','Instructor')),

                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            'add' => '?admin=com_admin&folder=courses&file=addcourse&type=course',
                                            
                                            'export' => '',
                                            'print' => '',
                                            'delete' => $link->geturl().'&task=del'
                                        ),
    
                         //the edit details
                         'edit' => array(
                                            'columns' => array('Course Name' => 
                                                                    array('class' => 'edit',
                                                                          'link' => $link->urlreplace('showcourses','addcourse').'&task=edit'
                                                                        )
                                                              ),

                                        ),
                        'image' => array(
                                                'columns' => array('Brochure' => 
                                                    array('src' => DEFAULT_TEMPLATE_PATH.'/admin/images/viewdoc.png',
                                                          'class' => 'imglink',
                                                          'link' => '?admin=com_admin&folder=courses&file=showcoursebrochure&alert=yes&debug=yes'
                                                        )
                                                )
                                            ),
                        'pagetitle' => 'Courses & Units Management',
                        'printtitle' => SITENAME.' - Courses',
                        'exceltitle' => 'ICHA Courses Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Date','Course Name','Category','Description','Start Date','End Date','Is Featured?','Brochure'),
                        
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('courses','contributors','prices','categories'),

                        //dbfields - if foreign table exists (table_name.table_column.foreign_key)
                        //'dbfields' => array('publishdate','coursename','description','enabled','startdate','enddate','contributors.name.cid'),

                        //displaydbfields - this is for display of the records as per the above stated columns (dbfield.fieldtype.dbtable)
                        //NOTE: the (dbfield.fieldtype.*dbtable) format works only when the primary table's primary key is a foreign column in the other *dbtable
                        //NOTE: the (dbfield.fieldtype.(dbtable)) format indicates the reverse of the above ie its when the other table's primary key is a foreign column on the primery table
                        //NOTE: the (dbfield.fieldtype|[specified_field_type].dbtable) format now includes an additional square [] feature which allows for more specification of the field type, the pipe symbol separates the field type from the specifications
                        'displaydbfields' => array('publishdate.date','coursename.text','name.text.(categories)','description.longtext','startdate.date','enddate.date','featured.boolean'),

                        //'price.number|[currency].prices'
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
