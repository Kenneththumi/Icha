<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if($_GET['task']=='del'){
    
    $ids = $_GET['ids'];
    
    if(strpos($ids,',') == FALSE)
    {
        $teammember = $this->runquery("SELECT foldername FROM ourteam WHERE teamid = '$ids'",'single');
        
        //delete files and folders
        unlink(ABSOLUTE_MEDIA_PATH.'training/'.$teammember['foldername']);
        $this->deleterow('ourteam','teamid',$ids);
    }
    else
    {
        $idarray = explode(',',$ids);
        
        foreach($idarray as $id){
            
            $teammember = $this->runquery("SELECT foldername FROM ourteam WHERE teamid = '$id'",'single');
        
            //delete files and folders
            unlink(ABSOLUTE_MEDIA_PATH.'training/'.$teammember['foldername']);
        
            $this->deleterow('ourteam','teamid',$id);
        }
    }
    //$this->print_last_query();    exit();
    redirect($link->urlreturn('Team Members').'&msgvalid=The_selected_records_have_been_deleted');
}

//the image fancybox settings
$this->wrapscript("$(document).ready(function(){
                        $('a.loginlink').fancybox({
                                        'width': 550,
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
                                        'hideOnOverlayClick': false
                                });
                        $('a.imglink').fancybox({
                                        'width': 550,
                                        'height': 500,
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
                                        'type': 'iframe'
                                });
                         });");

$tableparameters = array(
                            'querydata' => array(
                            'query' => "SELECT ".
                                
                            "ourteam.* ".
                            //"users.enabled AS userenabled ".
                            "FROM ourteam ".
                                
                            //"RIGHT JOIN users ON ourteam.teamid = users.sourceid ".                                
                            //"WHERE users.usertype = 'instructor'".
                                
                            "ORDER BY teamid DESC",
                                'querytype' => 'multiple',
                                'rpg' => '10',
                                'pgno' => $_GET['pageno'],
                                'action' => 'read'
                            ),
                            'tools' => array(
                                'search' => '',
                                'add' => $link->urlreplace('showmembers','addteammember'),
                                'export' => '',
                                'print' => '',
                                'delete' => $link->geturl().'&task=del'
                            ),
                            //the edit parameter indicates which columns to include the edit link
                            'edit' => array(
                                                'columns' => array('Member Name' => array('class' => 'edit',
                                                                                          'link' => $link->urlreplace('showmembers','addteammember').'&task=edit'
                                                                                         )
                                                                  )
                                            ),
                            'pagetitle' => 'Team Member Management',
                            'printtitle' => SITENAME.' - Team Members',
                            'exceltitle' => 'ICHA Team Members Excel Document for '.date('d-m-Y',time()),
                            'tablecolumns' => array('ID','Member Name','Designation','Description','Category','Registration Date'),
                            'dbtables' => array('ourteam'),
                            //'dbfields' => array('teamid','name','contactinfo','registrationdate'),
                            'displaydbfields' => array('teamid.text','name.text','designation.text','contactinfo.longtext','category.text','registrationdate.date'),
                            //page search parameters
                            'formtitle' => 'Search Courses',
                            'searchmap' => array(1,1,2),
                            'searchfields' => array('Instructor Name','Email','Telephone'),
                            'searchdbcolumns' => array('name','emailaddress','telephone'),
                            'searchfieldtypes' => array('textbox','textbox','textbox')
);

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>
