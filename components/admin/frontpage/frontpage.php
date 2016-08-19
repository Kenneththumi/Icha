<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$admin = new user();

$admin_details = $admin->returnUserSourceDetails($_SESSION['sourceid'],'backend');
$login_details = $admin->returnUserSourceDetails($_SESSION['userid'], 'login');

//load the cover photo
echo '<div id="coverphoto">';

//remember to add the photo upload functionality
echo '<div id="instructorpic">';
echo '<img src="'.STYLES_PATH.$this->set_template.'/instructor/images/instructorpic.png" />';
echo '</div>';

echo '<div id="instructordetails">';
echo '<h1>ICHA Administrator</h1>';
echo '<p><strong>Email Address:</strong> '.$admin_details['email'].'</p>';
echo '<p><strong>Last Login:</strong> '.date('l, d M y',$login_details['lastlogin']).'</p>';
echo '</div>';
echo '</div>';

//the required charts files
$this->loadplugin('charts/highcharts','js');
$this->loadplugin('charts/modules/exporting','js');

$this->wrapscript("$(function () {
        $('#graph2').highcharts({
            title: {
                text: '',
                x: 0 //center
            },
            subtitle: {
                text: 'International Center for Humanitarian Affairs Activity Logs',
                x: 0
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Page Visits and Sales'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '°C'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            series: [{
                name: 'Course Registrations',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'Publication Downloads',
                data: [0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }, {
                name: 'Event Registration',
                data: [0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
            }, {
                name: 'Website Visits',
                data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
            }]
        });
    });");

echo '<div class="full_page">
    <div class="headings">
        <h2>Monthly System <strong>Activity Numbers</strong></h2>
        </div>
    <div id="graph2" class="full_page" style="width:100%; height:350px;"></div></div>';

?>