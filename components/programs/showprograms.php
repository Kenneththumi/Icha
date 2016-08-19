
<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 class="fullarticleTitle">Our Programs</h1>';

//get training article
$training = $this->runquery("SELECT * FROM articles WHERE articleid='1111'",'single');

echo '<div class="programsholder">';
echo '<div class="programscontent">';

echo '<h1 class="programsTitle">'.$training['title'].'</h1>';
echo '<p class="fulltxt">'.strip_tags($this->shortentxt($training['body'],600)).'</p>';
echo '<a class="readmore" href="?content=com_articles&artid=1111">Read More</a>';

echo '</div>';

echo '<div class="sidepanes">';

//enter the current available courses
$params = array('items'=>'3');
$this->loadmodule('availablecourses','courses',$params);

echo '</div>';
echo '</div>';

//get policy article
$policy = $this->runquery("SELECT * FROM articles WHERE articleid='1112'",'single');

echo '<div class="programsholder">';
echo '<div class="programscontent">';

echo '<h1 class="programsTitle">'.$policy['title'].'</h1>';
echo '<p class="fulltxt">'.strip_tags($this->shortentxt($policy['body'],400)).'</p>';
echo '<a class="readmore" href="?content=com_articles&artid=1112">Read More</a>';

echo '</div>';

echo '<div class="sidepanes">';

//enter the current available courses
$params = array('items'=>'3');
$this->loadmodule('upcomingevents','events',$params);

echo '</div>';
echo '</div>';

//get research article
$research = $this->runquery("SELECT * FROM articles WHERE articleid='1113'",'single');

echo '<div class="programscontent">';

echo '<h1 class="programsTitle">'.$research['title'].'</h1>';
echo '<p class="fulltxt">'.strip_tags($this->shortentxt($research['body'],400)).'</p>';
echo '<a class="readmore" href="?content=com_articles&artid=1113">Read More</a>';

echo '</div>';

//get strategy article
$strategy = $this->runquery("SELECT * FROM articles WHERE articleid='1114'",'single');

echo '<div class="programscontent">';

echo '<h1 class="programsTitle">'.$strategy['title'].'</h1>';
echo '<p class="fulltxt">'.strip_tags($this->shortentxt($strategy['body'],400)).'</p>';
echo '<a class="readmore" href="?content=com_articles&artid=1114">Read More</a>';

echo '</div>';
?>
