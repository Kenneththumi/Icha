<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(isset($_POST['viewmenus'])&&$_POST['selectmenu']!='none')
{
	$name = $_POST['selectmenu'];
	$menu = $this->runquery("SELECT * FROM menus WHERE menugroup='".$name."' ORDER BY linkorder ASC",'multiple',20,$_GET['pageno']);

	$this->inlinemessage('Only Menu: "'.$name.'" items are in view','valid');
}
else
{
	$menu = $this->runquery("SELECT * FROM menus ORDER BY menugroup,linkorder ASC",'multiple',20,$_GET['pageno']);
}

$mCount = $this->getcount($menu);

$link = new navigation;

if($_GET['task']=='del')
{
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
                
		$this->deleterow('menus','menuid',$id);
		
		redirect($link->urlreturn('Menus','',$_SESSION['usertype'],'no').'&msgvalid=The_link_has_been_removed');
	}
	elseif(isset($_GET['ids']))
	{
		$ids = explode(',',$_GET['ids']);
		
		foreach($ids as $key=>$id)
		{
			$this->deleterow('menus','menuid',$id);
		}
		
		redirect($link->urlreturn('Menus','',$_SESSION['usertype'],'no').'&msgvalid=The_links_have_been_removed');
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

$this->wrapscript("$(document).ready(function() 
					{
						$(\"a.fancyalert\").fancybox({
                                                                        'width': 620,
                                                                        'height': 430,
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
						
						$(\"a.chgorder\").fancybox({
                                                                    'width': 640,
                                                                    'height': 440,
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
						

						$(\"a.add\").fancybox({
                                                                'width': 630,
                                                                'height': 520,
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

echo '<h1 class="pagetitle">Site Navigation</h1>';

//load the toolbar
$settings = array(
            'buttons' => 'add,delete',
            'addhref1' => '?admin=com_admin&folder=navigation&file=newlink&alert=yes',
            'addtext1' => 'Add New Link',
            'addhref2' => '?admin=com_admin&folder=navigation&file=newmenu&alert=yes',
            'addtext2' => 'Add New Menu Group',
            'delhref' => $link->geturl().'&task=del',
            'deltext' => 'Delete Checked Items',
            'iteration' => array('add' => 2)
            );
	
$this->loadtoolbar($settings);

//the deletion JQuery script
$this->loadscripts('multipleDelete','yes');

if(isset($_POST['viewmenus'])&&$_POST['selectmenu']!='none')
{
	$name = $_POST['selectmenu'];
	$link->createPgNav("SELECT * FROM menus WHERE menugroup=".$name." ORDER BY menugroup ASC",20);
}
else
{
	$link->createPgNav("SELECT * FROM menus ORDER BY menugroup ASC",20);
}
	
echo '</td>
  </tr>
</table>';

echo '<table width="100%" border="0" cellpadding="8" class="table table-bordered">
	  <tr align="center" class="tabletitle">
	  	<td>
			<input type="checkbox" name="itemlist" class="checkall" >
		</td>
		<td width="46%" colspan="2">Link Name</td>
		<td width="16%">Link Url</td>
		<td width="16%">Position</td>
		<td width="16%">Enabled</td>
		<td>Menu Name</td>
		<td></td>
		<td></td>
	  </tr>';


	  for($i=1; $i<=$mCount; $i++)
	  {

		  $mArray = $this->fetcharray($menu);

		  if($mArray['parentid']==0||$mArray['parentid']=='')
		  {
			  echo '<tr align="left" '.($i%2==0 ? 'class="item"' : 'class="item2"').' rel="'.$mArray['linkname'].'" id="test'.$i.'">
			  		<td>
						<input type="checkbox" name="item[]" class="chkitem" value="'.$mArray['menuid'].'" id="'.$i.'" >
					</td>
					<td width="36%" colspan="2"><a href="'.$link->urlreplace('file=shownavigation','file=changemenu').'&alert=yes&menuid='.$mArray['menuid'].'" class="fancyalert">'.$mArray['linkname'].'</a></td>
					<td width="36%"><a href="'.$link->urlreplace('file=shownavigation','file=changemenu').'&alert=yes&menuid='.$mArray['menuid'].'" class="fancyalert">'.$mArray['menulink'].'</a></td>
					<td width="20%">'.$mArray['position'].'</td>
					<td width="20%">'.$mArray['enabled'].'</td>
					<td width="20%">'.@navigation::getgroupname($mArray['menugroup']).'</td>
					<td width="20%">';


				if(($i==1||$menuchk!=$mArray['menugroup'])&&$mArray['menugroup']!='')
				{
					echo '<a href="'.$link->urlreplace('file=shownavigation','file=changeorder').'&alert=yes&mname='.$mArray['menugroup'].'" class="chgorder"><img alt="Use this feature to change the order of menu items" src="'.STYLES_PATH.'df_template/images/icons/reorder.png" width="28" height="28"></a>';

					$menuchk = $mArray['menugroup'];
				}

			  echo '</td><td width="20%">
			  <a class="deleteico" rel="'.$link->geturl().'&lid='.$mArray['menuid'].'&task=del" id="'.$i.'">
				<img src="'.STYLES_PATH.'df_template/images/delete.png" width="15" height="15">
				</a>
			  </td>';
			  echo '</tr>';
		  }

		  $menuid = $mArray['menuid'];
		  $subs = $this->runquery("SELECT * FROM menus WHERE parentid='$menuid' ORDER BY linkorder ASC",'multiple','all');
		  $subCount = $this->getcount($subs);


		  if($subCount >= 1)
		  {
			  for($j=1; $j<=$subCount; $j++)
			  {
				  $sublink = $this->fetcharray($subs);

				  echo '<tr align="left" rel="'.$sublink['linkname'].'" '.($j%2==0 ? 'class="item"' : 'class="item2"').' id="test'.($i*1000).'">
				  		  <td>
								<input type="checkbox" name="item[]" class="chkitem" value="'.$sublink['menuid'].'" id="'.$i.'" >
							</td>
						  <td width="8%" align="right"><sup>|_</sup></td>
						  <td width="19%"><a href="'.$link->urlreplace('file=shownavigation','file=changemenu').'&alert=yes&menuid='.$sublink['menuid'].'" class="fancyalert">'.$sublink['linkname'].'</a></td>
						  <td><a href="'.$link->urlreplace('file=shownavigation','file=changemenu').'&alert=yes&menuid='.$sublink['menuid'].'" class="fancyalert">'.$sublink['menulink'].'</a></td>
                                                  <td></td>
						  <td>'.$sublink['enabled'].'</td>
						  <td>'.@navigation::getgroupname($sublink['menugroup']).'</td>
						  <td></td>
						  <td><a class="deleteico" rel="'.$link->geturl().'&id='.$sublink['menuid'].'&task=del" id="'.($i*1000).'">
				<img src="'.STYLES_PATH.'df_template/images/delete.png" width="15" height="15">
				</a></td>
						</tr>';
			  }
		  }
	  }

echo '</table>';

if(isset($_POST['viewmenus'])&&$_POST['selectmenu']!='none')
{
	$name = $_POST['selectmenu'];
	$link->createPgNav("SELECT * FROM menus WHERE menugroup=".$name." ORDER BY menugroup ASC",20);
}
else
{
	$link->createPgNav("SELECT * FROM menus ORDER BY menugroup ASC",20);
}
?>