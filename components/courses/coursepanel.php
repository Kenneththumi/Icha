<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<div class="tbpanes">
	<div>
      <div class="one">';
echo '</div>
    <div class="two">';
    include ABSOLUTE_PATH.'components/courses/courseapplicationform.php';
echo '</div>
    </div>';
    
echo '</div>';

