<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//this has been moved to a separate file because it is reused numerous times in the table.view.generator file

//check if the field has brackets () which will indicate reverse ordering of keys
if(strpos($field[2],'(')!==false)
{ //if the brackets are there

    $otable = $this->findText('(',')',$field[2]);
    $position = array_search($otable, $tableparameters['dbtables']);
    
    if ($position !== false) {
        $primary_key2 = $this->get_primary_key($tableparameters['dbtables'][$position]);
        
        $querystr2 = "SELECT ".$field[0]." FROM ".$otable." WHERE ".$primary_key2."='".$query_fetch[$primary_key2]."'";
        $query = $this->runquery($querystr2,'single'); 

        $value = $query[$field[0]];
    } 
}
elseif(strpos($field[2],'{')!==false)
{
    $ftable = explode(',',$this->findText('{','}',$field[2]));
    
    $core_table = $tableparameters['dbtables'][0];
    $primary_key1 = $this->get_primary_key($core_table);
    $foreignkey2 = $this->get_primary_key($ftable[1]);
    
    $querystr2 = "SELECT ". $ftable[1] . "." . $field[0] . " as " . $field[0] . " FROM " . $ftable[0];
    $querystr2 .= " INNER JOIN " . $core_table . " ON " . $ftable[0] . "." . $primary_key1 ." = " . $core_table . "." . $primary_key1;
    $querystr2 .= " INNER JOIN " . $ftable[1] . " ON " . $ftable[0] . "." . $foreignkey2 . " = " . $ftable[1] . "." . $foreignkey2;
    $querystr2 .= " WHERE " . $ftable[0] . "." . $primary_key1 . " = " . $query_fetch[$primary_key1];
    
    $query = $this->runquery($querystr2,'single'); 
    //$this->print_last_query();
    $value = $query[$field[0]];
}
else
{//if not
    $querystr2 = "SELECT ".$field[0]." FROM ".$field[2]." WHERE ".$primary_key."='".$query_fetch[$primary_key]."'";
    $query = $this->runquery($querystr2,'single');                                
    $value = $query[$field[0]];
}
?>
