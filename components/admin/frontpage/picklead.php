<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;
//$this->loadstyles('backoffice');

//echo '<h1>Pick International Center for Humanitarian Affairs Leading Article</h1>';

//form processing
if(isset($_POST['transferchk']))
{
	$artid = $this->findText('[',']',$_POST['leadArticle']);
	
	$artArray = array(
					  'lead'=>'yes'
					  );
	
	if($this->dbupdate('articles',$artArray,"articleid='$artid'"))
	{
		if(isset($_POST['uploadimg']))
		{
			$imgarray = array(
							  'imgname' => $_POST['uploadimg'],
							  'filename' => $_POST['uploadimg'],
							  'imgcategory' => 'banner',
							  'articleid' => $artid
							  );
			
                        //image check
                        $img = $this->runquery("SELECT count(*) FROM imgmanager WHERE articleid='".$artid."'",'single');
                        
                        if($img['count(*)']=='0')
                        {
                            $this->dbinsert('imgmanager',$imgarray);
                        }
                        else
                        {
                            $this->dbupdate('imgmanager',$imgarray,"articleid='$artid'");
                        }
		}
		
		$this->inlinemessage('The lead article has been saved.','valid');
	}
}

//$this->loadscripts();
$this->loadscripts('jquery.autocomplete','yes');

$this->wrapscript('$().ready(function() {
				function log(event, data, formatted) {
					$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
				}
				
				function formatItem(row) {
					return "<p>" + row[0] + "</p>";
				}
				function formatResult(row) {
					return row[0].replace(/(<.+?>)/gi, \'\');
				}
				
				$("#namesearch").flushCache();
				$("#namesearch").autocomplete("'.SITE_PATH.'components/admin/frontpage/transferlead.php", {
					minChars: 1,
					selectFirst: false,
					mustMatch: false,
					width: 500,
					multiple: false,
					matchContains: true,
					formatItem: formatItem,
					formatResult: formatResult
				});
			}); ');

$this->loadplugin('classForm/class.form');

$transferform = new form;

$transferform->setAttributes(array(
							"includesPath"=> PLUGIN_PATH.'/classForm/includes',
							"preventJQueryLoad" => true,
							"preventJQueryUILoad" => true,
							"action"=>$link->geturl(),
							"class"=>'addproduct'
							));

$transferform->addHidden('transferchk','transferchk');

$transferform->addHTML('<div id="uploaddiv">
<div class="qq-upload-button"> Upload an image here</div>
</div>');
$transferform->addTextbox('Write the title of the Lead Article, then select the article from the list that appears:','leadArticle','',array('required'=>true,'id'=>'namesearch'));

$transferform->addButton('Add Lead Article');

$transferform->render();
?>