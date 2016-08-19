<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

class category extends database
{
	//the $this object/variable wil be extracted from the initialized database object	
	function addsubcategory($name,$imgid,$catid)
	{
		$real_name = mysql_real_escape_string($name);
		
		$add_string = "INSERT INTO subcategories(categoryname,imgid,catid) VALUES ('$real_name',$imgid,'$catid')";
		
		$add_query = $this->runquery($add_string);
		
		if($add_query)
		{
			return true;
		}
		else
		{
			return mysql_error();
		}
	}
	
	function addcategory($name,$template)
	{
		$realname = mysql_real_escape_string($name);
		$realtemplate = mysql_real_escape_string($template);
		
		$addquery = $this->runquery("INSERT INTO categories(categoryname,templatename) VALUES('$realname','$realtemplate')");
		
		if($addquery)
		{
			return $this->previd('categories','catid','desc');
		}
		else
		{
			echo mysql_error();
		}
	}
	
	function returncategory($id)
	{
		$catquery = $this->runquery("SELECT categoryname FROM categories WHERE catid='".$id."'",'single');
		
		return $catquery['categoryname'];
	}
}
?>