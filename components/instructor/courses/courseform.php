<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

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

if($_GET['task']=='edit')
{
    $coursedetails = $this->runquery("SELECT * FROM courses WHERE courseid = '".$_GET['id']."'",'single');
    $price = $this->runquery("SELECT * FROM prices WHERE courseid='".$coursedetails['courseid']."'",'single');
}

//load the contributors
$querystr = "SELECT contributorid, name FROM contributors ORDER BY contributorid DESC";

$queries = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queries);

if($_GET['task']!='edit')
{
    $contributors = array(''=>'Select Contributor');
}
else {
    $contributor = $this->runquery("SELECT contributorid,name FROM contributors WHERE contributorid='".$coursedetails['contributorid']."'",'single');
    $contributors = array($contributor['contributorid']=>$contributor['name']);
}

for($r=1; $r<=$querycount; $r++)
{
    $query = $this->fetcharray($queries);
    
    $contributors[$query['contributorid']] = $query['name'];
}

//get the default price
$cur = finances::defaultCurrency();



$course = new form;
$course->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => array(1,2,1,2,1,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action"=>$action,
                              "id"=>'cform'
                              ));

$course->addHidden('savestep', 'saveone');
$course->addHidden('cur', $cur['currencyid']);

if($_GET['task']=='edit')
{
    $course->addHidden('courseid', $coursedetails['courseid']);
}

$course->addHTML('<h1>Enter course details below:</h1>');

$course->addTextbox('Course Name', 'coursename',$coursedetails['coursename'],array('class'=>'required','readonly'=>true));
$course->addDate('Publish Date', 'publishdate', ($coursedetails['publishdate']=='' ? '' : date('M d, Y',$coursedetails['publishdate'])),array('class'=>'date'));

$course->addTextarea('Description', 'desc',$coursedetails['description'],array('class'=>'description'));

$course->addDate('Start Date', 'startdate', ($coursedetails['startdate']=='' ? '' : date('M d, Y',$coursedetails['startdate'])),array('class'=>'date'));
$course->addDate('End Date', 'enddate', ($coursedetails['enddate']=='' ? '' : date('M d, Y',$coursedetails['enddate'])),array('class'=>'date'));

$course->addSelect('Created By', 'createdby', '', $contributors,array('class'=>'required'));
//$course->addTextbox('Course Price ('.$cur['currencycode'].')', 'price', $price['price'], array('class'=>'required'));

$course->addYesNo('Enabled', 'enabled', '1',array('class'=>'required'));

$course->addButton('Save Details >');

$course->render();

$this->wrapscript('
    function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if( !emailReg.test( $email ) ) {
        return false;
      } else {
        return true;
      }
    }  
    
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
     $("#cform").submit(function(){
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
?>
