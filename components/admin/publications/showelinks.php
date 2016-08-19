<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$article = $this->runquery("SELECT * FROM articles WHERE articleid='1609'",'single');

$spl_article = explode(',',ltrim($article['body'],','));

//delete function
if($_GET['task']=='del')
{
	$key = $_GET['key'];
	
	unset($spl_article[$key]);
	
	$body = join($spl_article,',');
	$save = array(
					  'body'=> $body
				  );
				  
	if($this->dbupdate('articles',$save,"articleid='1609'"))
	{
		redirect($link->urlreturn('External Links','','no').'&msgvalid=External_link_has_been_deleted');
	}
}



echo '<table width="100%" border="0" cellpadding="0" height="30" class="tableicons">
  <tr align="center">
    <td width="60%" align="left"><h1>External Links</h1></td>
    <td width="10%"></td>
    <td width="10%"><a href="?admin=com_admin&folder=articles&file=elinks&artid=1609&alert=yes" class="edit"><img src="'.MEDIA_PATH.'images/plusicon.jpg" width="30" height="30"></a></td>
  </tr></table>';

echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
	  <tr align="center" class="tabletitle">
		<td width="36%">Title</td>
		<td width="10%">URL</td>
		<td width="10%"></td>
	  </tr>';

$this->wrapscript('
				  $(document).ready(function(){
						$(\'a.deleteico\').click(function(){
								var alink = $(this).attr("rel");
								var rowid = $(this).attr("id");
								
								var pname = $(\'#test\'+rowid).attr(\'rel\');
								
								var confirmDel = $(\'<tr></tr>\').after(\'#test\'+rowid).html(\'<td colspan="7" align="center"><table width="100%"><tr><td colspan="2" align="center"><strong>Do you want to delete "\'+pname+\'" from your list?</strong> </td></tr><tr><td align="right"><a href="\'+alink+\'" class="yesIcon"></a></td><td><a href="'.$link->urlreturn('External Links','','no').'" class="noIcon"></a></td></tr></table>\');
								});
						
						$(\'.enabled\').click(
																 function()
																 {
																	 status = $(this).attr(\'id\');
																	 $(\'#\'+status).html(\'<img src="styles/ichatemplate/images/smallajaxloader.gif" width="43" height=\"11\">\');
																	 $(\'#\'+status).load(\'components/admin/articles/changestat.php?status=\'+status);
																 }
																 );
						   });');

$this->wrapscript("$(document).ready(function() 
					{
						$(\"a.edit\").fancybox({
										'width': 700,
										'height': 200,
										'autoDimensions': false,
										'autoScale'			: false,
										'transitionIn'		: 'elastic',
										'transitionOut'		: 'elastic',
										'enableEscapeButton' : false,
										'overlayShow' : true,
										'overlayColor' : '#fff',
										'overlayOpacity' : 0,
										'type': 'iframe',
										'scrolling': 'auto',
										'hideOnOverlayClick': false
									});
					});");
	  
	  for($i=0; $i<=(count($spl_article)-1); $i++)
	  {
		  echo '<tr class="item" rel="'.$this->shortentxt($this->findText('[title]','[|title]',$spl_article[$i]),30).'" id="test'.$i.'">
			<td><a href="?admin=com_admin&folder=articles&file=elinks&artid=1609&key='.$i.'&task=edit&alert=yes" class="edit">'.$this->findText('[title]','[|title]',$spl_article[$i]).'</a></td>
			<td><a href="?admin=com_admin&folder=articles&file=elinks&artid=1609&key='.$i.'&task=edit&alert=yes" class="edit">
			'.$this->findText('[url]','[|url]',$spl_article[$i]).'
			</a></td>
			<td align="center">
				<a class="deleteico" rel="'.$link->geturl().'&task=del&key='.$i.'" id="'.$i.'">
				<img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28">
				</a>
			</td>
		  </tr>';
	  }

echo '</table>';
?>