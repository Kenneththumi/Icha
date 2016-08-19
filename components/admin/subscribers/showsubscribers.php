<?php
//prevents direct access of these files
defined('LOAD_POINT') or die('RESTRICTED ACCESS');

$link = new navigation;

$rawCount = $this->rawquery("SELECT * FROM subscribers ORDER BY regdate DESC");
$rawCount = $this->getcount($rawCount);

if(isset($_POST['cmd']))
{
    if(isset($_POST['name'])&&$_POST['name']!='')
    {
            $name = $_POST['name'];
            $condition .= "name LIKE '%$name%' AND ";
            $searchStr .= 'Subscriber Name: '.$name.', '; 
    }
    
    if(isset($_POST['email'])&&$_POST['email']!='')
    {
            $email = $_POST['email'];
            $condition .= "email LIKE '%$email%' AND ";
            $searchStr .= 'Subscriber Email: '.$email.', '; 
    }
    
    if(isset($_POST['category'])&&$_POST['category']!='')
    {
            //get the categories
            $categoryclass = new category;
        
            $category = $_POST['category'];
            $condition .= "catidsubscribed = '$category' AND ";
            $searchStr .= 'Category subscribed: '.$categoryclass->returncategory($category).', '; 
    }
    
    $fCondition = rtrim($condition,' AND ');
	
    if($fCondition!='')
    {
        $fCondition = 'WHERE '.$fCondition;
    }
    
    $query = "SELECT * FROM subscribers ".$fCondition." ORDER BY name ASC";
}
else
{
    $query = "SELECT * FROM subscribers ORDER BY regdate DESC";
}

$subs = $this->runquery($query, 'multiple', 30,$_GET['pageno']);
$subCount = $this->getcount($subs);

echo '<h1>Subcriber Management (' . $rawCount . ' subcribers)</h1>';

if(isset($_POST['cmd']))
{
	$this->inlinemessage('Search Value: '.$searchStr,'valid');
}

if(isset($_GET['ids']))
{
        $ids = ltrim($_GET['ids'],',');
        $idarray = explode(',',$ids);

        foreach($idarray as $key=>$value)
        {
                if($value!='')
                {
                        //delete the subscriber listing
                        $this->deleterow('subscribers','subid',$value);
                }
        }	

        redirect($link->urlreturn('subscribers','','no').'&msgvalid=The_subscribers_have_been_deleted.');
}

$this->wrapscript("$(document).ready(function() 
					{
						$(\"a.subs\").fancybox({
                                                'width': 650,
                                                'height': 400,
                                                'autoDimensions': false,
                                                'autoScale' : false,
                                                'transitionIn' : 'elastic',
                                                'transitionOut' : 'elastic',
                                                'enableEscapeButton' : false,
                                                'overlayShow' : true,
                                                'overlayColor' : '#fff',
                                                'overlayOpacity' : 0,
                                                'type': 'iframe',
                                                'scrolling': 'auto'
									});
                                                
$(\"#searchfilter\").hide();
						
$(\".searchopen\").click(function () {
        $(\"#searchfilter\").animate({height: 'toggle'});
 });
					});");

    //the deletion JQuery script
    $this->loadscripts('multipleCheckboxes', 'yes');

    echo '<table width="80%" border="0" style="float: right;">
  <tr align="center">
    <td><a href="' . $link->urlreplace('file=showsubscribers', 'file=sendarticles') . '" ><img src="' . STYLES_PATH . 'ichatemplate/images/icons/email_subscribers.png" width="50" height="50" /></a>
    </td>
    <td><a href="#" class="searchopen"><img src="' . STYLES_PATH . 'ichatemplate/images/icons/search.png" width="50" height="50" /></a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=addsubscriber') . '&alert=yes">
    <img src="' . STYLES_PATH . 'ichatemplate/images/icons/addsubscriber.png" width="50" height="50" />
    </a>
    </td>
    <td>
    <a class="subs" href="' . $link->urlreplace('file=showsubscribers', 'file=csvimport') . '&alert=yes">
    <img src="' . STYLES_PATH . 'ichatemplate/images/icons/import.png" width="50" height="50" />
    </a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=setstatus') . '&status=yes&alert=yes">
    <img src="' . STYLES_PATH . 'ichatemplate/images/icons/enable.png" width="50" height="50" />
    </a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=setstatus') . '&status=no&alert=yes">
    <img src="' . STYLES_PATH . 'ichatemplate/images/icons/disable.png" width="50" height="50" />
    </a>    
    </td>
    <td>
    <a class="docslinks checkItem" href="' . $link->geturl() . '&task=del">
        <img src="' . STYLES_PATH . 'ichatemplate/images/icons/delete.png" width="50" height="50" />
    </a>    
    </td>
  </tr>
  <tr align="center">
    <td>
    <a href="' . $link->urlreplace('file=showsubscribers', 'file=sendarticles') . '&alert=yes" class="subs">
    Send Article
    </a>
    </td>
    <td>
    <a href="#" class="searchopen">
    Search
    </a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=addsubscriber') . '&alert=yes">
    Add
    </a>
    </td>
    <td>
    <a class="docslinks" href="#">
    Import
    </a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=setstatus') . '&status=yes&alert=yes">
    Enable 
    </a>
    </td>
    <td>
    <a class="subs checkItem" href="' . $link->urlreplace('file=showsubscribers', 'file=setstatus') . '&status=no&alert=yes">
    Disable
    </a>
    </td> 
    <td>
    <a class="docslinks" id="deleteItem" href="' . $link->geturl() . '&task=del">
    Delete
    </a>
    </td> 
  </tr>
</table>';

echo '<div id="searchfilter">';
include_once(ABSOLUTE_PATH.'components/admin/subscribers/searchform.php');
echo '</div>';    
    
$link->createPgNav($query,30);

if ($subCount >= 1) {    

    echo '<table width="100%" border="0" cellpadding="10" class="tablelist">
      <tr class="tabletitle">
        <td><input type="checkbox" name="itemlist" class="checkall" ></td>
        <td>Name</td>
        <td>Email Address</td>
        <td>Category</td>
        <td>Enabled</td>
      </tr>';

    for ($r = 1; $r <= $subCount; $r++) {
        //$catQuery = $this->runquery("SELECT * FROM categories WHERE catid='$catid'",'single');
        //$subs = $this->runquery("SELECT count(*) FROM subscribers WHERE catidsubscribed='$catid'",'single');
        $subscriber = $this->fetcharray($subs);

        echo '<tr class="' . ($subscriber['enabled'] == 'no' ? 'disenabled' : 'item') . '">
                            <td><input type="checkbox" name="item[]" value="' . $subscriber['subid'] . '" id="' . $r . '" class="chkItem"></td>
                            <td>' . ($subscriber['name']!=' ' ? $subscriber['name'] : 'Name not specified') . '</td>
                            <td>' . $subscriber['email'] . '</td>
                            <td>' . category::returncategory($subscriber['catidsubscribed']) . '</td>
                            <td>' . $subscriber['enabled'] . '</td>
                      </tr>';
    }
    echo '</table>';
} else {
    $this->inlinemessage('No subscribers found', 'valid');
}
?>