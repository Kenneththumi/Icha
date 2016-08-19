<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$uploadscript3 = "$(document).ready(
                  function(){
                       var button = $('.upload-button3');
                       var imgholder = $('#profilediv');
                       var pathname = $(location).attr('href');
                       var numRand = Math.floor(Math.random()*1001);

                       new AjaxUpload(button, {
                                              action: 'plugins/fileUpload/fileupload.php',
                                              name: 'userfile',
                                              data: {
                                                        saveto: 'profilepics',
                                                        randomkey: numRand
                                                },
                                              onSubmit: function(file,ext){
                                                      //insert the loading graphic
                                                            if (ext && /^(jpg|JPG|png|jpeg|gif)$/.test(ext)){
                                                                      //insert the loading graphic
                                                                      imgholder.html('<p><img src=\"styles/ichatemplate/admin/images/smallajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
                                                              }
                                                              else
                                                              {
                                                                      imgholder.html('<p><img src=\"styles/ichatemplate/admin/images/delete.png\" width=\"50\"><br/><strong>Error: only images ( .png,  .jpg, .jpeg, .gif) can be uploaded here</strong></p>');
                                                                      return false;
                                                              }

                                                      },
                                              onComplete: function(file,response){
                                                      //show uploaded image
                                                      //alert(response);
                                                      imgholder.html('<div id=\"freshimg\"><img src=\"media/profilepics/'+numRand+'_'+file+'\" width=\"180\"><input type=\"hidden\" name=\"uploadimg\" id=\"uploadimg\" value=\"'+numRand+'_'+file+'\" /></div>');
                                                      jQuery.queryString(window.location.href, '&newimg=yes');
                                                     }
                                      });			
                       });";

$this->loadplugin('fileUpload/js/ajaxupload','js');
$this->wrapscript($uploadscript3);

$this->loadplugin('classForm/class.form');

$studentform = new form();

$studentform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'98%',
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action"=>'?content=com_courses&folder=same&file=confirmregistration',
                                  ));

$studentform->addHidden('cmd', 'save');
$studentform->addHidden('courseid',$courseid);
$studentform->addHidden('student_id', $student['id']);

$course = $this->runquery("SELECT * FROM courses WHERE courseid='".$courseid."'",'single');
    
if($course['post_application_message']!=''){
    
    $studentform->addHTML('<div style="padding:10px; background-color:#f4f4f4; border: 1px solid #ccc;">'
            . $course['post_application_message']
            . '</div>');
}

$img = '<div class="upload-button3">
            <img src="'.DEFAULT_TEMPLATE_PATH.'/images/usericon.jpg" width="180"><br/><strong>Upload a passport size photo</strong>
        </div>';

$studentform->addHTML('<table width="100%" border="0" cellpadding="10">
<tr>
<td rowspan="4" width="20%">
<div id="profilediv">'.$img.'</div>
</td>
    <td>
    <strong>Name:</strong> '.$details['name'].'
    </td>
</tr>
<tr>
    <td>
    <strong>Registration Number:</strong> '.$details['registrationid'].'
    </td>
</tr>
<tr>
    <td>
    <strong>Email Address:</strong> '.$details['emailaddress'].'
    </td>
</tr>
<tr>
    <td>
    <strong>Mobile No:</strong> '.$details['mobile'].'
    </td>
</tr>
</table>');

$studentform->addButton('Finish Course Application');

$studentform->render();
