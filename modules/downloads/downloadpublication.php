<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$documentid = $params['id'];
$document = $this->runquery("SELECT * FROM documents INNER JOIN publications ON documents.docid = publications.docid WHERE publicationid='".$documentid."'",'single');

echo '<h1 class="fullarticleTitle">Download Full Article</h1>';

if(file_exists(ABSOLUTE_PATH.'media/pdf/'.$document['filename'])&&!is_null($document['filename']))
{
    echo '<table width="100%" border="0" cellpadding="10" class="tablelist">
      <tr>
        <td width="80%">
            <a href="'.SITE_PATH.'media/pdf/'.$document['filename'].'" target="_blank">'.$document['docname'].'</a>
        </td>
        <td width="20%" align="center">
        <a href="'.SITE_PATH.'media/pdf/'.$document['filename'].'" target="_blank">
            <img src="'.SITE_PATH.'modules/downloads/images/pdf-download.png" >
         </a>
        </td>
      </tr>
    </table>';
}
else
{
    $this->inlinemessage('No PDF document found','error');
}
?>
