<?php	
	$location = decrypt($_GET['location']);
	$old_position = decrypt($_GET['oldpos']);
	$new_position = decrypt($_GET['newpos']);
	
	$switch = mysql_query("UPDATE menu SET  `menu_order` = IF( menu_order =$old_position, $new_position, $old_position ) WHERE menu_order IN ( $old_position, $new_position )");
	
	
	redirect('?section='.encrypt('menus').'&msg=reordersuccess');
?>