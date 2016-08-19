<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$hits = $this->runquery("SELECT sum(hits) FROM articles",'single');

echo '<table width="95%" border="0" cellpadding="5">
  <tr>
    <td>
		<h1 class="moduleheading">Website Views</h1>
	</td>
    <td><h1 class="hits">'.$hits['sum(hits)'].'</h1></td>
  </tr>
</table>';
?>