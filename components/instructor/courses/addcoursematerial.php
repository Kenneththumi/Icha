<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 class="pagetitle">Add Course Materials</h1>';
//var_dump(dirname( __FILE__ ));

$link = new navigation();

//get the instructor's courses
$coursequery = $this->runquery("SELECT * FROM courses WHERE  contributorid='".$_SESSION['sourceid']."' ORDER BY courseid ASC",'multiple','all');
$coursecount = $this->getcount($coursequery);

$courselist = array(''=>'Select Course');
for($r=1; $r<=$coursecount; $r++)
{
    $course = $this->fetcharray($coursequery);
    $courselist[$course['courseid']] = $course['coursename'];
}

$this->loadcss(array('plugins','dropzone','css','dropzone'));
$this->loadplugin('dropzone/dropzone.min','js');

//url: "'.SITE_PATH.'plugins/dropzone/php/uploadhandler.php"
$this->wrapscript('$(document).ready(function(){
    var myDropzone = new Dropzone("div#my-awesome-dropzone", {  
        url: "?content=plg_dropzone&folder=php&file=uploadhandler&alert=yes",
        addRemoveLinks : true,
        acceptedFiles : "application/pdf",
        maxFilesize: 5, // MB
        maxFiles: 5
     });
});');

$this->loadplugin('classForm/class.form');

$material = new form;
$material->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'100%',
                              "map" => array(1,1,1,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "enctype" => 'multipart/form-data'
                              ));

$material->addHTML('<p></p>');
$material->addSelect('Linked Course','course',$getunit['courseid'],$courselist,array('required'=>true));

$material->addHTML('<div class="dropzone" id="my-awesome-dropzone"></div>');

$material->addHTML('<p style="font-size: 12px; margin-top: -45px; margin-right: 10px; margin-bottom: 5px; padding: 0px; text-align: right">* Note - You can select multiple files at once, just click to add a new file</p>');
$material->addButton('Save Course Materials', 'submit');
$material->render();
?>
