<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadplugin('classForm/class.form');

$step1form = new form;

$step1form->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'98%',
								  "map" => array(1,2,1,3,2,1,3),
								  "preventJQueryLoad" => true,
								  "preventJQueryUILoad" => true,
                                                                  "preventDefaultCSS" => false,
								  "action"=>$link->urlreplace('one','two').'&msgvalid=Now_enter_your_vehicle_details'
								  ));

$step1form->addHidden('step', 'one');

$step1form->addHTML('<p>Please enter your personal details below:</p>');

$step1form->addTextbox('Full Names','ful1name',$_POST['fullname'],array('required'=>true));
$step1form->addEmail('Email Address','email',$_POST['email'],array('required'=>true));

$step1form->addHTML('<strong>Date of Birth</strong>');

$step1form->addSelect('Day',$this->strClean('Day'),$step[$this->strClean('Day')],array('Select Day','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'),array('required'=>true));
$step1form->addSelect('Month',$this->strClean('Month'),$step[$this->strClean('Month')],array('Select Month','January','February','March','April','May','June','July','August','September','October','November','December'),array('required'=>true));
$step1form->addSelect('Year',$this->strClean('Year'),$step[$this->strClean('Year')],array('Select Year','2013','2012','2011','2010','2009','2008','2007','2006','2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994','1993','1992','1991','1990','1989','1988','1987','1986','1985','1984','1983','1982','1981','1980','1979','1978','1977','1976','1975','1974','1973','1972','1971','1970','1969','1968','1967','1966','1965','1964','1963','1962','1961','1960','1959','1958','1957','1956','1955'),array('required'=>true));

$step1form->addTextbox('ID or Passport No',$this->strClean('ID or Passport No'),$step[$this->strClean('ID or Passport No')],array('required'=>true));
$step1form->addTextbox('Mobile Number',$this->strClean('Mobile Number'),$step[$this->strClean('Mobile Number')],array('required'=>true));

$step1form->addHTML('<p><strong>Postal Address</strong></p>');

$step1form->addTextbox('P.O.Box',$this->strClean('P O Box'),$step[$this->strClean('P O Box')],array('required'=>true));
$step1form->addTextbox('Postal Code',$this->strClean('Postal Code'),$step[$this->strClean('Postal Code')],array('required'=>true));
$step1form->addSelect('Town',$this->strClean('Town'),$step[$this->strClean('Town')],array('Select Town','Baragoi',
'Bungoma','Busia','Butere','Dadaab','Diani Beach','Eldoret','Embu','Garissa','Gede','Hola','Homa Bay','Isiolo','Kajiado',   'Kakamega','Kakuma','Kapenguria','Kericho','Kiambu','Kilifi','Kisii','Kisumu','Kitale','Lamu','Langata','Lodwar','Lokichoggio', 'Loyangalani','Machakos','Malindi','Mandera','Maralal','Marsabit','Meru','Mombasa','Moyale','Mumias','Muranga','Nairobi',    'Naivasha','Nakuru','Namanga','Nanyuki','Naro Moru','Narok','Nyahururu','Nyeri','Ruiru','Shimoni','Takaungu','Thika','Vihiga',   'Voi','Wajir','Watamu','Webuye','Wundanyi'),array('required'=>true));

$step1form->addButton('Proceed to Course Details','submit',array('id'=>'stepButton'));

$step1form->render();

?>
