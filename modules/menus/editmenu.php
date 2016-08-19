<?php	
	//checks link position lower than the current
	function down_pos($order,$position)
	{
		$link_query = mysql_query("SELECT * FROM menuss WHERE linkorder = '$order' AND menu_order > '$position'");
		$link_num = mysql_num_rows($link_query);
		
		if($link_num>=1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	echo "<h2>Menu Manager</h2>";
	
	if($_GET['delete_id'])
	{
		$del_id = decrypt($_GET['delete_id']);
		
		$delete_query = mysql_query("DELETE FROM menus WHERE menuid='$del_id'");
		
		if($delete_query)
		{
			redirect('?section='.encrypt('menus').'&msg=deleted');
		}
	}
	
	$linkid = decrypt($_GET['linkid']);
	
	$menu_query = mysql_query("SELECT * FROM menus WHERE menuid = '$linkid'");
	$menu_array = mysql_fetch_array($menu_query);
	
	/*
	$ex_link = explode('=',$menu_array['menulink']);
				
	if(isset($ex_link[2]))
	{
		$ex_part = explode('&',$ex_link[1]);
		$menulink= $ex_link[0].'='.decrypt($ex_part[0]).'&'.$expart[1].'='.decrypt($ex_link[2]);
	}
	else
	{
		$menulink = $ex_link[0].'='.decrypt($ex_link[1]);
	}*/
	
	echo '<form method="post" action="">
		<table width="100%" border="0" cellspacing="1" cellpadding="10">
		<tr>
			<td width="42%">&nbsp;</td>
			<td width="58%" align="right"><a href="?section='.encrypt('editmenu').'&delete_id='.encrypt($menu_array['menuid']).'" id="partnerlink">Delete "'.$menu_array['menuname'].'" Link</a></td>
		  </tr>
		  <tr>
			<td width="42%" class="contenttitle">Edit Menu Link </td>
			<td width="58%" class="contenttitle">&nbsp;</td>
		  </tr>
		  <tr>
			<td class="contentpane">Menu Item </td>
			<td class="contentpane"><input name="id" type="hidden" id="id" value="'.$menu_array['menuid'].'"><input name="menuname" type="text" id="menuname" value="'.$menu_array['menuname'].'" size="30"/></td>
		  </tr>
		  <tr>
			<td class="contentpane">URL Link </td>
			<td class="contentpane"><input name="url_link" type="text" id="url_link" value="'.$menu_array['menulink'].'"/></td>
		  </tr>
		  <tr>
			<td class="contentpane">Location</td>
			<td class="contentpane"><select name="location">';
			if($menu_array['linkorder']=='frontend')
			{
				echo '<option value="frontend" selected>Front End</option>';
			}
			else if($menu_array['linkorder']=='universal')
			{
				echo '<option value="universal" selected>Universal</option>';
			}
			else if($menu_array['linkorder']=='partner_bank')
			{
				echo '<option value="partner_bank" selected>Partner Bank</option>';
			}
			else if($menu_array['linkorder']=='member_bank')
			{
				echo '<option value="member_bank" selected>Member Bank</option>';
			}
			else if($menu_array['linkorder']=='non_member')
			{
				echo '<option value="non_member" selected>Non Member</option>';
			}
			else if($menu_array['linkorder']=='admin')
			{
				echo '<option value="admin" selected>Administrator</option>';
			}
			else if($menu_array['linkorder']=='superadmin')
			{
				echo '<option value="superadmin" selected>Super Administrator</option>';
			}
		echo '<option value="universal">Universal</option>
			  <option value="frontend">Front End</option>
			  <option value="partner_bank">Partner Bank</option>
			  <option value="member_bank">Member Bank</option>
			  <option value="non_member">Non Member</option>
			  <option value="admin">Administrator</option>
			  <option value="superadmin">Super Administrator</option>
			</select>    </td>
		  </tr>
		  <tr>
			<td class="contentpane">Menu Order</td>
			<td class="contentpane">';
		echo $menu_array['menu_order'];
		
		$downer = down_pos($menu_array['linkorder'],$menu_array['menu_order']);
		
		if($menu_array['menu_order']!=1)
		{
			echo " <a href='?section=".encrypt('changeorder')."&location=".encrypt($menu_array['linkorder'])."&oldpos=".encrypt($menu_array['menu_order'])."&newpos=".encrypt($up_pos)."'><img src='../images/uparrow.png' width='12' height='12' border='0' /></a>";
		}
		if($downer==TRUE)
		{
			echo "<a href='?section=".encrypt('changeorder')."&location=".encrypt($menu_array['linkorder'])."&oldpos=".encrypt($menu_array['menu_order'])."&newpos=".encrypt($down_pos)."'><img src='../images/downarrow.png' width='12' height='12' border='0' /></a>";
		}
			
		echo '</td>
		  </tr>
		  <tr>
			<td class="contentpane">Published</td>
			<td class="contentpane"><select name="published">';
			if($menu_array['published']==1)
			{
			 	 echo '<option value="1" selected>Yes</option>
				  <option value="0">No</option>';
			}
			else if($menu_array['published']==0)
			{
				echo '<option value="0" selected>No</option>
				  <option value="1">Yes</option>';
			}
		echo '</select>    </td>
		  </tr>
		  <tr>
			<td class="contentpane"><a href="javascript:history.go(-1)">[Back]</a></td>
			<td class="contentpane"><input type="submit" name="menu_submit" id="menu_submit" value="Save Menu Changes"></td>
		  </tr>
		</table></form>';
	
	if($_POST['menu_submit'])
	{
		$id = $_POST['id'];
		$name = $_POST['menuname'];
		
		$post_url = rtrim($_POST['url_link']," ");
		$order = $_POST['location'];
		$published = $_POST['published'];
		
		$query="UPDATE menus  set menuname='$name', menulink='$post_url', linkorder='$order', published='$published' where menuid='$id'";
		if(mysql_query($query))
		{
			redirect('?section='.encrypt('menus').'&msg=changesin');
		}	
	}
?>