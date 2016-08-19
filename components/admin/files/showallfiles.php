<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$cid = $_SESSION['sourceid'];
$ds = DIRECTORY_SEPARATOR;

echo '<h1 class="pagetitle">System Files</h1>';

echo '<iframe width="999" height="450" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng"> </iframe>';
?>