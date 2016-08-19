<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<link rel="stylesheet" type="text/css" href="'.PLUGIN_PATH.'/realperson/css/jquery.realperson.css">';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/realperson/js/jquery.plugin.js"></script>';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/realperson/js/jquery.realperson.js"></script>';

echo "<script>
        $(function() {
                $('#defaultReal').realperson();
        });
     </script>";

$link = new navigation();
$contactform = new form();

if(isset($_POST['cmdsave'])){
    
    include(ABSOLUTE_PATH.'plugins/realperson/jquery.realperson.php');
    
    if (rpHash($_POST['defaultReal']) == $_POST['defaultRealHash']) { 
        
        //send the email
        $emailtxt = '
Greetings,

This is an enquiry sent from the ICHA contact form

Enquirer Name: '.$_POST['fname'].' '.$_POST['lname'].'
Email: '.$_POST['email'].'
Telehone: '.$_POST['mobile'].'

Enquiry: '.$_POST['message'].'

Thanks '.SITENAME;

        $emailhtml = '<html><p>Greetings,</p><p>This is an enquiry sent from the ICHA contact form.</p>

        <p>Enquirer Name: '.$_POST['fname'].' '.$_POST['lname'].'</p>
        <p>Email: '.$_POST['email'].'</p>
        <p>telephone: '.$_POST['mobile'].'</p>
        <p>Enquiry: '.$_POST['message'].'</p>
        <p>Thanks <br/>'.SITENAME.'</p></html>';

        //send email
        $this->multiPartMail(MAILADMIN,'ICHA Enquiry',$emailhtml,$emailtxt,$_POST['email'],SITENAME);

        redirect($link->geturl().'&msgvalid=Thanks_for_you_enquiry<br/>We will_get_back_as_soon_as_possible</p>');
    }
    else{
        
        $this->inlinemessage('Please enter the correct security letters','error');
    }
}

if(isset($_POST['smallform'])){
    
    $names = explode(' ', $_POST['names']);
    
    $this->inlinemessage('Please continue with feedback details','valid');
}

$contactform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'95%',
                                      "map"=>array(1,2,1,1,1),
                                      "preventJQueryLoad" => true,
                                      "preventJQueryUILoad" => true,
                                      "action"=>'',
                                      "captchaTheme"=>'clean',
                                      "id"=>'contactform'
                                      ));

$contactform->renderHead();

$contactform->addHidden('cmdsave', 'cmdsave');

$contactform->addHTML('<p>
<p><strong>International Centre for Humanitarian Affairs</strong>,</p>
<p>Email Address: info@icha.net</p>
<p>Phone: +254703037563</p>
</p>');

$contactform->addTextbox('*First Name', 'fname', $names[0],array('class'=>"required col-xs-12"));
$contactform->addTextbox('*Last Name', 'lname', $names[1],array('class'=>"required"));

$contactform->addTextbox('*Email Address', 'email', $_POST['email'],array('class'=>"required"));
$contactform->addTextbox('*Mobile No', 'mobile','',array('class'=>"required"));

$contactform->addTextarea('Message or Enquiry','message',$_POST['message']);
$contactform->addTextbox('Enter the security letters below','defaultReal','',array('class'=>"required",'id'=>'defaultReal'));

$contactform->addButton('Send Feedback','submit');

$contactform->render();

$this->wrapscript('
    
    function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if( !emailReg.test( $email ) ) {
        return false;
      } else {
        return true;
      }
    }  
    
    function checkDate($date)
    {
        if($date == "Click to Select Date..."){
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function(){
     $("#contactform").submit(function(){
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