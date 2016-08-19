<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadscripts('jquery.accordion','yes');

echo '<link href="'.SITE_PATH.'scripts/jquery.addons/jquery.css/accordion.css" rel="stylesheet" type="text/css" />';

$publications = $this->runquery("SELECT * FROM publications ORDER BY publishdate DeSC",'multiple');
$publicationcount = $this->getcount($publications);

echo '<h1 class="articleTitle">Related Publications</h1>';
echo '<span style="font-size: 11px; text-align: right; width: 95%; display: table; margin-top: -5px; margin-bottom: 10px; margin-left: auto; margin-left: auto">(Click year to open)</span>';
echo '<div class="accordion">
    <ul>';
for($t=1; $t<=$publicationcount; $t++)
{
    $publication = $this->fetcharray($publications);
    $date = explode('-',date('d-m-Y',$publication['publishdate']));
    
    if($year=='')
    {
        $year = $date[2];
        echo '<li>'.$date[2].'</li>';
        
        echo '<li class="dimension">';
    }
    elseif ($year!=$date[2]) {
        echo '<li>'.$date[2].'</li>';  
        
        echo '<li class="dimension">';
    }
    
    echo '<a href="?content=com_publications&folder=same&file=publicationdetails&pid='.$publication['publicationid'].'" target="_blank" class="publicationitem">'.$publication['title'].'</a>';
    
    if($year=='')
    {
        echo '</li>';
    }
    elseif ($year!=$date[2]) {
        echo '</li>'; 
    }
}
echo '</ul>
    </div>';
?>
