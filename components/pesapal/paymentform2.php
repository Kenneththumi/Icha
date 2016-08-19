<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$name = explode(' ',$buyerinfo['name']);

echo '<form action="?content=com_pesapal&folder=same&file=pesapal-iframe&from=publication&publicationid='.$_POST['pid'].'" method="post">
	<input type="hidden" name="amount" value="'.$publication['price'].'" />
        <input type="hidden" name="type" value="MERCHANT" readonly="readonly" />
	<input type="hidden" name="description" value="'.$publication['title'].'" />
        <input type="hidden" name="reference" value="'.$_POST['pid'].'" />
	<input type="hidden" name="first_name" value="'.$name[0].'" />
	<input type="hidden" name="last_name" value="'.$name[1].'" />
	<input type="hidden" name="email" value="'.$buyerinfo['email'].'" />
	<table width="100%" border="0" cellpadding="10">
            <tr>
              <td>&nbsp;</td>
              <td width="90%" align="right"><img src="'.STYLES_PATH.'df_template/images/pesapal.png" width="159" height="60" /></td>
              <td align="left"><input type="submit" id="stepButton" value="Confirm Payment" /></td>
            </tr>
          </table>
</form>'
?>
