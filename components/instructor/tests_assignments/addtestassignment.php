<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if($_GET['task']=='del')
{
    $docid = $_GET['docid'];
    $docGet = $this->runquery("SELECT * FROM documents WHERE docid='".$docid."'",'single');
    
    //delete the documents
    @unlink(ABSOLUTE_MEDIA_PATH.$_SESSION['subfolder'].'/tests_and_assignments/'.$docGet['filename']);

    //delete the document record
    $this->deleterow('documents','docid',$docid);
    
    //delete the document_id entry in the test_assignments table
    $test_id = $_GET['id'];
    $this->dbupdate('tests_assignments',array('document_id'=>'0'),"tests_assignments_id='$test_id'");
    
    redirect($link->urlreplace('&task=del','','yes').'&task=edit&msgvalid='.$docGet['docname'].'_has_been_deleted.','no');	
}

//the edit code
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $ta = $this->runquery("SELECT * FROM tests_assignments WHERE tests_assignments_id = '$id'",'single');
    //var_dump($ta);
    
    $docid = $ta['document_id'];
    $doc = $this->runquery("SELECT * FROM documents WHERE docid = '$docid'",'single');
    
    if((file_exists(ABSOLUTE_MEDIA_PATH.$_SESSION['subfolder'].'tests_and_assignments/'.$doc['filename']))&&($docid!='0'))
    {
        $doctable = '<table width="100%" border="0" cellpadding="10" class="tablelist">
      <tr>
        <td colspan="3" class="tabletitle"><strong>Attached Documents</strong></td>
      </tr>';

        $doctable .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
        <td>
        <a href="'.MEDIA_PATH.$_SESSION['subfolder'].'tests_and_assignments/'.$doc['filename'].'" target="_blank">
        <strong>'.$doc['filename'].'</strong>
        </a>
        </td>
        <td><input type="hidden" name="docname" value="'.$doc['filename'].'" />
        <a href="'.$link->urlreplace('task=edit','task=del').'&task=del&docid='.$docid.'"><img src="'.SITE_PATH.'components/instructor/images/trash.png" alt="Delete document" /></a></td>
        </tr>';

        $doctable .= '</table>';
    }
}

//get the courses
$courses = $this->runquery('SELECT * FROM courses WHERE contributorid=\''.$_SESSION['sourceid'].'\' AND parentid=\'0\' ORDER BY courseid ASC','multiple','all');
$coursecount = $this->getcount($courses);

$courselist[''] = 'Select Linked Course';
for($r=1; $r<=$coursecount; $r++)
{
    $course = $this->fetcharray($courses);
    $courselist[$course['courseid']] = $course['coursename'];
    
    //get children
    $children = $this->runquery("SELECT * FROM courses WHERE parentid='".$course['courseid']."'");
    $childrencount = $this->getcount($children);
    
    if($childrencount >= '1')
    {
        for($e=1; $e<=$childrencount; $e++)
        {
            $child = $this->fetcharray($children);

            $courselist[$child['courseid']] = '----- '.$child['coursename'];
        }
    }
}

//get the students
$students = $this->runquery("SELECT ".
    "DISTINCT registrationdate,name,emailaddress,mobile ".
    "FROM students ".
    "INNER JOIN student_courses ON student_courses.students_id = students.students_id ".
    "LEFT JOIN courses ON student_courses.courseid = courses.courseid ".
    "WHERE contributorid = '".$_SESSION['sourceid']."' AND students.approved = 'yes' ".
    "ORDER BY student_courses.students_id ASC",'multiple','all');

$studentcount = $this->getcount($students);

for($q=1; $q<=$studentcount; $q++)
{
    $student = $this->fetcharray($students);
    $studentoptions[$student['emailaddress']] = $student['name'];
    $studentvalue[] = $student['emailaddress'];
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
                       toolbar2: "| responsivefilemanager | link unlink anchor | forecolor backcolor  | print preview code ",
                       image_advtab: true ,

                       external_filemanager_path: "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/",
                       filemanager_title:"'.SITENAME.' Filemanager" ,
                       external_plugins: { "filemanager" : "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/plugin.min.js"}
                    });');


