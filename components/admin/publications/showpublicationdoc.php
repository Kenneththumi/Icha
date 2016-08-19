<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$pid = $_GET['id'];

$publication = $this->runquery("SELECT * FROM publications WHERE publicationid = '$pid'",'single');

if($publication['docid']!='0'&&!is_null($publication['docid']))
{
    $docid = $publication['docid'];
    $docs = $this->runquery("SELECT * FROM documents WHERE docid='$docid' ORDER BY uploaddate DESC",'multiple','all');
    $docCount = $this->getcount($docs);
    
    if($docCount>='1')
    {
        $doctable = '<br/><table width="100%" border="0" cellpadding="7" class="tablelist">
			  <tr class="tabletitle">
				<td class="attribute"><strong>Document Name</strong></td>
				<td class="attribute"><strong>Upload Date</strong></td>
				<td class="attribute" align="center"><strong>View Doc</strong></td>
				<td class="attribute"></td>
			  </tr>';
  
	  for($i=1; $i<=$docCount; $i++)
	  {
		  $docFetch = $this->fetcharray($docs);
		  $chkfilepath = ABSOLUTE_MEDIA_PATH.'pdf/'.$docFetch['filename'];
                  //var_dump(file_exists($chkfilepath));
                  
		    if(file_exists($chkfilepath))
                    {
                        $filepath = MEDIA_PATH.'pdf/'.$docFetch['filename'];
                        $target = '_blank';
                    }
                    else
                    {
                        $filepath = $link->geturl().'&msgvalid=Document_not_found';
                        $target = '_self';
                    }
		  
                    $doctable .= '<tr rel="'.$this->shortentxt($docFetch['docname'],30).'" "'.($t%2==0 ? 'class="even"' : 'class="odd"').'">
                            <td>'.$docFetch['docname'].'</td>
                            <td>'.($docFetch['uploaddate'] == 0 ? 'Not Specified' : date('d-m-Y',$docFetch['uploaddate'])).'</td>
                            <td align="center">
                                <a href="'.$filepath.'" target="'.$target.'">
                                    <img src="'.STYLES_PATH.'ichatemplate/admin/images/viewdoc.png">
                                </a>
                            </td>
                            <td>
                                <a class="confirmdeletion" rel="'.$link->geturl().'&task=del&docmanid='.$docFetch['docid'].'" >
                                    <img src="'.STYLES_PATH.'df_template/images/delete.png" width="28" height="28">
                                </a>
                            </td>
                            </tr>';
	  }
	  
	  $doctable .= '</table>';
          
          echo $doctable;
    }
    else
    {
        $this->inlinemessage('No publication document linked','error');
    }
}
else
{
    $this->inlinemessage('No publication document linked','error');
}
?>
