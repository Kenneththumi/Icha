<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('charts/highcharts','js');
$this->loadplugin('charts/modules/exporting','js');

echo '<h1 class="pagetitle">My Grades</h1>';

$this->wrapscript("$(document).ready(function(){
                        $('a.showcomments').fancybox({
                                    'width': 700,
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
                                    'hideOnOverlayClick': false,
                                    'centerOnScroll': true,
                                    'type':'iframe'
                                });
                         });");

$assignments = $this->runquery("SELECT DISTINCT ".
    "tests_assignments.*, ".
    "student_tests_assignments.* ".
    "FROM tests_assignments ".
    "LEFT JOIN student_courses ON tests_assignments.courseid = student_courses.courseid ".
    "LEFT JOIN student_tests_assignments ON tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id ".
    "WHERE student_tests_assignments.students_id = '".$_SESSION['sourceid']."' AND grade != 0 AND grade IS NOT NULL",'multiple','all');

$assigncount = $this->getcount($assignments);

if($assigncount>='1')
{
    for($r=1; $r<=$assigncount; $r++)
    {
        $assign = $this->fetcharray($assignments);
        $assigntxt .= "['".$assign['name']." / ".$assign['tatype']."', ".($assign['grade']=='' ? '0' : $assign['grade'])." ]";

        if($r!=$assigncount)
        {
            $assigntxt .= ',';
        }

        $graphtitle .= "'".$assign['name']." / ".$assign['tatype']."'".($r!=$assigncount ? ',' : '');
    }

    $this->wrapscript("$(function () { 
        $('#graph2').highcharts({
            chart: {
                type: 'bar',
                showAxes: true
            },
            title: {
                text: 'Performance by Assignments and Tests'
            },
            xAxis: {
                categories: [".$graphtitle."]
            },
            yAxis: [{
                title: {
                    text: 'Percentage'
                }
            }],
            plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
            series: [{
                    name: 'Grade (Percentage)',
                    data: [
                        ".$assigntxt."
                    ],
                    dataLabels: {
                        enabled: true,
                        color: '#FFFFFF',
                        align: 'right',
                        x: -8,
                        y: 5,
                        style: {
                            fontSize: '17px',
                            fontFamily: 'Verdana, sans-serif',
                            textShadow: '0 0 3px black'
                        }
                    }
                }]

        });
    });");

    echo '<div id="graph2" style="width:100%; height:350px;"></div>';
}

$link = new navigation();

$tableparameters = array('querydata' => array(
                                        'query' => "SELECT DISTINCT ".
    "tests_assignments.*, ".
    "student_tests_assignments.* ".
    "FROM tests_assignments ".
    "LEFT JOIN student_courses ON tests_assignments.courseid = student_courses.courseid ".
    "LEFT JOIN student_tests_assignments ON tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id ".
    "WHERE student_tests_assignments.grade != 0  AND student_tests_assignments.students_id = '".$_SESSION['sourceid']."'",
                                        'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                        'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                        'pgno' => $_GET['pageno'], //gets the specific page no
                                        'action' => 'read'
                                        ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                        'search' => '',
                                        //'add' => $link->urlreplace('showtests','addtestassignment'),
                                        'export' => '',
                                        'print' => '',
                                        //'delete' => $link->geturl().'&task=del'
                                        ),
                        'image' => array('columns'=>array('Comments' => array(
                            'src' => DEFAULT_TEMPLATE_PATH.'/student/images/comments.png',
                            'target' => '_blank',
                            'class' => 'showcomments',
                            'link' => '?content=com_students&folder=grades&file=showComments&alert=yes'))),
                        //'pagetitle' => 'Test & Assignment Management',
                        'printtitle' => SITENAME.' - Test and Assignments',
                        'exceltitle' => 'ICHA Tests and Assignments Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('ID','Name','Description','Linked Course','Type','Grade %','Comments'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('student_tests_assignments'),
                        'displaydbfields' => array('tests_assignments_id.int','name.text','description.longtext','coursename.text.(courses)','tatype.text','grade.int'),

                        //the page search parameters
                        'formtitle' => 'Search Tests and Assignments',
                        'searchmap' => array(1,3),
                        'searchfields' => array('Name','Creation Date','Due Date'),
                        'searchdbcolumns' => array('name','creationdate','duedate'),
                        'searchfieldtypes' => array('textbox','date','date')
                        //'Client_values' => $clients,
                        //'Classification_values' => array(''=>'Select Rating', 'GE' => 'GE (General Exhibition)', 'PG' => 'PG (Parental Guidance Required)','+16' => '+16 (Not Suitable for persons under 16)','+18' => '+18 (Restricted to 18 and Above)')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');
?>
