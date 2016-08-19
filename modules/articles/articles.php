<?php
if(isset($params['category'])){
    
    $category = $params['category'];
    $number = (isset($params['number']) ? $params['number'] : 'all' );

    $articles = $this->runquery("SELECT * FROM articles "
            . "JOIN categories ON categories.categoryid = articles.categoryid "
            . "WHERE categories.name='".$category."' "
            . "ORDER BY articleid ASC",'multiple',$number);
}
elseif(isset($params['title'])){
    
    $title = $params['title'];    
    $number = (isset($params['number']) ? $params['number'] : 'all' );
    
    $articles = $this->runquery("SELECT * FROM articles "
            . "WHERE title LIKE '%$title%'",'multiple',$number);
}

$articlecount = $this->getcount($articles);

if($articlecount >= 1){
    
    for($r=1; $r<=$articlecount; $r++){
        
        $article = $this->fetcharray($articles);
    
        if(!is_null($params['links'])){
            
            if(array_key_exists($article['alias'], $params['links'])){

                $linkalias = $params['links'][$article['alias']];

                $linkquery = $this->runquery("SELECT menulink FROM menus WHERE linkalias = '$linkalias'",'single');
                $link = $linkquery['menulink'];
            }
            else{

                $link = '#';
            }
        }
        
        echo '<div class="article '.$params['class'].'">';
        echo '<div class="header"><h2><a href="'.$link.'">'.$article['title'].'</a></h2></div>';
        echo '<div class="modulebody">'.$article['body'].'</div>';
        
        if($params['allow_read_more']){
        
            echo '<a href="'.$link.'" class="readmore">Read More</a>';
        }
        
        echo '</div>';
    }
}