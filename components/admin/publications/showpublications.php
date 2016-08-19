<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

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

//the add fancybox script
$this->wrapscript("$(document).ready(function(){						
                        $(\".addlink\").fancybox({
                                'width': 450,
                                'height': 280,
                                'autoDimensions': false,
                                'autoScale' : false,
                                'transitionIn' : 'elastic',
                                'transitionOut' : 'fade',
                                'enableEscapeButton' : false,
                                'overlayShow' : true,
                                'overlayColor' : '#fff',
                                'overlayOpacity' : 0.5,
                                'scrolling': 'auto',
                                'hideOnOverlayClick': false
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
                //$publication = $this->runquery("SELECT * FROM publications WHERE publicationid='$id'",'single');                
                if($this->deleterow('publications','publicationid',$id))
                {
                    //get the file, delete it and delete the publication
                    $document = $this->runquery("SELECT * FROM documents WHERE publicationid='".$id."'",'single');
                    
                    unlink(ABSOLUTE_MEDIA_PATH.'pdf/'.$document['filename']);
                    
                    $this->deleterow('documents','publicationid',$publication['publicationid']);
                    
                    $this->array_delete($ids,$id);
                }
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('Resources & Tools','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('Resources & Tools','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('Resources & Tools','&msgvalid=Please_select_records_to_delete'));
    }
}

$tableparameters = array(
                            'querydata' => array(
                                                    'query' => 'SELECT * FROM publications ORDER BY publicationid ASC',
                                                    'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                                    'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                                    'pgno' => $_GET['pageno'], //gets the specific page no
                                                    'action' => 'read' 
                                                ),
                            'tools' => array(
                                                'search' => '',
                                                'add' => $link->urlreplace('showpublications', 'pickresource')
                                                        . '&alert=yes',
                                                'attributes' => array(
                                                    'addclass' => 'addlink'
                                                ),
                                                'export' => '',
                                                'print' => '',
                                                'delete' => $link->geturl().'&task=del'
                                            ),
                             'edit' => array(
                                        'columns' => array(
                                            'Title' => array(
                                                'class'=>'edit',
                                                'link'=>$link->urlreplace('showpublications', 'addpublication').'&task=edit'
                                            )
                                        )
                             ),
                             'image' => array(
                                                'columns' => array('Linked Documents' => 
                                                    array('src' => DEFAULT_TEMPLATE_PATH.'/admin/images/viewdoc.png',
                                                          'class' => 'imglink',
                                                          'link' => '?admin=com_admin&folder=publications&file=showpublicationdoc&alert=yes&debug=yes'
                                                        )
                                                )
                                            ),
                             'pagetitle' => 'Resources Management',
                             'printtitle' => SITENAME.' - Publications',
                             'exceltitle' => 'ICHA Publications Excel Document for '.date('d-m-Y',time()),
                             'tablecolumns' => array('Publish Date','Title','Brief','Category','Author','Type','Linked Course','Linked Documents'),
                             'dbtables' => array('publications','courses','prices'),
                             //'dbfields' => array('publishdate','title','body','authorname','courses.coursesname.coursesid'),
                             'displaydbfields' => array('publishdate.date','title.text','body.longtext','category.text','author.text','ptype.text','coursename.text.(courses)'),
                             //page search parameters
                             'formtitle' => 'Search Publications',
                             "searchmap" => array(1,2,2),
                             'searchfields' => array('Title','Publish Date','Start Date','End Date'),
                             'searchdbcolumns' => array('title','publishdate','startdate','enddate'),
                             'searchfieldtypes' => array('textbox','date','start date','end date')
                        );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
