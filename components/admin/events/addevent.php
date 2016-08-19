<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    
    $event = $this->runquery("SELECT * FROM events WHERE eventid='$id'",'single');
}

$this->loadplugin('classForm/class.form');
$this->loadplugin('tinymce/tinymce.min','js');

$this->wrapscript('tinymce.init({
                        selector: "textarea",
                        plugins: [
                                "advlist autolink autosave link image code lists charmap print preview hr anchor pagebreak spellchecker",
                                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor filemanager"
                        ],
                        convert_urls: false,
                       toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                       toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                       image_advtab: true ,

                       external_filemanager_path: "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/",
                       filemanager_title:"'.SITENAME.' Filemanager" ,
                       external_plugins: { "filemanager" : "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/plugin.min.js"}
                    });');

echo '<h1 class="pagetitle">Event Management</h1>';

if(isset($_POST['eventsave'])){
    
    $saveevent = array(
                        'title'=>$_POST['title'],
                        'description'=> $_POST['desc'],
                        'startdate' => strtotime($_POST['sdate']),
                        'enddate' => strtotime($_POST['edate']),
                        'sourceid' => ($_POST['lcourse']=='' ? '0' : $_POST['lcourse']),
                        'source' => ($_POST['lcourse']=='' ? 'None' : 'course'),
                        'venue' => $_POST['venue'],
                        'focusarea' => $_POST['farea']
                  );
                  
    if(!isset($_POST['eventid'])){
        
        $this->dbinsert('events',$saveevent);
        redirect($link->urlreturn('events').'&msgvalid=The_event_has_been_added');
    }
    else{
        
        $id = $_POST['eventid'];
        
        $this->dbupdate('events',$saveevent,"eventid='$id'");
        redirect($link->urlreturn('events').'&msgvalid=The_event_has_been_edited');
    }  
}

//get the linked courses
$courses = $this->runquery("SELECT * FROM courses ORDER BY coursename ASC",'multiple','all');
$coursecount = $this->getcount($courses);

$courselist = array(''=>'Select Course');
for($r=1; $r<=$coursecount; $r++)
{
    $course = $this->fetcharray($courses);
    
    $courselist[$course['courseid']] = $course['coursename'];
}

//get focus areas
$areas = $this->runquery("SELECT * FROM categories",'multiple','all');
$areacount = $this->getcount($areas);

$arealist[''] = 'Select Focus Area';
for($e=1; $e<=$areacount; $e++)
{
    $area = $this->fetcharray($areas);
    $arealist[$area['name']] = $area['name'];
}

$eventform = new form;
$eventform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => array(1,1,2,1,3),
                              "action"=>'',
                              "id"=>'eform'
                              ));

$eventform->addHidden('eventsave', 'eventsave');

if(isset($_GET['id']))
{
    $eventform->addHidden('eventid', $_GET['id']);
}
    
$eventform->addHTML('<h1>Enter event details below:</h1>');

$eventform->addTextbox('Event Title', 'title',$event['title'],array('required'=>true));

$eventform->addDate('Start Date', 'sdate',($event['startdate']!=0 ? date('F d, Y',$event['startdate']) : 'Click to select Start Date'),array('class'=>'date required'));
$eventform->addDate('End Date', 'edate',($event['enddate']!=0 ? date('F d, Y',$event['enddate']) : 'Click to select End Date'),array('class'=>'date required'));

$eventform->addTextarea('Description', 'desc',$event['description'],array('required'=>true));

$eventform->addSelect('Linked Course', 'lcourse', $event['sourceid'], $courselist);
$eventform->addTextbox('Venue', 'venue',($event['venue']==NULL ? 'None' : $event['venue']),array('required'=>true));
$eventform->addSelect('Focus Area', 'farea', $event['focusarea'], $arealist);

$eventform->addButton('Save Event', 'submit');

$eventform->renderHead();
$eventform->render();

$this->wrapscript('
    
    $(function() {
        $( "input[name=\'sdate\']" ).datepicker();
        $( "input[name=\'edate\']" ).datepicker();
    });  
    
    function checkDate($date)
    {
        if($date == "Click to Select Date...")
        {
            return false;
        }
        else
        {
            return true
        }
    }

    $(document).ready(function(){
     $("#eform").submit(function(){
        var isFormValid = true;

    $(".required").each(function(){
        if ($.trim($(this).val()).length == 0){
            $(this).addClass("redline");
            isFormValid = false;
        }
        else{
            $(this).removeClass("redline");
        }
    });
    
    $(".date").each(function(){        
        var sentdate = $(this).val();
        if( !checkDate(sentdate)) { 
            $(this).addClass("pinkline");
        }
        else
        {
            $(this).removeClass("pinkline");
        }
    });

    $(".redline").first().focus();

    return isFormValid;
});
});');
