<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

class user extends database
{
	//the $db_user object/variable wil be extracted from the initialized database object
	function chkuser($username)
	{
		$chk_string = "SELECT * FROM users WHERE username = '$username'";
		$chk_query = $this->runquery($chk_string,'multiple');
		$chk_count = $this->getcount($chk_query);
		
		if($chk_count>=1)
		{
			return $username;
		}
		else
		{
			return "name_available";
		}
	}
	
	//returns the name of the user
	function return_name($id,$type)
	{
		if($type=='buyer')
		{
			$namestr = "SELECT fullname FROM buyers WHERE buyerid='$id'";
		}
		else if($type=='seller')
		{
			$namestr = "SELECT companyname FROM sellers WHERE sellerid='$id'";
		}
		else if($type=='admin')
		{
			$namestr = "SELECT fullname FROM sbusers WHERE sbuid='$id'";
		}
		
		$namequery = $this->runquery($namestr);
		$name = $this->fetchrow($namequery);
		
		return $name[0];
	}
	
	function adduser($id='none',$username,$password=NULL,$usertype='',$sourceid='')
	{
		$real_uname = mysql_real_escape_string($username);
		$real_password = mysql_real_escape_string($password);
		
		$chk_name = $this->chkuser($real_uname);
		
		if($chk_name=='name_available'&&$id=='none')
		{
			$add_string = "INSERT INTO users(username,password,usertype,sourceid) VALUES ('$real_uname','$real_password','$usertype','$sourceid')";
			
			$add_query = $this->runquery($add_string,'multiple');
			
			if($add_query)
			{
				return "success";
			}
			else
			{
				return mysql_error();
			}
		}
		else if($id!='none')
		{
			$edit_string = "UPDATE users SET ";
			
			if($real_uname!=NULL)
			{
				$edit_string .= "username='$real_uname'";
			}
			if($real_password!=NULL)
			{
				$edit_string .= ", password='$real_password' ";
			}
			
			$edit_string .= " WHERE userid='$id'";
			
			$edit_query = $this->runquery($edit_string);
			
			if($edit_query)
			{
				return "success";
			}
			else
			{
				return mysql_error();
			}
		}
		else
		{
			return $chk_name;
		}
	}
	
        //takes two user types: subscriber and backend users
        function returnUserSourceDetails($id,$type){
            
            if($type=='subscriber'){
                $user = $this->runquery("SELECT * FROM subscribers WHERE subid='$id'",'single');
            }
            elseif($type=='backend'){
                $user = $this->runquery("SELECT * FROM sbusers WHERE sbid='$id'",'single');
            }
            elseif($type=='student'){
                $user = $this->runquery("SELECT * FROM students WHERE students_id='$id'",'single');
            }
            elseif($type=='instructor'){
                $user = $this->runquery("SELECT * FROM contributors WHERE contributorid = '$id' AND category = 'Instructor'",'single');
                //$this->print_last_error();
            }
            elseif($type=='login'){
                $user = $this->runquery("SELECT * FROM users WHERE userid='$id'",'single');
            }
            
            return $user;
        }
        
	function enableuser($id,$type)
	{
		if($type=='backend')
		{
			$str = "UPDATE sbusers SET enabled='yes' WHERE sbuid='$id'";
		}
		elseif($type=='subscriber')
		{
			$str = "UPDATE subscribers SET enabled='yes' WHERE subid='$id'";
			$email_query = $this->runquery("SELECT email FROM subscribers WHERE subid='$id'",'single');
		}
			
			$enable = $this->rawquery($str);
			
			if($enable)
			{				
				$email_html = '<html><body><strong>Dear '.SITENAME.' Subscriber</strong>
				<p>Your subscription application has just been approved. <br/>You will subsequently be receiving ICHA updates from us.</p>
				<p><b>Thanks</b></p>
				</body></html>';
				
				$email_text = '
Dear M3 Member

Your status has just been approved.

Please login into the system to enjoy our services. 

Thanks';
				$mail = $this->multipart_mail($email_query['email'], SITENAME.' Subscriber Approval',$email_html,$email_text,'info@M3.com',SITENAME);
				
				if($mail!='The email could not be sent.')
				{
					return 'success';
				}
			}
			else
			{
				echo mysql_error();
			}
	}
	
