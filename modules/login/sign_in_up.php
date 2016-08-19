<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadplugin('encryption/encrypt');
$code = new encryption();

if($_SESSION['username']==NULL || $_SESSION['username']=='Guest User')
{    
    echo '<div class="member socialtag">
            <a href="'.SITE_PATH.'login" target="_blank" 
                class="launchlogin">
                <img src="'.DEFAULT_TEMPLATE_PATH.'/images/member-tag.png" width="188" height="44" />
            </a>
          </div>';
}
?>
