<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');
$this->loadstyles('backoffice');

$link = new navigation();

//form processing part
if(isset($_POST['cmd']))
{
	$link = new navigation;
	
	if(($_POST['fullname']=='')||($_POST['email']==''))
	{
		redirect($link->geturl().'&msgerror=Please_fill_in_required_subscription_fields&alert=yes');
	}
	
	$save = array(
				  'name'=>$_POST['fullname'],
				  'email'=>$_POST['email'],
				  'catidsubscribed'=>$_POST['catid'],
				  'enabled'=>'yes',
				  'regdate'=>time()
				  );
	
	if($this->dbinsert('subscribers',$save))
	{
            $categoryid = $_POST['catid'];
            $category = $this->runquery("SELECT * FROM categories WHERE catid='$categoryid'",'single');

            //send email to Robert Davis
            $msg = '<p>Greetings,</p>
            <p>A user has subscribed for the '.($categoryid=='' ? 'ICHA' : $category['categoryname']).' updates. His/her details are:</p>
            <p>Subscriber Name: <strong>'.$_POST['fullname'].'</strong></p>
            <p>Email Address: <strong>'.$_POST['email'].'</strong></p>
            <p>Thanks</p>';

            $stripmsg = strip_tags($msg);

            $this->multiPartMail(MAILADMIN,'New User Subscription on '.date('d-m-Y'),$msg,$stripmsg,MAILFROM,SITENAME);

            $this->inlinemessage('The subscription has been saved','valid');
	}
	else
	{
		$this->print_last_error();
	}
}

//the form render section
$cname = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
$cCount = $this->getcount($cname);

$catArray['0'] = 'Pick Update Category';
for($j=0; $j<=($cCount-1); $j++)
{
	$caArray = $this->fetcharray($cname);
	$catArray[$caArray['catid']] = $caArray['categoryname'];
}

$subscribersform = new form();

$subscribersform->setAttributes(array(
								 "includesPath" => PLUGIN_PATH.'/classForm/includes',
								 "width" => '95%',
								 "noAutoFocus" => 1,
								 "preventJQueryLoad" => true,
								 "preventJQueryUILoad" => true,
								 "preventDefaultCSS" => false,
								 "action"=>'',
								 "id" => 'subscribeform'
								 ));

$subscribersform->addHidden("cmd","subscribe");

if(isset($_GET['cid']))
{
	$subscribersform->addHidden("catid",$_GET['cid']);
	
	$categoryid = $_GET['cid'];
	$category = $this->runquery("SELECT * FROM categories WHERE catid='$categoryid'",'single');
	
	$subscribersform->addHTML('<h1 class="articleTitle">'.str_replace('_',' ',ucfirst($category['categoryname'])).' Subscription</h1>');
}
else
{
	$subscribersform->addHTML('<img src="'.STYLES_PATH.'ichatemplate/images/subscribetoday.png" width="202" height="45" />;');
}

$subscribersform->addTextBox('Full Names: ','fullname','',array('required'=>true));
$subscribersform->addEmail('Email Address: ','email','',array('required'=>true));

if(!isset($_GET['cid']))
{
	$subscribersform->addSelect("Updates Category:",'catid','',$catArray);
}

$subscribersform->addButton('Subscribe Now','submit',array("id"=>'myformbutton'));
$subscribersform->render();
?>