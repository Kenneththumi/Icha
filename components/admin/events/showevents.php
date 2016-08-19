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
                //$publication = $this->runquery("SELECT * FROM publications WHERE courseid='$id'",'single');                
                if($this->deleterow('events','eventid',$id))
                {
                    $this->array_delete($ids,$id);
                }
            }
            else {
                $this->array_delete($ids,$id);
            }
        }
        
        if(count($ids)=='0')
        {
            redirect($link->urlreturn('events','&msgvalid=The_record(s)_have_been_deleted'));
        }
        else
        {
            redirect($link->urlreturn('events','&msgerror=The_record(s)_have_NOT_been_deleted'));
        }
    }
    else
    {
        redirect($link->urlreturn('events','&msgvalid=Please_select_records_to_delete'));
    }
}

$tableparameters = array(
                            'querydata' => array(
                                'query' => 'SELECT * FROM events ORDER BY startdate DESC',
                                'querytype' => 'multiple',
                                'rpg' => '10',
                                'pgno' => $_GET['pageno'],
                                'action' => 'read'
                            ),
                            'tools' => array(
                                'search' => '',
                                'add' => $link->urlreplace('showevents', 'addevent'),
                                'export' => '',
                                'print' => '',
                                'delete' => $link->geturl().'&task=del'
                            ),
                            'edit' => array(
                                'columns' => array('Title' => array('class' => 'edit',
                                                                                     'link' => $link->urlreplace('showevents','addevent').'&task=edit'
                                                                                   )
                                                   )
                            ),
                            'pagetitle' => 'Event Management',
                            'printtitle' => SITENAME.' - Events',
                            'exceltitle' => 'ICHA Events Excel Document for '.date('d-m-Y',time()),
                            'tablecolumns' => array('ID','Title','Description','Start Date','End Date','Venue','Focus Area'),
                            'dbtables' => array('events'),
                            'dbfields' => array('eventid','title','description','startdate','enddate','venue','source'),
                            'displaydbfields' => array('eventid.int','title.text','description.longtext','startdate.date','enddate.date','venue.text','focusarea.text'),
                            'formtitle' => 'Search Events',
                            'searchmap' => array(1,3),
                            'searchfields' => array('Title','Venue','Event Date'),
                            'searchdbcolumns' => array('title','venue','startdate'),
                            'searchfieldtypes' => array('textbox','textbox','date')
);

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');

