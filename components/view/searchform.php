<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

echo '<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>';

$search = new form;
$search->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'98%',
                              "map" => $tableparameters['searchmap'],
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action"=>'',
                              "id"=>'sform'
                              ));

$search->addHidden('formsearch','formsearch');

$search->addHTML('<h1>'.$tableparameters['formtitle'].'</h1>', array("nodiv" => 1));

//generate the search fields
$count = 0;
foreach($tableparameters['searchfields'] as $value){
    
    $date = false;
    $userfriendlyname = $tableparameters['searchfields'][$count];
    $fieldname = strtolower(str_replace(' ','',$tableparameters['searchfields'][$count]));
    
    if($tableparameters['searchfieldtypes'][$count]=='textbox'){
        $search->addTextbox($userfriendlyname,$fieldname,'');
    }
    elseif($tableparameters['searchfieldtypes'][$count]=='select'){
        $search->addSelect($userfriendlyname, $fieldname, 'Select '.$userfriendlyname,$tableparameters[$userfriendlyname.'_values']);
    }
    elseif(stristr($tableparameters['searchfieldtypes'][$count],'date')==TRUE){
        $search->addTextbox($userfriendlyname,$fieldname,'',['class'=>'searchdate']);
        $date = true;
    }
    
    $count++;
}

//generate the table column choices
$search->addHTML('<hr>');
$search->addCheckbox('Please select columns to include in results', 'tablecolumns',$tableparameters['tablecolumns'], $tableparameters['tablecolumns']);

$search->addButton('Start Filter','submit',array("id"=>"reportbutton"));

$search->render();

if($date==true){
    $this->wrapscript('    
        $(function() {
            $( "input.searchdate" ).datepicker({dateFormat:"M d, yy"});
        });');
}
