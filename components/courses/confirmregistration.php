<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(isset($_POST['cmd']))
{
    //save student photo
    $photosave = array(
        'filename' => $_POST['uploadimg']
    );
    
    $this->dbupdate('students',$photosave,"students_id='".$_POST['student_id']."'");
    
    //save identification document
    if(isset($_POST['id_docname'])&&isset($_POST['id_docname'])){
        
        $id_docsave = array(
            'filename' => $_POST['id_docname'],
            'docname' => $_POST['id_docname'],
            'uploaddate' => time(),
            'documenttype' => 'ID'
        );

        $this->dbinsert('documents',$id_docsave);
        $id_docid = mysql_insert_id();

        $id_studentdoc = array(
            'students_id' => $_POST['student_id'],
            'documents_id' => $id_docid
        );

        $this->dbinsert('students_documents',$id_studentdoc);
    }
    else{
        
        $idset = FALSE;
    }
    
    if(isset($_POST['cv_docname']) && isset($_POST['cv_docname'])){
        
        //save curriculum vitae
        $cv_docsave = array(
            'filename' => $_POST['cv_docname'],
            'docname' => $_POST['cv_docname'],
            'uploaddate' => time(),
            'documenttype' => 'CV'
        );

        $this->dbinsert('documents',$cv_docsave);
        $cv_docid = mysql_insert_id();

        $cv_studentdoc = array(
            'students_id' => $_POST['student_id'],
            'documents_id' => $cv_docid
        );

        $this->dbinsert('students_documents',$cv_studentdoc);
    }
    else{
        
        $cvset = FALSE;
    }
    
    echo '<h1 class="articleTitle">Course Registration</h1>';
    
    echo '<div id="itemContainer">';
    
    $this->inlinemessage('Thank you for registering for our courses. <br/> We will process your application and get back to you within 7 days','valid');
    
    echo '</div>';
}
