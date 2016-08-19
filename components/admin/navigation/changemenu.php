<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

$this->loadscripts();

$this->wrapscript("$(document).ready(function() 
					{
						function displaySelection() 
						{							
							var values = $('#parentSelect').val() || [];	

							$('#parentsdiv').load('components/admin/navigation/linkParents.php?values='+values.join(\"_\"));
	

							//$('#parentsdiv').html('Link Parents: '+values.join(\", \"));
						}		

						$('#parentSelect').change(displaySelection);
					});");



$menuid = $_GET['menuid'];

$menu = $this->runquery("SELECT * FROM menus WHERE menuid='$menuid'",'single');

//get parents
$parents = $this->runquery("SELECT * FROM menus WHERE menugroup='".$menu['menugroup']."'",'multiple','all');
$parentCount = $this->getcount($parents);

$parentsArray[''] = 'No Parent';

for($k=1; $k<=$parentCount; $k++)
{
	$parent = $this->fetcharray($parents);

	$parentsArray[$parent['menuid']] = $parent['linkname'].' ['.$parent['menugroup'].']';
}

//get the menugroup
$names = $this->runquery("SELECT * FROM menugroups ORDER BY menuname ASC",'multiple','all');
$namesCount = $this->getcount($names);

if(isset($_GET['menuid']))
{
	$namesArray[$menu['menugroup']] = @navigation::getgroupname($menu['menugroup']);
}
else
{
	$namesArray[''] = 'Select Menu Group';
}

for($t=1; $t<=$namesCount; $t++)
{
	$nameFetch = $this->fetcharray($names);

	$namesArray[$nameFetch['groupid']] = $nameFetch['menuname'];
}

if(strpos($menu['parentid'],',')!=0)
{	
	$pids = explode($menu['parentid']);

	foreach($pids as $key=>$value)
	{
		$getParent = $this->runquery("SELECT * FROM menus WHERE menuid='$value'",'single');

		$parentLinks .= '<strong>'.ucfirst($getParent['linkname']).'</strong>, ';
	}
}
elseif(strpos($menu['parentid'],',')==0&&is_numeric($menu['parentid'])&&$menu['parentid']!=0)
{
	$getParent = $this->runquery("SELECT * FROM menus WHERE menuid='".$menu['parentid']."'",'single');

	$parentLinks = '<strong>'.ucfirst($getParent['linkname']).'</strong><input name="plinks" type="hidden" id="plinks" value="'.$getParent['linkname'].'" />';
}
else
{
	$parentLinks = '<strong>No Parents Selected</strong><input name="plinks" type="hidden" id="plinks" value="" />';
}

//get accesslevels
$levels = $this->runquery("SELECT * FROM accesslevels",'multiple','all');
$levelcount = $this->getcount($levels);

for($l=1; $l<=$levelcount; $l++)
{
	$level = $this->fetcharray($levels);
	
	$accessarray[$level['accessid']] = $level['displayname'];
}

$urlform = new form();

$urlform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "preventJQueryLoad" => true,
								  "preventJQueryUILoad" => true,
								  "action"=>''
								  ));

$urlform->addHidden('urlsave','urlsave');
$urlform->addHidden('menuid',$menuid);

$urlform->addHTML('<h1>Url Change for '.$menu['linkname'].'</h1>');

$urlform->addTextbox('Menu Name','mname',$menu['linkname'],array('required'=>true));

$urlform->addTextbox('Menu URL','murl',$menu['menulink'],array('required'=>true));

$urlform->addSelect('Parent Menu <div id="parentsdiv">Link Parents: '.$parentLinks.'</div>','parents[]','',$parentsArray,array('multiple'=>'multiple','size'=>'5','id'=>'parentSelect'));

$urlform->addSelect('Menu Group Name','menugroup',@navigation::getgroupname($menu['menugroup']),$namesArray,array('required'=>true));

$urlform->addSelect('Select AccessLevel','accesslevels','',$accessarray,array('multiple'=>'multiple','size'=>'5','required'=>true));

$urlform->addRadio("Enabled:", "enabled", ($menu['enabled']=='' ? 'no' : $menu['enabled']), array("yes", "no"));
$urlform->addHTML('<p></p>');

$urlform->addRadio("Default Menu Item:", "defaultitem", ($menu['default']=='' ? 'no' : $menu['default']), array("yes", "no"));

$urlform->addButton('Save URL Change');



if(isset($_POST['urlsave'])){

	$menuid = $_POST['menuid'];

	$murl = $_POST['murl'];
	

	if(in_array('0',$_POST['parents'])==true)

	{

		$rawparent = $this->removeArrayItem($_POST['parents'],'0');
	}
	
	else

	{

		$rawparent = $_POST['parents'];

	}

	

	$pLinks =  join(',',$rawparent);

	if($pLinks=='')
	{
		$pLinks ='0';
	}

	if(strpos($pLinks,',')!=0)
	{
            $update = array(
                            'linkname'=>$_POST['mname'],
                            'menulink'=>$_POST['murl'],
                            'parentid'=>$pLinks,
                            'menugroup'=>$_POST['menugroup'],
                            'enabled' => $_POST['enabled'],
                            'home' => $_POST['defaultitem']
                            );
	}
	else
	{
            $update = array(
                            'linkname'=>$_POST['mname'],
                            'menulink'=>$_POST['murl'],
                            'parentid'=>$pLinks,
                            'menugroup'=>$_POST['menugroup'],
                            'enabled' => $_POST['enabled'],
                            'home' => $_POST['defaultitem']
                            );
	}

	

	if($this->dbupdate('menus',$update,"menuid='$menuid'"))
	{
		$this->inlinemessage('The menu details have been changed.','valid');
	}
        else{
            
            $this->print_last_error();
        }
	
	$urlform->render();
}

else

{

	$urlform->render();

}

?>