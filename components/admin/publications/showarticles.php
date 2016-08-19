<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//search processing
if(isset($_POST['artbutton']))
{
	$name = $_POST['artname'];
	$articles = $this->runquery("SELECT * FROM articles WHERE title LIKE '%$name%' AND articleid!='1609' ORDER BY articleid DESC",'multiple',30,$_GET['pageno']);
}
else
{
	$articles = $this->runquery("SELECT * FROM articles WHERE articleid!='1609' ORDER BY publishdate DESC",'multiple',30,$_GET['pageno']);
}

$articleCount = $this->getcount($articles);

//processing of article deletion
if($_GET['task']=='del')
{
	$aid = $_GET['aid'];
	
	if($this->deleterow('articles','articleid',$aid))
	{
		redirect($link->urlreturn('articles','','no').'&msgvalid=The_article_has_been_deleted.');
	}
}

$this->wrapscript('
				  $(document).ready(function(){
						$(\'a.deleteico\').click(function(){
								var alink = $(this).attr("rel");
								var rowid = $(this).attr("id");
								
								var pname = $(\'#test\'+rowid).attr(\'rel\');
								
								var confirmDel = $(\'<tr></tr>\').after(\'#test\'+rowid).html(\'<td colspan="7" align="center"><table width="100%"><tr><td colspan="2" align="center"><strong>Do you want to delete "\'+pname+\'" from your article list?</strong> </td></tr><tr><td align="right"><a href="\'+alink+\'" class="yesIcon"></a></td><td><a href="'.$link->urlreturn('articles','','no').'" class="noIcon"></a></td></tr></table>\');
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
										'height': 540,
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

if($articleCount==0&&!isset($_POST['artbutton']))
{
	$this->inlinemessage('No articles listed. Please add some!!','valid');
}

echo '<table width="100%" border="0" cellpadding="0" height="30" class="tableicons">
  <tr align="center">
    <td width="60%" rowspan="2" align="left"><h1>Site Information Management</h1></td>
    <td width="10%"><a href="?admin=com_admin&folder=articles&file=addedit&alert=yes" class="edit"><img src="'.MEDIA_PATH.'images/plusicon.jpg" width="30" height="30"></a></td>
    <td width="10%"><a href="?admin=com_admin&folder=categories&file=showcats"><img src="'.MEDIA_PATH.'images/addicon.jpg" width="30" height="30"></a></td>
  </tr>
  <tr align="center">
    <td><a href="?admin=com_admin&folder=articles&file=addedit&alert=yes" class="edit"></a></td>
    <td><a href="?admin=com_admin&folder=categories&file=showcats"></a></td>
  </tr>
  <tr align="center">
    <td align="left">
	<form action="" method="post" name="artform" id="artform">
  <table width="95%" border="0" cellpadding="5">
    <tr>
      <td><input type="text" name="artname" id="artname" /></td>
      <td><input type="submit" name="artbutton" id="myformbutton" value="Search Articles" /></td>
    </tr>
  </table>
</form>
</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>';

//diaplay search title
if(isset($_POST['artbutton']))
{
	echo '<br/><h3>Search result for "'.$_POST['artname'].'"</h3>';
	
	if($articleCount==0)
	{
		$this->inlinemessage('<a href="'.$link->urlreturn('articles','','no').'">Back to full listing</a>','valid');
	}
}

$link->createPgNav('SELECT * FROM articles ORDER BY publishdate DESC',30);

if($articleCount>=1)
{
	echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
	  <tr align="center" class="tabletitle">
		<td width="4%">&nbsp; [ID]</td>
		<td width="36%">Title</td>
		<td width="10%">Enabled</td>
		<td width="10%">Type</td>
		<td width="10%">Category</td>
		<td width="10%">Publish Date</td>
		<td width="10%">Expiry Date</td>
		<td width="10%">Parent Article ID</td>
		<td width="10%">Delete Article</td>
	  </tr>';
	for($i=1; $i<=$articleCount; $i++)
	{
		$article = $this->fetcharray($articles);
		
		$catid = $article['categoryid'];
		$cat = $this->runquery("SELECT * FROM categories WHERE catid='$catid'",'single');
		
		$regid = $article['regionid'];
		$reg = $this->runquery("SELECT * FROM region WHERE regionid='$regid'",'single');
		
		if($cat['categoryname']=='')
		{
			$catname = 'None Selected';
		}
		else
		{
			$catname = $cat['categoryname'];
		}
		
		echo '<tr class="item" rel="'.$this->shortentxt($article['title'],30).'" id="test'.$i.'">
			<td>'.$i.' ['.$article['articleid'].']</td>
			<td><a href="?admin=com_admin&folder=articles&file=addedit&artid='.$article['articleid'].'&alert=yes" class="edit">'.$this->shortentxt($article['title'],50).'</a></td>
			<td align="center"><span class="enabled" id="'.$article['articleid'].'_'.$article['enabled'].'">'.$article['enabled'].'</span></td>
			<td align="center">'.($article['atype']=='' ? 'None Selected' : ucfirst($article['atype'])).'</td>
			<td align="center">'.$catname.'</td>
			<td>'.date('d-m-Y H:i',$article['publishdate']).'</td>
			<td>'.($article['expirydate']<='0' ? 'Not Set' : date('d-m-Y H:i',$article['expirydate'])).'</td>
			<td>'.($article['parentid']=='0' ? 'None' : $article['parentid']).'</td>
			<td width="10%">
			<a class="deleteico" rel="'.$link->geturl().'&task=del&aid='.$article['articleid'].'" id="'.$i.'">
			<img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28">
			</a>
			</td>
		  </tr>';
	}
	echo '</table>';
}
$link->createPgNav('SELECT * FROM articles ORDER BY publishdate DESC',30);
?>