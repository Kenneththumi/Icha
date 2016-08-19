<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$student = new user;

//the password strength script
$this->loadscripts('passwordstrength','yes');

$this->wrapscript("$(document).ready(function(){
                        $('.passcheck').passStrengthify();
                     });");

$student_details = $student->returnUserSourceDetails($_SESSION['sourceid'],'student');
$login_details = $student->returnUserSourceDetails($_SESSION['userid'], 'login');

//save function
if(isset($_POST['cmdsave']))
{
    $save = array('filename' => $_POST['uploadimg']);    
    $this->dbupdate('students',$save,"students_id='".$_POST['sourceid']."'");
    
    $lsave = array('password' => $_POST['password']);
    $this->dbupdate('users',$lsave,"userid='".$_POST['loginid']."'");
    
    $this->inlinemessage('The details have been saved','valid');
}

echo '<h1 class="pagetitle">My Profile</h1>';

echo '<div id="contentholder">';

$this->loadplugin('classForm/class.form');
$this->loadplugin('fileUpload/js/ajaxupload','js');

echo '<link href="'.SITE_PATH.'/plugins/advUpload/fileuploader.css" rel="stylesheet" type="text/css">	';

$this->wrapscript("$(document).ready(
					  function(){
							   var button = $('.upload-button');
							   var imgholder = $('#profilediv');
							   var pathname = $(location).attr('href');
							   var numRand = Math.floor(Math.random()*1001);
							   
							   new AjaxUpload(button, {
                                                                                  action: 'plugins/fileUpload/fileupload.php',
                                                                                  name: 'userfile',
                                                                                  data: {
                                                                                            saveto: 'profilepics'
},
                                                                                  onSubmit: function(file,ext){
                                                                                          //insert the loading graphic
                                                                                                if (ext && /^(jpg|JPG|png|jpeg|gif)$/.test(ext))
                                                                                                  {
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
                                                                                          imgholder.html('<div id=\"freshimg\"><img src=\"media/profilepics/'+file+'\" width=\"180\"><input type=\"hidden\" name=\"uploadimg\" id=\"uploadimg\" value=\"'+file+'\" /></div>');
                                                                                          jQuery.queryString(window.location.href, '&newimg=yes');
											 }
												  });			
							   });");

$addform = new form();

$addform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'95%',
                              "map" => array(1,1,2,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => false,
                              "action"=>''
                              ));


$addform->addHidden('cmdsave', 'cmdsave');

$addform->addHidden('sourceid', $student_details['students_id']);
$addform->addHidden('loginid', $login_details['userid']);

//proflie image check
if($student_details['filename']=='')
{
    $img = '<div class="upload-button">
            <img src="'.DEFAULT_TEMPLATE_PATH.'/images/usericon.jpg" width="180">
            Upload an image here
        </div>';
}
 else {
    $img = '<div class="profilepic">
                <img src="'.MEDIA_PATH.'profilepics/'.$student_details['filename'].'" width="180">
                <a href="'.$link->geturl().'&task=edit&filedelete='.$student_details['filename'].'"><img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28"></a>
            </div>';
}

$addform->addHTML('<table width="100%" cellpadding="10" border="0">
  <tbody><tr>
    <td width="20%" rowspan="6"><div id="profilediv">'.$img.'</div></td>
    <td valign="bottom"><strong>Full Name: </strong>'.$student_details['name'].'
      </td>
  </tr>
  <tr>
    <td><strong>Email Address: </strong>'.$student_details['emailaddress'].'</td>
    </tr>
  <tr>
    <td><strong>Telephone: </strong>'.$student_details['mobile'].'</td>
  </tr>
  <tr>
    <td><strong>Date of Birth: </strong>'.date('d-F-Y',$student_details['dateofbirth']).'</td>
  </tr>
  <tr>
    <td><strong>Description: </strong>'.$student_details['description'].'</td>
  </tr>
  </tbody>
</table>');

$addform->addHTML('Change the password below:');
$addform->addPassword('Password', 'password',$login_details['password'],array('class'=>'passcheck'));
$addform->addPassword('Verify Password', 'vpassword',$login_details['password']);

$addform->addButton('Save Login Details', 'submit');

$addform->render();

echo '</div>';
?>
