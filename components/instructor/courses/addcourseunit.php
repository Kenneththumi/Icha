<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

//the save function
if(isset($_POST['cmd']))
{
    $savestep = array(
                        'coursename' => $_POST['coursename'],
                        'description'=>$_POST['desc'],
                        'enabled' => ($_POST['enabled']=='1' ? 'Yes' : 'No'),
                        'publishdate' => strtotime($_POST['publishdate']),
                        'startdate' => strtotime($_POST['startdate']),
                        'enddate' => strtotime($_POST['enddate']),
                        'contributorid' => $_POST['createdby'],
                        'parentid' => $_POST['course']
                    );
            
            if(isset($_POST['courseunitid']))
            {
                $courseunitid = $_POST['courseunitid'];
                    
                $this->dbupdate('courses',$savestep,"courseid='".$courseunitid."'");
                
            }
            else
            {
                $this->dbinsert('courses', $savestep);
            }
            //$this->print_last_error();
            
    if(isset($_POST['courseunitid']))
    {
        redirect($link->urlreturn('Course Units').'&msgvalid=The_course_unit_has_been_edited');
    }
    else
    {
        redirect($link->urlreturn('Course Units').'&msgvalid=The_course_unit_has_been_added');
    }
    
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

//get the instructor's courses
$coursequery = $this->runquery("SELECT * FROM courses WHERE  contributorid='".$_SESSION['sourceid']."' ORDER BY courseid ASC",'multiple','all');
$coursecount = $this->getcount($coursequery);

$courselist = array(''=>'Select Course');
for($r=1; $r<=$coursecount; $r++)
{
    $course = $this->fetcharray($coursequery);
    $courselist[$course['courseid']] = $course['coursename'];
}

//load the contributors
$querystr = "SELECT contributorid, name FROM contributors ORDER BY contributorid DESC";

$queries = $this->runquery($querystr,'multiple','all');
$querycount = $this->getcount($queries);

if($_GET['task']!='edit')
{
    $contributors = array(''=>'Select Contributor');
}
else {
    $contributor = $this->runquery("SELECT contributorid,name FROM contributors WHERE contributorid='".$coursedetails['contributorid']."'",'single');
    $contributors = array($contributor['contributorid']=>$contributor['name']);
}

for($r=1; $r<=$querycount; $r++)
{
    $query = $this->fetcharray($queries);
    
    $contributors[$query['contributorid']] = $query['name'];
}

//edit 
if($_GET['task']=='edit')
{
    $id = $_GET['id'];
    
    $getunit = $this->runquery("SELECT * FROM courseunits WHERE courseunit_id='$id'",'single');
}

echo '<h1 class="pagetitle">Add Course Unit</h1>';

$unitform = new form;
$unitform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map"=>array(1,1,2,1,2),
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action" => ''
                              ));

$unitform->addHidden('cmd', 'cmd');
//$unitform->addHidden('instructor_id', $_SESSION['sourceid']);

if(isset($_GET['id']))
{
    $unitform->addHidden('courseunitid', $_GET['id']);
}

$unitform->addHTML('<h1>Enter course unit details below:</h1>');

$unitform->addTextbox('Course Name', 'coursename',$coursedetails['coursename'],array('required'=>true));
$unitform->addDate('Publish Date', 'publishdate', ($coursedetails['publishdate']=='' ? '' : date('M d, Y',$coursedetails['publishdate'])),array('class'=>'date'));
$unitform->addSelect('Linked Course','course',$getunit['courseid'],$courselist,array('required'=>true));

$unitform->addTextarea('Description', 'desc',$coursedetails['description'],array('class'=>'description'));

$unitform->addDate('Start Date', 'startdate', ($coursedetails['startdate']=='' ? '' : date('M d, Y',$coursedetails['startdate'])),array('class'=>'date','required' => true));
$unitform->addDate('End Date', 'enddate', ($coursedetails['enddate']=='' ? '' : date('M d, Y',$coursedetails['enddate'])),array('class'=>'date','required' => true));

$unitform->addSelect('Created By', 'createdby', $_SESSION['sourceid'], $contributors,array('required'=>true));
//$unitform->addTextbox('Course Price ('.$cur['currencycode'].')', 'price', $price['price'], array('class'=>'required'));

$unitform->addYesNo('Enabled', 'enabled', '1',array('required'=>true));

$unitform->addButton('Save Course Unit and Proceed >');

$unitform->render();

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
        if($date == "Click to Select Date...")
        {
            return false;
        }
        else
        {
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

/*
if($_GET['task']=='edit')
{
    $unitform->addHidden('unitid', $id);
}

$unitform->addHTML('<p></p>');

$unitform->addTextbox('Unit Name', 'unitname',$getunit['unitname'],array('required'=>true));
$unitform->addSelect('Hours per day', 'hours', $getunit['hours_per_day'], array(''=>'Select Duration','30min'=>'30min','1hr'=>'1hr','2hr'=>'2hr'));

$unitform->addSelect('Linked Course','course',$getunit['courseid'],$courselist,array('required'=>true));
$unitform->addTextarea('Description', 'desc',$getunit['description'],array('class'=>'description','required'=>true));

$unitform->addButton('Save Course Unit', 'submit');
$unitform->render();
 * 
 */
?>
