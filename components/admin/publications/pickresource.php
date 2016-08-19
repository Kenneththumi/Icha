<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<table width="100%" border="0" align="center">
    <tr>
        <td colspan="2" cellpadding="10">
            <h1>Pick Resource</h1>
        </td>
    </tr>
  <tr align="center">
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=publication">
        <img src="'.STYLES_PATH.'ichatemplate/admin/images/publications.png" />
        </a>
    </td>
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=research">
        <img src="'.STYLES_PATH.'ichatemplate/admin/images/researchpaper.png" />
        </a>
    </td>
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=policy">
        <img src="'.STYLES_PATH.'ichatemplate/admin/images/policy.png" />
        </a>
    </td>
  </tr>
  <tr align="center">
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=publication">
            <strong>Publication</strong>
        </a>
    </td>
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=research">
            <strong>Research</strong>
        </a>
    </td>
    <td>
        <a href="?admin=com_admin&folder=publications&file=addpublication&from=policy">
            <strong>Policy</strong>
        </a>
    </td>
  </tr>
</table>';

