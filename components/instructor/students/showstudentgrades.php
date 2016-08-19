<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadplugin('charts/highcharts','js');
$this->loadplugin('charts/modules/exporting','js');



/*$this->wrapscript("$(function () { 
    $('#graph').highcharts({
        chart: {
            type: 'bar',
            showAxes: true
        },
        title: {
            text: 'Performance by Students'
        },
        xAxis: {
            categories: [".$students."]
        },
        yAxis: {
            title: {
                text: 'Grades Assigned (Percentage)'
            }
        },
        plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
        series: [".$assignments."]
    });
});");*/
//new
$querystr = "SELECT DISTINCT
                   student_tests_assignments.students_id AS id,
                   students.name AS student
            FROM
                    tests_assignments
            INNER JOIN 
                    student_tests_assignments 
            ON
                    tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id
            INNER JOIN 
                    contributors
            ON
                    contributors.contributorid = tests_assignments.instructor_id
            INNER JOIN 
                    students
            ON
                    students.students_id =    student_tests_assignments.students_id 
            INNER JOIN
                    courses
            ON  
                courses.courseid = tests_assignments.courseid
            WHERE 
                courses.expirydate > '".time()."'
            AND
                    contributors.contributorid='".$_SESSION['sourceid']."' ";
 
$queryrun = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queryrun);

for($l=1; $l<=$querycount; $l++)
{
    $getquery = $this->fetcharray($queryrun);
               $students .= "'".$getquery['student']."' ".($l!=$querycount? ', ' : '')."";
            $_SESSION['id'] = $getquery['id'];
                      
              $query = "SELECT 
                             tests_assignments_id AS testid,
                            name
                        FROM 
                               tests_assignments
                       WHERE
                               instructor_id ='".$_SESSION['sourceid']."' 
                       ORDER BY tests_assignments_id ASC";
                        
             $queryrun1 = $this -> runquery($query,'multiple','all');
            $querycount1 = $this -> getcount($queryrun1);
            $assigns_ids = $this -> fetcharray($queryrun1);
                    $_SESSION['testid'] = $assigns_ids['testid'];
                    $assignments = "{
                                name : '".$getquery['student']."' ,
                                data : [ ";
                  
                    
                    $testnamess ="";
             for($i=1; $i <= $querycount1; $i++){
                  
               
                  $testnamess .= "'".substr($assigns_ids['name'],0,12)."' ".($i!=$querycount1? ', ' : '')."";
                 
                  
                 $assigns_ids = $this -> fetcharray($queryrun1);
                 
                    $std_id = $_SESSION['id']."<br/>";
                    $testid = $_SESSION['testid'];
                    
                    $run2 = $this->runquery("select  
                                         *
                                from 
                                        student_tests_assignments
                     
                                where
                                       student_tests_assignments.tests_assignments_id = '".(isset($assigns_ids['testid'])?$assigns_ids['testid'] : $testid )."'
                                and 
                                      student_tests_assignments.students_id ='".$std_id."' ",'single');
                  
                    if(!isset($run2['grade'])){
                        $grade = 0;
                    }else{
                        $grade = $run2['grade'];
                    }                   
                       $marks = $grade.($i!=$querycount1 ? '' : '');
                      $assignments .= "".$marks." ".($i!=$querycount1 ? ','  : '')."";         
             }
             $assignments .="]} ".($l!=$querycount ? ', ' : '')."";
             $data .=$assignments;
             
              
           
              
}



$this->wrapscript("$(function () {
    $('#graph2').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Student Performance (Click student to disable/enable bar)'
        },
        xAxis: {
            categories: [".$testnamess."],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Performance (%)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Percent(%)'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [".$data."]
    });
});");


echo '<h1 class="pagetitle">Students Performance</h1>';

echo '<div id="graph2" style="width:100%; height:350px;"></div>';







