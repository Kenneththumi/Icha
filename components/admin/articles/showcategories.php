<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$tableparameters = array('querydata' => array(
                                    'query' => 'SELECT * FROM categories ORDER BY categoryid ASC',
                                    'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                    'rpg' => '20', //these are the rows per page set to 'all' for display of all rows in db
                                    'pgno' => $_GET['pageno'], //gets the specific page no
                                    'action' => 'read' 
                                ),
                         //declares the tools to be included in the page
                            'tools' => array(
                                    'add' => $link->urlreplace('showcategories','addcategory'),
                                    'delete' => $link->geturl().'&task=del'
                                ),
                            //the edit details
                             'edit' => array(
                                                'columns' => array('Category Name' => array('class' => 'edit',
                                                                                     'link' => $link->urlreplace('showcategories','addcategory').'&task=edit'
                                                                                   )
                                                                  ),
                                 ),
                            //page information
                              'pagetitle' => 'Article Category Management',
                              'tablecolumns' => array('ID','Category Name','Description'),
    
                              //the first table is the primary table, any other tables to be used to generate the table contents
                              'dbtables' => array('categories'),
                              'dbfields' => array('categoryid','name'),
                              'displaydbfields' => array('categoryid.int','name.text','description.text'),
                              
    );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');