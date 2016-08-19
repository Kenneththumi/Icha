<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if(isset($_POST['emailsave']))
{
	$aid = $_POST['aid'];
	
	$article = $this->runquery("SELECT * FROM articles WHERE articleid='$aid'",'single');
	
	$subject = $_POST['rsubject'];
	$email = $_POST['rmail'];
	$from = $_POST['rname'];
	
	$htmlbody = $article['title'].'
	'.$article['body'];
	
	$txtbody = strip_tags($htmlbody);
	
	$this->multiPartMail($email,$subject,$htmlbody,$txtbody,$from,'noreply@childsurvival.net');
	
	$this->inlinemessage('The Article has been sent to '.$email,'valid');
}

$this->loadstyles('backoffice');
$this->loadplugin('classForm/class.form');

$artid = $_GET['article'];

$emailform = new form();

$emailform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'500',
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$emailform->addHidden('emailsave','emailsave');
$emailform->addHidden('aid',$artid);

$emailform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Send Article as Email</h1> </td></tr></table>');

$emailform->addTextbox('Receiver\'s Name','rname','',array('required'=>true));
$emailform->addTextbox('Receiver\'s Subject','rsubject','',array('required'=>true));

$emailform->addEmail('Enter Receiver\'s Email','rmail','',array('required'=>true));

$emailform->addButton('Send Article');

$emailform->render();
?>