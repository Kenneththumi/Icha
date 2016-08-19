<?php
define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/chmp/');
define('LOAD_POINT',1);

//require_once(ABSOLUTE_PATH.'includes/header.php');
include_once(ABSOLUTE_PATH.'classes/db.class.php');
include_once(ABSOLUTE_PATH.'plugins/encryption/encrypt.php');
include_once(ABSOLUTE_PATH.'plugins/encryption/rsaFunctions.php');

//initialize the db object
$dbase = new database;

//loads all the core files required for the app
$dbase->loadclasses();

$code = new encryption;

$query = $dbase->runquery("SHOW TABLES FROM m3db");

	
	$start = '<select name="tablenames" id="tablenames">';
	$options= '';
	while ($table = $dbase->fetchrow($query)) 
	{
   		$options .= '<option>'.$table[0].'</option>';
	}
	$end = '</select>';

$selectlist = $start.$options.$end;

$encrypt_form = '<form action="" method="post" name="encrypt_form" id="encrypt_form">
			  <table width="100%" border="0" cellspacing="1" cellpadding="10">
				<tr>
				  <td width="24%">Table Name </td>
				  <td width="76%">'.$selectlist.'</td>
				</tr>
				<tr>
				  <td>Table ID field</td>
				  <td><input name="id_field" type="text" id="id_field"></td>
			    </tr>
				<tr>
				  <td>Encrypt/Decrypt</td>
				  <td><select name="function" id="function">
				    <option value="en">Encrypt</option>
				    <option value="de">Decrypt</option>
					<option value="md5_en">MD_5 Encryption</option>
					<option value="plain_text">Plain Text</option>
					<option value="rsa_reup">RSA re_encrypt</option>
					<option value="rsa_en">RSA encrypt</option>
					<option value="rsa_de">RSA decrypt</option>
				    </select>
				  </td>
			    </tr>
				<tr>
				  <td>Table field to process </td>
				  <td><input type="text" name="table_field" id="table_field"></td>
				</tr>
				<tr>
				  <td>If "plain text", enter data to be inserted</td>
				  <td><input type="text" name="table_input" id="table_input"></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><input type="submit" name="en_submit" id="en_submit" value="Encrypt Value"></td>
				</tr>
			  </table>
			</form>';
			
if(isset($_POST['en_submit']))
{
	$table_name = $_POST['tablenames'];
	$table_field = $_POST['table_field'];
	$id = $_POST['id_field'];
	$input = $_POST['table_input'];
	
	$table_query = mysql_query("SELECT * FROM $table_name");
	$row_count = mysql_num_rows($table_query);
	
	while ($field = mysql_fetch_field($table_query))
	{
		if($field->name==$table_field)
		{
			for($i=1; $i<=$row_count; $i++)
			{
				$row_array = mysql_fetch_array($table_query);
				echo $field = $row_array[$table_field];
				$field_id = $row_array[$id];
				
				if(isset($field))
				{
					if($_POST['function']=='en')
					{
						$en_field =$code->encrypt($field);
					}
					else if($_POST['function']=='de')
					{
						$en_field = $code->decrypt($field);
					}
					else if($_POST['function']=='md5_en')
					{
						$en_field = md5($field);
					}
					else if($_POST['function']=='plain_text')
					{
						$en_field = $input;
					}
					else if($_POST['function']=='rsa_reup')
					{
						$decrypted_value = $code->decrypt($field);
						$en_field = $rsa->encrypt($decrypted_value, $keys[1], $keys[0], 5);
					}
					else if($_POST['function']=='rsa_en')
					{
						$en_field = $rsa->encrypt($field, $keys[1], $keys[0], 5);
					}
					else if($_POST['function']=='rsa_de')
					{
						$en_field = $rsa->decrypt($field, $keys[2], $keys[0]);
					}
					
					$update_query = mysql_query("UPDATE $table_name SET $table_field = '$en_field' WHERE $id = '$field_id'");
					
					if($update_query)
					{
						echo $table_field.' has been updated.<br/>';
					}
					else
					{
						echo mysql_error().'<br/>';
					}
				}
				else
				{
					continue;
				}
			}
		}
	}
}
else
{
	echo $encrypt_form;
}
?>