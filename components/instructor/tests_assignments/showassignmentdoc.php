<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

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
    
    $this->inlinemessage($docGet['docname'].' has been deleted.','valid');	
}

//the edit code
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $ta = $this->runquery("SELECT * FROM tests_assignments WHERE tests_assignments_id = '$id'",'single');
    
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
        <a href="'.$link->urlreplace('','task=del').'&task=del&docid='.$docid.'"><img src="'.SITE_PATH.'components/instructor/images/trash.png" alt="Delete document" /></a></td>
        </tr>';

        $doctable .= '</table>';
        
        echo $doctable;
    }
    else{
        $this->inlinemessage('No document attached','error');
    }
    
}
?>