$querystr = "SELECT ".
    //the variables
    "student_tests_assignments.student_tests_assignments_id AS student_tests_assignments_id, ".
    "students.registrationid AS registrationid, ".
    "students.name AS student, ".
    "tests_assignments.name AS docname, ".
    "tests_assignments.description AS description, ".
    "tests_assignments.tatype AS type, ".
    "student_tests_assignments.uploaddate AS uploaddate, ".
    "student_tests_assignments.grade AS grade, ".
    "courses.coursename AS coursename ".
    
    //main table
    "FROM tests_assignments".
    
    //student_tests_assignments join
    " INNER JOIN student_tests_assignments ON student_tests_assignments.tests_assignments_id = tests_assignments.tests_assignments_id ".
    //courses join
    " INNER JOIN courses ON tests_assignments.courseid = courses.courseid ".
    //students join
    " INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".
    
    "WHERE courses.expirydate>'".time()."' AND student_tests_assignments.grade >=0 AND student_tests_assignments.uploaddate != 0 AND tests_assignments.instructor_id='".$_SESSION['sourceid']."' ";

$queryrun = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queryrun);

for($l=1; $l<=$querycount; $l++)
{
    $getquery = $this->fetcharray($queryrun);
    
    if($getquery['student']!=$setname)
    {
        $students .= "'".$getquery['student']."'".($l!=$querycount ? ', ' : '');
        
        $setname = $getquery['student'];
    } 
    
    $marks = $getquery['grade'].($l!=$querycount ? '' : '');
    $assignments .= "{
            name : '".$getquery['docname']."',
            data : [".($marks=='' ? '0' : $marks)."]
        }".($l!=$querycount ? ', ' : '');
}

$tableparameters = array('querydata' => array(
                                            'query' => $querystr,
                                            'querytype' => 'multiple', //this specifies if the query should be processed as 'multiple' - many or single - which returns a single mysql_fetch_array
                                            'rpg' => '10', //these are the rows per page set to 'all' for display of all rows in db
                                            'pgno' => $_GET['pageno'], //gets the specific page no
                                            'action' => 'read' 
                                            ),
                        //declares the tools to be included in the page
                        'tools' => array(
                                            'search' => '',
                                            'export' => '',
                                            'print' => '',
                                            //'delete' => $link->geturl().'&task=del'
                                        ),
                        //'pagetitle' => 'Submission Management',
                        'printtitle' => SITENAME.' - Submissions',
                        'exceltitle' => 'ICHA Submissions Excel Document for '.date('d-m-Y',time()),
                        'tablecolumns' => array('Registration No.','Submission Name','Course','Submission Type','Grade %'),
                        //the first table is the primary table, any other tables to be used to generate the table contents
                        'dbtables' => array('student_tests_assignments'),
                        'displaydbfields' => array('registrationid.text','docname.text','coursename.text','type.text','grade.text'),

                        //the page search parameters
                        'formtitle' => 'Search Submissions',
                        'searchmap' => array(1,2,2),
                        'searchfields' => array('Course Name','Publish Date','Start Date','End Date'),
                        'searchdbcolumns' => array('coursename','publishdate','startdate','enddate'),
                        'searchfieldtypes' => array('textbox','date','start date','end date')
                         );

include_once (ABSOLUTE_PATH.'components/view/table.view.generator.php');

/*
$this->wrapscript("$(function () { 
    $('#graph2').highcharts({
        chart: {
            type: 'column',
            showAxes: true
        },
        title: {
            text: 'Performance by Assignments'
        },
        xAxis: {
            categories: ['Assignment 1','Assignment 2','Assignment 3','Assignment 4']
        },
        yAxis: {
            title: {
                text: 'Grades Assigned (Percentage)'
            }
        },
        plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
        series: [{
            name: 'Student 1',
            data: [10,75,90,70]
        }, {
            name: 'Student 2',
            data: [47,54,48,24]
        }]
    });
});");

echo '<div id="graph2" style="width:100%; height:350px;"></div>';
 * 
 */
?>
