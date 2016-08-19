<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadscripts();
$this->loadscripts('jquery.autocomplete','yes');

$this->wrapscript('$().ready(function() {
				function log(event, data, formatted) {
					$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
				}
				
				function formatItem(row) {
					return "<p>" + row[0] + "</p>";
				}
				function formatResult(row) {
					return row[0].replace(/(<.+?>)/gi, \'\');
				}
				
				$("#namesearch").autocomplete("'.SITE_PATH.'components/admin/frontpage/transferlead.php", {
					minChars: 1,
					selectFirst: false,
					mustMatch: true,
					width: 427,
					multiple: false,
					matchContains: true,
					formatItem: formatItem,
					formatResult: formatResult
				});
			}); ');

$this->loadplugin('classForm/class.form');

$catsearch = new form;
$catsearch->setAttributes(array(
								 "includesPath" => PLUGIN_PATH.'/classForm/includes',
								 "width" => 140,
								 "map"=>array(1,1,1),
								 "noAutoFocus" => 1,
								 "preventJQueryLoad" => true,
								 "preventJQueryUILoad" => true,
								 "preventDefaultCSS" => false,
								 "action"=>'?content=mod_search&folder=same&file=searchresults&stype=simple'
								 ));

$catsearch->addHidden("cmd","search");

$catsearch->addTextBox(' ','namesearch','');
$catsearch->addButton('Search','submit',array('id'=>'myformbutton'));

$catsearch->render();
?>