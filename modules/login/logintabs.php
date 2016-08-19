<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadscripts('jquery.tools.min','yes');

echo '<link href="'.SITE_PATH.'scripts/jquery.addons/jquery.css/tabs.css" rel="stylesheet" type="text/css" />';

$this->wrapscript('// perform JavaScript after the document is scriptable.
                    $(function() {
                        // setup ul.tabs to work as tabs for each div directly under div.panes
                        $("ul.tabs").tabs("div.tbpanes > div");
                    });');

echo '<ul class="tabs">
	<li><a href="#">Sign Up / Register</a></li>
	<li><a href="#">Login / Sign In</a></li>
</ul>';
 
//<!-- tab "panes" -->
echo '<div class="tbpanes">
	<div>
        <div class="one">';

echo '</div></div>';

echo '<div>
    <div class="two">';

echo '</div>
        </div>';
    
echo '</div>';
?>
