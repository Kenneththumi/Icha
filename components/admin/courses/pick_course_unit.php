<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 style="color:#FFF">heading</h1>';
echo '<table width="100%" border="0" align="center" cellpadding="0">
  <tr align="center">
    <td>
    <a href="?admin=com_admin&folder=courses&file=addcourse&type=course">
    <img src="'.STYLES_PATH.'ichatemplate/admin/images/course-icon.jpg" />
        </a>
        </td>
    <td>
    <a href="?admin=com_admin&folder=courses&file=addcourse&type=unit">
    <img src="'.STYLES_PATH.'ichatemplate/admin/images/unit-icon.jpg" />
        </a>
        </td>
  </tr>
  <tr align="center">
    <td>
        <a href="?admin=com_admin&folder=courses&file=addcourse&type=course">
            Course
        </a>
    </td>
    <td>
        <a href="?admin=com_admin&folder=courses&file=addcourse&type=unit">
            Course Unit
        </a>
    </td>
  </tr>
</table>';

