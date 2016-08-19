<?php
//restrict direct access
defined('LOAD_POINT')or die('RESTRICTED ACCESS');

$this->loadplugin('fileUpload/js/ajaxupload','js');
//$this->loadplugin('advUpload/fileuploader','js');

echo '<link href="'.SITE_PATH.'/plugins/advUpload/fileuploader.css" rel="stylesheet" type="text/css">	';

echo '<h1 class="pagetitle">Site Frontpage Configuration</h1>';
$link = new navigation;

$pid = $_GET['pid'];

//removes article from the Frontpage
if(isset($_GET['fpremove']))
{
	$artid = $_GET['artid'];
	
	$getimg = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$artid."'",'single');
	
	if(@unlink(ABSOLUTE_PATH.'media/banners/'.$getimg['filename']))
	{
		$this->deleterow('imgmanager','articleid',$_GET['artid']);
	}
	
	$rmvlead = array(
					 'lead' => ''
					 );
	$this->dbupdate('articles',$rmvlead,"articleid='".$artid."'");
	
	redirect($link->urlreturn('frontpage','','no').'&msgvalid=The_article_has_been_removed');
}

//deletes any files sent by the user
if(isset($_GET['filedelete']))
{
	$file = $_GET['filedelete'];
	
	if(unlink(ABSOLUTE_PATH.'media/banners/'.$file))
	{
		$this->deleterow('imgmanager','articleid',$_GET['artid']);
		redirect($link->urlreturn('frontpage','','no').'&msgvalid=The_image_has_been_deleted');
	}
	else
	{
		redirect($link->urlreturn('frontpage','','no').'&msgerror=The_image_has_NOT_been_deleted');
	}
}

//echo '<div id="adminbanner">';

$this->wrapscript("$(document).ready(
					  function(){
							   var button = $('.qq-upload-button');
							   var imgholder = $('#uploaddiv');
							   var pathname = $(location).attr('href');
							   var numRand = Math.floor(Math.random()*1001);
							   
							   new AjaxUpload(button, {
											  action: 'plugins/fileUpload/fileupload.php',
											  name: 'userfile',
											  onSubmit: function(file,ext){
												  //insert the loading graphic
													if (ext && /^(jpg|JPG|png|jpeg|gif)$/.test(ext))
													  {
														  //insert the loading graphic
														  imgholder.html('<p><img src=\"styles/ichatemplate/admin/images/ajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
													  }
													  else
													  {
														  imgholder.html('<p><img src=\"styles/ichatemplate/admin/images/delete.png\" width=\"50\"><br/><strong>Error: only images ( .png,  .jpg, .jpeg, .gif) can be uploaded here</strong></p>');
														  return false;
													  }
														  
												  },
											  onComplete: function(file,response){
												  //show uploaded image
												  //alert(response);
												  imgholder.html('<div id=\"freshimg\"><img src=\"media/banners/'+file+'\" ><input type=\"hidden\" name=\"uploadimg\" id=\"uploadimg\" value=\"'+file+'\" /></div>');
												  jQuery.queryString(window.location.href, '&newimg=yes');
											 }
												  });			
							   });");

/*
$this->wrapscript("function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('uploaddiv'),
                action: 'plugins/fileUpload/fileupload.php',
				params: {
					name: 'userfile'
					},
				allowedExtensions: ['jpg', 'JPG', 'JPEG', 'jpeg', 'png', 'gif'],
                debug: true,
				onComplete: function(id, fileName, responseJSON){
					alert(responseJSON);
					//this.disable();
					},
            });           
        }
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = createUploader;  
		");
*/
echo '<div id="uploadarticle">';

echo '<div id="addleadarticles">';
require_once(ABSOLUTE_PATH.'components/admin/frontpage/picklead.php');
echo '</div>';
echo '</div>';

$lead = $this->runquery("SELECT * FROM articles WHERE lead = 'yes'",'multiple','all');
$leadCount = $this->getcount($lead); 

if($leadCount>=1)
{
	for($k=1; $k<=$leadCount; $k++)
	{
		echo '<div id="articleHolder">';
		
		$article = $this->fetcharray($lead);
		$articleID = $article['articleid'];
		
		$imgquery = $this->runquery("SELECT filename FROM imgmanager WHERE articleid='".$articleID."'",'single');
		//$this->print_last_error();
		
		if(!file_exists(ABSOLUTE_PATH.'media/banners/'.$imgquery['filename'])||$imgquery['filename']=='')
		{
			if($articleID!='')
			{
				$data = "data:{
								articleid: '".$articleID."'
								},";
			}
			$imgRand = rand();
			
			$this->wrapscript("$(document).ready(
					  function(){
							   var button = $('#imgupload".$imgRand."');
							   //var pathname = $(location).attr('href');
							   //var numRand = Math.floor(Math.random()*1001);
							   
							   new AjaxUpload(button, {
											  action: 'plugins/fileUpload/fileupload.php',
											  name: 'userfile',
											  ".$data."
											  onSubmit: function(file,ext){
												  //insert the loading graphic
													if (ext && /^(jpg|JPG|png|jpeg|gif)$/.test(ext))
													  {
														  //insert the loading graphic
														  button.html('<p><img src=\"styles/ichatemplate/admin/images/smallajaxloader.gif\" width=\"43\" height=\"11\"></p>');
													  }
													  else
													  {
														  button.html('<p><img src=\"styles/ichatemplate/admin/images/delete.png\" width=\"50\"><br/><strong>Error: only images ( .png,  .jpg, .jpeg, .gif) can be uploaded here</strong></p>');
														  return false;
													  }
														  
												  },
											  onComplete: function(file,response){
												  //show uploaded image
												  //alert(response);
												  button.html('<img src=\"media/banners/'+file+'\" height=\"100\" >');
												  this.disable();
											 }
												  });			
							   });");
			
			echo '<div id="leadimg">';
                            echo '<div id="delimg">';
                                echo '<span class="smalltxt">Click Image to Upload</span>';
                            echo '</div>';
                            
                            echo '<div id="imgholder">';
                                echo '<div id="imgupload'.$imgRand.'">';
                                    echo '<img src="'.SITE_PATH.'media/banners/noimage.png" width="100" height="100">';
                                echo '</div>';
                            echo '</div>';
		}
		else
		{
			echo '<div id="leadimg">';
                            echo '<div id="delimg">';
                                echo '<a href="'.$link->geturl().'&filedelete='.$imgquery['filename'].'&artid='.$articleID.'"><img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28"></a>';
                            echo '</div>';
                            echo '<div id="imgholder">';
                                echo '<img src="'.SITE_PATH.'media/banners/'.$imgquery['filename'].'" >';
                            echo '</div>';
		}
		echo '</div>';
		
		echo '<div id="leadholder">';
		echo '<div id="leadarticles">';
		if($leadCount==0)
		{
			$leadArticle = '<a href="#" class="leadpick"><strong>No Articles Selected</strong></a>';
			echo '</div>';
		}
		else
		{
			echo '<span class="leadpick"><strong>'.$article['title'].'</strong></span>';
			
			echo '<p>'.$this->shortentxt(strip_tags($article['body']),150).'</p>';
			echo '</div>';
		}
		echo '<a href="'.$link->geturl().'&fpremove=yes&artid='.$articleID.'" class="fpRemove">Remove Article and Image from Frontpage</a>';
		echo '</div>';
		echo '</div>';
		
	}
}
else
{
	$this->inlinemessage('No article has been selected for the Frontpage','valid');
}
?>