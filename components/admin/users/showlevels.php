<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

	if($_GET['task']=='del')
	{
		$delid = $_GET['acid'];
		
		//delete the user details
		if($this->deleterow('accesslevels','accessid',$delid))
		{			
			redirect($link->urlreturn('access levels','','no').'&msgvalid=The_level_has_been_deleted');
		}
	}

$this->wrapscript("$(document).ready(function() 
						{							
							$(\"a.button\").fancybox({
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

$this->wrapscript('
					  $(document).ready(function(){
							$(\'a.deleteico\').click(function(){
									var alink = $(this).attr("rel");
									var rowid = $(this).attr("id");
									
									var pname = $(\'#test\'+rowid).attr(\'rel\');
									//alert(rowid);
									var confirmDel = $(\'#delete_confirm\').html(\'<div class="show_delete"><strong>Do you want to delete "\'+pname+\'" from your list?</strong> <a href="\'+alink+\'" class="button">Yes</a><a href="'.$link->geturl().'" class="button" >No</a></div>\');
									});
							
							   });');

$levels = $this->runquery("SELECT * FROM accesslevels ORDER BY displayname DESC",'multiple',10);
$levelCount = $this->getcount($levels);

echo '<h1>System Access Levels</h1>';

//load the toolbar
$settings = array(
						'buttons' => 'add,delete',
						'addhref' => '?admin=com_admin&folder=users&file=addlevel&alert=yes',
						'addtext' => 'Add New Level',
						'delhref' => $link->geturl().'&task=del',
						'deltext' => 'Delete Checked Levels'
					);
	
$this->loadtoolbar($settings);

//the deletion JQuery script
$this->loadscripts('multipleDelete','yes');

echo '<table width="100%" border="0" cellpadding="5" class="table table-bordered">
	  <thead>
	  	<td>
			<input type="checkbox" name="itemlist" class="checkall" >
		</td>
		<td>Access Level</td>
		<td>Deletion Allowed</td>
		<td></td>
	  </thead>';
	  
	  for($j=1; $j<=$levelCount; $j++)
	  {
		  $levelFetch = $this->fetcharray($levels);
		  
		  echo '<tr '.($j%2==0 ? 'class="item"' : 'class="item2"').' rel="'.$levelFetch['displayname'].'" id="test'.$j.'">';
		  
		  echo '<td>
					<input type="checkbox" name="item[]" class="chkitem" value="'.$levelFetch['accessid'].'" id="'.$i.'" >
				</td>
		  		<td><a href="?admin=com_admin&folder=users&file=addlevel&levelid='.$levelFetch['accessid'].'&alert=yes" class="button">'.$levelFetch['displayname'].'</a></td>
				<td>'.($levelFetch['deletionallowed']=='' ? 'Not Specified' : ucfirst($levelFetch['deletionallowed'])).'</td>
				<td>
			<a class="deleteico" rel="'.$link->geturl().'&task=del&acid='.$levelFetch['accessid'].'" id="'.$j.'">
				<img src="'.STYLES_PATH.'df_template/images/delete.png" width="28" height="28">
				</a>
			</td>';
		  
		  echo '</tr>';
		  
		  unset($linknames);
	  }
	  
echo '</table>';
?>