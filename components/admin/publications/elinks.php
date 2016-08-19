<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadplugin('classForm/class.form');

if(isset($_GET['alert']))
{
	$this->loadstyles('backoffice');
}

$artid = $_GET['artid'];
$article = $this->runquery("SELECT * FROM articles WHERE articleid='$artid'",'single');

$links = explode(',',$article['body']);

$articleform = new form();

$articleform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'650',
								  "map"=>array(1,2),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$articleform->addHidden('articlesave','articlesave');
$articleform->addHidden('aid',$artid);
$articleform->addHidden('task',$_GET['task']);
$articleform->addHidden('key',$_GET['key']);

$articleform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Site Information: Add/Edit External Links</h1> </td></tr></table>');


$articleform->addTextbox('Link Title','ltitle',$this->findText('[title]','[|title]',$links[$_GET['key']]),array('required'=>true));
$articleform->addTextbox('Link','abody',$this->findText('[url]','[|url]',$links[$_GET['key']]),array('required'=>true));

$articleform->addButton('Save Elink','submit',array('id'=>'elinks'));

if(isset($_POST['articlesave']))
{
		$artid = $_POST['aid'];
		$article = $this->runquery("SELECT * FROM articles WHERE articleid='$artid'",'single');
		
		if($_POST['task']=='')
		{
			$save = array(
					  	'body'=>$article['body'].',[title]'.$_POST['ltitle'].'[|title]'.'[url]'.$_POST['abody'].'[|url]'
					  );
		}
		else
		{
			$links = explode(',',$article['body']);
			
			//remove array entry
			unset($links[$_POST['key']]);
			
			/*
			foreach($links as $key=>$value)
			{
				echo $key.'=>'.$value.'<br/>';
			}
			*/
			
			//insert new entry
			$links[$_POST['key']] = '[title]'.$_POST['ltitle'].'[|title]'.'[url]'.$_POST['abody'].'[|url]';
			
			$body = join($links,',');
			
			$save = array(
					  		'body'=> $body
						  );
		}
		
		$aid = $_POST['aid'];
		$this->dbupdate('articles',$save,"articleid='$artid'");
		
		$this->inlinemessage('The links has been saved.','valid');
}
else
{
	$articleform->render();
}
?>