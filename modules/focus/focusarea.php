<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$article = $this->runquery("SELECT * FROM articles WHERE title='".'Focus Areas'."'",'single');

echo '<h1 class="articleTitle">'.$article['title'].'</h1>';
echo $article['body'];
?>
