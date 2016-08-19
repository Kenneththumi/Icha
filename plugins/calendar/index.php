<?php
//restrict direct access
defined('LOAD_POINT')or die('RESTRICTED ACCESS');
define('CALENDAR_PATH', PLUGIN_PATH.'/calendar');
define('ABSOLUTE_CALENDAR_PATH', ABSOLUTE_PATH.'/plugins/calendar');

echo '<link type="text/css" href="'.CALENDAR_PATH.'/css/calendar.css" rel="stylesheet" />
        <script src="'.CALENDAR_PATH.'/js/calendar.js" ></script>
        <script type="text/javascript">
            var ajaxurl = "'.$_SERVER['SCRIPT_URI'].'";
            var pn_appointments_calendar = null;
            jQuery(function() {
                pn_appointments_calendar = new PN_CALENDAR();
                pn_appointments_calendar.init();
            });
        </script>';

include_once ABSOLUTE_CALENDAR_PATH.'/classes/calendar.php';
$calendar_attributes = array(
    'min_select_year' => 1981,
    'max_select_year' => 2025
);
//ajax request
if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'pn_get_month_cal') {
    
    $calendar = new PN_Calendar($calendar_attributes);
    echo $calendar->draw(array(), $_REQUEST['year'], $_REQUEST['month']);
    exit;
}
else
{
    $calendar = new PN_Calendar($calendar_attributes);
    echo $calendar->draw();
}
?>

        