	function disableuser($id,$type)
	{
		if($type=='backend')
		{
			$str = "UPDATE sbusers SET enabled='no' WHERE sbuid='$id'";
		}
		elseif($type=='subscriber')
		{
			$str = "UPDATE subscribers SET enabled='no' WHERE subid='$id'";
			$email_query = $this->runquery("SELECT email FROM subscribers WHERE subid='$id'",'single');
		}
			
			$enable = $this->rawquery($str);
			
			if($enable)
			{				
				$email_html = '<html><body><strong>Dear '.SITENAME.' Subscriber</strong>
				<p>Sorry, your subscription application has just been cancelled. <br/>You will NOT subsequently be receiving ICHA updates from us.</p>
				<p><b>Thanks</b></p>
				</body></html>';
				
				$email_text = '
Dear M3 Member

Your status has just been approved.

Please login into the system to enjoy our services. 

Thanks';
				$mail = $this->multipart_mail($email_query['email'], SITENAME.' Subscriber Cancellation',$email_html,$email_text,'info@M3.com',SITENAME);
			
			if($mail!='The email could not be sent.')
			{
				return 'success';
			}
		}
		else
		{
			echo mysql_error();
		}
	}
	
        //create a guest session to save shopper's products
        function createGuestSession($productid,$currencyid,$price)
        {
            //create and save guest
            $userip = $_SERVER['REMOTE_ADDR'];
            $sessionid = md5(session_id());
            
            $sessiondata = array(
                'sessionid' => $sessionid,
                'userip' => $userip
            );
            
            $this->dbinsert('guest', $sessiondata);
            $id = mysql_insert_id();
            
            
            
            $scart = array(
                'shopperid' => $id,
                'shoppertype' => 'guest',
                'sessionid' => $sessionid,
                'productid' => $productid,
                'price' => $price,
                'currencyid' => $currencyid,
                'datetime' => time()
            );
            
            $this->dbinsert('shoppingcart', $scart);
            $cartid = mysql_insert_id();
            
            //initialize the session
            $this->initializeSession($sessionid, $id, 'Guest User', 'guest', $cartid);
        }
        
        function initializeSession($sessionid,$sourceid,$username,$usertype,$cartid)
        {
            $_SESSION['logid'] = $sessionid;
            $_SESSION['sourceid'] = $sourceid;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $usertype;
            
            //add cart id to session
            $_SESSION['cartid'] = $cartid;
        }
        
	function login($username,$password)
	{
            $real_uname = mysql_real_escape_string($username);
            $real_password = mysql_real_escape_string($password);
            $timestamp = time();
            $sessionid = md5(session_id());

            $chk_str = "SELECT * FROM users WHERE username='$real_uname' AND password='$real_password'";
            $chk_query = $this->runquery($chk_str,'multiple');
            $chk_count = $this->getcount($chk_query);
            
            if($chk_count>=1)
            {			
                    $type_array = mysql_fetch_array($chk_query);

                    if($type_array['enabled']=='yes')
                    {
                            //userid both can be the source id
                            $_SESSION['logid'] = $sessionid;
                            $_SESSION['userid'] = $type_array['userid'];
                            $_SESSION['sourceid'] = $type_array['sourceid'];
                            $_SESSION['username'] = $type_array['username'];

                            //get the access level
                            $access = $this->runquery("SELECT * FROM accesslevels WHERE accessid = '".$type_array['accesslevelid']."'",'single');				
                            //set the access level
                            $_SESSION['usertype'] = $access['accesslevel'];

                            $type = $access['accesslevel'];
                            
                            //configure the session for the filemanager
                            if($type=='ins')
                            {
                                $folder = $this->returnUserSourceDetails($type_array['sourceid'], 'instructor');
                                
                                $_SESSION['subfolder'] = 'training/';
                            }
                            elseif($type=='std') {
                                
                                //enter time check
                                //require ABSOLUTE_PATH.'modules'.DS.'login'.DS.'studentchecker.php';
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
                               
                                //if currnt timestamp greater than expiry timestamp your session is expired 
                                if(time() > $expirydate){
                                      return 'student_expired';   
                                }else{
                                    $student = $this->returnUserSourceDetails($type_array['sourceid'], 'student');
                                    //var_dump($student);
                                    $getfolder = $this->runquery("SELECT contributors.foldername AS fname FROM contributors ".
                                            "INNER JOIN courses ON courses.contributorid = contributors.contributorid ".
                                            "INNER JOIN student_courses ON student_courses.courseid = courses.courseid ".
                                            "WHERE students_id = '".$student['students_id']."'",'single');
                                    //$this->print_last_error(); exit;

                                    $_SESSION['subfolder'] = 'training/students/'.$student['foldername'].'/';
                                }
                                
                                
                            }

                            //check if the user has logged in before
                            $query_str = "SELECT count(*) FROM sessions WHERE username = '$real_uname' AND usertype='$type'";
                            $usrcount = $this->runquery($query_str,'single');

                            //remove the duplicate entries in the db
                            if($usrcount['count(*)']>=1)
                            {
                                    //echo "DELETE * FROM sessions WHERE username = '$real_uname' AND usertype='$type'";
                                    //exit;
                                    $this->rawquery("DELETE FROM sessions WHERE username = '$real_uname' AND usertype='$type'");
                            }

                            $login_str = "INSERT INTO sessions(sessionid,username,usertype,timestamp) VALUES ('$sessionid','$real_uname','$type','$timestamp')";

                            $login_query = $this->rawquery($login_str);

                            if(($login_query)&&(isset($_SESSION['username'])&&isset($_SESSION['usertype'])))
                            {
                                    return "login_pass";
                            }
                            else
                            {
                                    return mysql_error();
                            }
                    }
                    elseif($type_array['enabled']=='no')
                    {
                            return 'userdisabled';
                    }
            }
            else
            {
                    return "no_such_user";
            }
	}
	
