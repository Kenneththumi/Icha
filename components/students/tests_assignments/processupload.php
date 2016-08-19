<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if(isset($_POST['cmd'])){
    $tid = $_POST['id'];
}
else{
    $tid = $_GET['id'];
}
        $tid;
        $studentid = $_SESSION['sourceid'];
//check if the assignment has already been uploaded
$chk = $this->runquery("SELECT * FROM student_tests_assignments WHERE tests_assignments_id = '$tid' AND students_id = '$studentid' ",'single');

//get the test_assignment date
$doc = $this->runquery("SELECT * FROM tests_assignments WHERE tests_assignments_id = '$tid'",'single');

if(time() < $doc['duedate']){
    
    if($chk['uploaddate']>'0'){
       $this->inlinemessage('You already uploaded your document on this assignment, uploading a new one will erase the previous submission','valid');
    }
    elseif(!isset($chk['downloaddate'])){
        $this->inlinemessage('Please download and complete the assignment before trying to upload anything','error');
        exit();
    }
    
    //get the course and student folder details
    $student = $this->runquery("SELECT * FROM students ".
            "INNER JOIN student_tests_assignments ON student_tests_assignments.students_id = students.students_id ".
            "WHERE student_tests_assignments.tests_assignments_id = '$tid' AND student_tests_assignments.students_id = '$studentid' ",'single');
			
    $course = $this->runquery("SELECT contributors.foldername AS foldername FROM contributors ".
            "INNER JOIN courses ON courses.contributorid = contributors.contributorid ".
            "WHERE courses.courseid = '".$doc['courseid']."'",'single');
	
         
    if(isset($_POST['cmd'])){
       
        //save the document
        $docsave = array(
            'filename' => $_POST['docname'],
            'uploaddate' => time(),
            'docname' => $_POST['docname'],
            'documenttype' => 'submitted_doc'
        );

        //insert into docs
        $this->dbinsert("documents",$docsave);
        $docid = mysql_insert_id();

        $stud_doc = array(
            'students_id' => $studentid,
            'documents_id' => $docid
        );

        //insert into student docs
        $this->dbinsert('students_documents',$stud_doc);
        $studentdoc = mysql_insert_id();

        $saveupload = array(
                            'uploaddate' => time(),
                            'students_documents_id' => $studentdoc
                            );
        $this->dbupdate('student_tests_assignments',$saveupload,"tests_assignments_id = '$tid' AND student_tests_assignments.students_id = '$studentid' ");

        $this->inlinemessage('The document has been uploaded','valid');
        exit;
    }

    $this->loadscripts();
    $this->loadplugin('classForm/class.form');

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
                                          saveto: 'training/submissions',
                                          randomkey: numRand
                                          },
                                  onSubmit: function(file,ext){
                                          //insert the loading graphic
                                            if (ext && /^(pdf|PDF)$/.test(ext)){

                                                  //insert the loading graphic
                                                  imgholder.html('<p><img src=\"styles/df_template/images/ajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
                                              }
                                              else{
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

    $this->loadplugin('fileUpload/js/ajaxupload','js');
    $this->wrapscript($uploadscript);

    $uploadform = new form();

    $uploadform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'98%',
                                      "map"=>$map,
                                      "preventJQueryLoad" => true,
                                      "preventJQueryUILoad" => true,
                                      "action"=>'',
                                      "id"=>'publishform'
                                      ));

    $uploadform->addHidden('cmd','save');
    $uploadform->addHidden('id',$tid);

    $uploadform->addHTML('<h1>Upload "'.$doc['name'].'" Document</h1>');
    $uploadform->addHTML('<p style="font-size:12px; color:#900; float:right;">* - indicates required field</p>');

    $uploadform->addHTML($doclist.'<div id="attachdoc"><div class="qq-upload-button">* Attach Document</div></div>');

    $uploadform->addButton('Save Test/Assignemnt Upload', 'submit', array('id'=>'saveButton'));

    $uploadform->render();

    $this->wrapscript('
        $(document).ready(function(){
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
}
else
{
    $this->inlinemessage("The due date for submission has already expired",'error');
}
