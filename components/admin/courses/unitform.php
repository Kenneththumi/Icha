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
    $unitdetails = $this->runquery("SELECT * FROM courses WHERE courseid = '".$_GET['id']."'",'single');
    $price = $this->runquery("SELECT * FROM prices WHERE courseid='".$unitdetails['courseid']."'",'single');
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
    $contributor = $this->runquery("SELECT contributorid,name FROM contributors WHERE contributorid='".$unitdetails['contributorid']."'",'single');
    $contributors = array($contributor['contributorid']=>$contributor['name']);
}

for($r=1; $r<=$querycount; $r++)
{
    $query = $this->fetcharray($queries);
    
    $contributors[$query['contributorid']] = $query['name'];
}

//get the default price
$cur = finances::defaultCurrency();

//get the course list
$unitget = $this->runquery("SELECT * FROM courses WHERE parentid = '0'",'multiple','all');
$unitcount = $this->getcount($unitget);

$unitlist[''] = 'Select Parent Course';
for($q=1; $q<=$unitcount; $q++)
{
    $unitfetch = $this->fetcharray($unitget);
    
    $unitlist[$unitfetch['courseid']] = $unitfetch['coursename'];
}

$unit = new form;
$unit->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => array(1,2,1,1,2,2,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action"=>$action,
                              "id"=>'cform'
                              ));

$unit->addHidden('savestep', 'saveone');
$unit->addHidden('cur', $cur['currencyid']);
$unit->addHidden('type', $type);

if($_GET['task']=='edit')
{
    $unit->addHidden('courseid', $unitdetails['courseid']);
}

$unit->addHTML('<h1>Enter course unit details below:</h1>');

$unit->addTextbox('Course Unit Name', 'coursename',$unitdetails['coursename'],array('class'=>'required'));
$unit->addDate('Publish Date', 'publishdate', ($unitdetails['publishdate']=='' ? '' : date('M d, Y',$unitdetails['publishdate'])),array('class'=>'date'));

$unit->addSelect('Parent Course', 'parent', $unitdetails['parentid'], $unitlist,array('class'=>'required'));

$unit->addTextarea('Description', 'desc',$unitdetails['description'],array('class'=>'description'));

$unit->addDate('Start Date', 'startdate', ($unitdetails['startdate']=='' ? '' : date('M d, Y',$unitdetails['startdate'])),array('class'=>'date'));
$unit->addDate('End Date', 'enddate', ($unitdetails['enddate']=='' ? '' : date('M d, Y',$unitdetails['enddate'])),array('class'=>'date'));

//$unit->addSelect('Created By', 'createdby', '', $contributors,array('class'=>'required'));
//$unit->addTextbox('Course Price ('.$cur['currencycode'].')', 'price', $price['price'], array('class'=>'required'));

$unit->addYesNo('Enabled', 'enabled', '1',array('class'=>'required'));

$unit->addButton('Save Course Unit >');

$unit->render();

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
