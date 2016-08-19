<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$menus = $this->runquery("SELECT * FROM menus",'multiple','all');
$menucount = $this->getcount($menus);

for($r=1; $r<=$menucount; $r++){
    
    $menu = $this->fetcharray($menus);
    
    if($menu['linkalias'] == ''){
        
        //create alias
        $rawalias = strtolower(str_replace('--', '-', $this->strClean(strip_tags($menu['linkname']))));
        
        $splalias = explode('-', $rawalias);
        
        if(count($splalias)>3){
            $alias = $splalias[0].'-'.$splalias[1].'-'.$splalias[2];
        }
        else{
            
            $alias = $rawalias;
        }
        
        $savemenu = ['linkalias' => $alias];
        $this->dbupdate('menus',$savemenu,"menuid='".$menu['menuid']."'");
    }
    
    echo 'done';
}