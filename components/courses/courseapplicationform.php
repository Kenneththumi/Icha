<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

echo '<link rel="stylesheet" type="text/css" href="'.PLUGIN_PATH.'/realperson/css/jquery.realperson.css">';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/realperson/js/jquery.plugin.js"></script>';
echo '<script type="text/javascript" src="'.PLUGIN_PATH.'/realperson/js/jquery.realperson.js"></script>';

echo "<script>
        $(function() {
                $('#defaultReal').realperson();
        });
     </script>";

$link = new navigation;

$application = new form;
$application->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "id" => 'cform',
                                  "width"=>'97%',
                                  "map" => array(1,2,2,2,2,1,2,2,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "captchaTheme"=>'clean',
                                  "action" => '?content=com_courses&folder=same&file=registerstudent'
                                  ));

$application->renderHead();

$application->addHidden('cmdsave', 'cmdsave');
$application->addHidden('course', $_GET['cid']);
$application->addHidden('studymode','online');
$application->addHidden('url',$link->geturl());

$application->addHTML('<h2>Apply for '.$course['coursename'].'</h2>');

$application->addTextbox('First Name', 'fname', '',array('required'=>true,'class'=>'required'));
$application->addTextbox('Last Name', 'lname', '',array('required'=>true,'class'=>'required'));

$application->addTextbox('ID or Passport No.', 'idno','',array('required'=>true,'class'=>'required'));
$application->addTextbox('Mobile No', 'mobile','',array('required'=>true,'class'=>'required'));

$application->addEmail('Email Address', 'email', '',array('required'=>true,'class'=>'required'));
$application->addCountry('Nationality', 'nation', '',array('required'=>true,'class'=>'required'));

$application->addTextbox('Organisation', 'org', '',array('required'=>true,'class'=>'required'));
$application->addTextbox('Organisation Address', 'orgaddress', '');

$application->addHTML('<p><strong>Emergency Contacts</strong></p>');

$application->addTextbox('First Name', 'emergencyfname', '',array('required'=>true,'class'=>'required'));
$application->addTextbox('Last Name', 'emergencylname', '',array('required'=>true,'class'=>'required'));

$application->addTextbox('Mobile No', 'emergencymobile','',array('required'=>true,'class'=>'required'));
$application->addEmail('Email Address', 'emergencyemail', '',array('required'=>true,'class'=>'required'));

$application->addSelect('How did you hear of us?', 'howe', '',
    ['word_of_mouth'=>'Word of Mouth',
        'print' => 'Newspapers or Magazines',
        'online' => 'Online']);

$application->addTextbox('Enter the security letters below','defaultReal','',array('class'=>"required",'id'=>'defaultReal'));

$application->addButton('Continue', 'submit');

$application->render();

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

    $(".redline").first().focus();

    return isFormValid;
});
});');
