<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$tableparameters = array('querydata' => array(
                                            'query' => "SELECT ".
    //variables
    "paymentconfirmation.paymentconfirmations_id AS paymentconfirmations_id, ".
    "paymentconfirmation.tracking_id AS tracking_id, ".
    //"courses.coursename AS course, ".    
    "paymentconfirmation.paymentdate AS paymentdate, ".
    "currency.currencycode AS currency, ".
    "paymentconfirmation.amount AS amount, ".
    "publications.title AS ptitle, ".
    "courses.coursename AS coursename ".
    
    //main table
    "FROM  paymentconfirmation ".
    
    //joins
    //"INNER JOIN courses ON student_course_payments.courseid = courses.courseid ".
    "LEFT JOIN publications ON publications.publicationid = paymentconfirmation.merchant_reference ".
    "LEFT JOIN courses ON courses.courseid = paymentconfirmation.merchant_reference ".
    "INNER JOIN currency ON paymentconfirmation.curid = currency.currencyid ".
    
    //conditions
    "WHERE paymentconfirmation.sourceid = '".$_SESSION['sourceid']."' AND membertype = 'student' ".
    
    //ordering
    "ORDER BY paymentconfirmation.paymentconfirmations_id ASC",
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read'
                                            //'show' => 'yes'
                                            ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            //'add' => $link->urlreplace('showcourses','addcourse'),
                                            'export' => '',
                                            'print' => ''
                                            //'delete' => $link->geturl().'&task=del'
                                        ),
                        'pagetitle' => 'Financial Management',
                        'printtitle' => SITENAME.' - Finances',
                        'exceltitle' => 'ICHA Finances Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('ID','Tracking ID','Course Name','Payment Date','Currency','Amount'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('paymentconfirmation'),
                        'displaydbfields' => array('paymentconfirmations_id.int','tracking_id.float','coursename.text','paymentdate.date','currency.text','amount.int'),

                        //the page search parameters
                        'formtitle' => 'Search Finances',
                        'searchmap' => array(1,2,1,1),
                        'searchfields' => array('Course Name','Payment Date'),
                        'searchdbcolumns' => array('coursename','publishdate','startdate','enddate'),
                        'searchfieldtypes' => array('textbox','date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include (ABSOLUTE_PATH.'components/view/table.view.generator.php');
//include (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>