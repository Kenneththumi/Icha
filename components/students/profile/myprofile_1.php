<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
$student = new user;

$student_details = $student->returnUserSourceDetails($_SESSION['sourceid'],'student');
$login_details = $student->returnUserSourceDetails($_SESSION['userid'], 'login');

//save function
if(isset($_POST['cmdsave']))
{
    $save = array(
        'name' => $_POST['fname'],
        'dateofbirth' => strtotime($_POST[$this->strClean('Day')].'-'.$_POST[$this->strClean('Month')].'-'.$_POST[$this->strClean('Year')]),
        'emailaddress' => $_POST['email'],
        'mobile' => $_POST['number'],
        'filename' => $_POST['uploadimg'],
        'description' => $_POST['desc']
    );
    
    $this->dbupdate('students',$save,"students_id='".$_POST['editid']."'");
    //$this->print_last_error();
    
    $this->inlinemessage('The details have been saved','valid');
}

$dob = explode('-',date('d-F-Y',$student_details['dateofbirth']));

echo '<h1 class="pagetitle">My Profile</h1>';

echo '<div id="contentholder">';

$this->loadplugin('classForm/class.form');
$this->loadplugin('fileUpload/js/ajaxupload','js');
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
                              "width"=>'100%',
                              "map" => array(1,3,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => false,
                              "action"=>''
                              ));


$addform->addHidden('cmdsave', 'cmdsave');

$addform->addHidden('editid', $student_details['students_id']);

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

$addform->addHTML('<table width="100%" border="0" cellpadding="5">
  <tr>
    <td rowspan="3" width="20%">
    <div id="profilediv">'.$img.'</div>
    </td>
    <td colspan="2" valign="bottom"><label for="fname">Full Name</label><br/>
      <input type="text" name="fname" value="'.$student_details['name'].'" id="fname" class="pfbc-textbox" />
      <p></p>
    <p></p></td>
  </tr>
  <tr>
    <td><label for="fname4">Email Address</label>
      <br/>
    <input type="text" name="email" id="email" value="'.$student_details['emailaddress'].'" class="pfbc-textbox" /></td>
    <td><label for="fname3">Telephone</label>
      <br/>
    <input type="text" name="number" id="number" value="'.$student_details['mobile'].'" class="pfbc-textbox" /></td>
  </tr>
</table>');

$addform->addSelect('Day',$this->strClean('Day'),$dob[0],array('Select Day','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),array('required'=>false,'readonly'=>true));
$addform->addSelect('Month',$this->strClean('Month'),$dob[1],array('Select Month','January','February','March','April','May','June','July','August','September','October','November','December'),array('required'=>false));
$addform->addSelect('Year',$this->strClean('Year'),$dob[2],array('Select Year','2013','2012','2011','2010','2009','2008','2007','2006','2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994','1993','1992','1991','1990','1989','1988','1987','1986','1985','1984','1983','1982','1981','1980','1979','1978','1977','1976','1975','1974','1973','1972','1971','1970','1969','1968','1967','1966','1965','1964','1963','1962','1961','1960','1959','1958','1957','1956','1955'),array('required'=>false));

$addform->addTextarea('Description', 'desc',$student_details['description'],array( "style" => "height: 200px; width: 100%;"));

$addform->addButton('Save Profile', 'submit');

$addform->render();

echo '</div>';
?>
