<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//$this->loadplugin('classForm/class.form');
//$this->loadextraClass('phpexcel');

if(isset($_POST['formsearch']))
{
    $string = rtrim($search_string,', ');
    $title = $tableparameters['formtitle'].' for '.  str_replace('__','_',preg_replace('/[^A-Za-z0-9\-]/', '_',strip_tags($string)));
}
else
{
    $title = $tableparameters['exceltitle'];
}
$strip_title = strtolower($this->strClean(strip_tags($title)));

$expform = new form;
$expform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                              "width"=>'100%',
                              "preventJQueryLoad" => true,
                              "preventJQueryUILoad" => true,
                              "action" => SITE_PATH.'components/view/exportsearch.php',
                              "target"=>'_blank'
                              ));

$expform->addHidden('pagetitle',$title);
$expform->addHidden('filename',$strip_title);

$expform->addHidden('columns',$exp_columns);
$expform->addHidden('rows',$exp_rows);


$expform->addHTML('<div class="excelholder"><h1>'.$title.'.xls</h1> <input type="submit" id="excelbutton" value="Download File"></div>');

$expform->render();
?>
