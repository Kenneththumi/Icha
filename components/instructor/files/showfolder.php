<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$tid = $_GET['id'];
    
//if($_GET['show']=='submissions'||$_GET['show']=='markedpaper')
//{
//    $submission = $this->runquery("SELECT ".
//        //the variables
//        "students.foldername AS folder ".
//
//        //main table
//        "FROM student_tests_assignments".
//
//        //students join
//        " INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".
//
//        "WHERE student_tests_assignments_id='".$tid."' ",'single');
//
//    if ($_GET['show']=='submissions') 
//    {
//        $show = 'students/'.$submission['folder'].'/tests_and_assignments'; 
//    }
//    elseif($_GET['show']=='markedpaper') 
//    {
//        $show = 'students/'.$submission['folder'].'/marked_papers'; 
//    }
//    
//    $type = 0;
//}
//elseif($_GET['show']=='courses')
//{
//    $course = $this->runquery("SELECT coursename FROM courses WHERE courseid='$tid'",'single');
//    
//    $show = 'courses/'.$course['coursename'];
//    $type = 2;
//}
//elseif($_GET['show']=='tests_assignments')
//{
//    $show = 'tests_and_assignments';
//    $type = 0;
//}

//echo '<iframe width="880" height="380" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type='.$type.'&lang=eng&fldr='.$show.'"> </iframe>';

$folder=$this->runquery("SELECT * FROM courses WHERE courseid='$tid'",'single');

$this->loadscripts();
$this->loadcss(array('ichatemplate','basestyles_css'));

$uploadscript = ("$(document).ready(function(){
    
                   var button = $('.qq-upload-button');
                   var imgholder = $('#attachdoc');
                   var responsediv = $('div.response');
                   
                   var pathname = $(location).attr('href');
                   var numRand = Math.floor(Math.random()*1001);

                   new AjaxUpload(button, {
                          action: 'plugins/fileUpload/coursedocupload.php',
                          name: 'file',
                          data:{
                              docsave: 'yes',
                              saveto: 'media/training/courses/".$folder['coursename']."',
                               randomkey: numRand,
                              courseid:  '".$_GET['id']."'
                              
                              },
                          onSubmit: function(file,ext){
                                //insert the loading graphic
                                if (ext && /^(pdf|PDF|doc|DOC|docx|DOCX|png|PNG|jpg|JPG|jpeg|JPEG|gif|GIF)$/.test(ext))
                                  {
                                      //insert the loading graphic
                                      responsediv.addClass('success message');
                                      responsediv.css({'border-radius':'4px','padding':'10px','margin-bottom':'10px', 'border': '1px solid #ccc'});
                                      responsediv.html('<p><img src=\"styles/df_template/images/ajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
                                  }
                                  else
                                  {
                                      alert('Error: only PDF, Word documents and images(png or jpg) can be uploaded here');
                                      return false;
                                  }
                              },
                          onComplete: function(file,response){
                          //alert(response);
                              //remove error message if present
                              var errormsg = $('.error');                                  
                              if(errormsg.length){
                                errormsg.remove();
                              }
                              imgholder.html('<input name=\"docname\" type=\"hidden\" id=\"docname\" value=\"'+numRand+'_'+file+'\" /><div id=\"fileholder\">Document Name: '+file+'</div>');
                              responsediv.html('<strong>Upload Complete</strong>: '+file);
                              
                              if(responsediv.length){                                  
                                responsediv.after('<div class=\"doc\" style=\"border-radius: 4px; padding:10px; background-color:#f4f4f4; border: 1px solid #ccc;\"><strong>Document Name</strong>: '+file+'</div>');
                              }
                         }
                      });			
                   });");

$this->loadplugin('fileUpload/js/ajaxupload','js');
$this->wrapscript($uploadscript);

$docquery = $this->runquery("SELECT "
                                . "documents.filename AS filename, "
                                . "documents.docname AS docname, "
                                . "courses.coursename AS folder, "
                                . "documents.documenttype AS filetype, "
                                . "courses_document.time_created AS timecreated "
                            . "FROM "
                                . "ichadb.courses_document "
                            . " INNER JOIN "
                                . "ichadb.documents "
                            . "ON "
                                . "courses_document.documents_id = documents.docid "
                            . "INNER JOIN "
                                . "ichadb.courses "
                            . "ON "
                                . "courses_document.courses_id = courses.courseid "
                            . "WHERE "
                                . "courses_document.courses_id =  '$tid'",
                            'multiple','all');
$documentcount = $this->getcount($docquery);

echo '<h1>Linked Documents</h1>';

    echo '<div class="response"></div>';
    
    if($documentcount>='1')
        {
            $doctable = '<table width="100%" border="0" cellpadding="10" class="tablelist">';
            $doctable .= '<tr><td>Filename</td><td>Date Created</td><td>Type</td><td>Download</td></tr>';
            $this->loadplugin('encryption/encrypt');
            $cipher = new encryption();

            for($r=1; $r<=$documentcount; $r++)
            {
                $document = $this->fetcharray($docquery);

                if(file_exists(ABSOLUTE_PATH.'media/training/courses/'.$document['folder'].'/'.$document['filename']))
                {
                    $doclink = SITE_PATH.'media/training/courses/'.$document['folder'].'/'.$document['filename'];
                    $target = 'target="_blank"';

                    $dochref = '<a href="'.$doclink.'" '.$target.'>
                                    Download File
                                </a>';
                }
                else
                {
                    $doclink = $link->geturl().'&msgerror=No_document_found';
                    $dochref = '<p>No Linked File</p>';
                }
                //check time if null
                $timecreated=$document['timecreated'];
                if($timecreated > 0){                  
                    $timecreated = date('d/M/Y @ H:i',$timecreated);
                }else{
                    $timecreated ="Not Recorded";
                }
                $doctable .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
                <td><strong>'.substr($document['docname'],0,40).'</strong></td>
                <td><strong>'.$timecreated.' Hrs</strong></td>
                <td><strong>'.$document['filetype'].'</strong></td>
                <td>'.$dochref.'</td>
              </tr>';
            }

            $doctable .= '</table>';

            echo $doctable;
        }
        else
        {
                $course=$this->runquery("SELECT * FROM courses WHERE courseid='$tid'",'single');
                $this->inlinemessage('No documents attached to '.$course['coursename'].' course.','error');
        }








echo '<div id="attachdoc" style="border-radius:4px; margin-top: 10px; margin-left: 2px">'
        . '<div class="qq-upload-button">'
            . '* Attach Student Document'
        . '</div>'
    . '</div>';
//    '.ABSOLUTE_PATH.'media/training/courses/'.$folder['coursename'].'