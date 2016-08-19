<?php
$u = $t;
$u++;

$pagetable .= '<tr class="child" rel="'.$this->shortentxt($childfetch[0],30).'" >
<td><img src="'.SITE_PATH.'styles/ichatemplate/images/arrow.png" /></td>                
<td><input type="checkbox" name="item[]" value="'.$childfetch[$primary_key].'" id="'.$u.'" class="chkItem"></td>';
                        
$colcheck = 0;
foreach($columns as $colvalue)
{
    $colposition = array_search($colvalue,$columns);

    $field = $tableparameters['displaydbfields'][$colposition];
    $field = explode('.',$field);

    if($field[1]=='int')
    {
        $value = (int)$childfetch[$field[0]];
    }

    if($field[1]=='date')
    {
        $value = ($childfetch[$field[0]]=='0' ? 'Not Specified' : date('d-m-Y',$childfetch[$field[0]]));
    }

    if($field[1]=='float')
    {
        $value = $childfetch[$field[0]];
    }

    if($field[1]=='text')
    {
        $value = $childfetch[$field[0]];
    }

    if($field[1]=='longtext')
    {
        $value = $this->shortentxt(strip_tags($childfetch[$field[0]]),30);
    }

    if(count($field)>='3')
    {
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
        $pagetable .= '<a href="'.$tableparameters['edit']['columns'][$colname]['link'].'&id='.$childfetch[$primary_key].'" class="'.$tableparameters['edit']['columns'][$colname]['class'].'">';
    }

    //check if any table value should be replaced by an image
    if(is_array($tableparameters['image']['columns'][$colname]))
    {
        if(isset($tableparameters['image']['columns'][$colname]['link']))
        {
            $value = '<a href="'.$tableparameters['image']['columns'][$colname]['link'].'&id='.$childfetch[$primary_key].'" class="'.$tableparameters['image']['columns'][$colname]['class'].'" target="'.$tableparameters['image']['columns'][$colname]['target'].'">';
        }

        $value .= '<img src="'.$tableparameters['image']['columns'][$colname]['src'].'">';

        if(isset($tableparameters['image']['columns'][$colname]['link']))
        {
            $value .= '</a>';
        }
    }

    $pagetable .= (($value=='') ? 'Not specified' : $value);

    if(is_array($tableparameters['edit']['columns'][$colname]))
    {
        $pagetable .= '</a>';
    }
    
    $colcheck++;
}

$pagetable .= '</td>';

?>