	function logout($id)
	{
            $user_str = "DELETE FROM sessions WHERE sessionid = '$id'";		
            $user_query = $this->rawquery($user_str);

            if($user_query)
            {
                $id = $_SESSION['userid'];
                $update = array('lastlogin'=>time());

                $this->dbupdate('users', $update, "userid='$id'");

                session_destroy();
                return 'sessionout';
            }
            else
            {
                return mysql_error();
            }
	}
	
	//retrieves the image given the img id
	function img($imgid,$path='none',$type='thumbs',$size,$user)
	{
		$img = $this->runquery("SELECT filename FROM imgmanager WHERE imgid='$imgid'");
		$imgrow = $this->fetchrow($img);
		
		if($imgid==0)
		{
			$imgfile='noimage.png';
		}
		else
		{
			$imgfile = $imgrow[0];
		}
		
		if($path=='path')
		{			
			if($type=='thumbs')
			{
				switch ($user)
				{
					case "buyer":
						return 'styles/ichatemplate/images/buyerpics/'.$imgfile;
					break;
					
					case "seller":
						return 'styles/ichatemplate/images/userpics/'.$imgfile;
					break;
					
					default:
						return 'styles/ichatemplate/images/productimgs/'.$imgfile;
					break;
				}
			}
			else if($type=='originals')
			{
				return 'styles/ichatemplate/images/productimgs/originals/'.$imgfile;
			}
		}
		else
		{
			if($type=='thumbs')
			{
				switch ($user)
				{
					case "buyer":
						return '<input name="imgid" type="hidden" id="imgid" value="'.$imgid.'" /><img src="styles/ichatemplate/images/userpics/'.$imgfile.'" '.$size.'>';
					break;
					
					case "seller":
						return '<input name="imgid" type="hidden" id="imgid" value="'.$imgid.'" /><img src="styles/ichatemplate/images/userpics/'.$imgfile.'" '.$size.'>';
					break;
					
					default:
						return '<input name="imgid" type="hidden" id="imgid" value="'.$imgid.'" /><img src="styles/ichatemplate/images/productimgs/'.$imgfile.'" '.$size.'>';
					break;
				}
				
			}
			else if($type=='originals')
			{
				return '<input name="imgid" type="hidden" id="imgid" value="'.$imgid.'" /><img src="styles/ichatemplate/images/productimgs/originals/'.$imgfile.'" '.$size.'>';
			}
		}
	}
	
	//return accesslevel name
	function returnAccessDetails($usertype,$returnvalue='name')
	{
		$name = $this->runquery("SELECT * FROM accesslevels WHERE accesslevel = '$usertype'",'single');
		
                if($returnvalue=='name')
                {
                    return strtolower($name['displayname']);
                }
                elseif($returnvalue=='id')
                {
                    return $name['accessid'];
                }
	}
	
        
	function deleteAllowed($utype)
	{
		$user = $this->runquery("SELECT * FROM accesslevels WHERE accesslevel = '$utype'",'single');
		
		if($user['deletionallowed']=='yes'||$user['deletionallowed']=='')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	//return whether user is allowed or not
	function chk_level($usertype)
	{		
		//get the current url
		$currentURL = navigation::geturl();
		
		parse_str($currentURL,$urlvars);
		
		if(!array_key_exists('alert',$urlvars))
		{
			$groupid = navigation::getgroupid($usertype);
			
			//get links in group
			$links_in_group = $this->runquery("SELECT menulink FROM menus WHERE menugroup='$groupid'",'multiple','all');
			$links_count = $this->getcount($links_in_group);
			
			for($i=0; $i<=($links_count-1); $i++)
			{
				$link_array = $this->fetcharray($links_in_group);
				
				//parse_str($links_array['menulink'],$menuvariables);
				$menuvariables[$i] = $link_array['menulink'];
			}
			
			//check whether the current folder is within the folders array
			if(in_array($currentURL,$menuvariables)==TRUE||$urlvars['file']=='chglogin'||$urlvars['?admin']=='com_frontpage')
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
}
?>