<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if($_GET['task']=='edit'){
    
    $id = $_GET['id'];    
    $articledetails = $this->runquery("SELECT * FROM articles WHERE articleid='$id'",'single');
    
    if($articledetails['categoryid'] != 0){
        
        $selectcat = $this->runquery("SELECT * FROM categories WHERE categoryid='".$articledetails['categoryid']."'",'single');
        $categorylist[$selectcat['categoryid']] = $selectcat['name'];
    }
    else{
        
        $categorylist[''] = 'Select Category';
    }
}
else{
    
    $categorylist[''] = 'Select Category';
}

//get categories
$categories = $this->runquery("SELECT * FROM categories",'multiple','all');
$catcount = $this->getcount($categories);

for($i=1; $i<=$catcount; $i++){
    
    $category = $this->fetcharray($categories);    
    $categorylist[$category['categoryid']] = $category['name'];
}

if(isset($_POST['save'])){
    
    $bodytxt = 'body_'.$_POST['randomvalue'];
    
    $savearticle = array(
                    'title'=>$_POST['articlename'],
                    'categoryid' => ($_POST['category']=='' ? 0 : $_POST['category']),
                    'body'=> mysql_real_escape_string($_POST[$bodytxt]),
                    'publishdate'=>time(),
                    'hits' => 0,
                    'lead' => '',
                    'atype' => '',
                    'parentid' => 0
                  );
    
    if(!isset($_POST['articleid'])){
        
        //generate the article alias
        $savearticle['alias'] = str_replace(' ', '-', trim(strtolower(strip_tags($_POST['alias']))));
        
        $this->dbinsert('articles',$savearticle);
        redirect($link->urlreturn('articles').'&msgvalid=The_article_has_been_added');
    }
    else{
        
        $id = $_POST['articleid'];
        
        if(!array_key_exists('alias', $_POST)){
            
            //generate the article alias from the title
            $savearticle['alias'] = str_replace(' ', '-', trim(strtolower(strip_tags($_POST['articlename']))));
        }
        
        $this->dbupdate('articles',$savearticle,"articleid='$id'");
        redirect($link->urlreturn('articles').'&msgvalid=The_article_has_been_edited');
    }    
}

echo '<h1 class="pagetitle">Article Management</h1>';

$this->loadplugin('classForm/class.form');
$this->loadplugin('tinymce/tinymce.min','js');

$this->wrapscript('tinymce.init({
                        selector: "textarea",
                        plugins: [
                                "advlist autolink autosave link image code lists charmap print preview hr anchor pagebreak spellchecker",
                                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor filemanager"
                        ],
                        convert_urls: false,
                       toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                       toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                       image_advtab: true ,

                       external_filemanager_path: "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/",
                       filemanager_title:"'.SITENAME.' Filemanager" ,
                       external_plugins: { "filemanager" : "'.PLUGIN_PATH.'/tinymce/plugins/filemanager/plugin.min.js"}
                    });');

//sorting the publication abstract bug - attaching random number to the field
$rand = rand(1, 1000);
$abstract =  $articledetails['body'];

//sort the article alias
if($articledetails['alias']==''){
    
    $map = array(1,2,1);
}
else{
    
    $map = array(1,2,1,1);
}

$article = new form;
$article->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => $map,
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action"=>'',
                              "id"=>'cform'
                              ));

$article->addHidden('save', 'save');
$article->addHidden('randomvalue',$rand);

if($_GET['task']=='edit')
{
    $article->addHidden('articleid', $id);
}

$article->addHTML('<h1>Enter article details below:</h1>');

$article->addTextbox('Article Name', 'articlename',$articledetails['title'],array('class'=>'required'));

if($articledetails['alias']!=''){
    
    $article->addTextbox('Alias <small>(don\'t change unless necessary)</small>','alias',$articledetails['alias'],array('class'=>'required'));
}

$article->addSelect('Category', 'category', '', $categorylist);

$article->addTextarea('Article Body','body_'.$rand,$abstract,array('style'=>'height: 300px'));

$article->addButton('Save Article', 'submit', array('id'=>'saveButton'));
$article->render();

