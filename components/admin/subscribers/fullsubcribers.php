<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$catid = $_GET['catid'];
$cat = $this->runquery("SELECT * FROM categories WHERE catid='$catid'",'single');

$this->loadscripts();
$this->loadstyles('backoffice');

$link = new navigation;

echo '<h1>Category: '.ucfirst($cat['categoryname']).' Subscribers</h1>';

//processing of article deletion
if($_GET['task']=='del')
{
	$sid = $_GET['sid'];
	
	if($this->deleterow('subscribers','subid',$sid))
	{
		redirect($link->urlreplace('task=del','','yes').'&msgvalid=The_subscriber_has_been_deleted.');
	}
}

$subs = $this->runquery("SELECT * FROM subscribers WHERE catidsubscribed='$catid'",'multiple',10,$_GET['pageno']);
$subCount = $this->getcount($subs);

$link->createPgNav("SELECT * FROM subscribers WHERE catidsubscribed='$catid'",10);

$this->wrapscript('
				  $(document).ready(function(){
						$(\'a.deleteico\').click(function(){
								var alink = $(this).attr("rel");
								var rowid = $(this).attr("id");
								
								var pname = $(\'#test\'+rowid).attr(\'rel\');
								
								var confirmDel = $(\'<tr></tr>\').after(\'#test\'+rowid).html(\'<td colspan="6" align="center"><table width="100%"><tr><td colspan="2" align="center"><strong>Do you want to delete "\'+pname+\'" from your subscriber list?</strong> </td></tr><tr><td align="right"><a href="\'+alink+\'" class="yesIcon"><img src="styles/ichatemplate/images/yesIcon.png" width="82" height="42"></a></td><td><a href="'.$link->urlreturn('Site Information','','no').'" class="noIcon"><img src="styles/ichatemplate/images/noIcon.png" width="82" height="42"></a></td></tr></table>\');
								});
						
						$(\'.enabled\').click(
																 function()
																 {
																	 status = $(this).attr(\'id\');
																	 $(\'#\'+status).html(\'<img src="styles/ichatemplate/images/smallajaxloader.gif" width="43" height=\"11\">\');
																	 $(\'#\'+status).load(\'components/admin/subscribers/changestat.php?status=\'+status);
																 }
																 );
						   });');


if($subCount>=1)
{
	echo '<table width="99%" border="0" cellpadding="10" class="tablelist">
	  <tr class="tabletitle">
		<td>&nbsp;</td>
		<td>Subcriber Name</td>
		<td>Email</td>
		<td>Enabled</td>
		<td></td>
	  </tr>';
	  
	for($i=1; $i<=$subCount; $i++)
	{
		$subArray = $this->fetcharray($subs);
		
		echo '<tr class="item" rel="'.$subArray['name'].'" id="test'.$i.'">
			<td>'.$i.'</td>
			<td>'.$subArray['name'].'</td>
			<td>'.$subArray['email'].'</td>
			<td><span class="enabled" id="'.$subArray['subid'].'_'.$subArray['enabled'].'">'.$subArray['enabled'].'</span></td>
			<td>
			<a class="deleteico" rel="'.$link->geturl().'&task=del&sid='.$subArray['subid'].'" id="'.$i.'">
				<img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28">
			</a>
			</td>
		  </tr>';
	}
	
	echo '</table>';
}
else
{
	$this->inlinemessage('No subscribers are listed under '.ucfirst($cat['categoryname']),'valid');
}
?>