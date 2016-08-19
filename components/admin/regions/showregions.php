<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$regionq = $this->runquery("SELECT * FROM region ORDER BY regionid ASC",'multiple','all','','read');

$regionCount = $this->getcount($regionq);

//processing of article deletion
if($_GET['task']=='del')
{
	$regionid = $_GET['regionid'];
	
	if($this->deleterow('region','regionid',$regionid))
	{		
		redirect($link->urlreturn('regions').'&msgvalid=The_region_has_been_deleted.');
	}
}

$this->wrapscript('
				  $(document).ready(function(){
						$(\'a.deleteico\').click(function(){
								var alink = $(this).attr("rel");
								var rowid = $(this).attr("id");
								
								var pname = $(\'#test\'+rowid).attr(\'rel\');
								
								var confirmDel = $(\'<tr></tr>\').after(\'#test\'+rowid).html(\'<td colspan="5" align="center"><table width="100%"><tr><td colspan="2" align="center"><strong>Do you want to delete "\'+pname+\'" from your region list?</strong> </td></tr><tr><td align="right"><a href="\'+alink+\'" class="yesIcon"></a></td><td><a href="'.$link->geturl().'" class="noIcon"></a></td></tr></table>\');
								});
						
						$(\'.enabled\').click(
																 function()
																 {
																	 status = $(this).attr(\'id\');
																	 $(\'#\'+status).html(\'<img src="styles/ichatemplate/images/smallajaxloader.gif" width="43" height=\"11\">\');
																	 $(\'#\'+status).load(\'components/admin/regions/changestat.php?status=\'+status);
																 }
																 );
						   });');


$this->wrapscript("
				  $(document).ready(function() 
					{
						$(\"a.cats\").fancybox({
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
					});");

if($regionCount==0)
{
	$this->inlinemessage('No regions listed. Please add some!!','valid');
}

echo '<table width="100%" border="0" cellpadding="0" height="30" class="tableicons">
  <tr align="center">
    <td width="60%" rowspan="2" align="left"><h1>Site Region Management</h1></td>
    <td width="10%"><a href="?admin=com_admin&folder=regions&file=addedit&alert=yes" class="cats"><img src="'.MEDIA_PATH.'images/plusicon.jpg" width="30" height="30"></a></td>
  </tr>
  <tr align="center">
    <td></td>
  </tr>
  <tr align="center">
    <td align="left">
</td>
    <td>&nbsp;</td>
  </tr>
</table>';

if($regionCount>=1)
{
	echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
	  <tr align="center" class="tabletitle">
		<td>&nbsp;</td>
		<td>Region Name</td>
		<td>Parent Region</td>
		<td>Enabled</td>
		<td></td>
	  </tr>';
	  
	for($i=1; $i<=$regionCount; $i++)
	{
		$region = $this->fetcharray($regionq);
		
		$pid = $region['parentregionid'];
		$parent = $this->runquery("SELECT * FROM region WHERE regionid='$pid'",'single');
		
		if($parent['region']=='')
		{
			$parent = 'None Selected';
		}
		else
		{
			$parent = $parent['region'];
		}
		
		echo '<tr class="item" rel="'.$region['region'].'" id="test'.$i.'">
			<td>'.$i.'</td>
			<td><a href="?admin=com_admin&folder=regions&file=addedit&regionid='.$region['regionid'].'&alert=yes" class="cats">'.$region['region'].'</a></td>
			<td><a href="?admin=com_admin&folder=regions&file=addedit&regionid='.$region['regionid'].'&alert=yes" class="cats">'.$parent.'</a></td>
			<td><span class="enabled" id="'.$region['regionid'].'_'.$region['enabled'].'">'.$region['enabled'].'</span></td>
			<td width="20%">
			<a class="deleteico" rel="'.$link->geturl().'&task=del&regionid='.$region['regionid'].'" id="'.$i.'">
			<img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28">
			</a>
			</td>
		  </tr>';
	}
	
	echo '</table>';
}

?>