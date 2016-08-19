<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$pid = $_GET['id'];

$course = $this->runquery("SELECT * FROM courses WHERE courseid = '$pid'",'single');

if($course['brochure']!=''){
    
        $docFetch = json_decode($course['brochure'], TRUE);
        
        if($docFetch['filename']!=''){
            
            $doctable = '<br/><table width="100%" border="0" cellpadding="7" class="tablelist">
                              <tr class="tabletitle">
                                    <td class="attribute"><strong>Document Name</strong></td>
                                    <td class="attribute" align="center"><strong>View Doc</strong></td>
                              </tr>';


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
                    <td>'.$docFetch['document'].'</td>
                    <td align="center">
                        <a href="'.$filepath.'" target="'.$target.'">
                            <img src="'.STYLES_PATH.'ichatemplate/admin/images/viewdoc.png">
                        </a>
                    </td>
                    </tr>';	  

          $doctable .= '</table>';

          echo $doctable;
        }
        else{
            $this->inlinemessage('No course brochure linked','error');
        }
}
else
{
    $this->inlinemessage('No course brochure linked','error');
}

