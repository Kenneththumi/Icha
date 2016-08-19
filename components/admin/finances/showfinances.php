<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

function getName($params)
{
    $db = new database;
    
    if($params['type']=='subscriber')
    {
        $chk = $db->runquery("SELECT * FROM subscribers WHERE subid='".$params['id']."'",'single');
        $name = $chk['name'];
    }
    elseif($params['type']=='student')
    {
        $chk = $db->runquery("SELECT * FROM students WHERE students_id='".$params['id']."'",'single');
        $name = $chk['name'];
    }
    
    return $name;
}

function getID($params)
{
    $db = new database;
    
    if($params['type']=='subscriber')
    {
        $id = $params['id'];
    }
    elseif($params['type']=='student')
    {
        $chk = $db->runquery("SELECT * FROM students WHERE students_id='".$params['id']."'",'single');
        $id = $chk['registrationid'];
    }
    
    return $id;
}


$tableparameters = array('querydata' => array(
                                            'query' => "SELECT ".
    //variables
    "paymentconfirmation.paymentconfirmations_id AS paymentconfirmations_id, ".
    "paymentconfirmation.tracking_id AS tracking_id, ".
    "paymentconfirmation.membertype AS membertype, ".
    "paymentconfirmation.sourceid AS sourceid, ".
    "paymentconfirmation.paymentdate AS paymentdate, ".
    "paymentconfirmation.status AS status, ".
    "currency.currencycode AS currency, ".
    "paymentconfirmation.amount AS amount, ".
    "publications.title AS ptitle, ".
    "courses.coursename AS coursename ".
    
    //main table
    "FROM  paymentconfirmation ".
    
    //joins
    "LEFT JOIN students ON students.students_id = paymentconfirmation.paymentconfirmations_id ".
    "LEFT JOIN publications ON publications.publicationid = paymentconfirmation.merchant_reference ".
    "LEFT JOIN courses ON courses.courseid = paymentconfirmation.merchant_reference ".
    "INNER JOIN currency ON paymentconfirmation.curid = currency.currencyid ".
    
    //conditions
    //"WHERE student_course_payments.student_id = '".$_SESSION['sourceid']."' ".
    
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
                        'functions' => array(
                            'Name' => array(
                                'name' => 'getName',
                                'paramtype' => array('id','type'),
                                'paramcolumn' => array('sourceid','membertype')                                     
                                ),
                            'Payee ID' => array(
                                'name' => 'getID',
                                'paramtype' => array('id','type'),
                                'paramcolumn' => array('sourceid','membertype')                                     
                                )
                        ),
                        'pagetitle' => 'Financial Management',
                        'printtitle' => SITENAME.' - Finances',
                        'exceltitle' => 'ICHA Finances Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Tracking ID','Payee ID','Name','Type','Course Name','Publication Title','Payment Date','Amount (KSH)','Status'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('paymentconfirmation'),
                        'displaydbfields' => array('tracking_id.float','','','membertype.text','coursename.text','ptitle.text','paymentdate.date','amount.int','status.text'),

                        //the page search parameters
                        'formtitle' => 'Search Finances',
                        'searchmap' => array(1),
                        'searchfields' => array('Tracking ID'),
                        'searchdbcolumns' => array('tracking_id'),
                        'searchfieldtypes' => array('textbox')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include (ABSOLUTE_PATH.'components/view/table.view.generator.php');
//include (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>