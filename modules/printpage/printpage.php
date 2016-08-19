<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');

$cipher = new encryption;
$link = new navigation;

if(!isset($_GET['alert']))
{
        echo '<a class="docslinks" href="?admin=com_view&folder=same&file=printsearch&alert=yes&enquery='.$cipher->encrypt($params['query']).'&dbfields='.$cipher->encrypt($params['displaydbfields']).'&dbtables='.$cipher->encrypt($params['dbtables']).'&tablecolumns='.$cipher->encrypt($params['tablecolumns']).'&printtitle='.$cipher->encrypt($params['printtitle']).'" title="Print" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=840,height=480,directories=no,location=no\'); return false;" rel="nofollow">
		<img src="'.STYLES_PATH.$this->set_template.'/images/icons/printericon.png" width="40" height="40" /><br/>
		<span class="docs">Print</span>
	</a>';
}
else
{
       echo '<script type="text/javascript">$(document).ready(function () { window.print(); });</script>';
}