$uploadscript = ("$(document).ready(function(){
                                               var button = $('.qq-upload-button');
                                               var imgholder = $('#attachdoc');
                                               var pathname = $(location).attr('href');
                                               var numRand = Math.floor(Math.random()*1001);

                                               new AjaxUpload(button, {
                                                                              action: 'plugins/fileUpload/test_assignment_upload.php',
                                                                              name: 'userfile',
                                                                              data:{
                                                                                      docsave: 'yes',
                                                                                      saveto: 'training/tests_and_assignments',
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
                                                                              //alert(response);
                                                                                      //show uploaded image
                                                                                      var stripfile = file.replace(/ /g,'');

                                                                                      imgholder.html('<input name=\"docname\" type=\"hidden\" id=\"docname\" value=\"'+numRand+'_'+file+'\" /><div id=\"fileholder\">Document Name: '+file+'</div>');
                                                                             }
                                                                                      });			
							   });");

if(isset($_GET['id']))
{
    if($doctable=='')
    {
        $this->loadplugin('fileUpload/js/ajaxupload','js');
        $this->wrapscript($uploadscript);
    }
    
}
else
{
    $this->loadplugin('fileUpload/js/ajaxupload','js');
    $this->wrapscript($uploadscript);
}

$this->wrapscript("$(document).ready(function(){
                                                   $('.newjob').change(function(){
                                                        var val = this.value;
                                                        
                                                        if(val=='Yes')
                                                        {
                                                            $('#saveButton').val('Add New Work');
                                                            $('#publishform').attr('action','".$link->geturl()."&task=new');
                                                        }
                                                    });
    });");

echo '<h1 class="pagetitle">Add Test / Assignment</h1>';

//sorting the job abstract bug - attaching random number to the field
$rand = rand(1, 1000);

$abstract =  $ta['body'];

$taform = new form();

$taform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width" => '98%',
                              "map" => array(1,2,2,1,1,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action" => $link->urlreplace('addtestassignment', 'savetestassignment')
                              ));

$taform->addHidden('cmd','save');
$taform->addHidden('url', $link->geturl());

if(isset($_GET['id']))
{
    $taform->addHidden('id', $_GET['id']);
}

$taform->addHTML('<p style="font-size:12px; color:#900; float:right; padding:0px; margin-bottom: 0px;">* - indicates required field</p>');

$taform->addTextbox("* Test / Assignment Title:",'tatitle',$ta['name'],array('class'=>'required'));
$taform->addSelect('Linked Course or Unit <span style="font-size: 11px; color: red;">(indicated by ----)</span>', 'courseid', $ta['courseid'], $courselist,array('required'=>true));

$taform->addSelect('* Type', 'tatype', $ta['tatype'], array(''=>'Select Type','Assignment'=>'Assignment','Test'=>'Test'));
$taform->addTextbox('* Due Date', 'duedate', ($ta['duedate']=='' ? '' : date('F d, Y', $ta['duedate'])),array('class'=>'required date'));

$taform->addTextarea('* Description','description',$ta['description']);

if($doctable=='')
{
    $taform->addHTML($doclist.'<div id="attachdoc"><div class="qq-upload-button">* Attach Test / Assignment Document</div></div>');
}
else {
    
    $taform->addHTML($doctable);
}

$taform->addCheckbox('Select the students to send the assignment to', 'student_select', $studentvalue, $studentoptions,array('required'=>true));

$taform->addButton('Save Work', 'submit', array('id'=>'saveButton'));

$taform->render();

$this->wrapscript('
    $(document).ready(function(){
    
    $(function() {
        $( "input[name=\'duedate\']" ).datepicker({dateFormat:"M d, yy"});
    });

     $("#publishform").submit(function(){
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

    $(".redline").first().focus();

    return isFormValid;
});
});');
?>