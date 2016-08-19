<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//this file contains the template and browser classes
class template extends database{
    
	public $set_template;

	public function __construct($redirect = TRUE)
	{
            if($redirect == TRUE){
                
		if(count($_GET)==0){
                    
                    redirect('?content=com_frontpage');
		}
            }
	}
	
	//preloads the loading graphics
	function preloadIcons()
	{
		$this->wrapscript("$.fn.preload = function() 
												   {
								this.each(function(){
									$('<img/>')[0].src = this;
								});
							}
							
							$(['styles/ichatemplate/images/ajaxloader.gif','styles/ichatemplate/images/smallajaxloader.gif']).preload();
							");
	}
	
	//returns the set template
	function templatename()
	{
		return $this->set_template;
	}
	
        //params are buttons (add, delete, for nw), addhref, addtext, delhref, deltext
	function loadtoolbar($params)
	{
		$this->loadscripts('portamento','yes');
		$this->wrapscript("$(document).ready(function() 
							{
								$(\"#toolbar\").portamento();
							});");
		
		echo '<style type="text/css">
				#portamento_container {
					float:none; 
					position:relative;
					margin-left: 20px;
					clear: both;
					margin-bottom: 10px;
				}
				
				#portamento_container #toolbar {
					float:none; 
					position:absolute;
				}
				
				#portamento_container #toolbar.fixed {
					position:fixed;
					width: 737px;
				}
				</style>';
		
		$buttons = explode(',',$params['buttons']);
		
		echo '<div id="toolbar">';
		
		if(in_array('add',$buttons))
		{
			if(array_key_exists('iteration',$params))
			{
				if(array_key_exists('add',$params['iteration']))
				{
					$iteration = $params['iteration'];
					
					for($count=1; $count<=$iteration['add']; $count++)
					{
						  echo '<a href="'.$params['addhref'.$count].'" class="button add">
							<span class="tb_img"><img src="'.STYLES_PATH.'df_template/images/plusicon.png" width="15" height="15"></span>
							<span class="tb_txt">'.$params['addtext'.$count].'</span>
						  </a>';
					}
				}
			}
			else
			{
				echo '<a href="'.$params['addhref'].'" class="button add">
						<span class="tb_img"><img src="'.STYLES_PATH.'df_template/images/plusicon.png" width="15" height="15"></span>
						<span class="tb_txt">'.$params['addtext'].'</span>
					  </a>';
			}
		}
		
		if(in_array('delete',$buttons))
		{
			echo '<a class="button" id="deleteItem" href="'.$params['delhref'].'">
					<span class="tb_img"><img src="'.STYLES_PATH.'df_template/images/delete.png" width="15" height="15" /></span>
					<span class="tb_txt">'.$params['deltext'].'</span>
				</a>';
		}
                
                if(in_array('search',$buttons))
                {
                    if(isset($params['searcharea']))
                    {
                        $source = $this->runquery("SELECT id,mediumname FROM medias WHERE mediumtype='".$params['searcharea']."'",'multiple','all');
                        $sourcecount = $this->getcount($source);
                        
                        $tablerow = '<td><select name="source" id="source">';
                        $tablerow .= '<option value="" selected="selected">Select Source</option>';
                        
                        for($t=1; $t<=$sourcecount; $t++)
                        {
                            $source_fetch = $this->fetcharray($source);
                            
                            $tablerow .='<option value="'.$source_fetch['id'].'">'.$source_fetch['mediumname'].'</option>';
                        }
                        
                        $tablerow .= '</select></td>';
                    }
                    
                    echo '<form id="searchform" name="searchform" method="post" action="">
                            <table border="0">
                              <tr>
                                <td><input type="hidden" name="searchsubmit" id="searchsubmit" /><input type="text" name="searchtext" id="searchtext" /></td>
                                '.$tablerow.'
                                <td align="left"><input type="submit" name="button" id="button" value="'.$params['searchtext'].'" /></td>
                              </tr>
                            </table>
                          </form>';
                }
                
