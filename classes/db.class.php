<?php
//prevents direct access of these files
//defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

// constants used by class
define('MYSQL_TYPES_NUMERIC', 'int real ');
define('MYSQL_TYPES_DATE', 'datetime timestamp year date time ');
define('MYSQL_TYPES_STRING', 'string blob ');

class database 
{	
	var $sql_query;
	var $sql_result;
	var $last_error;         // holds the last error. Usually mysql_error()
        var $last_query;         // holds the last query executed.
        var $row_count;          // holds the last number of rows from a select
	var $prompt;
	public $auto_slashes;
	
	public function __construct(){
            
            //define the connection settings
            $config = new M3Config;

            if($_SERVER['HTTP_HOST']=='localhost'
                    ||$_SERVER['HTTP_HOST']=='127.0.0.1'
                    ||$_SERVER['HTTP_HOST']=='192.168.0.13'){

                    //local settings
                    @mysql_connect($config->localhost,$config->localuser, $config->localpassword)or die("<h2>Could not connect to the database (local)</h2>");
                    @mysql_select_db($config->localdb);	
            }	
            else{
                
                    //remote settings
                    $conn = mysql_connect($config->remotehost,$config->remoteuser, $config->remotepassword)or die("<h2>Could not connect to the database (remote)</h2>");
                    
                    /**
                    if(!mysql_query("GRANT ALL PRIVILEGES ON ".$config->remotedb.".* TO '".$config->remoteuser."' WITH GRANT OPTION", $conn)){
                        
                        $this->last_error = mysql_error();
                        $this->print_last_error();
                    }
                     * 
                     */
                    
                    mysql_select_db($config->remotedb, $conn)or die('<h4>Database: '.$config->remotedb.' not selected</h4> Error: '.  mysql_error($conn));
            }	
	}
	
	//performs a mysql_query and subsequent mysql_fetch_array operation on the query string
        
	public function runquery($query_str,$type='multiple',$rpg='10',$pgNo='1',$action='read')
	{
		//the $type variable determines whether mysql_fetch_array will be performed or not
			if($action=='read')
			{
				//process the pages for the various pages
				$query = mysql_query($query_str);
				$qCount = $this->getcount($query);
				
				if($rpg=='all')
				{
					$rpg = $qCount;
				}
				
				$this->loadextraClass('paginate');
					
				$paginator = new pagination;
				$pages = $paginator->calculatePages($qCount,$rpg,$pgNo);
					
				$query_str = $query_str.' '.$pages['limit'];
			}
			
			//run the query with its limits
			$sql_query = mysql_query($query_str);
			$this->last_query = $query_str;
			
			if($sql_query)
			{				
				if($type=='single')
				{
					//return the processed query
					return $sql_result = @mysql_fetch_array($sql_query);
				}
				else if($type == 'multiple')
				{
					//return the raw query for processing later
					return $sql_query;
				}
			}
			else
			{
                            
				$this->last_error = mysql_error();
				$this->print_last_error();
			}
		
	}
	
	public function rawquery($sql)
	{		
		$rsql = mysql_query($sql);
		$this->last_query = $query_str;
		
		if($rsql)
		{
			return $rsql;
		}
		else
		{
			return $this->last_error = mysql_error();
		}
	}
	
	public function catchFatalErrors($p_OnOff='On')
	{
            ini_set('display_errors','On');
            $phperror='><div id="phperror" style="display:none">';

            ini_set('error_prepend_string',$phperror);

            $phperror='</div>><form name="catcher" action="/sos.php" method="post" ><input type="hidden" name="fatal"  value=""></form> <script> document.catcher.fatal.value = document.getElementById("phperror").innerHTML; document.catcher.submit();</script>';
            ini_set('error_append_string',$phperror);
	} 
        
	public function getcount($query)
	{ 
		$count = @mysql_num_rows($query);
		
		if($count>=1)
		{
			return $count;
		}
		else
		{
			return "0";
		}
	}
	
        public function get_primary_key($table)
        {
            $sql = "SHOW INDEX FROM $table WHERE Key_name = 'PRIMARY'";
            
            $gp = mysql_query($sql);
            $cgp = mysql_num_rows($gp);
            
            if($cgp > 0)
            {
                // Note I'm not using a while loop because I never use more than one prim key column
                $agp = mysql_fetch_array($gp);
                extract($agp);
                
                return($Column_name);
            }
            else
            {
                return(false);
            }
        }
        
