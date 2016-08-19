<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

class finances extends database
{
	function currency($curid,$type)
	{
		$curquery = $this->runquery ("SELECT currencyname,currencycode FROM currency WHERE currencyid='$curid'");
		
		$curdetails = $this->fetcharray($curquery);
		
		if($type=='name')
		{
			return $curdetails['currencyname'];
		}
		else if($type=='code')
		{
			return $curdetails['currencycode'];
		}
	}
	
        function defaultCurrency()
        {
            $cur = $this->runquery("SELECT currencyid, currencycode FROM currency WHERE status='default'",'single');
            
            return $cur;
        }
        
        function findPrice($id,$type,$format='formatted')
        {
            $price = $this->runquery("SELECT * FROM prices WHERE ".$type."id = '$id'", 'single');
            $cur = finances::defaultCurrency();
            
            if($format=='formatted')
            {
                return (($price['price']==''||$price['price']=='0.00') ? 'Free' : $cur['currencycode'].' '.number_format($price['price'],2));
            }
            elseif($format=='raw') 
            {
                return (($price['price']==''||$price['price']=='0.00') ? '0' : $price['price']);
            }
        }
        
        
        function getCurrency($id)
        {
            $curcy = $this->runquery("SELECT * FROM currency WHERE currencyid='$id'",'single');
            
            return $curcy;
        }
        
	//get the appropriate charge level
	function findCharge($amount,$cid)
	{
		$charge = $this->runquery("SELECT * FROM charges WHERE chargeto LIKE 'withdrawal%' AND curid='$cid'",'multiple','all');
		$chargeCount = $this->getcount($charge);
		
		for($j=1; $j<=$chargeCount; $j++)
		{
			$chargeArray = $this->fetcharray($charge);
			
			$splitCharge = explode('_',$chargeArray['chargeonamount']);
			
			if($amount>=$splitCharge[0]&&$amount<=$splitCharge[1])
			{
				return $chargeArray['charge'];
			}
		}
	}	
	
	
}
?>