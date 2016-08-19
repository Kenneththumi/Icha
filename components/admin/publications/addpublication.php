<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<link rel="stylesheet" type="text/css" href="'.PLUGIN_PATH.'/select2/css/select2.min.css">';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/select2/js/select2.min.js"></script>';

$this->wrapscript("$(document).ready(function(){"
                        . "$(\"select[name='category']\").select2({"
                                . "theme: 'classic',"
                                . "tags: true"
                                . "});"
                        . "$(\"select[name='createdby']\").select2({"
                                . "theme: 'classic',"
                                . "tags: true"
                                . "});"
                    . "});");

$link = new navigation;

if(isset($_GET['from'])){
    
    $title = ucfirst($_GET['from']);
}

if($_GET['task']=='del'){
    
	$docGet = $this->runquery("SELECT * FROM documents WHERE docid='".$_GET['docmanid']."'",'single');
			
	if(strstr($docGet['doctype'],'pdf')=='pdf'){
            
            @unlink(ABSOLUTE_PATH.'media/pdf/'.$docGet['filename']);
	}
	else{
            
            @unlink(ABSOLUTE_PATH.'media/pdf/'.$docGet['filename']);
	}
			
	$this->deleterow('documents','docid',$_GET['docmanid']);
	
	redirect($link->urlreplace('&task=del','','yes').'&task=edit&msgvalid='.$docGet['docname'].'_has_been_deleted.','no');
}

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


$uploadscript = ("$(document).ready(function(){
                   var button = $('.qq-upload-button');
                   var imgholder = $('#attachdoc');
                   var pathname = $(location).attr('href');
                   var numRand = Math.floor(Math.random()*1001);

                   new AjaxUpload(button, {
                          action: 'plugins/fileUpload/fileupload.php',
                          name: 'userfile',
                          data:{
                                  docsave: 'yes',
                                  saveto: 'pdf',
                                  publicationid: '".$_GET['id']."',
                                  randomkey: numRand
                                  },
                          onSubmit: function(file,ext){
                                  //insert the loading graphic
                                    if (ext && /^(pdf|PDF)$/.test(ext))
                                      {
                                          //insert the loading graphic
                                          imgholder.html('<p><img src=\"styles/df_template/images/ajaxloader.gif\" width=\"128\" height=\"15\"><br/>Upload in Progress<br/>Please dont close window.</p>');
                                      }
                                      else
                                      {
                                          alert('Error: only PDF documents can be uploaded here');
                                          return false;
                                      }

                                  },
                          onComplete: function(file,response){
                                  //show uploaded image
                                  var stripfile = file.replace(/ /g,'');

                                  imgholder.html('<input name=\"filename\" type=\"hidden\" id=\"filename\" value=\"'+numRand+\"_\"+file+'\" /><input name=\"docname\" type=\"hidden\" id=\"docname\" value=\"'+file+'\" /><div id=\"fileholder\">Document Name: '+file+'</div>');
                         }
                                  });			
                               });");


//get the publication name - the courseid is gotten from the addcourse file
$course = $this->runquery("SELECT * FROM courses WHERE courseid='$courseid'",'single');

//get the lecturer
$lecturer = $this->runquery("SELECT * FROM sbusers WHERE sbid = '".$course['sbid']."'",'single');

//get the default price
$cur = $this->runquery("SELECT currencyid, currencycode FROM currency WHERE status='default'",'single');

if(isset($_GET['id'])&&$_GET['task']!='new'){
        
    $id = $_GET['id'];
    $publication = $this->runquery("SELECT * FROM publications WHERE publicationid = '".$id."'",'single');
    
    $from = $publication['ptype'];
    $title = ucfirst($publication['ptype']);
    
    $cash = new finances;
    $value = $cash->findPrice($id, 'publication','raw');
   
    //include the batch publications document
    include ABSOLUTE_PATH.'components/admin/publications/batchpublications.php';
    
    if($doctable==''){
        
        $this->loadplugin('fileUpload/js/ajaxupload','js');
        $this->wrapscript($uploadscript);
    }
    
    //get the research categories
    $catlist[] = $publication['category'];
}
else{
    
    $this->loadplugin('fileUpload/js/ajaxupload','js');
    $this->wrapscript($uploadscript);
    
    $from = $_GET['from'];
    
    //get the research categories
    $catlist[] = 'None';
}

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

