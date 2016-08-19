<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$courseprice = $this->runquery("SELECT price FROM prices WHERE courseid = '".$getvalue['courseid']."'",'single');

$emailhtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
body {
	margin: 0px;
	padding: 0px;
	font-family: Calibri;
	font-size: 15px;
}
.firstline {
	color: #0C0;
	font-size: 18px;
	font-weight: bold;
}
.aligntable {
	margin-right: auto;
	margin-left: auto;
}
.topgreyline {
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #CCC;
}
.aligntable tr td.bottom {
	font-size: 11px;
}
</style>
</head>

<body>
<table width="700" border="0" cellpadding="5" class="aligntable">
  <tr>
    <td><img src="'.STYLES_PATH.'ichatemplate/images/small_cdi_logo.png" width="440" height="75" /></td>
    <td align="right"><p>'.date('r',  time()).' <br/>Transaction ID: '.$paymentsave['tracking_id'].' </p></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td height="25" colspan="2"><strong>Greetings '.$getvalue['name'].',</strong></td>
  </tr>
  <tr>
    <td height="40" colspan="2"><h4 class="firstline">You sent a payment of KSH '.number_format($courseprice['price'],2).' to '.SITENAME.'</h4></td>
  </tr>
  <tr>
    <td height="40" colspan="2"><table width="90%" border="0" cellpadding="10" class="aligntable">
      <tr>
        <td width="42%" class="topgreyline"><strong>Description</strong></td>
        <td width="7%" class="topgreyline"><strong>Unit</strong></td>
        <td width="15%" class="topgreyline"><strong>Qty</strong></td>
        <td width="36%" class="topgreyline"><strong>Amount (KSH)</strong></td>
      </tr>
      <tr>
        <td class="topgreyline">'.$getvalue['course'].'</td>
        <td class="topgreyline">1</td>
        <td class="topgreyline">1</td>
        <td class="topgreyline">'.number_format($courseprice['price'],2).'</td>
      </tr>
      <tr>
        <td colspan="3" align="right" class="topgreyline"><strong>Sub Payment</strong></td>
        <td class="topgreyline">'.number_format($courseprice['price'],2).'</td>
      </tr>
      <tr>
        <td colspan="3" align="right" class="topgreyline"><strong>Total Payment</strong></td>
        <td class="topgreyline">'.number_format($courseprice['price'],2).'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="40" colspan="2" class="bottom"><p>For any queries about this payment or any other, please contact the administrator at <strong><a href="mailto:'.MAILADMIN.'">'.MAILADMIN.'</a></strong></p></td>
  </tr>
  <tr>
    <td height="40" colspan="2" align="right" class="bottom">(C)2014 - All Rights Reserved - '.SITENAME.'</td>
  </tr>
</table>
</body>
</html>';

$emailtxt = strip_tags($emailhtml);

$this->multiPartMail($getvalue['emailaddress'],'Receipt for payment of '.$getvalue['course'].' to '.SITENAME,$emailhtml,$emailtxt,MAILFROM,SITENAME);
?>
