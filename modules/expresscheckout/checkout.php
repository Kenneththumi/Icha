<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$cash = new finances;

echo '<form action="'.$params['step1action'].'" method="POST" id="expresscheckout">';
echo '<h2>'.$params['title'].'</h2>';

//get the publication details
$publication = $this->runquery("SELECT * FROM publications WHERE publicationid = '".$params['pid']."'",'single');

//get currency details
$price = finances::findPrice($params['pid'],'publication','formatted');
$cur = finances::defaultCurrency();

echo '<div class="item '.($e%2=='0' ? 'even' : 'odd').'">
             
<table width="100%" border="0" cellpadding="10">
  <tr>
    <td><p>'.$this->shortentxt(strip_tags($publication['title']),30).'</p></td>
    <td><p>'.$cur['code'].' '.$price.'</p></td>
  </tr>
</table>
</div>';
echo '<input type="hidden" name="pid" value="'.$params['pid'].'">';
echo '<input type="Submit" name="checkout" value="Process Payment">';
echo '</form>';
?>
