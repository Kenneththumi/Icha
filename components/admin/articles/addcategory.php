<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $getcategory = $this->runquery("SELECT * FROM categories WHERE categoryid = '".$id."'",'single');
}

if(isset($_POST['save'])){
    
    $bodytxt = 'body_'.$_POST['randomvalue'];
    
    $savecategory = array(
                    'name'=>$_POST['name'],
                    'description'=> mysql_real_escape_string($_POST[$bodytxt])
                  );
    
    if(!isset($_POST['categoryid']))
    {
        $this->dbinsert('categories',$savecategory);
        redirect($link->urlreturn('categories').'&msgvalid=The_category_has_been_added');
    }
    else
    {
        $id = $_POST['categoryid'];
        
        $this->dbupdate('categories',$savecategory,"categoryid='$id'");
        redirect($link->urlreturn('categories').'&msgvalid=The_category_has_been_edited');
    }    
}

echo '<h1 class="pagetitle">Add/Edit Category</h1>';

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

$category = new form;
$category->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => array(1,2,1),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action"=>'',
                              "id"=>'cform'
                              ));

$category->addHidden('save', 'save');

$rand = rand(1, 1000);
$category->addHidden('randomvalue',$rand);

if($_GET['task']=='edit')
{
    $category->addHidden('categoryid', $id);
}

$category->addTextbox('Category', 'name', $getcategory['name']);

$category->addTextarea('Category Description','body_'.$rand, $getcategory['description'],$abstract,array('style'=>'height: 300px'));

$category->addButton('Save Category', 'submit', array('id'=>'saveButton'));
$category->render();