<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1>Menu List Groups</h1>';

$link = new navigation;

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
						});");

$groups = $this->runquery("SELECT * FROM menugroups ORDER BY groupid ASC",'multiple');
$groupcount = $this->getcount($groups);

//load the toolbar
$settings = array(
						'buttons' => 'add,delete',
						'addhref' => '?admin=com_admin&folder=navigation&file=newmenu&alert=yes',
						'addtext' => 'Add Menu Group',
						'delhref' => $link->geturl().'&task=del',
						'deltext' => 'Delete Checked Groups'
					);
	
$this->loadtoolbar($settings);

//the deletion JQuery script
$this->loadscripts('multipleDelete','yes');

echo '<table width="100%" border="0" cellpadding="5" class="table table-bordered">
	  <thead>
		<td width="5%">
			<input type="checkbox" name="itemlist" class="checkall" >
		</td>
		<td><strong>Menu Group Name</strong></td>
		<td>&nbsp;</td>
	  </thead>';

for($r=1; $r<=$groupcount; $r++)
{
	$group = $this->fetcharray($groups);
	
	echo '<tr rel="'.$group['menuname'].'" id="test'.$r.'">
		  			<td>
						<input type="checkbox" name="item[]" class="chkitem" value="'.$group['groupid'].'" id="'.$r.'" >
					</td>
		  			<td>'.$group['menuname'].'</td>
					<td>
						<a class="deleteico" rel="'.$link->geturl().'&task=del&id='.$group['groupid'].'" id="'.$r.'">
							<img src="'.STYLES_PATH.'df_template/images/delete.png" width="15" height="15">
						</a>
					</td>
				</tr>';
}

echo '</table>';
?>