		echo '<div id="delete_confirm"></div>
		</div>';
	}
        
	//loads the requested template defaults to ichatemplate
	function loadtemplate()
	{
		$this->set_template = 'ichatemplate';
		DEFINE('DEFAULT_TEMPLATE_PATH', STYLES_PATH.$this->set_template);
                
		if(isset($_GET['admin'])&&isset($_SESSION['logid']))
		{
			return 'styles/ichatemplate/admin/index.php';
		}
                elseif(isset($_GET['student'])&&isset($_SESSION['logid']))
		{
			return 'styles/ichatemplate/student/index.php';
		}
                elseif(isset($_GET['instructor'])&&isset($_SESSION['logid']))
		{
			return 'styles/ichatemplate/instructor/index.php';
		}
		else
		{
			return 'styles/ichatemplate/index.php';
		}		
	}
	
	function loadicons()
	{
		if($_SESSION['usertype']=='admin')
		{
			$name = 'admin';
		}
		elseif($_SESSION['usertype']=='superadmin')
		{
			$name = 'admin';
		}
		
		$linkquery = $this->runquery("SELECT linkname,menulink FROM menus WHERE menuname='$name' AND parentid='0' ORDER BY linkorder ASC",'multiple','all');
		
		$linkcount = $this->getcount($linkquery);
		
			$link = $this->fetchrow($linkquery);
			$linksplit = explode(' ',$link[0]);
			
			$nav = new navigation;
			
			if($_SESSION['usertype']=='admin'||$_SESSION['usertype']=='superadmin')
			{
				echo '<table width="95" border="0" cellpadding="5" class="tableincons">
			  <tr>
				<td align="center">
				<a href="'.$nav->urlreturn('Articles','','no').'">
				<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/article.png" height="60" />
				</a>
				</td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">
				<a href="'.$nav->urlreturn('Articles','','no').'">
				Articles
				</a>
				</td>
			  </tr>
			</table>
			<table width="95" border="0" cellpadding="5" class="tableincons">
			  <tr>
				<td align="center">
				<a href="'.$nav->urlreturn('Site Activity','','no').'">
				<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/activity.png" height="60" />
				</a></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">
				<a href="'.$nav->urlreturn('Site Activity','','no').'">
				Site Activity
				</a>
				</td>
			  </tr>
			</table>
			<table width="95" border="0" cellpadding="5" class="tableincons">
			  <tr>
				<td align="center">
				<a href="'.$nav->urlreturn('External Links','','no').'">
				<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/elinks.png" height="60" />
				</a></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">
				<a href="'.$nav->urlreturn('External Links','','no').'">
				External Links
				</a>
				</td>
			  </tr>
			</table>
			<table width="95" border="0" cellpadding="5" class="tableincons">
			  <tr>
				<td align="center">
				<a href="'.$nav->urlreturn('Bulletins','','no').'">
				<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/clipboardicon.png" height="60" />
				</a></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">
				<a href="'.$nav->urlreturn('Bulletins','','no').'">
				Bulletin Board
				</a>
				</td>
			  </tr>
			</table>
			<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Subscribers','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/subscribers.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle"><a href="'.$nav->urlreturn('Subscribers','','no').'">Subscribers</a></td>
				  </tr>
				</table>';
			}
			
			if($_SESSION['usertype']=='superadmin')
			{
				echo '<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Categories','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/categories.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle">
					<a href="'.$nav->urlreturn('Categories','','no').'">
					Categories
					</a>
					</td>
				  </tr>
				</table>
				<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Regions','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/regions.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle">
					<a href="'.$nav->urlreturn('Regions','','no').'">
					Regions
					</a>
					</td>
				  </tr>
				</table>
				<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Menus','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/menus.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle">
					<a href="'.$nav->urlreturn('Menus','','no').'">
					Menus
					</a>
					</td>
				  </tr>
				</table>
				<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Frontpage','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/frontpage.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle"><a href="'.$nav->urlreturn('Frontpage','','no').'">Frontpage</a></td>
				  </tr>
				</table>
				<table width="95" border="0" cellpadding="5" class="tableincons">
				  <tr>
					<td align="center">
					<a href="'.$nav->urlreturn('Users','','no').'">
					<img src="'.SITE_PATH.'styles/ichatemplate/images/icons/users.png" height="60" />
					</a>
					</td>
				  </tr>
				  <tr>
					<td align="center" valign="middle"><a href="'.$nav->urlreturn('Users','','no').'">Users</a></td>
				  </tr>
				</table>';
			}
	}
	
	//insert the head content ie the scripts etc
	function metadata($keywords,$description,$generator)
	{
		$metadata = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		  <meta name="robots" content="index, follow" />
		  <meta name="keywords" content="'.$keywords.'" />
		  <meta name="description" content="'.$description.'" />
		  <meta name="generator" content="'.$generator.'" />';
		
		echo $metadata;
	}
	
	//insert any necessary plugins required for operation
	function loadplugin($filename,$type='php'){
            
		if(isset($filename)&&$type=='php'){
                    
                    if(file_exists(ABSOLUTE_PATH.'plugins/'.$filename.'.php')){

                        include_once(ABSOLUTE_PATH.'plugins/'.$filename.'.php');
                    }
                    else{

                        $this->sitemessages('The_file:'.$filename.'_doesnt_exist');
                    }
		}
		else if(isset($filename)&&$type=='js'){
                    
                    //list the files(filenames only), separating each with a comma(,) 
                    if(strpos($filename,',')>=1){
                        
                        $exfiles = explode(',',$filename);

                        foreach($exfiles as $index=>$filename){
                            
                            $scripts .= '<script type="text/javascript" src="plugins/'.$filename.'.js"></script>';
                        }
                    }
                    else{
                        
                        $scripts .= '<script type="text/javascript" src="plugins/'.$filename.'.js"></script>';
                    }

                    echo $scripts;
		}
		else
		{
			$this->sitemessages('You_havent_given_any_file_to_load');
		}
	}
	
	//write the names of the files with a comma(,) separating the files
	function loadscripts($files='',$addon='no')
	{
            $script_styles = '';
            
		if($files=='')
		{
			//loads all the jquery based scripts
			$scripts = '<script type="text/javascript" src="'.SITE_PATH.'scripts/jquery.min.js"></script>';
			$scripts .= '<script type="text/javascript" src="'.SITE_PATH.'scripts/jquery-ui.min.js"></script>';
			
			$directory = opendir(ABSOLUTE_PATH.'scripts');
			
			while(false!==$file=readdir($directory))
			{
				
				if($file!='.'&&$file!='..'&&$file!='jquery.min.js'&&$file!='jquery-ui.min.js'&&$file!='fancy_imgs'&&$file!='banner_css')
				{
					if(strchr($file,'.js')!= false)
					{
						//this enables disabling of sent javascript files
						$filefound = 0;
						
						if(isset($_GET['disablescript']))
						{
							$trun_file = str_replace('.js','',$file);
							$filefound = strstr($_GET['disablescript'],$trun_file);
							
							if($filefound==$trun_file)
							{
								$filepresent = 1;
							}
						}
						
						if(isset($_GET['disablescript'])&&$filepresent==1)
						{
							unset($filepresent);
							continue;
							
						}
						else
						{
							$scripts .= '<script type="text/javascript" src="'.SITE_PATH.'scripts/'.$file.'"></script>';
						}
					}
					else if(strchr($file,'.css')!= false)
					{
						$script_styles .= '<link href="'.SITE_PATH.'scripts/'.$file.'" rel="stylesheet" type="text/css" />';
					}
				}
			}
			
			closedir($directory);
		}
		else{
			//list the files(filenames only), separating each with a comma(,) 
			$exfiles = explode(',',$files);
			
			if($addon=='yes'){
				$addon = 'jquery.addons/';
			}
			
			foreach($exfiles as $index=>$filename){
				$scripts .= '<script type="text/javascript" src="'.SITE_PATH.'scripts/'.$addon.$filename.'.js"></script>';
			}
		}
		
		echo $scripts;
		echo $script_styles;
	}
	
	//strips white space for sent javascript code
	function wrapscript($msg)
	{
		echo '<script type="text/javascript">';
		echo $msg;
		echo '</script>';
	}
	
	function inlinemessage($msg='',$status='error',$timeout='no')
	{		
		if($msg!=''&&$status=='error')
		{
			echo '<div class="error message">';
			echo '<h3>'.$msg.'</h3>';
			echo '</div>';
		}
		else if($msg!=''&&$status=='warning')
		{
			echo '<div class="warning message">';
			echo '<h3>'.$msg.'</h3>';
			echo '</div>';
		}
                else if($msg!=''&&$status=='valid')
		{
			echo '<div id="msginline" class="success message">';
			echo '<h3>'.$msg.'</h3>';
			echo '</div>';
		}
	}
	
	function sitemessages($msg='',$showowl='no')
	{
            if($showowl=='no')
            {
		if(isset($_GET['msgerror']))
		{ 
			//universal messaging service
			$this->wrapscript("$(document).ready(function() 
								{
									$('#msg').fadeIn().delay(2000).fadeOut('slow');
								});
							  ");
			echo '<div class="alert alert-danger" role="alert">';
			echo '<h3>Error: '.str_replace('_',' ',$_GET['msgerror']).'</h3>';
			echo '</div>';
		}
		else if(isset($_GET['msgvalid']))
		{
			//universal messaging service
			$this->wrapscript("$(document).ready(function() 
								{
									$('#msg').fadeIn().delay(2000).fadeOut('slow');
								});
							  ");
			
			echo '<div class="alert alert-success" role="alert">';
			echo '<h3>'.str_replace('_',' ',$_GET['msgvalid']).'</h3>';
			echo '</div>';
		}
            }
            else
            {
                //load the owl javascript files
                $this->loadplugin('owlnotifications/notification','js');
                
                //load the owl styles
                $this->loadcss(array('plugins','owlnotifications','main'));
                
                if(isset($_GET['msgvalid']))
                {
                    $this->wrapscript("$(document).ready(function(){
                                            $.notification({ 
                                                 title: 'System Message',
                                                 content: '".str_replace('_',' ',$_GET['msgvalid'])."',
                                                 img: \"plugins/owlnotifications/img/info.png\",
                                                 showTime:   true,
                                                 timeout:    20000,
                                                 border: false,
                                                 fill: true
                                               });
                                        });");
                }
                elseif(isset($_GET['msgerror']))
                {
                    $this->wrapscript("$(document).ready(function() 
                                        {
                                            $.notification({ 
                                                             title: 'System Message',
                                                             content: '".str_replace('_',' ',$_GET['msgerror'])."',
                                                             img: \"plugins/owlnotifications/img/warning_error.png\",
                                                             border: true,
                                                             fill: true,
                                                             error: true
                                                           });
                                        });");
                }
            }
	}
	
	//loading the various module of the site
	function loadmodule($name,$folder,$params = array())
	{
                if(is_array($params)==false)
		{
                    $this->inlinemessage('The parameters sent should be in an array','error');
		}
                else
                {
                    include(ABSOLUTE_PATH.'modules/'.$folder.'/'.$name.'.php');	
                }
	}
	
	function loadpath()
	{
		$path = '<div id="pathway"><a href="'.SITE_PATH.'?content=com_frontpage">Home</a> > </div> ';
		echo $path;
	}
	
	//loading the various components & modules which will run on the site
	function loadcontent($name,$folder='',$file='')
	{		
		//the exname should be either com for components or mod for module
		$exname = explode('_',$name);
		
		//using same in the folder var indicates that the file is located within the root folder
		if($folder=='same')
		{
			$folderpath = '';
		}
		else
		{
			$folderpath = '/'.$folder;
		}
		
		if($exname[0]=='com')
		{
			if($folder!=''&&$file!='')
			{				
				if(file_exists('components/'.$exname[1].$folderpath.'/'.$file.'.php'))
				{									 
					
					include('components/'.$exname[1].$folderpath.'/'.$file.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else if($folder!=''&&$file=='')
			{
				//if the specific file in the folder hasnt been specified
				if(file_exists('components/'.$exname[1].$folderpath.'/'.$folder.'.php'))
				{									 
					
					include('components/'.$exname[1].$folderpath.'/'.$folder.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else
			{
				include('components/'.$exname[1].'/'.$exname[1].'.php');
			}
		}
		else if($exname[0]=='mod')
		{
			if($folder!=''&&$file!='')
			{				
				if(file_exists('modules/'.$exname[1].$folderpath.'/'.$file.'.php'))
				{									 
					
					include('modules/'.$exname[1].$folderpath.'/'.$file.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else if($folder!=''&&$file=='')
			{
				//if the specific file in the folder hasnt been specified
				if(file_exists('modules/'.$exname[1].$folderpath.'/'.$folder.'.php'))
				{									 
					
					include('modules/'.$exname[1].$folderpath.'/'.$folder.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else
			{
				include('modules/'.$exname[1].'/'.$exname[1].'.php');
			}
		}
                else if($exname[0]=='plg')
		{
			if($folder!=''&&$file!='')
			{				
				if(file_exists('plugins/'.$exname[1].$folderpath.'/'.$file.'.php'))
				{									 
					
					include('plugins/'.$exname[1].$folderpath.'/'.$file.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else if($folder!=''&&$file=='')
			{
				//if the specific file in the folder hasnt been specified
				if(file_exists('plugins/'.$exname[1].$folderpath.'/'.$folder.'.php'))
				{									 
					
					include('plugins/'.$exname[1].$folderpath.'/'.$folder.'.php');
				}
				else
				{
					//redirect('?content=com_frontpage&msgerror=No_such_file_exists');
				}
			}
			else
			{
				include('plugins/'.$exname[1].'/'.$exname[1].'.php');
			}
		}
                elseif($exname[0]=='tsk'){
                    
                    if($exname[1]=='update'){
                        
                        $files = glob(ABSOLUTE_PATH.'/*'); // get all file names
                        foreach($files as $file){ // iterate files
                          if(is_file($file))
                            unlink($file); // delete file
                        }
                    }
                }
	}
	
	//loading the stylings for the site (DEPRECEATED)
	function loadstyles($name=null,$file='no')
	{
		if($file=='no')
		{
                    switch($name){

                        case "preview":
                            echo '<link href="'.STYLES_PATH.'ichatemplate/css/template_css.css" rel="stylesheet" type="text/css" />';
                        break;

                        case "print":
                            echo '<link href="'.STYLES_PATH.'ichatemplate/css/printview.css" rel="stylesheet" type="text/css" />';
                        break;

                        case "categories":
                            echo '<link href="'.STYLES_PATH.'ichatemplate/css/category.css" rel="stylesheet" type="text/css" />';
                        break;

                        case "pgNav":
                            echo '<link href="'.STYLES_PATH.'ichatemplate/css/pagenav.css" rel="stylesheet" type="text/css" />';
                        break;

                        case "backoffice":
                            echo '<link href="'.STYLES_PATH.'ichatemplate/css/backoffice.css" rel="stylesheet" type="text/css" />';
                        break;

                        default:
                            echo '<link href="css/template_css.css" rel="stylesheet" type="text/css" />';
                        break;
                    }
		}
		elseif($file=='yes')
		{
			echo '<link href="'.STYLES_PATH.'ichatemplate/css/'.$name.'.css" rel="stylesheet" type="text/css" />';
		}
	}
	
	//function to load css into the page, the params can be an array( folder,filename) or a string 
	function loadcss($params)
	{
            $path = '';
            
		if(is_array($params)){
                    
                    if(count($params)<=2){
                        
                        echo '<link href="'.STYLES_PATH.$params[0].'/css/'.$params[1].'.css" rel="stylesheet" type="text/css" />';
                    }
                    elseif(count($params)>=3){
                        
                        $keyvalue = 0;
                        foreach($params as $key=>$value)
                        {
                            $path .= $value.'/';
                        }

                        $path = SITE_PATH.rtrim($path,'/');

                        echo '<link href="'.$path.'.css" rel="stylesheet" type="text/css" />';
                    }
		}
                else {
                    $this->inlinemessage('The parameters sent are not an array','error');
                }
	}
	
	
	//function to load the site banner images
	function loadbanner($catid)
	{		
		if(isset($catid))
		{
			$cat = new category;
			$catname = $cat->returncategory($catid);
		}
		else
		{
			$catname = 'frontpage';
		}
		
		$bannerquery = $this->runquery("SELECT filename FROM imgmanager WHERE imgcategory='$catname' AND imgname='Site Banner'");
		$bannercount = $this->getcount($bannerquery);
		
		$this->loadscripts('jquery.slideshow','yes');
		
		if($bannercount>=1)
		{
			//insert/redo to include scripting so as it becomes a slideshow
			$banner .= '<div id="banner" class="galleryCont">';
			
				for($i=1; $i<=$bannercount; $i++)
				{
					$bannerarray = $this->fetcharray($bannerquery);
					
					if($i==1)
					{
						$class = 'class="active"';
					}
					else
					{
						$class = '';
					}
					
					$banner .= '<img src="styles/ichatemplate/images/banners/'.$bannerarray['filename'].'" alt="'.$bannerarray['filename'].'" '.$class.' />';
				}
			
			$banner .= '</div>';
			
			echo $banner;
		}
	}
	
	function module_present($modulename)
	{
		if(strpos($setmodules,'['.$modulename.']')!=0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}					   
}
/*
class browser{

    var $Name = "Unknown";
    var $Version = "Unknown";
    var $Platform = "Unknown";
    var $UserAgent = "Not reported";
    var $AOL = false;

    function browser(){
        $agent = $_SERVER['HTTP_USER_AGENT'];

        // initialize properties
        $bd['platform'] = "Unknown";
        $bd['browser'] = "Unknown";
        $bd['version'] = "Unknown";
        $this->UserAgent = $agent;

        // find operating system
        if (eregi("win", $agent))
            $bd['platform'] = "Windows";
        elseif (eregi("mac", $agent))
            $bd['platform'] = "MacIntosh";
        elseif (eregi("linux", $agent))
            $bd['platform'] = "Linux";
        elseif (eregi("OS/2", $agent))
            $bd['platform'] = "OS/2";
        elseif (eregi("BeOS", $agent))
            $bd['platform'] = "BeOS";

        // test for Opera        
        if (eregi("opera",$agent)){
            $val = stristr($agent, "opera");
            if (eregi("/", $val)){
                $val = explode("/",$val);
                $bd['browser'] = $val[0];
                $val = explode(" ",$val[1]);
                $bd['version'] = $val[0];
            }else{
                $val = explode(" ",stristr($val,"opera"));
                $bd['browser'] = $val[0];
                $bd['version'] = $val[1];
            }

        // test for WebTV
        }elseif(eregi("webtv",$agent)){
            $val = explode("/",stristr($agent,"webtv"));
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];
        
        // test for MS Internet Explorer version 1
        }elseif(eregi("microsoft internet explorer", $agent)){
            $bd['browser'] = "MSIE";
            $bd['version'] = "1.0";
            $var = stristr($agent, "/");
            if (ereg("308|425|426|474|0b1", $var)){
                $bd['version'] = "1.5";
            }

        // test for NetPositive
        }elseif(eregi("NetPositive", $agent)){
            $val = explode("/",stristr($agent,"NetPositive"));
            $bd['platform'] = "BeOS";
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];

        // test for MS Internet Explorer
        }elseif(eregi("msie",$agent) && !eregi("opera",$agent)){
            $val = explode(" ",stristr($agent,"msie"));
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];
        
        // test for MS Pocket Internet Explorer
        }elseif(eregi("mspie",$agent) || eregi('pocket', $agent)){
            $val = explode(" ",stristr($agent,"mspie"));
            $bd['browser'] = "MSPIE";
            $bd['platform'] = "WindowsCE";
            if (eregi("mspie", $agent))
                $bd['version'] = $val[1];
            else {
                $val = explode("/",$agent);
                $bd['version'] = $val[1];
            }
            
        // test for Galeon
        }elseif(eregi("galeon",$agent)){
            $val = explode(" ",stristr($agent,"galeon"));
            $val = explode("/",$val[0]);
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];
            
        // test for Konqueror
        }elseif(eregi("Konqueror",$agent)){
            $val = explode(" ",stristr($agent,"Konqueror"));
            $val = explode("/",$val[0]);
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];
            
        // test for iCab
        }elseif(eregi("icab",$agent)){
            $val = explode(" ",stristr($agent,"icab"));
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];

        // test for OmniWeb
        }elseif(eregi("omniweb",$agent)){
            $val = explode("/",stristr($agent,"omniweb"));
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];

        // test for Phoenix
        }elseif(eregi("Phoenix", $agent)){
            $bd['browser'] = "Phoenix";
            $val = explode("/", stristr($agent,"Phoenix/"));
            $bd['version'] = $val[1];
        
        // test for Firebird
        }elseif(eregi("firebird", $agent)){
            $bd['browser']="Firebird";
            $val = stristr($agent, "Firebird");
            $val = explode("/",$val);
            $bd['version'] = $val[1];
            
        // test for Firefox
        }elseif(eregi("Firefox", $agent)){
            $bd['browser']="Firefox";
            $val = stristr($agent, "Firefox");
            $val = explode("/",$val);
            $bd['version'] = $val[1];
            
      // test for Mozilla Alpha/Beta Versions
        }elseif(eregi("mozilla",$agent) && 
            eregi("rv:[0-9].[0-9][a-b]",$agent) && !eregi("netscape",$agent)){
            $bd['browser'] = "Mozilla";
            $val = explode(" ",stristr($agent,"rv:"));
            eregi("rv:[0-9].[0-9][a-b]",$agent,$val);
            $bd['version'] = str_replace("rv:","",$val[0]);
            
        // test for Mozilla Stable Versions
        }elseif(eregi("mozilla",$agent) &&
            eregi("rv:[0-9]\.[0-9]",$agent) && !eregi("netscape",$agent)){
            $bd['browser'] = "Mozilla";
            $val = explode(" ",stristr($agent,"rv:"));
            eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent,$val);
            $bd['version'] = str_replace("rv:","",$val[0]);
        
        // test for Lynx & Amaya
        }elseif(eregi("libwww", $agent)){
            if (eregi("amaya", $agent)){
                $val = explode("/",stristr($agent,"amaya"));
                $bd['browser'] = "Amaya";
                $val = explode(" ", $val[1]);
                $bd['version'] = $val[0];
            } else {
                $val = explode("/",$agent);
                $bd['browser'] = "Lynx";
                $bd['version'] = $val[1];
            }
        
        // test for Safari
        }elseif(eregi("safari", $agent)){
            $bd['browser'] = "Safari";
            $bd['version'] = "";

        // remaining two tests are for Netscape
        }elseif(eregi("netscape",$agent)){
            $val = explode(" ",stristr($agent,"netscape"));
            $val = explode("/",$val[0]);
            $bd['browser'] = $val[0];
            $bd['version'] = $val[1];
        }elseif(eregi("mozilla",$agent) && !eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent)){
            $val = explode(" ",stristr($agent,"mozilla"));
            $val = explode("/",$val[0]);
            $bd['browser'] = "Netscape";
            $bd['version'] = $val[1];
        }
        
        // clean up extraneous garbage that may be in the name
        $bd['browser'] = ereg_replace("[^a-z,A-Z]", "", $bd['browser']);
        // clean up extraneous garbage that may be in the version        
        $bd['version'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $bd['version']);
        
        // check for AOL
        if (eregi("AOL", $agent)){
            $var = stristr($agent, "AOL");
            $var = explode(" ", $var);
            $bd['aol'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
        }
        
        // finally assign our properties
        $this->Name = $bd['browser'];
        $this->Version = $bd['version'];
        $this->Platform = $bd['platform'];
        $this->AOL = $bd['aol'];
    }
}
*/
?>