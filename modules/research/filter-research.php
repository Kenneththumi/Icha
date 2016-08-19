<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

//get the research categories
$pubcats = $this->runquery("SELECT DISTINCT category FROM publications","multiple","all");
$pubcount = $this->getcount($pubcats);

if($pubcount >= 1){
    
    for($u=1; $u<=$pubcount; $u++){
        
        $pub = $this->fetcharray($pubcats);
        
        if($pub['category'] !='')
            $catlist[] = $pub['category'];
    }
}

$this->loadplugin('classForm/class.form');

$researchform = new form;
$researchform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => array(1,4),
                              "action"=>'',
                              "id"=>'rform'
                              ));

$researchform->renderHead();
$researchform->addHidden('filter', 'filter');

$researchform->addHTML('<p>Filter by results by</p>');

$researchform->addTextbox('Title', 'title', '');
$researchform->addSelect('Category', 'category', '', $catlist);
$researchform->addSelect('Type','type',$params['type'],['research'=>'research',
                                                        'policy'=>'policy briefs',
                                                        'publication'=>'publication']);
$researchform->addSelect('Sort by date:', 'date', '', ['asc'=>'Ascending','desc'=>'Descending']);

$researchform->addButton('Search');

echo '<div class="filterform">';
    $researchform->render();
echo '</div>';

if(isset($_POST['filter'])){
    
    if($_POST['title']!=''){
        
        $search = 'Title: '.$_POST['title'].', ';
        $condition = 'title LIKE \'%'.$_POST['title'].'%\' AND ';
    }
    
    if($_POST['category']!='None'){
        
        $search .= 'Category: '.$_POST['category'] .', ';
        $condition .= 'category = \''.$_POST['category'].'\' AND ';
    }
    
    //publication type
    $search .= 'Type: '.ucfirst($_POST['type']);
    $condition .= 'ptype = \''.$_POST['type'].'\'';
    
    $query = $this->runquery("SELECT * FROM publications WHERE ".$condition." ORDER BY publishdate ".$_POST['date'],'multiple','all');
}
else{
    
    if(isset($params['type'])){
        $query = $this->runquery("SELECT * FROM publications WHERE ptype = '".$params['type']."'",'multiple','all');
    }
    else{
        $query = $this->runquery("SELECT * FROM publications",'multiple','all');
    }
}

$querycount = $this->getcount($query);

if(isset($_POST['filter']))
    echo '<h3><strong>Results found:</strong> '.$querycount.'<br/> <strong>Search criteria:</strong> '.$search.'</h3>';

if($querycount >= 1){
    
    echo '<div class="pubsholder">';

    for($r=1; $r<=$querycount; $r++){

        $publication = $this->fetcharray($query);
        
        echo '<div class="pubscontent">';

        echo '<div class="publication_header">';
        echo '<h1 class="pubsTitle">
            <a href="?content=com_publications&folder=same&file=publicationdetails&pid='.$publication['publicationid'].'">
            '.$publication['title'].' 
            </a></h1>';
        
        if($publication['ptype'] != 'research'){
            echo '<p class="pubauthor">Focal Person: '.$publication['author'].'</p>';
        }
        
        echo '</div>';

        echo '<div class="pubdetails">';

        echo '</div>';

        echo '<p class="fulltxt">'.strip_tags($this->shortentxt($publication['body'],400)).'</p>';
        
        if(!is_null($publication['docid'])){
            
            $docid = $publication['docid'];
            $doc = $this->runquery("SELECT * FROM documents WHERE docid='$docid' ORDER BY uploaddate DESC",'single');
            
            $chkfilepath = ABSOLUTE_MEDIA_PATH.'pdf/'.$doc['filename'];
                  
            if(file_exists($chkfilepath)){
                
                $filepath = MEDIA_PATH.'pdf/'.$doc['filename'];
                $target = '_blank';
            }
            else{
                
                $filepath = $link->geturl().'&msgvalid=Document_not_found';
                $target = '_self';
            }
            
            echo '<a class="action-link" href="'.$filepath.'" target="'.$target.'">
                    Download PDF
                </a>';
        }
        
        echo '<a class="readmore" '
            . 'href="?content=com_publications&folder=same&file=publicationdetails&pid='.$publication['publicationid'].'">'
                . 'Read More'
            . '</a>';

        echo '</div>';
    }

    echo '</div>';
}