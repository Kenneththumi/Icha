<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadcss(array('modules','stepwizard','css','wizard_css'));

//within the params array it should have - no of steps, current step, name of each step(array) and description of each step(array)
function stepWiz($params)
{
    $stepno = $params['stepno'];
    $currentstep = $params['currentstep'];
    $stepname = $params['stepnames'];
    $stepdesc = $params['stepdesc'];
    
    $wizard = '<div id="stepwizard">';
    
    for($i=1; $i<=$stepno; $i++)
    {
        if(($i<=($currentstep-1))&&$currentstep!=1)
        {
            $wizard .= '<a href="#" class="past">
                            <h2 class="steptitle">'.$stepname[$i].'</h2>
                            <p>'.$stepdesc[$i].'</p>
                         </a>';
            if($i==($currentstep-1))
            {
                $wizard .= '<span class="p2c"></span>';
            }
            else
            {
                $wizard .= '<span class="p2p"></span>';
            }
        }
        
        if($i==$currentstep)
        {
            $wizard .= '<a href="#" class="current">
                            <h2 class="steptitle">'.$stepname[$i].'</h2>
                            <p>'.$stepdesc[$i].'</p>
                         </a>';
            
            if($i!=$stepno)
            {
                $wizard .= '<span class="c2f"></span>';
            }
            else {
                $wizard .= '<span class="c2e"></span>';
            }
        }
        
        if($i>=($currentstep+1))
        {
            $wizard .= '<a href="#" class="future">
                            <h2 class="steptitle">'.$stepname[$i].'</h2>
                            <p>'.$stepdesc[$i].'</p>
                         </a>';
            if($i==$stepno)
            {
                $wizard .= '<span class="f2e"></span>';
            }
            else
            {
                $wizard .= '<span class="f2f"></span>';
            }
        }
    }
    
    $wizard .= '</div>';
    
    echo $wizard;
}

stepWiz($params);
?>
