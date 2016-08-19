<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<link rel="stylesheet" type="text/css" href="'.PLUGIN_PATH.'/select2/css/select2.min.css">';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/select2/js/select2.min.js"></script>';

$this->wrapscript("$(document).ready(function(){"
                        . "$(\"select[name='category']\").select2({"
                                . "theme: 'classic',"
                                . "tags: true"
                                . "});"
                    . "});");

$link = new navigation;

//the edit command
if($_GET['task']=='edit'){
    
    if(isset($_GET['filedelete'])){
        
        $this->deleterow('imgmanager','articleid',$_GET['id']);
    }
    
    $member = $this->runquery("SELECT * FROM ourteam WHERE teamid='".$_GET['id']."'",'single');
    $names = $member['name'];
    
    $img = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$_GET['id']."'",'single');
}

//the save command
if(isset($_POST['cmdsave']))
{
    $save = [
        'name' => $_POST['fullname'],
        'emailaddress' => $_POST['email'],
        'contactinfo' => mysql_real_escape_string($_POST['desc']),
        'category' => $_POST['category'],
	'designation' => $_POST['desig'],
	'listorder' => ($_POST['lorder']=='' ? 0 : $_POST['lorder']),
        'enabled' => 'Yes',
        'registrationdate' => time()
    ];
    
    if(!isset($_POST['editid'])){
        
        if(!$this->dbinsert('ourteam',$save)){
            $this->print_last_error();
        }
        
        $cid = mysql_insert_id();
        
        if(!is_null($_POST['uploadimg'])){
            
            $pic = array(
                'imgname' => $_POST['uploadimg'],
                'filename' => $_POST['uploadimg'],
                'imgcategory' => 'profilepic',
                'articleid' => $cid
            );

            $this->dbinsert('imgmanager',$pic);
        }
    }
    else{
        
        $this->dbupdate('ourteam',$save,"teamid='".$_POST['editid']."'");
        $cid = $_POST['editid'];
        
        if(!is_null($_POST['uploadimg'])){
            
            $pic = array(
                'imgname' => $_POST['uploadimg'],
                'filename' => $_POST['uploadimg'],
                'imgcategory' => 'profilepic',
                'articleid' => $cid
            );

            $imgcount = $this->runquery("SELECT count(*) FROM imgmanager WHERE articleid='$cid'",'single');

            if($imgcount['count(*)']>='1'){
                $this->dbupdate('imgmanager',$pic,"articleid='$cid'");
            }
            else{
                $this->dbinsert('imgmanager',$pic);
            }
        }
    }
    
    if(!isset($_POST['editid'])){
        redirect($link->urlreturn('Team Members', '&msgvalid=The_team_member_has_been_added'));
    }
    else{        
        redirect($link->urlreturn('Team Members', '&msgvalid=The_team_member_has_been_edited'));
    }
}

//get the research categories
$membercats = $this->runquery("SELECT DISTINCT category FROM ourteam","multiple","all");
$membercount = $this->getcount($membercats);

if($membercount >= 1){
    
    for($u=1; $u<=$membercount; $u++){
        
        $eachmember = $this->fetcharray($membercats);
        
        if($eachmember['category'] !='')
            $catlist[] = $eachmember['category'];
    }
}

echo '<h1 class="pagetitle">Add Team Member</h1>';

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

//echo '<link href="'.SITE_PATH.'plugins/advUpload/fileuploader.css" rel="stylesheet" type="text/css">';

$this->wrapscript("$(document).ready(
                      function(){
                           var button = $('.upload-button');
                           var imgholder = $('#profilediv');
                           var pathname = $(location).attr('href');
                           var numRand = Math.floor(Math.random()*1001);

                           new AjaxUpload(button, {
                                                  action: 'plugins/fileUpload/fileupload2.php',
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
                                                          alert(response);
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
                              "preventJQueryUILoad" => true,
                              "action"=>''
                              ));


$addform->addHidden('cmdsave', 'cmdsave');

if($_GET['task']=='edit'){
    
    $addform->addHidden('editid', $_GET['id']);
}

//proflie image check
if($img['filename']==''){
    
    $img = '<div class="upload-button">
                <img src="'.DEFAULT_TEMPLATE_PATH.'/images/usericon.jpg" width="180">
                Upload an image here
            </div>';
}
 else {
    $img = '<div class="profilepic">
                    <img src="'.MEDIA_PATH.'profilepics/'.$img['filename'].'" width="180">
                    <a href="'.$link->geturl().'&task=edit&filedelete='.$img['filename'].'"><img src="'.MEDIA_PATH.'images/delete.png" width="28" height="28"></a>
                    <input type="hidden" name="uploadimg" value="'.$img['filename'].'" class="pfbc-textbox" />
            </div>';
}

$addform->addHTML('<table width="100%" border="0" cellpadding="10">
  <tr>
    <td rowspan="3" width="20%">
    <div id="profilediv">'.$img.'</div>
    </td>
    <td colspan="2" valign="bottom"><label for="fname">Full Names</label><br/>
      <input type="text" name="fullname" value="'.$names.'" id="fullname" class="pfbc-textbox" /></td>
  </tr>
  <tr>
    <td valign="top"><label for="fname3">Email Address</label>
      <br/>
    <input type="text" name="email" value="'.$member['emailaddress'].'" id="fullname" class="pfbc-textbox" />
        </td>
  </tr>
</table>');

$addform->addTextbox('Designation','desig',$member['designation']);
$addform->addSelect('* Category', 'category', $member['category'], $catlist,['style'=>'width: 100%']);
$addform->addTextbox('Order','lorder',$member['listorder']);

$addform->addTextarea('Description', 'desc',$member['contactinfo'],array( "style" => "height: 300px; width: 100%;"));

$addform->addButton('Save Team Member', 'submit');

$addform->render();

echo '</div>';

