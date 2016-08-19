<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

$this->wrapscript("$(document).ready(function(){
                                                
                        $('.searchinput').val('Search all ICHA');
                        $('.searchinput').click(function(){
                                        $('.searchinput').val('');
                                    });

                        $('.searchinput').blur(function(){
                         if ($(this).val() == ''){
                           $(this).val('Search all ICHA');
                           }
                         });
                });");

$searchform = new form();

$searchform->setAttributes(array(
                                 "includesPath" => PLUGIN_PATH.'/classForm/includes',
                                 "width" => '100%',
                                 "noAutoFocus" => 1,
                                 "preventJQueryLoad" => true,
                                 "preventJQueryUILoad" => true,
                                 "preventDefaultCSS" => true,
                                 "action"=>'?content=mod_search&folder=same&file=searchresults&stype=simple',
                                 "id"=>'searchform'
                                 ));

$searchform->addHidden("cmd","search");

$searchform->addTextBox('','searchbox','',array('class'=>'searchinput'));

$searchform->addButton('');
$searchform->render();
