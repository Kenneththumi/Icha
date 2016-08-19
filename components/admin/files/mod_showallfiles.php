<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<iframe width="970" height="260" frameborder="0" '
        . 'src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng&fldr='.$show.'"> '
    . '</iframe>';
