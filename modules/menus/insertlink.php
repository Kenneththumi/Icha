<?php
require_once('../../includes/siteclasses.php');
//create dbase object to connect to dbase
$dbase = new database;

	echo "<h2>Menu Manager</h2>";
	echo '<form method="post" action="">
		<table width="100%" border="0" cellspacing="1" cellpadding="10">
		
		  <tr>
			<td width="42%" class="contenttitle">New Menu Link </td>
			<td width="58%" class="contenttitle">&nbsp;</td>
		  </tr>
		  <tr>
			<td class="contentpane">Menu Item </td>
			<td class="contentpane"><input name="menuitem" type="text" id="menuitem" size="30"></td>
		  </tr>
		  <tr>
			<td class="contentpane">URL Link </td>
			<td class="contentpane"><input name="url_link" type="text" id="url_link"></td>
		  </tr>
		  <tr>
			<td class="contentpane">Location</td>
			<td class="contentpane"><select name="location">
			  <option value="none">None Selected</option>
			  <option value="frontend">Front End</option>
			  <option value="buyer">Buyer</option>
			  <option value="seller">Seller</option>
			  <option value="admin">Administrator</option>
			</select></td>
		  </tr>
		  <tr>
			<td class="contentpane"><a href="javascript:history.go(-1)">[Back]</a></td>
			<td class="contentpane"><input type="submit" name="menu_submit" id="menu_submit" value="Save New Link"></td>
		  </tr>
		</table>';
	
	if($_POST['menu_submit']&&$_POST['menuitem']!=='')
	{
		$name = $_POST['menuitem'];
		$url = $_POST['url_link'];
		
		$location = $_POST['location'];
		$published = $_POST['published'];
		
		$menu_query = mysql_query("SELECT * FROM menus WHERE menutype = '$location' ORDER BY linkorder DESC");
		$menu_array = mysql_fetch_array($menu_query);
		$last_no = $menu_array['linkorder'];
		$order = $last_no+1;
		
		$query="INSERT INTO menus(menuname,menulink,menutype,linkorder) VALUES ('$name','$url', '$location','$order')";
		
		if(mysql_query($query))
		{
			echo "in";
			//redirect('?section='.encrypt('menus').'&msg=linkin');
		}	
	}

?>