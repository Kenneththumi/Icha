<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1>System Users</h1>';

	$users = $this->runquery("SELECT * FROM sbusers WHERE sbid!='3' ORDER BY sbid DESC",'multiple',10,$_GET['pageno']);
	$userCount = $this->getcount($users);
	
	$link = new navigation;
	
	if($_GET['task']=='del')
	{
		if(isset($_GET['uid']))
		{
			$delid = $_GET['uid'];
			
			//delete the user details
			if($this->deleterow('sbusers','sbid',$delid))
			{
				//delete the login details
				$this->deleterow('users','sourceid',$delid);
				
				redirect($link->urlreturn('System Users','',$_SESSION['usertype'],'no').'&msgvalid=The_user_has_been_deleted');
			}
		}
		elseif(isset($_GET['ids']))
		{
			$ids = ltrim($_GET['ids'],',');
			$idarray = explode(',',$ids);
			
			foreach($idarray as $key=>$delid)
			{
				if($delid!='')
				{
					//delete the login details
					$this->deleterow('sbusers','sbid',$delid);
					$this->deleterow('users','sourceid',$delid);
				}
			}	
			
			redirect($link->urlreturn('System Users','',$_SESSION['usertype'],'no').'&msgvalid=The_users_have_been_deleted');
		}
		else
		{
			$this->inlinemessage('Please select items to delete','error');
		}
	}
	
	
	$this->wrapscript('
					  $(document).ready(function(){
							$(\'a.deleteico\').click(function(){
									var alink = $(this).attr("rel");
									var rowid = $(this).attr("id");
									
									var pname = $(\'#test\'+rowid).attr(\'rel\');
									//alert(rowid);
									var confirmDel = $(\'#delete_confirm\').html(\'<div class="show_delete"><strong>Do you want to delete "\'+pname+\'" from your list?</strong> <a href="\'+alink+\'" class="button">Yes</a><a href="'.$link->geturl().'" class="button">No</a></div>\');
									});
							
							   });');
	
	$this->wrapscript("
					  $(document).ready(function() 
						{
							$(\"a.add\").fancybox({
											'width': 600,
											'height': 350,
											'autoDimensions': false,
											'autoScale'			: false,
											'transitionIn'		: 'elastic',
											'transitionOut'		: 'elastic',
											'enableEscapeButton' : false,
											'overlayShow' : true,
											'overlayColor' : '#fff',
											'overlayOpacity' : 0,
											'type': 'iframe',
											'scrolling': 'auto'
										});
							
							$(\"a.edit\").fancybox({
											'width': 600,
											'height': 400,
											'autoDimensions': false,
											'autoScale'			: false,
											'transitionIn'		: 'elastic',
											'transitionOut'		: 'elastic',
											'enableEscapeButton' : false,
											'overlayShow' : true,
											'overlayColor' : '#fff',
											'overlayOpacity' : 0,
											'type': 'iframe',
											'scrolling': 'auto'
										});
						});");
		
		//$link->createPgNav("SELECT * FROM sbusers WHERE sbid!='5' ORDER BY sbid ASC",10);
	
	//load the toolbar
	$settings = array(
						'buttons' => 'add,delete',
						'addhref' => '?admin=com_admin&folder=users&file=addedit&alert=yes',
						'addtext' => 'Add User',
						'delhref' => $link->geturl().'&task=del',
						'deltext' => 'Delete Checked Users'
						);
	
	$this->loadtoolbar($settings);
	
	//the deletion JQuery script
	$this->loadscripts('multipleDelete','yes');
	
	echo '<table width="100%" border="0" cellpadding="5" class="table table-bordered">
	  <thead>
		<td>
			<input type="checkbox" name="itemlist" class="checkall" >
		</td>
		<td><strong>Full Names</strong></td>
		<td><strong>Email</strong></td>
		<td>&nbsp;</td>
		<td><strong>Enabled</strong></td>
		<td><strong>Access Level</strong></td>		
		<td>&nbsp;</td>
	  </thead>';
	  for($i=1; $i<=$userCount; $i++)
	  {
		  $usrArray = $this->fetcharray($users);
		  
		  $uid = $usrArray['sbid'];
		 
		  $details = $this->runquery("SELECT * FROM users WHERE sourceid='$uid'",'multiple','all');
		  $detailCount = $this->getcount($details);
		  
		  if($detailCount>=1)
		  {
			  $detail = $this->fetcharray($details);
			  $login = '<a href="'.$link->urlreplace('file=showusers','file=userlogin').'&alert=yes&uid='.$uid.'&lid='.$detail['userid'].'" class="edit">[ Login Details ]</a>';
			  
			  if($detail['enabled']!='')
			  {
			  	$enabled = '<span class="enabled" id="'.$usrArray['sbid'].'_'.$detail['enabled'].'">'.$detail['enabled'].'</span>';
			  }
			  else
			  {
				  $enabled = 'Not Specified';
			  }
			  
			  //get access level
				$access = $this->runquery("SELECT * FROM accesslevels WHERE accessid='".$detail['accesslevelid']."'",'single');
				$accessname = $access['displayname'];
		  }
		  else
		  {
			  $login = '<a href="'.$link->urlreplace('file=showusers','file=userlogin').'&alert=yes&uid='.$uid.'" class="edit">[ Configure Login & Access Level ]</a>';
			  
			  $enabled = 'Not Specified';
			  
			  $accessname = '<a href="'.$link->urlreplace('file=showusers','file=userlogin').'&alert=yes&uid='.$uid.'" class="edit">No Set Access Level</a>';
		  }
		  
		  echo '<tr rel="'.$usrArray['fullname'].'" id="test'.$i.'" '.($i%2==0 ? 'class="item"' : 'class="item2"').'>
		  	<td>
				<input type="checkbox" name="item[]" class="chkitem" value="'.$usrArray['sbid'].'" id="'.$i.'" >
			</td>
			<td><a href="?admin=com_admin&folder=users&file=addedit&alert=yes&userid='.$usrArray['sbid'].'" class="edit">'.$usrArray['fullname'].'</a></td>
			<td>'.($usrArray['email']=='' ? 'Not Specified' : $usrArray['email']).'</td>
			<td>'.$login.'</td>
			<td>';
			
			echo $enabled;
			
			echo '</td>';
			
			echo '<td>'.$accessname.'</td>';
			
			echo '<td>
			<a class="deleteico" rel="'.$link->geturl().'&task=del&uid='.$usrArray['sbid'].'" id="'.$i.'">
				<img src="'.STYLES_PATH.'df_template/images/delete.png" width="15" height="15">
				</a>
			</td>
		  </tr>';
		  
		  unset($accessname);
	  }
	echo '</table>';

?>