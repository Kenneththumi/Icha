<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//the shopping cart class
class shoppingcart extends database
{
	function checkin_item($id_details,$producttype)
	{
           $productid = $id_details[0];
           
           $currencyid = $id_details[1];
           $price = $id_details[2];
            
            //the action variable is for checking whether the item is being added afresh or being updated
            $pchk = $this->runquery("SELECT count(*) FROM shoppingcart WHERE productid='$productid' AND sessionid='".$_SESSION['logid']."'",'single');
            $pcount = $pchk['count(*)'];

            if($pcount==0)
            {			
                $data_array = array('shopperid'=> $_SESSION['sourceid'],
                                    'shoppertype'=> $_SESSION['usertype'],
                                    'sessionid' => $_SESSION['logid'],
                                    'productid'=>$productid,
                                    'price' => $price,
                                    'currencyid'=>$currencyid,
                                    'datetime'=>time());

                $insert = $this->dbinsert('shoppingcart',$data_array);
                //$this->print_last_error();

                return 'insert';
            }
            else
            {
                    return 'already_in';
            }
	}
        
        function checkout_item($cartid)
        {
           $this->deleterow('shoppingcart', 'cartid', $cartid);
        }
	
	function removeitem($productid)
	{
		$querystr = "DELETE FROM shoppingcart WHERE productid=".$productid." AND shopperid='".$this->userid."'";
		
		return $del = $this->runquery($querystr);
	}
	
	function cart_total($source='physical')
	{
            $querystr = "SELECT sum(price) FROM shoppingcart WHERE shopperid='".$this->userid."'";
		
            $total = $this->runquery($querystr,'single');
	
            return $total['sum(price)'];
	}
	
	function cartcontents($source='products')
	{
		if($source=='products')
		{
			$cartquery = $this->runquery("SELECT * FROM shoppingcart WHERE shopperid='".$this->userid."'");
			$cartcount = $this->getcount($cartquery);
		}
		
		if($cartcount!=0)
		{
			$count = 1;
			$total = 0;
			while($contentarray = $this->fetcharray($cartquery))
			{
				$product = $this->runquery("SELECT * FROM product WHERE productid='".$contentarray['productid']."'",'single');
				
				$prodobj = new product;
				
				$total += $prodobj->returnprice($product['price'],$contentarray['productid']);
				
				$productstr .= '[product'.$count.']';
				$productstr .= '[id'.$count.']'.$contentarray['productid'].'[/id'.$count.']';
				$productstr .= '[productname'.$count.']'.$product['productname'].'[/productname'.$count.']';
				$productstr .= '[desc'.$count.']'.$product['description'].'[/desc'.$count.']';
				$productstr .= '[price'.$count.']'.$prodobj->returnprice($product['price'],$contentarray['productid']).'[/price'.$count.']';
				$productstr .= '[currency'.$count.']'.$product['currencyid'].'[/currency'.$count.']';
				$productstr .= '[datetime'.$count.']'.$contentarray['datetime'].'[/datetime'.$count.']';
				
				$productstr .= '[/product'.$count.']';
				
				$count++;
			}
			$productstr .= '[total]'.$total.'[/total]';
		}
		else
		{
			$productstr = 0;
		}
		
		return $productstr;
	}
	
	function totalquantity()
	{
		$itemquery = "SELECT sum(quantity) FROM shoppingcart WHERE shopperid='".$this->userid."'";
		
		$itemquantity = $this->runquery($itemquery,'single');
		
		return $itemquantity['sum(quantity)'];
	}
	
	function noofitems()
	{
		if(isset($this->sessionid))
		{
			$countquery = "SELECT count(*) FROM shoppingcart WHERE shopperid='".$this->userid."'";
			$dcountquery = "SELECT count(*) FROM downloadcart WHERE shopperid='".$this->userid."'";
			
			$count = $this->runquery($countquery,'single');
			$dcount = $this->runquery($dcountquery,'single');
			
			$tcount = $count['count(*)']+$dcount['count(*)'];
			
			return $tcount;
		}
		else
		{
			return '0';
		}
	}
	
	function clearcart($source='physical')
	{
		if($source=='download')
		{
			$cartquery = $this->runquery("SELECT * FROM downloadcart WHERE shopperid='".$this->userid."'");
		}
		else
		{
			$cartquery = $this->runquery("SELECT * FROM shoppingcart WHERE shopperid='".$this->userid."'");
		}
		$cartcount = $this->getcount($cartquery);
		
		for($i=1; $i<=$cartcount; $i++)
		{
			$cartarray = $this->fetcharray($cartquery);
			
			$productids .= $cartarray['productid'].'-';
			$quantity += $cartarray['quantity'];
			$curid = $cartarray['currencyid'];
		}
		
		$ids_trim = rtrim($productids,'-');
		
		if($source=='download')
		{
			$shoppingtotal = $this->cart_total('download');
		}
		else
		{
			$shoppingtotal = $this->cart_total();
		}
		
		$cartarray = array('shopperid'=>$this->userid,
							'productsbought'=>$ids_trim,
							'quantity'=>$quantity,
							'cartvalue'=>$shoppingtotal,
							'datetime'=>time());
		
		if($this->dbinsert('carthistory',$cartarray))
		{
			if($source=='download')
			{
				$this->deleterow('downloadcart','shopperid',$this->userid);
			}
			else
			{
				$this->deleterow('shoppingcart','shopperid',$this->userid);
			}
		}
	}
}
?>