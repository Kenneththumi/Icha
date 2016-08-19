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
                       filemanager_title:"ICHA Kenya Filemanager" ,
                       external_plugins: { "filemanager" : "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/plugin.min.js"}
                    });');

$uploadscript = ("$(document).ready(function(){
                   var button = $('.qq-upload-button');
                   var imgholder = $('#attachdoc');
                   var pathname = $(location).attr('href');
                   var numRand = Math.floor(Math.random()*1001);

                   new AjaxUpload(button, {
                          action: 'plugins/fileUpload/fileupload.php',
                          name: 'userfile',
                          data:{
                                  docsave: 'yes',
                                  saveto: 'pdf',
                                  publicationid: '".$_GET['id']."',
                                  randomkey: numRand
                                  },
                          onSubmit: function(file,ext){
                                  //insert the loading graphic
                                    if (ext && /^(pdf|PDF)$/.test(ext))
                                      {
                                          //insert the loading graphic
                                          imgholder.html('<p><img src=\"styles/df_template/images/ajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
                                      }
                                      else
                                      {
                                          alert('Error: only PDF documents can be uploaded here');
                                          return false;
                                      }

                                  },
                          onComplete: function(file,response){
                                  //show uploaded image
                                  var stripfile = file.replace(/ /g,'');

                                  imgholder.html('<input name=\"filename\" type=\"hidden\" id=\"filename\" value=\"'+numRand+\"_\"+file+'\" /><input name=\"docname\" type=\"hidden\" id=\"docname\" value=\"'+file+'\" /><div id=\"fileholder\">Document Name: '+file+'</div>');
                         }
                      });			
                   });");

if(isset($_GET['id'])&&$_GET['task']!='new'){
   
    $coursedetails = $this->runquery("SELECT * FROM courses WHERE courseid = '".$_GET['id']."'",'single');
    
    //include the batch publications document
    include ABSOLUTE_PATH.'components/admin/courses/batchbrochures.php';
    
    if($doctable==''){
        
        $this->loadplugin('fileUpload/js/ajaxupload','js');
        $this->wrapscript($uploadscript);
    }    
}
else{
    
    $this->loadplugin('fileUpload/js/ajaxupload','js');
    $this->wrapscript($uploadscript);
}

//load the contributors
$querystr = "SELECT contributorid, name FROM contributors ORDER BY contributorid DESC";

$queries = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queries);

if($_GET['task']!='edit'){
    $contributors = array('0'=>'Any Instructor');
}
else {
    $contributor = $this->runquery("SELECT contributorid,name FROM contributors WHERE contributorid='".$coursedetails['contributorid']."'",'single');
    
    if(is_null($contributor['contributorid'])){
    
        $contributors[0] = 'Any Instructor';
    }
    else{
        
        $contributors[$contributor['contributorid']] = $contributor['name'];
    }
}

for($r=1; $r<=$querycount; $r++)
{
    $query = $this->fetcharray($queries);
    
    $contributors[$query['contributorid']] = $query['name'];
}

//load the categories
$catquery = $this->runquery("SELECT * FROM categories ORDER BY categoryid ASC",'multiple','all');
$catcount = $this->getcount($catquery);

if($_GET['task']!='edit'){
    
    $categories = array(''=>'Select Category');
}
else{
    
    $getcat = $this->runquery("SELECT * FROM categories WHERE categoryid='".$coursedetails['categoryid']."'",'single');
    $categories[$getcat['categoryid']] = $getcat['name'];
}

for($r=1; $r<=$catcount; $r++){
        
    $catarray = $this->fetcharray($catquery);
    $categories[$catarray['categoryid']] = $catarray['name'];
}

//get the course list
$courseget = $this->runquery("SELECT * FROM courses WHERE parentid = '0'",'multiple','all');
$coursecount = $this->getcount($courseget);

$courselist['0'] = 'Select Course';
for($q=1; $q<=$coursecount; $q++){
    
    $coursefetch = $this->fetcharray($courseget);    
    $courselist[$coursefetch['courseid']] = $coursefetch['coursename'];
}

$course = new form;
$course->setAttributes(array("includesPath" => PLUGIN_PATH.'/classForm/includes',
                              "width" =>'98%',
                              "map" => array(1,2,1,1,1,2,2,1,1),
                              "action" => '',
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "id" => 'cform'
                              ));

$course->addHidden('savestep', 'saveone');
$course->addHidden('type', 'course');

if($_GET['task']=='edit'){    
    $course->addHidden('courseid', $coursedetails['courseid']);
}

$course->addHTML('<h1>Enter course details below:</h1>');

$course->addTextbox('Course Name', 'coursename', $coursedetails['coursename'],array('class'=>'required'));
$course->addTextbox('Publish Date', 'publishdate', ($coursedetails['publishdate']=='' ? date('d M, Y', time()) : date('d M, Y',$coursedetails['publishdate'])),array('readonly' => true));

$course->addTextarea('Description', 'desc',$coursedetails['description'],array('class'=>'description','style'=>'height: 400px;'));

if((isset($_GET['id'])&&$doctable=='')||!isset($_GET['id'])){
    
    $course->addHTML($doclist.'<div id="attachdoc">'
            . '<div class="qq-upload-button">'
            . '* Attach Course Brochure'
            . '</div>'
            . '</div>');
}
elseif($doctable != ''){
    
    $course->addHTML($doctable);
}

$course->addHTML('<small>Note: skip start and end dates if not applicable</small>');
$course->addTextbox('Start Date ', 'startdate', ($coursedetails['startdate']<='0' ? '' : date('M d, Y',$coursedetails['startdate'])),array('class'=>'date'));
$course->addTextbox('End Date ', 'enddate', ($coursedetails['enddate']<='0' ? '' : date('M d, Y',$coursedetails['enddate'])),array('class'=>'date'));

$course->addSelect('Course Category', 'category', '', $categories,array('class'=>'required'));
$course->addSelect('Authored By', 'author', '', $contributors,array('class'=>'required'));

$course->addYesNo('Enabled', 'enabled', '1',array('class'=>'required'));

$course->addTextarea('Course Application Message <br/><span style="font-size:small; font-weight:bold">This message would appear AFTER any new student has applied for the course</span>', 'pam',$coursedetails['post_application_message'],array('style'=>'height: 150px;'));
$course->addYesNo('Feautured in Upcoming Courses section?', 'featured', ($coursedetails['coursename'] == '' ? 1 : $coursedetails['featured']),array('class'=>'required'));

$course->addButton('Save Course');

$course->render();

$course->renderHead();
//$course->renderJS();

$this->wrapscript('    
    $(function() {
        $( "input[name=\'startdate\']" ).datepicker({dateFormat:"M d, yy"});
        $( "input[name=\'enddate\']" ).datepicker({dateFormat:"M d, yy"});
    });
    
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
        if($date == "Click to Select Date..."){
            return false;
        }
        else{
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
