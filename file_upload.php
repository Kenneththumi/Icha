<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$link = new navigation();
include 'classes\template.class.php';
   $this->loadscripts();


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
   echo '<div id="attachdoc" style="border-radius:4px; margin-top: 10px; margin-left: 2px">'
        . '<div class="qq-upload-button">'
            . '* Attach Document'
        . '</div>'
    . '</div>';
