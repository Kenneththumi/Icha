<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

$this->loadscripts();
$this->wrapscript("$(document).ready(function() {
                        function displaySelection(){
                        
                                var values = $('#parentSelect').val() || [];

                                $('#parentsdiv').load('components/admin/navigation/linkParents.php?values='+values.join(\"_\"));
                                //$('#parentsdiv').html('Link Parents: '+values.join(\", \"));
                        }

                        $('#parentSelect').change(displaySelection);
                });");

$mQuery = $this->rawquery("SELECT * FROM menugroups ORDER BY menuname ASC");
$mCount = $this->getcount($mQuery);

$names[''] = 'Select Menu Name';
for($j=1; $j<=$mCount; $j++)
{
    $mArray = $this->fetcharray($mQuery);

    if($mArray['menuname']!='')
    {		
        $names[$mArray['groupid']] = $mArray['menuname'];
    }
}

$parents = $this->runquery("SELECT * FROM menus",'multiple','all');
$parentCount = $this->getcount($parents);

$parentsArray[''] = 'No Parent';

for($k=1; $k<=$parentCount; $k++)
{
	$parent = $this->fetcharray($parents);
	
	$parentsArray[$parent['menuid']] = $parent['linkname'].' ['.$parent['menugroup'].']';
}

//get accesslevels
$levels = $this->runquery("SELECT * FROM accesslevels",'multiple','all');
$levelcount = $this->getcount($levels);

for($l=1; $l<=$levelcount; $l++)
{
	$level = $this->fetcharray($levels);
	
	$accessarray[$level['accessid']] = $level['displayname'];
}

$linkform = new form;

$linkform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "preventJQueryLoad" => true,
								  "preventJQueryUILoad" => true,
								  "action"=>''
								  ));

$linkform->addHidden('linksave','linksave');

$linkform->addHTML('<table width="100%">
				   <tr class="producttable">
				   <td>
				   <h1 class="articleTitle">New Menu Link</h1> 
				   </td>
				   </tr>
				   </table>');

$linkform->addTextbox('Link Name','lname',$menu['linkname'],array('required'=>true));
$linkform->addTextbox('Link URL','lurl',$menu['menulink'],array('required'=>true));

$linkform->addSelect('Current Menu Names','mnames','',$names);

$linkform->addSelect('Link Parents : <div id="parentsdiv">Link Parents: <strong>None Selected</strong></div>','parents[]','',$parentsArray,array('multiple'=>'multiple','size'=>'5','id'=>'parentSelect'));

$linkform->addSelect('Select AccessLevel','accesslevels','',$accessarray,array('multiple'=>'multiple','size'=>'5','required'=>true));

$linkform->addTextbox('Title','title',$menu['title'],array('required'=>false));
$linkform->addTextarea('Description','desc',$menu['description'],array('required'=>false));

$linkform->addRadio("Default Menu Item:", "defaultitem", ($level['default']=='' ? 'no' : 'yes'), array("yes", "no"));

$linkform->addButton('Save New Link');

//form processing
if(isset($_POST['linksave'])){
    echo 'here';
	if($_POST['mnames']!=''){
		$fnames = strtolower($_POST['mnames']);
		$mnames = str_replace(' ','',$fnames);
		
		$orderchk = $this->runquery("SELECT linkorder FROM menus WHERE menugroup='".$mnames."' ORDER BY linkorder DESC",'single');
		
		$order = ($orderchk['linkorder']+1);
	}
	else{
		$this->inlinemessage('Please select or create a menu name','error');
		$linkform->render();
		exit;
	}
	
	if(in_array('0',$_POST['parents'])==true){
		$rawparent = $this->removeArrayItem($_POST['parents'],'0');
	}
	else{
		$rawparent = $_POST['parents'];
	}
	
	$pLinks =  join(',',$rawparent);
	
	if($pLinks==''){
            $pLinks ='0';
	}
	
	$link = [
                  'linkname' => $_POST['lname'],
                  'menulink' => $_POST['lurl'],
                  'linkorder' => $order,
                  'menugroup' => $mnames,
                  'parentid'=>$pLinks,
                  'home' => $_POST['defaultitem']
                  ];
	
	if($this->dbinsert('menus',$link)){
		
            $this->inlinemessage('The new link has been saved','valid');
            $linkform->render();
	}
        else{
            $this->print_last_error();
        }
}
else{
	$linkform->render();
}
