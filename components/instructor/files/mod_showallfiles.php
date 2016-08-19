<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$_SESSION['subfolder'] = 'training';

echo '<iframe width="965" height="700" frameborder="0" '
. 'src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng&fldr=training"> '
. '</iframe>';
