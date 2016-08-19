<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

echo '<h1 class="fullarticleTitle">Checkout Processing</h1>';

if(!isset($_SESSION['usertype']))
{
    $params = array(
                        'stepno' => 3,
                        'currentstep' => (!isset($_GET['step']) ? '1' : $_GET['step']),
                        'stepnames' => array('1'=>'Step 1','2'=>'Step 2','3'=>'Step 3'),
                        'stepdesc' => array('1'=>'User Registration','2'=>'Confirm Purchase','3'=>'Process Payment')
                    );
    
    //
    $step1 = 'User Registration';
    $step1action = $link->urlreplace('1', '2').'&msgvalid=Please_confirm_purchase';
    $this->loadmodule('stepgen','stepwizard',$params);
    
    switch ($_GET['step']) {
    case '1':
        $params = array(
                      'step1action' => '?content=mod_login&folder=same&file=userregister',
                      'title' => 'Register to buy publication',
                      'from' => 'shoppingcart',
                      'url' => '?content=com_checkout&folder=same&file=processcheckout&step=2'
                    );
        
        $this->loadmodule('usersignup','login',$params);
        break;

    case '2':
      if(!isset($_SESSION['usertype']))
      {
          redirect(SITE_PATH);
      }
      
        $step2action = $link->urlreplace('2', '3').'&msgvalid=Please_confirm_purchase';
        require ABSOLUTE_PATH.'components/checkout/confirmpurchase.php';
        break;
    }
}
else
{
    $params = array(
                        'stepno' => 2,
                        'currentstep' => (!isset($_GET['step']) ? '1' : $_GET['step']),
                        'stepnames' => array('1'=>'Step 1','2'=>'Step 2'),
                        'stepdesc' => array('1'=>'Confirm Purchase','2'=>'Payment Confirmed')
                    );
    
    $this->loadmodule('stepgen','stepwizard',$params);
    switch ($_GET['step']) {
    case '1':
        
        $step2action = $link->urlreplace('2', '3').'&msgvalid=Please_confirm_purchase';
        require ABSOLUTE_PATH.'components/checkout/confirmpurchase.php';
        break;
    
    case '2':
        $this->inlinemessage('Your payment has been confirmed','valid');
        
        $params = array('id'=>$_GET['pid']);
        $this->loadmodule('downloadpublication','downloads',$params);
    }
    
}
?>
