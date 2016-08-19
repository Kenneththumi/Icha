<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//load the encryption script
$this->loadplugin('encryption/encrypt');

$cipher = new encryption;
$link = new navigation;

$querystr = $cipher->decrypt($_GET['enquery']);

//get the columns - $toolbarparameters['tablecolumns']
$sent_columns = $cipher->decrypt($_GET['tablecolumns']);
$columns = explode(',', $sent_columns);

//display db tables
$sent_dbtables = $cipher->decrypt($_GET['dbtables']);
$dbtables = explode(',', $sent_dbtables);

//display the db fields
$sent_dbfields = $cipher->decrypt($_GET['dbfields']);
$dbfields = explode(';', $sent_dbfields);

$primary_key = $this->get_primary_key($dbtables[0]); 

//var_dump($columns);
//echo '<br/><br/>';
//var_dump($dbtables);
//echo '<br/><br/>';
//var_dump($dbfields);
//exit;

$searches = $this->runquery($querystr,'multiple','all');
$searchcount = $this->getcount($searches);
            //$this->print_last_query(); exit;
            
            $pagetable .= '<table width="100%" border="0" cellpadding="7" class="tablelist">
                        <tr class="tabletitle">
                        <td></td>';

                        //$count = 0;
                        foreach($columns as $value )
                        {
                            $pagetable .= '<td>'.$value.'</td>';
                        }

                        $pagetable .= '</tr>';

                
                for($t=1; $t<=$searchcount; $t++)
                {                   
                        $query_fetch = $this->fetcharray($searches);              

                        $pagetable .= '<tr '.($t%2==0 ? 'class="item"' : 'class="item2"').' rel="'.$this->shortentxt($query_fetch[0],30).'" >
                                <td></td>';
                        
                        $colcount = 0;
                        foreach($columns as $colvalue)
                        {
                            $colposition = array_search($colvalue,$columns);
                            
                            $rawfield = $dbfields[$colposition];
                            $field = explode('.',$rawfield);
                            
                            if(count($field)<='2')
                            {
                                if($field[1]=='date')
                                {
                                    $value = date('d-m-Y',$query_fetch[$field[0]]);
                                }

                                if($field[1]=='text')
                                {
                                    $value = $query_fetch[$field[0]];
                                }
                            }
                            else
                            {
                                //check if the field has brackets () which will indicate reverse ordering of keys
                                if(strpos($field[2],'(')!==false)
                                { //if the brackets are there
                                   
                                    $otable = $this->findText('(',')',$field[2]);
                                    $position = array_search($otable, $dbtables);

                                    if ($position !== false) {
                                        $primary_key2 = $this->get_primary_key($dbtables[$position]);
                                        
                                        $querystr2 = "SELECT ".$field[0]." FROM ".$otable." WHERE ".$primary_key2."='".$query_fetch[$primary_key2]."'";
                                        $query = $this->runquery($querystr2,'single'); 
                                        
                                        $value = $query[$field[0]];
                                    } 
                                }
                                else
                                {//if not
                                    $tableparameters['dbtables'] = $dbtables;
                                    require ABSOLUTE_PATH.'components/view/secondary.tables.query.php';

                                    if(strpos($field[1],'|')!==false){
                                        
                                        $spl_field = explode('|',$field[1]);

                                        //if its a number
                                        if($spl_field[0]=='number')
                                        {
                                            //if the number is denoting currency
                                            if($this->findText('[',']',$spl_field[1])=='currency')
                                            {
                                                //get the default currency
                                                $cur = $this->runquery("SELECT currencyid, currencycode FROM currency WHERE status='default'",'single');

                                                $value = $cur['currencycode'].' '.number_format($value, 2);

                                                //if the value zero
                                                if($value == $cur['currencycode'].' 0.00')
                                                {
                                                    $value = 'No Charge';
                                                }
                                            }
                                        }
                                        //more will be added for other scenarios
                                    }
                                }
                            }

                            $pagetable .= '<td>'.$value.'</td>';
                            
                        }
                        
                        $pagetable .= '</tr>';
                }

                $pagetable .= '</table>';
            
            echo '<html>';
            echo '<head>';
            template::loadstyles('print');
            echo '</head>';
            
            echo '<body onload="window.print()">';
            echo '<h1>'.$cipher->decrypt($_GET['printtitle']).'</h1>';
            echo $pagetable;
            echo '</body>';
            echo '</html>';
