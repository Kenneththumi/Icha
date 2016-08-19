<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$price = explode('.',$cost['price']);
$name = explode(' ',$student['name']);

echo '<form action="?content=com_pesapal&folder=same&file=pesapal-iframe&from=course&courseid='.$_GET['courseid'].'" method="post">
	<input type="hidden" name="amount" value="'.$price[0].'" />
        <input type="hidden" name="type" value="MERCHANT" readonly="readonly" />
	<input type="hidden" name="description" value="'.$course['coursename'].'" />
        <input type="hidden" name="reference" value="'.$course['courseid'].' '.$_GET['id'].'" />
	<input type="hidden" name="first_name" value="'.$name[0].'" />
	<input type="hidden" name="last_name" value="'.$name[1].'" />
	<input type="hidden" name="email" value="'.$student['emailaddress'].'" />
        <input type="hidden" name="sourceid" value="'.$student['students_id'].'" />
	<table width="100%" border="0" cellpadding="10">
            <tr>
              <td>&nbsp;</td>
              <td width="90%" align="right"><img src="'.STYLES_PATH.'df_template/images/pesapal.png" width="159" height="60" /></td>
              <td align="left"><input type="submit" id="stepButton" value="Confirm Payment" /></td>
            </tr>
          </table>
</form>'
?>