	function fetcharray($query,$type='MYSQL_BOTH')
	{
		  // Returns a row of data from the query result.  You would use this
		  // function in place of something like while($row=mysql_fetch_array($r)). 
		  // Instead you would have while($row = $db->get_row($r)) The
		  // main reason you would want to use this instead is to utilize the
		  // auto_slashes feature.
		  
		  if (!$query) {
			 $this->last_error = "Invalid resource identifier passed to get_row() function.";
			 return false;  
		  }
		  
		  if ($type == 'MYSQL_ASSOC') $row = mysql_fetch_array($query, MYSQL_ASSOC);
		  if ($type == 'MYSQL_NUM') $row = mysql_fetch_array($query, MYSQL_NUM);
		  if ($type == 'MYSQL_BOTH') $row = mysql_fetch_array($query, MYSQL_BOTH); 
		  
		  if (!$row) return false;
		  if ($this->auto_slashes) {
			 // strip all slashes out of row...
			 foreach ($row as $key => $value) {
				$row[$key] = stripslashes($value);
			 }
		  }
		  return $row;
	}
	
	public function fetchrow($query)
	{
		return mysql_fetch_row($query);
	}
	
	public function previd($tablename,$variable,$order='desc')
	{
		$ins_string = "SELECT ".$variable." FROM ".$tablename." ORDER BY ".$variable." ".$order;
		$ins_array = $this->runquery($ins_string,'single');
		
		return $ins_array[$variable];
	}
	
	public function deleterow($tablename,$columnid,$value)
	{
		$del_str = "DELETE FROM ".$tablename." WHERE ".$columnid." = '".$value."'";
		$del_query = $this->rawquery($del_str);
		
		if($del_query)
		{
			return true;
		}
		else
		{
			return $del_query;
		}
	}
	
	public function listrows($tablename,$by,$order='ASC',$criteria='',$limit='')
	{
		if($criteria!='')
		{
			$sqlcriteria = "WHERE ".$criteria;
		}
		else
		{
			$sqlcriteria = '';
		}
		
		$order_str = "SELECT * FROM ".$tablename."  ".$sqlcriteria." ORDER BY ".$by." ".$order;
		$orderquery = $this->runquery($order_str,'multiple',$limit);
		
		if($orderquery)
		{
			return $orderquery;
		}
		else
		{
			echo $orderquery;
		}
	}
	
	function sql_pointer($sql) 
	{      
      // Performs an SQL query and returns the result pointer or false
      // if there is an error.
 
      $this->last_query = $sql;
      
      $r = mysql_query($sql);
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      $this->row_count = mysql_num_rows($r);
      return $r;
   }
	
	function dump_query($sql) 
	{   
      // Useful during development for debugging  purposes.  Simple dumps a 
      // query to the screen in a table.
 
      $r = $this->sql_pointer($sql);
      if (!$r) return false;
      echo "<div style=\"border: 1px solid blue; font-family: sans-serif; margin: 8px;\">\n";
      echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
      
      $i = 0;
      while ($row = mysql_fetch_assoc($r)) {
         if ($i == 0) {
            echo "<tr><td colspan=\"".sizeof($row)."\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
            echo "<tr>\n";
            foreach ($row as $col => $value) {
               echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
            }
            echo "</tr>\n";
         }
         $i++;
         if ($i % 2 == 0) $bg = '#E3E3E3';
         else $bg = '#F3F3F3';
         echo "<tr>\n";
         foreach ($row as $value) {
            echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$value</span></td>\n";
         }
         echo "</tr>\n";
      }
      echo "</table></div>\n";
   }
	
