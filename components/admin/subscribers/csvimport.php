<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadscripts();

$this->loadplugin('classForm/class.form');
$this->loadstyles('backoffice');

if(isset($_POST['csvdoc']))
{
	//$this->loadplugin('fileUpload/manualUpload');
	include_once(ABSOLUTE_PATH.'plugins/fileUpload/fileupload.php');
	
	if($filename!='FALSE')
	{		
		$this->wrapscript("$(document).ready(function() 
					{						
						$(\"#progressbar\").hide();
						
					});");
		
		$csvform = new form();
	
		$csvform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
										  "width"=>'500',
										  "preventJQueryLoad" => true,
										  "preventJQueryUILoad" => true,
										  "action"=>''
										  ));
		
		$csvform->addHidden('csv','csvdoc');		
		$csvform->addHidden('filename',$filename);
		
		$csvform->addHTML('<table width="100%"><tbody><tr class="producttable"><td><h1>'.ucfirst($filename).' - Step 2 of 2</h1> <p>This process can take upto several minutes, please bear with it.<p></td>    
		</tr>
		</tbody></table>');
		
		$csvform->addHTML($doclist.'<div id="attachcsv"><div id="fileholder">CSV Doc: '.$filename.'</div></div>');
		$csvform->addButton('Process Excel File');
		
		$csvform->render();
	}
}
elseif(isset($_POST['csv']))
{	
	if(isset($_POST['filename']))
	{
		if(!isset($_POST['from']))
		{
			//include the csv business processing function
			include_once(ABSOLUTE_PATH.'components/admin/subscribers/csvprocessor.php');
			
			$id = csv_to_mysql(ABSOLUTE_PATH.'media/csv/'.$_POST['filename']);
		}

		if($id=='timeout')
		{
			echo '<form style="padding: 0; margin: 0;" action="" method="post" id="myform">
<table width="100%" cellpadding="5">
				<tbody>
				<tr class="producttable">
				  <td width="14%" rowspan="2"><img src="'.STYLES_PATH.'ichatemplate/images/icons/red_clock.png" width="100" height="100" /></td>
				  <td width="86%">
				    <input type="hidden" value="csvdoc" name="csv" />
				    <input type="hidden" value="'.$_POST['filename'].'" id="filename" name="filename" />
				    <input type="hidden" value="'.$_POST['filename'].'" id="filename" name="filename" />
			      <h3>System Execution time has run out.</h3> <p>Click the <strong>&quot;Process CSV&quot;</strong> to continue <strong>'.$_POST['filename'].'</strong> processing</p></td>    
				  </tr>
				  <tr class="producttable">
					<td align="right">
					<div class="pfbc-buttons">
					<input type="submit" value="Process CSV">
					</div>
					</td>
				  </tr>
  </tbody>
</table>
				</form>';
		}
		else
		{
			$this->inlinemessage('The CSV document has been processed and stored','valid');
		}
	}
	else
	{
		$this->inlinemessage('Please upload a CSV document','error');
	}
}
else
{	
	$csvform = new form();
	
	$csvform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
									  "width"=>'98%',
									  "preventJQueryLoad" => true,
									  "preventJQueryUILoad" => true,
									  "action"=>'',
									  "enctype"=>'multipart/form-data'
									  ));
	
	$csvform->addHidden('csvdoc','csvdoc');
	$csvform->addHidden('filetype','.csv');
	
	if(isset($_GET['from']))
	{
		$csvform->addHidden('from','stickers');
	}
	
	//enter all allowed filtypes in a list, items separated by commas
	$csvform->addHidden('allowedfiletypes','csv');
	
	$csvform->addHTML('<table width="100%"><tbody><tr class="producttable"><td><h1>Excel Spreadsheet Processing (.csv) - Step 1 of 2</h1> <p>This process can take upto several minutes, please bear with it.<p></td>    
	</tr>
	</tbody></table>');
	
	$csvform->addFile('Click <strong>Browse</strong> to upload Excel file','userfile',array('id'=>'fileupload'));
	
	$csvform->addButton('Upload Excel File');
	
	$csvform->render();
}