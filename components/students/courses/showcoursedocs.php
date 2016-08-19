<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1>Course Documents</h1>';

$courseid = $_GET['id'];
$cfolder = $this->runquery("SELECT courses.coursename "
        . "AS course,contributors.foldername "
        . "AS folder FROM contributors "
        . "INNER JOIN courses "
        . "ON contributors.contributorid = courses.contributorid "
        . "WHERE courseid='$courseid'",'single');

$coursename = str_replace(' ', ' ', $cfolder['course']);
$path = ABSOLUTE_MEDIA_PATH."training/courses/".$cfolder['course']."/";

if(file_exists($path)){
    $directory = opendir($path);
    $doctable = '<table width="100%" border="0" cellpadding="0" class="tablelist">';

    while(false!==($file=readdir($directory)))
    {
        if($file!='.'&&$file!='..')
        {
            $doclink = MEDIA_PATH.'training/courses/'.$coursename.'/'.$file;
//            '.$cfolder['folder'].'/
            $target = 'target="_blank"';

            $doctable .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
            <td valign="center"><strong>'.substr($file,0,40).'</strong></td>
            <td valign="center" align="center">
            <a href="'.$doclink.'" '.$target.'>
                <img src="'.STYLES_PATH.'ichatemplate/student/images/downloadicon.png" />
            </a>        
            </td>
            </tr>';    
        }
//       else{
//            $this->inlinemessage('No documents found');
//        }
    }

    $doctable .= '</table>';
    echo $doctable;
}
else{
    $this->inlinemessage('No course folder found');
}

