<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

/*
$this->wrapscript("function initMenu() 
					{
						$('#acmenu ul').hide();
						$('#acmenu li a').click(
						
						function() 
						{
							$(this).next().slideToggle('normal');
						}
					);}
					
					$(document).ready(function() {initMenu();});");
*/
echo '<div id="accordionMenu">';
echo '<div id="accordionTop"></div>';

echo '<div id="accordionMid">';

//get the job opportunities bulletins

$jbQuery = $this->runquery("SELECT * FROM bulletins WHERE category='jobs' AND enabled='Yes' ORDER BY publishdate DESC",'multiple',10);
$jbCount = $this->getcount($jbQuery);

echo '<ul id="acmenu">';

//get the rfp bulletins
$rfpQuery = $this->runquery("SELECT * FROM bulletins WHERE category='rfp' AND enabled='Yes' ORDER BY publishdate DESC",'multiple',10);
$rfpCount = $this->getcount($rfpQuery);
		echo '<li>
			<a class="actitle"><img src="'.MEDIA_PATH.'images/rfp.png" width="219" height="36"></a>';
			
			if($rfpCount>=1)
			{
				echo '<ul class="acsubmenu">';
				for($k=1; $k<=$rfpCount; $k++)
				{
					$rfpArray = $this->fetcharray($rfpQuery);
					
					echo '<li><a href="?content=com_articles&butid='.$rfpArray['bulletinid'].'">'.$this->shortentxt($rfpArray['title'],30).'</a></li>';
				}
				echo '</ul>';
			}
			
		echo '</li>
		</ul>';

echo '</div>';

echo '<div id="accordionBottom"></div>';
echo '</div>';
?>