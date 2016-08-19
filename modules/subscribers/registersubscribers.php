<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(isset($_POST['cmd']))
{
	$link = new navigation;
	
	if(($_POST['fullname']=='')||($_POST['email']==''))
	{
		redirect('?content=com_frontpage&msgerror=Please_fill_in_required_subscription_fields');
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
		$id = $this->previd('subscribers','subid');
            
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
                
                
                $msg = '<p>Greetings,</p>
                <p>Congratulations, you have been subscribed for the '.($categoryid=='' ? 'ICHA' : $category['categoryname']).' updates.</p>
                <p>Thanks</p><p></p>
                <p>To unsubscribe, <a href="'.SITE_PATH.'?content=mod_subscribers&folder=same&file=setstatus&status=no&id='.$id.'&alert=yes" target="_blank">click here</a></p>';

                $stripmsg = strip_tags($msg);

                //send email to subscriber
                $this->multiPartMail($_POST['email'],'New User Subscription on '.date('d-m-Y'),$msg,$stripmsg,MAILFROM,SITENAME);

                $this->inlinemessage('The subscription has been saved','valid');
		
		redirect('?content=com_frontpage&msgvalid=Your_subscription_has_been_saved');
	}
	else
	{
		$this->print_last_error();
	}
}
?>
