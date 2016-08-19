<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if($_GET['show'] == 'publication'){
    $show = 'Publication';
    $type = 'publication';
}
elseif($_GET['show'] == 'research'){
    $show = 'Research Paper';
    $type = 'research';
}
elseif($_GET['show'] == 'policy'){
    $show = 'Policy Brief';
    $type = 'policy';
}

echo '<h1 class="fullarticleTitle">Our '.$show.'s</h1>';

echo '<div id="itemContainer">';

$this->loadmodule('filter-research','research',['type'=>$type]);

echo '</div>';
