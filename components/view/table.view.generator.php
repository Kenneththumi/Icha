<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(!isset($_GET['alert'])){
    
    if(isset($tableparameters['pagetitle'])){
        echo '<h1 class="pagetitle">'.$tableparameters['pagetitle'].'</h1>';
    }
    
    //get the table primary
    $primary_key = $this->get_primary_key($tableparameters['dbtables'][0]); 
    
    //search form processing - shouldn't be here :-(
    if(isset($_POST['formsearch'])){
        
            //load the encryption script
            $this->loadplugin('encryption/encrypt');
            
            //generate the search fields
            $count = 0;
            foreach($tableparameters['searchfields'] as $value){
                
                $userfriendlyname = $tableparameters['searchfields'][$count];
                $fieldname = strtolower(str_replace(' ','',$tableparameters['searchfields'][$count]));
                
                $table_name = $tableparameters['dbtables'];
                $column_name = $tableparameters['searchdbcolumns'][$count];
                
                if(strpos($column_name,'.')!='0'){
                    
                    $column_name = explode('.', $column_name);
                }
                
                if($_POST[$fieldname]!=''&&$tableparameters['searchfieldtypes'][$count]=='textbox')
                {
                    if(!in_array($column_name[0], $table_name))
                    {
                        $condition .= $column_name.' LIKE \'%'.$_POST[$fieldname].'%\' AND ';                        
                    }
                    else 
                    {
                        $union_condition .= 'SELECT '.$column_name[2].' FROM '.$column_name[0].' WHERE '.$column_name[1].' LIKE \'%'.$_POST[$fieldname].'%\' AND ';
                    }
                    
                    $search_string .= '<strong>'.$userfriendlyname.'</strong>: '.$_POST[$fieldname].', ';
                }
                
                if($_POST[$fieldname]!=''&&$tableparameters['searchfieldtypes'][$count]=='select')
                {
                    if(!in_array($column_name[0], $table_name))
                    {
                        $condition .= $column_name.' = \''.$fieldname.'\' AND ';
                    }
                    else 
                    {
                        $union_condition .= 'SELECT '.$column_name[2].' FROM '.$column_name[0].' WHERE '.$column_name[1].' = \''.$_POST[$fieldname].'\' AND ';
                    }
                    $search_string .= '<strong>'.$userfriendlyname.'</strong>: '.$_POST[$fieldname].', ';
                }
                
                if($_POST[$fieldname]!=''&&$tableparameters['searchfieldtypes'][$count]=='start date'&&$_POST[$fieldname]!='Click to Select Date...')
                {
                    if(!in_array($column_name[0], $table_name))
                    {
                        $condition .= $column_name.' >= '.strtotime($_POST[$fieldname]).' AND ';
                    }
                    $search_string .= '<strong>'.$userfriendlyname.'</strong>: '.$_POST[$fieldname].', ';
                }
                
                if($_POST[$fieldname]!=''&&$tableparameters['searchfieldtypes'][$count]=='end date'&&$_POST[$fieldname]!='Click to Select Date...')
                {
                    if(!in_array($column_name[0], $table_name))
                    {
                        $condition .= $column_name.' <= '.strtotime($_POST[$fieldname]).' AND ';
                    }
                    $search_string .= '<strong>'.$userfriendlyname.'</strong>: '.$_POST[$fieldname].', ';
                }
                
                $count++;
            }
            
            //generate the query
            $condition = rtrim($condition,' AND ');
            
            if(isset($union_condition))
            {
                $union_condition = rtrim($union_condition, ' AND ');
                
                if($condition=='')
                {
                    $condition = $primary_key;
                }
                else
                {
                    $condition = $condition.' AND '.$primary_key;
                }
                
                $querystr = 'SELECT * FROM '.$tableparameters['dbtables'][0].' WHERE '.$condition.' IN ('.$union_condition.')';
            }
            else 
            {
                $querystr = 'SELECT * FROM '.$tableparameters['dbtables'][0].' WHERE '.$condition;
            }
            
            //set the search variable to stop the generation 
            $search_set = 1;
            
            $queries = $this->runquery($querystr,'multiple','all');
            $rowcount = $this->getcount($queries);
            
            //configure the table columns
            $columns = $_POST['tablecolumns'];
            
            //generate the exportsearch form hidden fields            
            //join the columns for the export search form section
            $exp_columns = join(',',$_POST['tablecolumns']);
        }
    else{
        
        //convert the sent query date values
        $querystr = $tableparameters['querydata']['query'];

        //check for nested table
        if(isset($tableparameters['children']))
        {
            $querystr .= (isset($tableparameters['children']['checkjoin']) ? $tableparameters['children']['checkjoin'] : '').' WHERE '.$tableparameters['children']['checkcondition'];
        }
        
        //check for ordering 
        if(isset($tableparameters['querydata']['orderby'])){
            
            $querystr .= ' ORDER BY '.$tableparameters['querydata']['orderby'][0].' '.$tableparameters['querydata']['orderby'][1];
        }

        $queries = $this->runquery($querystr,$tableparameters['querydata']['querytype'],$tableparameters['querydata']['rpg'],$tableparameters['querydata']['pgno'],$tableparameters['querydata']['action']);
        //$this->print_last_query();
        $rowcount = $this->getcount($queries);

        //configure the table columns
        $columns = $tableparameters['tablecolumns'];

        //join the columns for the export search form section
        $exp_columns = join(',',$tableparameters['tablecolumns']);
    }
        
    if($tableparameters['querydata']['show']=='yes'){
        $this->print_last_error();
    }
        
        if($rowcount >= 1){
            
            //the deletion JQuery script
            $this->loadscripts('multipleDelete','yes');

            $this->wrapscript("$(document).ready(function(){
        
                            $(\".export\").hide();

                            $(\".exportopen\").click(function () {
                                    $(\".export\").animate({height: 'toggle'});
                             });

                            $(\".searchform\").hide();

                            $(\".searchopen\").click(function () {
                                    $(\".searchform\").animate({height: 'toggle'});
                             }); 

                             $('.confirmdeletion').click(function() {
                                    $.notification( 
                                            {
                                                 title: 'Confirm Deletion:',
                                                 img: \"plugins/owlnotifications/img/recyclebin.png\",
                                                 content: '<p class=\"deletiontext\">Are you sure you want to delete? <a href=\"'+$(this).attr('rel')+'\">Yes</a><a href=\"#\">No</a></p>',
                                                 fill: true,
                                                 border: false,
                                                 showTime: true
                                            }
                                    );
                            });

                    });");
            
            $pagetable .= '<table width="100%" border="0" cellpadding="7" class="tablelist">
                        <tr class="tabletitle">
                        <td '.(isset($tableparameters['children']) ? 'colspan="2"' : '').'><input type="checkbox" name="itemlist" class="checkall" ></td>';

                        //$count = 0;
                        foreach($columns as $value )
                        {
                            $pagetable .= '<td>'.$value.'</td>';
                        }

                        $pagetable .= '</tr>';

                $colcount = 0;
                $export_row_array = array();
                
                for($t=1; $t<=$rowcount; $t++)
                {                   
                        $query_fetch = $this->fetcharray($queries);              

                        $pagetable .= '<tr '.($t%2==0 ? 'class="even"' : 'class="odd"').' rel="'.$this->shortentxt($query_fetch[0],30).'" >
                                <td '.(isset($tableparameters['children']) ? 'colspan="2"' : '').'><input type="checkbox" name="item[]" value="'.$query_fetch[$primary_key].'" id="'.$t.'" class="chkItem"></td>';
                        
                        $colcheck = 0;
                        foreach($columns as $colvalue)
                        {
                            $colposition = array_search($colvalue,$columns);
                            
                            $field = $tableparameters['displaydbfields'][$colposition];
                            $field = explode('.',$field);
                                                                                   
                            if($field[1]=='int')
                            {
                                $value = (int)$query_fetch[$field[0]];
                            }

                            if($field[1]=='date')
                            {
                                $value = ($query_fetch[$field[0]]<='0' ? 'Not Specified' : date('d-m-Y',$query_fetch[$field[0]]));
                            }
                            
                            if($field[1]=='float')
                            {
                                $value = $query_fetch[$field[0]];
                            }

                            if($field[1]=='text')
                            {
                                $value = $query_fetch[$field[0]];
                            }
                            
                            if($field[1]=='boolean')
                            {
                                $value = $query_fetch[$field[0]]==0 ? 'false' : 'true';
                            }

                            if($field[1]=='longtext')
                            {
                                $value = $this->shortentxt(strip_tags($query_fetch[$field[0]]),100);
                            }

                            if(count($field)>='3'){
                                
                                require ABSOLUTE_PATH.'components/view/secondary.tables.query.php';

                                if(strpos($field[1],'|')!==false)
                                {
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
                            
                            $colname = $tableparameters['tablecolumns'][$colcheck];
                            $pagetable .= '<td '.(is_array($tableparameters['image']['columns'][$colname]) ? 'align="center"' : '').'>';
                            
                            //get which column to edit
                            if(is_array($tableparameters['edit']['columns'][$colname]))
                            {
                                $pagetable .= '<a href="'.$tableparameters['edit']['columns'][$colname]['link'].'&id='.$query_fetch[$primary_key].'" class="'.$tableparameters['edit']['columns'][$colname]['class'].'">';
                            }
                            
                            //check if any table value should be replaced by an image
                            if(is_array($tableparameters['image']['columns'][$colname]))
                            {
                                if(isset($tableparameters['image']['columns'][$colname]['link']))
                                {
                                    $value = '<a href="'.$tableparameters['image']['columns'][$colname]['link'].'&id='.$query_fetch[$primary_key].'" class="'.$tableparameters['image']['columns'][$colname]['class'].'" target="'.$tableparameters['image']['columns'][$colname]['target'].'">';
                                }
                                
                                $value .= '<img src="'.$tableparameters['image']['columns'][$colname]['src'].'">';
                                
                                if(isset($tableparameters['image']['columns'][$colname]['link']))
                                {
                                    $value .= '</a>';
                                }
                            }
                            
                            //check if any column has a user defined function
                            if(is_array($tableparameters['functions'][$colname]))
                            {
                                $functionname = $tableparameters['functions'][$colname]['name'];
                                $ftype = $tableparameters['functions'][$colname]['paramtype'];
                                $fcols = $tableparameters['functions'][$colname]['paramcolumn'];
                                
                                //generate params
                                $params = array();
                                
                                $paramposition = 0;
                                foreach($ftype as $type)
                                {
                                    $params[$type] = $query_fetch[$fcols[$paramposition]];
                                    $paramposition++;
                                }
                                
                                $value = $functionname($params);
                            }
                            
                            $pagetable .= (($value=='') ? 'Not specified' : $value);
                            
                            if(is_array($tableparameters['edit']['columns'][$colname]))
                            {
                                $pagetable .= '</a>';
                            }
                            
                            $pagetable .= '</td>';
                            $export_value .= $value.',';
                            $colcheck++;
                        }
                        
                        //insert the row cell value into export array for the exported columns
                        $export_row_array[$colcount] = $export_value;
                        $colcount++;
                        
                        unset($export_value);
                            
                        $pagetable .= '</tr>';
                        
                        //set the child row
                        if(isset($tableparameters['children']))
                        {
                            $childstr = $tableparameters['querydata']['query'].' WHERE '.$tableparameters['children']['checkdbcolumn']." = '".$query_fetch[$primary_key]."'";
                            
                            $childquery = $this->runquery($childstr,$tableparameters['querydata']['querytype'],$tableparameters['querydata']['rpg'],$tableparameters['querydata']['pgno'],$tableparameters['querydata']['action']);
                            $childcount = $this->getcount($childquery);
                            
                            for($r=1; $r<=$childcount; $r++)
                            {
                                $childfetch = $this->fetcharray($childquery);
                                include ABSOLUTE_PATH.'components/view/childtablerow.php';
                            }
                        }
                }

                $pagetable .= '</table>';
                
                //join the array for the row cell values
                $exp_rows = join(';',$export_row_array);

                //load the page toolbar
                echo '<div id="toolbar">
                        <table width="100%" border="0">
                        <td align="left" width="2%"></td>';

                        //insert search function
                        if(array_key_exists('search', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%"><br/>
                                <a class="docslinks searchopen">
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/searchicon.png" ><br/>
                                    <span class="docs">Filter</span>
                                </a></td>';
                        }

                        echo '<td></td>
                        <td align="center" width="7%">
                        </td>';

                        //insert add icon
                        if(array_key_exists('add', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%">';

                            echo '<a href="'.$tableparameters['tools']['add'].'" class="'.$tableparameters['tools']['attributes']['addclass'].' docslinks">
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/plusicon.png" width="40" height="40"><br/>
                                    <span class="docs">Add</span>
                            </a>';

                            echo '</td>';
                        }

                        //insert import icon
                        if(array_key_exists('import', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%">';
                            echo '<a href="'.$importlink.'" class="'.$tableparameters['tools']['attributes']['importclass'].' docslinks">
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/importxls.png" width="40" height="40" /><br/>
                                    <span class="docs">Import</span>
                            </a>';

                            echo '</td>';
                        }

                        //insert export icon
                        if(array_key_exists('export', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%"><a class="'.$tableparameters['tools']['attributes']['exportclass'].' docslinks exportopen" >
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/exportxls.png" width="40" height="40" /><br/>
                                    <span class="docs">Export</span>
                            </a></td>';
                        }

                        //insert print icon
                        if(array_key_exists('print', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%">';

                            $this->loadmodule('printpage','printpage',array('query'=>$querystr,'displaydbfields'=>join(';',$tableparameters['displaydbfields']),'dbtables'=>join(',',$tableparameters['dbtables']),'tablecolumns'=>join(',',$columns),'printtitle'=>$tableparameters['printtitle']));
                            echo '</td>';
                        }

                        //insert delete icon
                        if(array_key_exists('delete', $tableparameters['tools'])==true)
                        {
                            echo '<td align="center" width="7%">';

                            echo '<a class="'.$tableparameters['tools']['attributes']['deleteclass'].' docslinks confirmdeletion" id="deleteItem" href="#" rel="'.$tableparameters['tools']['delete'].'">
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/delicon.png" width="40" height="40" /><br/>
                                    <span class="docs">Delete</span>
                            </a>';

                            echo '</td>';
                        }

                echo '</tr></table></div>';

                if(array_key_exists('search', $tableparameters['tools'])==true)
                {
                    //process the search parameters
                    echo '<div class="searchform">';
                    include(ABSOLUTE_PATH.'components/view/searchform.php');
                    echo '</div>';
                }

                if(array_key_exists('export', $tableparameters['tools'])==true)
                {
                    //the search export form
                    echo '<div class="export">';
                    include(ABSOLUTE_PATH.'components/view/exportform.php');
                    echo '</div>';
                }

                //echo the processed page results
                if(isset($_POST['formsearch']))
                {
                    echo '<div class="searchinfo">';
                    echo '<h3>Search Conditions: '.$search_string.'</h3>';
                    echo '<a href="'.$link->geturl().'" class="clearsearch">Cancel Search</a>';   
                    echo '</div>';
                }

                echo '<div id="pagetable">';
                echo $pagetable;

                if(!isset($_POST['formsearch']))
                {
                    $link->createPgNav($querystr,$tableparameters['querydata']['rpg']);
                }

                echo '</div>';
        }
        else
        {
                            echo '<div id="toolbar">
                                        <table width="100%" border="0" cellpadding="10">
                                        <td align="center" width="2%">';
                            echo '<a href="'.$tableparameters['tools']['add'].'" class="'.$tableparameters['tools']['attributes']['addclass'].' docslinks">
                                    <img src="'.STYLES_PATH.$this->set_template.'/images/icons/plusicon.png" width="40" height="40"><br/>
                                    <span class="docs">Add</span>
                            </a>';
                            echo '<td></td>
                            <td align="center" width="7%">
                            </td>';
                            echo '<td align="center" width="7%">';

                            

                            echo '</td>';
                            echo '<td align="center" width="7%">';

                            echo '</td>';
                            echo '<td align="center" width="7%">
                            </a></td>';
                            echo '<td align="center" width="7%">';
                            echo '</td>';
                            echo '<td align="center" width="7%">';
                            echo '</td>';

                echo '</tr></table></div>';
            
            $this->inlinemessage('No records found','warning');
        }       
}
