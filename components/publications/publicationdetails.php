<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$publication = $this->runquery("SELECT * FROM publications WHERE publicationid='".$_GET['pid']."'",'single');

$author = $this->runquery("SELECT * FROM contributors WHERE contributorid = '".$publication['authorid']."'",'single');

echo '<h1 class="fullpubTitle">'.$publication['title'].'<p class="pubauthor">Author: '.$publication['author'].'</p></h1>';
echo '<div class="pubsholder">';

if($publication['ptype']=='researchpaper'){
    
    echo '<div class="sidepanes">';
    echo '<div class="tbpanes">';

    $cprice = @finances::findPrice($publication['publicationid'],'publication','raw');
    
    //if($cprice!='0')
    if($cprice=='0')
    {
        $params = array('id'=>$_GET['pid']);

        $this->loadmodule('downloadpublication','downloads',$params);
    }
    elseif(isset($_SESSION['username']))
    {
        $params['step1action'] = '?content=com_checkout&folder=same&file=processcheckout&step=1';
        $params['title'] = 'Express Checkout';
        $params['pid'] = $_GET['pid'];

        $this->loadmodule('checkout','expresscheckout',$params);
    }
    else
    {
        $params['step1action'] = '?content=mod_login&folder=same&file=userregister';
        $params['title'] = 'Register to download publication';

        $this->loadmodule('usersignup','login',$params);
    }

    echo '</div>';
    echo '</div>';
}

echo '<div class="innerpubscontent">';
    
    if($publication['ptype']=='researchpaper'){
        
        echo '<img src="'.DEFAULT_TEMPLATE_PATH.'/images/publication.png" class="pubsimg" >';
    }
    
    echo '<p class="fulltxt">'.$publication['body'].'</p>';
    
    echo '</div>';

echo '</div>';

echo '<div class="feedback">';

if(!isset($_POST['save'])){
    
    $this->loadplugin('classForm/class.form');

    $article = new form;
    $article->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'50%',
                                  "map" => array(1,1,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action"=>'',
                                  "id"=>'cform'
                                  ));

    $article->addHidden('save', 'save');
    $article->addHTML('<h3>Leave a comment</h3><p>(*) - the field is required</p>');

    $article->addTextbox('Guest Name*', 'name','',array('class'=>'required'));
    $article->addEmail('Email Address*', 'email','',array('class'=>'required'));

    $article->addTextarea('Message Body*','body','',array('class'=>'required'));
    $article->addButton('Send Feedback', 'submit', array('id'=>'saveButton'));
    
    $article->renderHead();
    $article->render();
    
    $this->wrapscript('
    
    function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if( !emailReg.test( $email ) ) {
        return false;
      } else {
        return true;
      }
    }  

    $(document).ready(function(){
     $("#cform").submit(function(){
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
    
    $(".date").each(function(){        
        var sentdate = $(this).val();
        if( !checkDate(sentdate)) { 
            $(this).addClass("pinkline");
        }
        else
        {
            $(this).removeClass("pinkline");
        }
    });

    $(".redline").first().focus();

    return isFormValid;
});
});');
}
else{
    
    //send the email

    $emailhtml = '<html>
        <p>Below are the feedback details:</p>

    <p>Name: '.$_POST['name'].'</p>
    <p>Email: '.$_POST['email'].'</p>
    <p>Message: '.$_POST['body'].'. <br/>'
    . 'Thanks <br/>'.SITENAME.'</p></html>';

    $emailtxt = strip_tags($emailhtml);
    
    //send email
    $email = $this->multiPartMail($publication['authoremail'],'Feedback fo your paper:'.$publication['title'],$emailhtml,$emailtxt,MAILFROM,SITENAME);

    if($email)
        $this->inlinemessage('Your feedback message has been sent','success');
    else
        $this->inlinemessage('Error sending to '.$contributor['emailaddress'],'error');
}
echo '</div>';