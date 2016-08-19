<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadscripts();

$docid = $_GET['id'];

$student = new user;
$student_details = $student->returnUserSourceDetails($docid,'student');

$this->loadcss(array('ichatemplate','basestyles_css'));

$uploadscript = ("$(document).ready(function(){
    
                   var button = $('.qq-upload-button');
                   var imgholder = $('#attachdoc');
                   var responsediv = $('div.response');
                   
                   var pathname = $(location).attr('href');
                   var numRand = Math.floor(Math.random()*1001);

                   new AjaxUpload(button, {
                          action: 'plugins/fileUpload/studentdocupload.php',
                          name: 'userfile',
                          data:{
                              docsave: 'yes',
                              saveto: 'training/students/".$student_details['foldername']."',
                              studentid: '".$_GET['id']."',
                              randomkey: numRand
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
                          
                              //remove error message if present
                              var errormsg = $('.error');                                  
                              if(errormsg.length){
                                errormsg.remove();
                              }
                              
                              responsediv.html('<strong>Upload Complete</strong>: '+file);
                              
                              if(responsediv.length){                                  
                                responsediv.after('<div class=\"doc\" style=\"border-radius: 4px; padding:10px; background-color:#f4f4f4; border: 1px solid #ccc;\"><strong>Document Name</strong>: '+file+'</div>');
                              }
                         }
                      });			
                   });");

$this->loadplugin('fileUpload/js/ajaxupload','js');
$this->wrapscript($uploadscript);

$docquery = $this->runquery("SELECT ".
        "documents.docname AS docname, ".
        "documents.documenttype AS documenttype, ".
        "documents.filename AS filename, ".
        "students.foldername AS folder ".
        
        "FROM students_documents ".
        
        "INNER JOIN documents ON students_documents.documents_id = documents.docid ".
        "INNER JOIN students ON students_documents.students_id = students.students_id ".
        
        "WHERE students_documents.students_id = '$docid'",'multiple','all');
$documentcount = $this->getcount($docquery);

echo '<h1>Linked Documents</h1>';

if($documentcount>='1')
{
    $doctable = '<table width="100%" border="0" cellpadding="10" class="tablelist">';

    $this->loadplugin('encryption/encrypt');
    $cipher = new encryption();

    for($r=1; $r<=$documentcount; $r++)
    {
        $document = $this->fetcharray($docquery);
        
        if(file_exists(ABSOLUTE_PATH.'media/training/students/'.$document['folder'].'/'.$document['filename']))
        {
            $doclink = SITE_PATH.'media/training/students/'.$document['folder'].'/'.$document['filename'];
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
        
        $doctable .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
        <td><strong>'.$document['docname'].'</strong></td>
        <td><strong>'.$document['documenttype'].'</strong></td>
        <td>'.$dochref.'</td>
      </tr>';
    }

    $doctable .= '</table>';
    
    echo $doctable;
}
else
{
    $this->inlinemessage('No documents attached to '.$student_details['name'],'error');
    echo '<div class="response"></div>';
}

echo '<div id="attachdoc" style="border-radius:4px; margin-top: 10px; margin-left: 2px">'
        . '<div class="qq-upload-button">'
            . '* Attach Student Document'
        . '</div>'
    . '</div>';
