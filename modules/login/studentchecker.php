<?php

$studentid = $type_array['sourceid'];

$course = $this->runquery("SELECT ".
        "courses.courseid AS courseid, ".
        "courses.enddate AS enddate ".
        "FROM student_courses ".
        "INNER JOIN courses ON student_courses.courseid = courses.courseid ".
        "WHERE student_courses.students_id = '".$studentid."'",'single');
//enddate
$enddate = $course['enddate'];

//expiry after 3 months
$expirydate = strtotime("+3 month", $enddate);
echo "<script>alert('kweli')</script>";
//if currnt timestamp greater than expiry timestamp your session is expired 
if(time() > $expirydate){
  return 'student_expired';
  
  echo "<script>alert('kweli')</script>";
  
}
    

