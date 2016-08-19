<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$cat = $this->runquery("SELECT * FROM categories ORDER BY catid DESC");

echo '<img src="'.STYLES_PATH.'ichatemplate/images/subscribetoday.png" width="202" height="45" />';

/*
for($i=1; $i<=3; $i++)
{
	$catArray = $this->fetcharray($cat);
	
	echo '<tr>
		<td>To subscribe to <strong>'.ucfirst($catArray['categoryname']).'</strong>, <a href="?content=mod_subscribers&folder=same&file=subscribeusers&cid='.$catArray['catid'].'" class="subscribe"><strong>click here</strong></a></td>
	  </tr>';
}
echo '<tr>
    <td>To subscribe to <strong>any International Center for Humanitarian Affairs Updates</strong>, <a href="?content=mod_subscribers&folder=same&file=subscribeusers&ctype=pick" class="subscribe"><strong>click here</strong</a></td>
  </tr>
</table>';
*/
echo '<table><tr><td>To subscribe to <strong>Immunization Updates</strong>, write an E-mail to kidsurvivalvaccination@gmail.com<br/><br/>
To subscribe to <strong>Malaria Updates</strong>, write an E-mail to kidsurvivalmalaria@gmail.com<br/><br/>
To subscribe to <strong>All Updates</strong>, write an E-mail to kidsurvival@gmail.com<td>
</tr></table>';
?>