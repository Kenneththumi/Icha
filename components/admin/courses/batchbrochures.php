<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->wrapscript("$(document).ready(function(){
                         $('.confirmdeletion').click(function() {
                                $.notification( 
                                        {
                                             title: 'Confirm Deletion:',
                                             img: \"plugins/owlnotifications/img/recyclebin.png\",
                                             content: '<p class=\"deletiontext\">Are you sure you want to delete? <a href=\"'+$(this).attr('rel')+'\">Yes</a><a href=\"".$link->geturl()."\">No</a></p>',
                                             fill: true,
                                             border: false,
                                             showTime: true
                                        }
                                );
                        });
                });");

$link = new navigation;
$doc = json_decode($coursedetails['brochure']);

if($doc->filename == ''){
    
    $this->inlinemessage('No publication documents have been uploaded','valid');
}
else{
    
    if($_GET['task']=='deletedocument'){
        
        $coursedoc = [
            'brochure' => ''
        ];
        
        $this->dbupdate('courses',$coursedoc,"courseid='".$_GET['courseid']."'");
        
        redirect($cipher->decrypt($_GET['url']).'&msgvalid=The_document_has_been_deleted');
    }
    
      $docFetch = json_decode($coursedetails['brochure'], TRUE);
      
      $doctable = '<br/><table width="100%" border="0" cellpadding="7" class="tablelist">
                      <tr class="tabletitle">
                            <td class="attribute"><strong>Document Name</strong></td>
                            <td class="attribute"><strong>Upload Date</strong></td>
                            <td class="attribute" align="center"><strong>View Doc</strong></td>
                            <td class="attribute"></td>
                      </tr>';

      
      $chkfilepath = MEDIA_PATH.'pdf/'.$docFetch['filename'];
      
        if($docFetch['filename']!=''){
            
            $filepath = MEDIA_PATH.'pdf/'.$docFetch['filename'];
            $target = '_blank';
            
            $url = $cipher->encrypt($link->geturl());
        }
        else{
            
            $filepath = $link->geturl().'&msgvalid=Document_not_found';
            $target = '_self';
        }

        $doctable .= '<tr rel="'.$this->shortentxt($docFetch['docname'],30).'" "'.($t%2==0 ? 'class="even"' : 'class="odd"').'">
                <td>
                <input name="filename" type="hidden" id="filename" value="'.$docFetch['filename'].'" />
                <input name="docname" type="hidden" id="docname" value="'.$docFetch['document'].'" />
                '.$docFetch['document'].'</td>
                <td>'.($docFetch['uploaddate'] == 0 ? 'Not Specified' : date('d-m-Y',$docFetch['uploaddate'])).'</td>
                <td align="center">
                    <a href="'.$filepath.'" target="'.$target.'">
                        <img src="'.STYLES_PATH.$this->set_template.'/admin/images/viewdoc.png">
                    </a>
                </td>
                <td>
                    <a class="confirmdeletion" rel="'.$link->geturl().'&task=deletedocument&courseid='.$coursedetails['courseid'].'&url='.$url.'" >
                        <img src="'.STYLES_PATH.'df_template/images/delete.png" width="28" height="28">
                    </a>
                </td>
                </tr>';

      $doctable .= '</table>';
  }

