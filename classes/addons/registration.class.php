<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

class registration extends database
{
	function registerSubscriber($userDetails)
	{
            $skin = new template;
            
            $email = $userDetails['email'];
            
            $subscribers = $this->runquery("SELECT * FROM subscribers WHERE email='$email'", 'multiple','all');
            $subcount = $this->getcount($subscribers);

            if(is_array($userDetails)&&$subcount=='0')            
            {
                    $saveSubscriber = array(
                                            'name' => $userDetails['fname'].'-'.$userDetails['lname'],
                                            'mobileno' => $userDetails['mobileno'],
                                            'email' => $userDetails['email'],
                                            'enabled' => 'yes',
                                            'regdate' => time()
                                            );

                    //save the subscriber
                    $this->dbinsert('subscribers',$saveSubscriber);			
                    $subid = $this->previd('subscribers','subid');

                    //save the new user details
                    $username = $userDetails['email'];
                    $password = $this->createPassword();

                    $uservalues = array(
                                    'username' => $username,
                                    'password' => $password,
                                    'sourceid' => $subid,
                                    'enabled' => 'yes',
                                    'accesslevelid' => '16'
                                    );

                    $this->dbinsert('users',$uservalues);

                    //send the registration details
                    $this->sendRegistration($userDetails['email'],$username,$password,$userDetails['name']);		
            }
            elseif($subcount>='1')
            {
                $userid = user::returnAccessDetails('sub','id');
                
                $uservalues = $this->runquery("SELECT * FROM users WHERE username='$email' AND accesslevelid='$userid'", 'single');
            }
            
            return $uservalues;
	}
        
        function registerStudent($details)
        {
            //check for student
            $student = $this->runquery("SELECT * FROM students WHERE emailaddress='".$details['emailaddress']."'",'multiple','all');
            $studentcount = $this->getcount($student);
            
            //check for course
            $cfolder = $this->runquery("SELECT * FROM contributors INNER JOIN courses ON courses.contributorid = contributors.contributorid WHERE courses.courseid = '".$details['course']."'",'single');
            
            if($cfolder == false){
                $contfolder = 'contributors';
            }
            else{
                $contfolder = $cfolder['filename'];
            }
            
            if($studentcount=='0'){
                
                $savestudent = false;
                
                //generate username/registration number based on format ICHA/ reg number / reg year
                $stds = $this->runquery("SELECT * FROM students ORDER BY students_id DESC",'single');
                
                $sid = $stds['students_id'];
                $sid++;
                
                $regno = sprintf('%05d', $sid);
                $year = date('Y');

                $regid = 'ICHA/'.$regno.'/'.$year;
                        
                $student = array(
                    'registrationid' => $regid,
                    'name' => $details['name'],
                    'idno' => $details['idno'],
                    'emailaddress' => $details['emailaddress'],
                    'mobile' => $details['mobile'],
                    'nationality' => $details['nationality'],
                    'organisation' => $details['organisation'],
                    'orgaddress' => $details['orgaddress'],
                    'emergencydetails' => $details['emergencydetails'],
                    'approved' => 'no',
                    'paid' => 'no',
                    'registrationdate' => time()
                );
                
                $savestudent = $this->dbinsert('students', $student);
                
                if($savestudent){
                    
                    $id = mysql_insert_id();
                    
                    //create the student folder
                    $name = strtolower(str_replace(' ','',$details['name']));
                    $foldername = $this->shortentxt($name,'8','no');
                    
                    $ds = DIRECTORY_SEPARATOR;

                    if(!is_dir(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername))
                    {
                        //create student folder
                        mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername,0777);
                        chmod(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername,0777);
                        
                        //create tests and assignments folder
                        mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername.'/tests_and_assignments',0777);
                        chmod(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername.'/tests_and_assignments',0777);
                        
                        //create marked papers folder
                        mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername.'/marked_papers',0777);
                        chmod(ABSOLUTE_MEDIA_PATH.'training/'.$contfolder.'/students/'.$foldername.'/marked_papers',0777);

                        //copy the config file from the file manager folder
                        copy(ABSOLUTE_PATH . 'plugins/filemanager/filemanager/subconfig/student/config.php', ABSOLUTE_MEDIA_PATH . 'training/'.$contfolder.'/students/' . $foldername . '/config.php');
                    }
                    
