<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$temp = new template;
$link = new navigation;

$temp->loadcss(['plugins','smartmenus','css','sm-core-css']);
$temp->loadcss(['plugins','smartmenus','addons','bootstrap','jquery.smartmenus.bootstrap']);

$temp->loadplugin('smartmenus/jquery.smartmenus.min','js');
$temp->loadplugin('smartmenus/addons/bootstrap/jquery.smartmenus.bootstrap.min','js');

echo "<!-- SmartMenus jQuery init -->
    <script type=\"text/javascript\">
            $(function() {
                    $('#main-menu').smartmenus({
                            subMenusSubOffsetX: 1,
                            subMenusSubOffsetY: -8,
                            subIndicatorsText: ''
                    });
            });
    </script>"; 

$link->buildMenu('ftd');