$cats = $this->runquery("SELECT name FROM categories","multiple","all");
$catcount = $this->getcount($cats);

if($catcount >= 1){
    
    for($w=1; $w<=$catcount; $w++){
        
        $cat = $this->fetcharray($cats);        
        $catlist[] = $cat['name'];
    }
}

$this->wrapscript("$(document).ready(function(){
                       $('.newpublication').change(function(){
                            var val = this.value;

                            if(val=='Yes')
                            {
                                $('#saveButton').val('Add New Publication');
                                $('#publishform').attr('action','".$link->geturl()."&task=new');
                            }
                        });
                    });");

echo '<h1 class="pagetitle">Resources Management</h1>';

//sorting the publication abstract bug - attaching random number to the field
$rand = rand(1, 1000);

$abstract =  $publication['body'];

//set the form map based on where its called and form action
 $map = array(1,2,1,1,2);
 $action = $link->urlreplace('addpublication', 'savepublication');

$publicationform = new form();

$publicationform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'98%',
                                      "map"=>$map,
                                      "preventJQueryLoad" => true,
                                      "preventJQueryUILoad" => true,
                                      "action"=>$action,
                                      "id"=>'publishform'
                                      ));

$publicationform->addHidden('savestep','savetwo');
$publicationform->addHidden('publicationid',$publication['publicationid']);
$publicationform->addHidden('docid',$publication['docid']);
$publicationform->addHidden('randomvalue',$rand);
$publicationform->addHidden('curid', $cur['currencyid']);
$publicationform->addHidden('task', 'edit');
$publicationform->addHidden('url', $link->geturl());
$publicationform->addHidden('ptype', $from);

$publicationform->renderHead();
$publicationform->addHTML('<p style="font-size:12px; color:#900; float:right;">* - indicates required field</p>');

$publicationform->addTextbox("* ".$title." Title:",'publicationtitle',$publication['title'],array('class'=>'required'));
$publicationform->addSelect('* Category', 'category', $publication['category'], $catlist,['style'=>'width: 100%']);

$publicationform->addTextarea('* '.$title.' Brief','pabstract_'.$rand,strip_tags($abstract));

if($doctable != '') {
    
    $publicationform->addHTML($doctable);
}
else{
    
    $publicationform->addHTML($doclist.'<div id="attachdoc"><div class="qq-upload-button">* Attach Document PDF</div></div>');
}


//load the contributors
$querystr = "SELECT contributorid, name FROM contributors ORDER BY contributorid DESC";

$queries = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queries);

if($_GET['task']!='edit'){
    $contributors = array('0'=>'Any Instructor');
}
else {
    $contributors[] = $publication['author'];
}

for($r=1; $r<=$querycount; $r++){
    
    $query = $this->fetcharray($queries);
    
    $contributors[] = $query['name'];
}

$publicationform->addSelect('*Authored By', 'createdby', $publication['author'], $contributors,array('class'=>'required','style'=>'width:100%'));
$publicationform->addTextbox('*Author Contact Email', 'authoremail','',['class'=>'required']);

$publicationform->addButton('Save '.$title, 'submit', array('id'=>'saveButton'));

$publicationform->render();

$this->wrapscript('
    $(document).ready(function(){
     $("#publishform").submit(function(){
        var isFormValid = true;

    $(".required").each(function(){
        if ($.trim($(this).val()).length == 0){
            $(this).addClass("redline");
            isFormValid = false;
        }
        else{
            $(this).removeClass("redline");
        }
    });

    $(".redline").first().focus();

    return isFormValid;
});
});');