                    //create students registration folder
                    if(!is_dir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername)){
                        
                        mkdir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername,0777);
                        chmod(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername,0777);
                    }
                    
                    $foldersave = ['foldername' => $foldername];                        
                    $this->dbupdate('students',$foldersave,"students_id = '$id'");
                    
                    $student_course = array(
                        'students_id' => $id,
                        'courseid' => $details['course']
                    );
                    
                    $this->notifyAdmin($id, $details['course']);
                    $this->dbinsert('student_courses', $student_course);
                    
                    $details = array(
                        'registrationid' => $regid,
                        'status' => 'new',
                        'id' => (int)$id,
                        'folder' => $foldername
                    );
                    
                    return $details;
                }
                else
                {
                    $this->print_last_error();
                }
            }
            else
            {
                //get the folder name
                $getdetails = $this->fetcharray($student);
                $foldername = $getdetails['foldername'];
                
                if($this->is_empty_dir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername))
                {
                    $status = 'no_documents';
                }
                else
                {
                    $status = 'student_present';
                }
                
                return array(
                    'id' => $getdetails['students_id'],
                    'status' => $status,
                    'folder' => $foldername
                    );
            }
            
        }
	
	function notifyAdmin($id,$courseid)
	{
            //get student
            $student = $this->runquery("SELECT * FROM students WHERE students_id='$id'",'single');
            
            //get course
            $course = $this->runquery("SELECT * FROM courses WHERE courseid='$courseid'",'single');
            
		//send the email
		$emailtxt = '
Greetings ,

This is to inform you that a new student has registered for '.$course['coursename'].', please log into the Administrator and verify the details below.

Registration Number: '.$student['registrationid'].'
Name: '.$student['name'].'
Email Address: '.$student['emailaddress'].'

Thanks 
'.SITENAME;

		$emailhtml = '<html><p>Greetings</p><p>This is to inform you that a new student has registered for '.$course['coursename'].', please log into the Administrator and verify the details below.</p>

		<p>Registration Number: '.$student['registrationid'].'</p>
                <p>Name: '.$student['name'].'</p>
		<p>Email Address: '.$student['emailaddress'].'</p>
                    <p></p>
		<p>Thanks <br/>'.SITENAME.'</p></html>';
		
		//send email
		$this->multiPartMail(MAILADMIN,'New Student Registration for '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
                
                //$this->multiPartMail('sngumo@gmail.com','New Student Registration for '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
                //$this->multiPartMail('kaguimah@gmail.com','New Student Registration for '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
                
                //$this->multiPartMail('korir.belinda@icha.net','New Student Registration for '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
                //$this->multiPartMail('koome.agnes@icha.net','New Student Registration for '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
                
		return true;
	}
        
        function sendRegistration($email,$username,$password,$DisplayName)
	{
            //get the course
            $course = $this->runquery("SELECT courses.coursename AS course FROM courses ".
                    "INNER JOIN student_courses ON student_courses.courseid = courses.courseid ".
                    "INNER JOIN students ON student_courses.students_id = students.students_id ".
                    "WHERE registrationid = '$username'",'single');
            
		//send the email
		$emailtxt = '
Dear '.$DisplayName.',

Congratulations on your admission to the International Center for Humanitarian Affairs to take a course in '.$course['course'].'

Kindly use the login details below to access your online students’ portal.  Please don’t delete this email, so as to use it for reference later.

Username: '.$username.'
Password: '.$password.'

The above details can be changed from your '.SITENAME.' profile.

Thanks 
Administrator
'.SITENAME;

		$emailhtml = '<html><p>Dear '.$DisplayName.'</p>
                    <p>Congratulations on your admission to the International Center for Humanitarian Affairs to take a course in '.$course['course'].'</p>
                    <p>Kindly use the login details below to access your online students’ portal.  Please don’t delete this email, so as to use it for reference later.</p>

		<p>Username: '.$username.'</p>
		<p>Password: '.$password.'</p>
		<p>The above details can be changed from your '.SITENAME.' profile. </p>
                
                <p>Thanks <br/>Administrator<br/>'.SITENAME.'</p></html>';
		
		//send email
		$this->multiPartMail($email,'ICHA Registration Details',$emailhtml,$emailtxt,MAILFROM,SITENAME);
		
		return true;
	}
        
        function sendConfirmation($email,$registrationid,$DisplayName)
	{
		//send the email
		$emailtxt = '
Congratulations '.$DisplayName.',

This is a confirmation email of your acceptance into the International Center for Humanitarian Affairs.

Your Student ID is '.$registrationid.'

Thanks 
'.SITENAME;

		$emailhtml = '<html><p>Congratulations '.$DisplayName.'</p><p>This is a confirmation email of your acceptance into the International Center for Humanitarian Affairs.</p><p>Below are your login details, please dont delete this email, so as to use it for reference later.</p>

		<p>Your Student ID is '.$registrationid.'</p>Thanks <br/>'.SITENAME.'</p></html>';
		
		//send email
		$this->multiPartMail($email,'ICHA Registration Details',$emailhtml,$emailtxt,MAILFROM,SITENAME);
		
		return true;
	}
}
?>