	function dbinsert($table, $data_array) 
	{      
      // Inserts a row into the database from key->value pairs in an array. The
      // array passed in $data must have keys for the table's columns. You can
      // not use any MySQL functions with string and date types with this 
      // function.  You must use insert_sql for that purpose.
      // Returns the id of the insert or true if there is not auto_increment
      // column in the table.  Returns false if there is an error.
      
      if (empty($data_array)) {
         $this->last_error = "You must pass an array to the insert_array() function.";
         return false;
      }
      
      $cols = '(';
      $values = '(';
      
      foreach ($data_array as $key=>$value) {     // iterate values to input
          
         $cols .= "$key,";  
         
         $col_type = $this->get_column_type($table, $key);  // get column type
         if (!$col_type) return false;  // error!
         
         // determine if we need to encase the value in single quotes
         if (is_null($value)) {
            $values .= "NULL,";   
         } 
         elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
            $values .= "$value,";
         }
         elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
            $value = $this->sql_date_format($value, $col_type); // format date
            $values .= "'$value',";
         }
         elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
            if ($this->auto_slashes) $value = addslashes($value);
            $values .= "'$value',";  
         }
      }
      $cols = rtrim($cols, ',').')';
      $values = rtrim($values, ',').')';     
     
      // insert values
      $sql = "INSERT INTO $table $cols VALUES $values";
	  
      return $this->runquery($sql,'multiple',0,0,'write');
   }
	
	function dbupdate($table, $data_array, $condition) {
      
      // Updates a row into the database from key->value pairs in an array. The
      // array passed in $data must have keys for the table's columns. You can
      // not use any MySQL functions with string and date types with this 
      // function.  You must use insert_sql for that purpose.
      // $condition is basically a WHERE claus (without the WHERE). For example,
      // "column=value AND column2='another value'" would be a condition.
      // Returns the number or row affected or true if no rows needed the update.
      // Returns false if there is an error.
      
      if (empty($data_array)) {
         $this->last_error = "You must pass an array to the update_array() function.";
         return false;
      }
      
      $sql = "UPDATE $table SET";
      foreach ($data_array as $key=>$value) {     // iterate values to input
          
         $sql .= " $key=";  
         
         $col_type = $this->get_column_type($table, $key);  // get column type
         if (!$col_type) return false;  // error!
         
         // determine if we need to encase the value in single quotes
         if (is_null($value)) {
            $sql .= "NULL,";   
         } 
         elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
            $sql .= "$value,";
         }
         elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
            $value = $this->sql_date_format($value, $col_type); // format date
            $sql .= "'$value',";
         }
         elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
            if ($this->auto_slashes) $value = addslashes($value);
            $sql .= "'$value',";  
         }

      }
      $sql = rtrim($sql, ','); // strip off last "extra" comma
      if (!empty($condition)) $sql .= " WHERE $condition";
      
      // insert values
      return $this->runquery($sql,'multiple',0,0,'write');
   }
   
   function get_column_type($table, $column) {
      
      // Gets information about a particular column using the mysql_fetch_field
      // function.  Returns an array with the field info or false if there is
      // an error.
 
      $r = mysql_query("SELECT $column FROM $table");
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      $ret = mysql_field_type($r, 0);
      if (!$ret) {
         $this->last_error = "Unable to get column information on $table.$column.";
         mysql_free_result($r);
         return false;
      }
      mysql_free_result($r);
      return $ret;
      
   }
	
	function print_last_error($show_query=true) 
	{      
      // Prints the last error to the screen in a nicely formatted error message.
      // If $show_query is true, then the last query that was executed will
      // be displayed aswell.
 
      ?>
      <div style="border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
         <span style="font-weight: bold">db.class.php Error:</span><br><?php echo $this->last_error; ?>
      </div>
      <?php
      if ($show_query && (!empty($this->last_query))) {
      $this->print_last_query();
      } 
   }

   function print_last_query() {
    
      // Prints the last query that was executed to the screen in a nicely formatted
      // box.
     
      ?>
      <div style="border: 1px solid blue; font-size: 9pt; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF">
         <span style="font-weight: bold">Last SQL Query:</span><br><?= str_replace("\n", '<br>', $this->last_query) ?>
      </div>
      <?php  
   }
	
	function smsCredits($credits,$uid,$utype)
	{
		$smsquery = $this->runquery("SELECT * FROM smscredits WHERE sourceid='$uid' AND usertype='$utype'");
		$smscount = $this->getcount($smsquery);		
		
		if($smscount>=1)
		{
			$sms = $this->fetcharray($smsquery);
			
			$creditamt = $credits;
			
			$smsArray = array(
						  'credits'=>$sms['credits']+$creditamt,
						  'sourceid'=>$uid,
						  'usertype'=>$utype
						  );
			
			$this->dbupdate('smscredits',$smsArray,"sourceid='$uid' AND usertype='$utype'");
		}
		else
		{
			$smsArray = array(
						  'credits'=>$credits,
						  'sourceid'=>$uid,
						  'usertype'=>$utype
						  );
			
			$this->dbinsert('smscredits',$smsArray);
		}
	}
	
	public function sendSms($msg,$number)
	{		
		$this->prompt = new template;
		
		$user = "user";
		$password = "password";
		$api_id = "xxxx";
		$baseurl ="http://api.clickatell.com";
		$text = urlencode($msg);
		$to = $number;
		
		// auth call
		$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
		
		// do auth call
		$ret = file($url);
		
		// split our response. return string is on first line of the data returned
		$sess = split(":",$ret[0]);
		
		if ($sess[0] == "OK") 
		{
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
			
			// do sendmsg call
			$ret = file($url);
			$send = split(":",$ret[0]);
			
			if ($send[0] == "ID")
			{
				//$this->prompt->inlinemessage("Message ID: ". $send[1]." Sent",'valid');
				return TRUE;
			}
			else
			{
				//$this->prompt->inlinemessage("Send message failed",'error');
				return FALSE;
			}
		} 
		else 
		{
			//$prompt->inlinemessage("Authentication failure: ". $ret[0],'error');
			return FALSE;
		}
	}
	
	public function multiPartMail ($to, $subject, $html_message, $text_message, $from_address, $from_display_name='')
	{
		$this->prompt = new template;
		
		$email_from_addr = $from_address; // sender email
		$email_from_name = $from_display_name; // name to
		$email_subject =  $subject; // subject of the email
		$email_txt = $text_message; // text version of the email
		$email_htm = $html_message; // html version of the email
		$email_to = $to; // email to
		$headers = $email_from_name == '' ? "From: ".$email_from_addr : "From: ".$email_from_name." <".$email_from_addr.">";
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		$headers .= "\nMIME-Version: 1.0\n" . 
					"Content-Type: multipart/alternative;\n" . 
					" boundary=\"{$mime_boundary}\""; 
		$email_message .= "This is a multi-part message in MIME format.\n\n" . 
							"--{$mime_boundary}\n" . 
							"Content-Type:text/plain; charset=\"iso-8859-1\"\n" . 
							"Content-Transfer-Encoding: 7bit\n\n" . 
							$email_txt . "\n".
							"--{$mime_boundary}\n" . 
							"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
							"Content-Transfer-Encoding: 7bit\n\n" . 
							$email_htm ;
		
		@mail($email_to, $email_subject, $email_message, $headers);
	}
	
	function loadclasses()
	{
		$directory = opendir(ABSOLUTE_PATH.'classes');
		
		while(false!==$file=readdir($directory))
		{
			if($file!='.'&&$file!='..'&&$file!='db.class.php'&&$file!='addons')
			{
				include_once(ABSOLUTE_PATH.'classes/'.$file);
			}
		}
		
		closedir($directory);
	}
	
	function is_empty_dir($dir)
	{
		if ($dh = @opendir($dir))
		{
			while ($file = readdir($dh))
			{
				if ($file != '.' && $file != '..') {
					closedir($dh);
					return false;
				}
			}
			closedir($dh);
			return true;
		}
		else return false; // whatever the reason is : no such dir, not a dir, not readable
	}
	
	function loadextraClass($file)
	{
		$directory = opendir(ABSOLUTE_PATH.'classes/addons/');
		
		include_once(ABSOLUTE_PATH.'classes/addons/'.$file.'.class.php');
		
		closedir($directory);
	}
	
	
	function shortentxt($entext,$charno,$addDots='yes'){
            
        // Change to the number of characters you want to display
        $text = $entext." ";

        $shortText = substr($text,0,$charno);
		
		if(strlen($entext)>=$charno)
		{
                    
        	return $shortText.($addDots=='yes' ? " ..." : '');
		}
		else
		{
        	return $text;
		}
        }
        
        //clean up a string	
	function strClean($text)
	{
		$text=strtolower($text);
		
		$code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
		$code_entities_replace = array('-','-','','','','','','','','','','','','','','','','','','','','','','','','');
		
		$text = str_replace($code_entities_match, $code_entities_replace, $text);
		
		return ucfirst($text);
	} 
        
        function array_delete(&$array, $value, $strict = TRUE) 
        {
            $count = 0;
            if ($strict) {
                foreach ($array as $key => $item) {
                    if ($item === $value) {
                        $count++;
                        unset($array[$key]);
                    }
                }
            } else {
                foreach ($array as $key => $item) {
                    if ($item == $value) {
                        $count++;
                        unset($array[$key]);
                    }
                }
            }
            return $count;
        }
	
	function findText($start_limiter,$end_limiter,$haystack,$invert='no')
	{
		$startno = strlen($start_limiter);
		$endno = strlen($end_limiter);
		
		$start_pos = strpos($haystack,$start_limiter)+$startno;
		
		if ($start_pos === FALSE)
		{
			return FALSE;
		}
		
		$end_pos = strpos($haystack,$end_limiter);
		
		if ($end_pos === FALSE)
		{
			return FALSE;
		}
		
		if($invert=='no')
		{
			return substr($haystack, $start_pos, ($end_pos)-$start_pos);
		}
		else
		{
			return substr($haystack,($end_pos+1)+$startno);
		}
	}
	//validates the reCaptcha field
	function chkCaptcha($privatekey,$challenge,$response)
	{
            require_once(ABSOLUTE_PATH.'/plugins/classForm/includes/recaptchalib.php');
            $response = recaptcha_check_answer($privatekey,$_SERVER['REMOTE_ADDR'],$challenge,$response);

            if($response->is_valid)																															
            {
                return 'true';
            }
            else
            {
                return $response->error;
            }
	}
	
	//generates a random Password
	function createPassword() 
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";	
		srand((double)microtime()*1000000);
	
		$i = 0;	
		$pass = '' ;	
	
		while ($i <= 7) 
		{	
			$num = rand() % 33;	
			$tmp = substr($chars, $num, 1);
	
			$pass = $pass.$tmp;
	
			$i++;	
		}	
	
		return $pass;	
	}
        
        public function createAllCourses() {
            
            //create all the course folders
            $courses = $this->runquery("SELECT * FROM courses",'multiple','all');
            $coursecount = $this->getcount($courses);

            $filepath = ABSOLUTE_MEDIA_PATH . 'training/courses/';

            for($r=1; $r<=$coursecount; $r++){

                $course = $this->fetcharray($courses);

                if(!file_exists($filepath.$course['coursename'])){
                    @mkdir($filepath.trim($course['coursename']), 0777);
                }
                else{
                    @mkdir($filepath.trim($course['coursename'].'_2'), 0777);
                }
            }
        }
        
        public function createAllStudents() {
            
            //create all the course folders
            $students = $this->runquery("SELECT * FROM students",'multiple','all');
            $studentcount = $this->getcount($students);

            $filepath = ABSOLUTE_MEDIA_PATH.'training/students/';

            for($r=1; $r<=$studentcount; $r++){

                $student = $this->fetcharray($students);

                //get student id
                $id = explode('/', $student['registrationid'])[1];
                $studentfolder = $id.'_'.str_replace(' ', '_', ucwords(strtolower(trim($student['name']))));
                
                if(!file_exists($filepath.$studentfolder)){
                    @mkdir($filepath.$studentfolder, 0777);
                }
                else{
                    @mkdir($filepath.$studentfolder.'_2', 0777);
                }
            }
        }
        
        public function saveAllStudentFolderNames(){
            
            //create all the course folders
            $students = $this->runquery("SELECT * FROM students",'multiple','all');
            $studentcount = $this->getcount($students);

            $filepath = ABSOLUTE_MEDIA_PATH.'training/students/';
            
            for($r=1; $r<=$studentcount; $r++){

                $student = $this->fetcharray($students);

                //get student id
                $id = explode('/', $student['registrationid'])[1];
                $studentfolder = $id.'_'.str_replace(' ', '_', ucwords(strtolower(trim($student['name']))));
                
                $save = [
                    'foldername' => $studentfolder
                ];
                
                if(file_exists($filepath.$studentfolder)){
                    
                    $this->dbupdate('students', $save, "students_id = '".$student['students_id']."'");                    
                }
                elseif(!file_exists($filepath.$student['foldername'])){
                    
                    if($student['foldername'] != ''){
                        
                        @mkdir($filepath.$student['foldername'], 0777);
                        $this->dbupdate('students', $save, "students_id = '".$student['students_id']."'");
                    }
                }
            }
        }
        
    public function url_exists($url) {
            
        $a_url = parse_url($url);
        
        if (!isset($a_url['port'])) $a_url['port'] = 80;
        
        $errno = 0;
        $errstr = '';
        $timeout = 30;
        
        if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
            
            $fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
            if (!$fid) return false;
            $page = isset($a_url['path']) ?$a_url['path']:'';
            $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
            fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
            $head = fread($fid, 4096);
            fclose($fid);
            return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
        } else {
        return false;
        }
}
}
?>