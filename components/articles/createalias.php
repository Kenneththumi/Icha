<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$articles = $this->runquery("SELECT * FROM articles",'multiple','all');
$artcount = $this->getcount($articles);

for($r=1; $r<=$artcount; $r++){
    
    $article = $this->fetcharray($articles);
    
    if($article['linkalias'] == ''){
        
        //create alias
        $rawalias = strtolower(str_replace('--', '-', $this->strClean(strip_tags($article['title']))));
        
        $splalias = explode('-', $rawalias);
        
        if(count($splalias)>3){
            $alias = $splalias[0].'-'.$splalias[1].'-'.$splalias[2];
        }
        else{
            
            $alias = $rawalias;
        }
        
        $savearticle = ['alias' => $alias];
        $this->dbupdate('articles',$savearticle,"articleid='".$article['articleid']."'");
    }
    
    echo 'done';
}