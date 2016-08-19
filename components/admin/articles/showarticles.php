<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//processing of article deletion
if($_GET['task']=='del')
{
    $id = $_GET['ids'];
    if($this->deleterow('articles','articleid',$id))
    {
        redirect($link->urlreturn('articles','&msgvalid=The_article(s)_have_been_deleted'));
    }
    else
    {
        redirect($link->urlreturn('articles','&msgerror=The_record(s)_have_NOT_been_deleted'));
    }
}

$tableparameters = array('querydata' => array(
                                    'query' => 'SELECT * FROM articles',
                                    'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                    'rpg' => '20', //these are the rows per page set to 'all' for display of all rows in db
                                    'pgno' => $_GET['pageno'], //gets the specific page no
                                    'action' => 'read',
                                    'orderby' => ['articleid','desc']
                                ),
                         //declares the tools to be included in the page
                            'tools' => array(
                                    'search' => '',
                                    'add' => $link->urlreplace('showarticles','addarticle').'&step=1',
                                    'export' => '',
                                    'print' => '',
                                    'delete' => $link->geturl().'&task=del'
                                ),
                            //the edit details
                             'edit' => array(
                                                'columns' => array('Title' => array('class' => 'edit',
                                                                                     'link' => $link->urlreplace('showarticles','addarticle').'&task=edit'
                                                                                   )
                                                                  ),
                                 ),
                            //page information
                              'pagetitle' => 'Article Management',
                              'printtitle' => SITENAME.' - Articles',
                              'exceltitle' => 'ICHA Articles Excel Document for '.date('d-m-Y',time()),
                              'tablecolumns' => array('ID','Title','Category','Body','Publish Date'),
    
                              //the first table is the primary table, any other tables to be used to generate the table contents
                              'dbtables' => array('articles','categories'),
                              'dbfields' => array('articleid','title','body','publishdate'),
                              'displaydbfields' => array('articleid.int','title.text','name.text.(categories)','body.longtext','publishdate.date'),
                              
                              //the page search parameters
                              'formtitle' => 'Search Articles',
                              'searchmap' => array(1,2,1),
                              'searchfields' => array('Title','Body','Publish Date'),
                              'searchdbcolumns' => array('title','body','publishdate'),
                              'searchfieldtypes' => array('textbox','textbox','date')
    );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');