<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<style>
.odd {
    background-color: linen;
}

</style>';

echo '<h1>'.$_GET['name'].'</h1>';

$query = "SELECT 
                    student_tests_assignments.grade AS grade,
                    tests_assignments.`name` AS `name`,
                    tests_assignments.tatype AS `type`,
                    courses.coursename AS coursename
            FROM
                tests_assignments
            INNER JOIN
                student_tests_assignments
            ON
                tests_assignments.tests_assignments_id = student_tests_assignments.tests_assignments_id
            INNER JOIN
                courses
            ON
                courses.courseid = tests_assignments.courseid
            WHERE
                students_id = ".$_GET['id']."
            AND
                courses.expirydate > ".time()."
            AND 
                tests_assignments.instructor_id=".$_SESSION['sourceid'];

$getresource = $this->runquery($query);
$count = $this->getcount($getresource);

if($count > 0){
            $table ='<table width="100%" border="0" cellpadding="0" class="tablelist">'
                    . '<tr class="tabletitle">'
                        . '<th>Name</th>'
                        . '<th>Type</th>'
                        .'<th>Course</th>'
                        . '<th>Grade(%)</th>'
                    . '</tr>';
            $total = 0;
        for($i = 1; $i<=$count;$i++){
            
           $resource = $this->fetcharray($getresource);
           
           
           $table.='<tr '.($i%2 == 0 ? 'class="odd"' : 'class="even"').'>
                        <td valign="center" align="center">'.$resource['name'].'</td>
                        <td valign="center" align="center">'.$resource['type'].'</td>
                        <td valign="center" align="center">'.$resource['coursename'].'</td>
                        <td valign="center" align="center">'.(empty($resource['grade'])?'Not Graded':$resource['grade']).'</td>
                    </tr>';
           $total+=(empty($resource['grade'])?0:$resource['grade']);
        }    
            $table.='<tr class="tabletitle">'
                    . '<th></th>'
                    . '<th></th>'
                    . '<th>Average Grade(%)</th>'
                    . '<td  valign="center" align="center">'.$total/$count.'</td>'
                    . '</tr>'
                    . '</table>';
            echo $table;
}else{
    $this->inlinemessage('No Records Found','valid');
}


