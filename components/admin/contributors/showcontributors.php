<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if($_GET['task']=='del')
{
    $ids = $_GET['ids'];
    
    if(strpos($ids,',') == FALSE)
    {
        $contributor = $this->runquery("SELECT foldername FROM contributors WHERE contributorid = '$ids'",'single');
        
        //delete files and folders
        unlink(ABSOLUTE_MEDIA_PATH.'training/'.$contributor['foldername']);
        
        $this->deleterow('contributors','contributorid',$ids);
    }
    else
    {
        $idarray = explode(',',$ids);
        
        foreach($idarray as $id){
            
            $contributor = $this->runquery("SELECT foldername FROM contributors WHERE contributorid = '$id'",'single');
        
            //delete files and folders
            unlink(ABSOLUTE_MEDIA_PATH.'training/'.$contributor['foldername']);
        
            $this->deleterow('contributors','contributorid',$id);
        }
    }
    //$this->print_last_query();    exit();
    redirect($link->urlreturn('Instructors').'&msgvalid=The_selected_records_have_been_deleted');
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
                                
                            "contributors.* ".
                            //"users.enabled AS userenabled ".
                            "FROM contributors ".
                                
                            //"RIGHT JOIN users ON contributors.contributorid = users.sourceid ".                                
                            //"WHERE users.usertype = 'instructor'".
                                
                            "ORDER BY registrationdate DESC",
                                'querytype' => 'multiple',
                                'rpg' => '10',
                                'pgno' => $_GET['pageno'],
                                'action' => 'read'
                            ),
                            'tools' => array(
                                'search' => '',
                                'add' => $link->urlreplace('showcontributors','addcontributor'),
                                'export' => '',
                                'print' => '',
                                'delete' => $link->geturl().'&task=del'
                            ),
                            //the edit parameter indicates which columns to include the edit link
                            'edit' => array(
                                                'columns' => array('Instructor Name' => array('class' => 'edit',
                                                                                          'link' => $link->urlreplace('showcontributors','addcontributor').'&task=edit'
                                                                                         )
                                                                  )
                                            ),
                            //the images parameter indicates which columns to be replaced by an image, link and the image source url
                            'image' => array(
                                                'columns' => array('Linked Courses' => array('src' => DEFAULT_TEMPLATE_PATH.'/admin/images/course_icon.png',
                                                                                  'class' => 'imglink',
                                                                                  'link' => '?admin=com_admin&folder=contributors&file=selectcourse&alert=yes'),
                                                                  'Login Details' => array('src'=>DEFAULT_TEMPLATE_PATH.'/admin/images/login.png',
                                                                                          'class' => 'loginlink',
                                                                                          'link' => '?admin=com_admin&folder=contributors&file=logindetails&alert=yes')
                                                )
                                            ),
                            'pagetitle' => 'Instructor Management',
                            'printtitle' => SITENAME.' - Instructors',
                            'exceltitle' => 'ICHA Instructors Excel Document for '.date('d-m-Y',time()),
                            'tablecolumns' => array('ID','Instructor Name','Description','Category','Registration Date','Linked Courses','Login Details'),
                            'dbtables' => array('contributors'),
                            //'dbfields' => array('contributorid','name','contactinfo','registrationdate'),
                            'displaydbfields' => array('contributorid.text','name.text','contactinfo.longtext','category.text','registrationdate.date'),
                            //page search parameters
                            'formtitle' => 'Search Courses',
                            'searchmap' => array(1,1,2),
                            'searchfields' => array('Instructor Name','Email','Telephone'),
                            'searchdbcolumns' => array('name','emailaddress','telephone'),
                            'searchfieldtypes' => array('textbox','textbox','textbox')
);

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>
