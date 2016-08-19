<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadplugin('classForm/class.form');

echo '<h1>Search Articles to send to subscribers</h1>';

$cat = new category();
$catArray = array( ''=>'All Updates',$cat->returncategory('Vaccine and Vaccination') => 'Vaccine and Vaccination', $cat->returncategory('Malaria')=>'Malaria');

$searchform = new form;

$searchform->setAttributes(array(
                                "includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                "preventJQueryLoad" => true,
                                "preventJQueryUILoad" => true,
                                "action"=>'',
                                "class"=>'addproduct'
                                ));

$searchform->addHidden('artbutton','artbutton');
$searchform->addTextbox('Enter Article Name:','artname','',array('id'=>'namesearch'));
$searchform->addDateRange("Date Range of Article:", "artdate",'Click to select Article Date Range ...');

$searchform->addSelect("Select Category:",'catid','',$catArray);

$searchform->addButton('Search Articles','submit',array('id'=>'myformbutton'));

if(isset($_POST['artbutton']))
{
    echo '<div id="searchfilter">';
    $searchform->render();
    echo '</div>';
    
    if($_POST['artname']!='')
    {
            $condition .= "title LIKE '%".$_POST['artname']."%'";
            $searchStr .= 'Article Name: '.$_POST['artname'];
    }
    
    if($_POST['artdate']!='Click to select Article Date Range ...')
    {
            if(strstr($_POST['artdate'],' - ')!='')
            {
                    $splitdate = explode(' - ',$_POST['artdate']);
                    $condition .= 'publishdate > '.strtotime($splitdate[0]).' AND publishdate < '.strtotime($splitdate[1]).' ';
                    $searchStr .= 'Date Range: '.$_POST['artdate'].', ';
            }
            else
            {
                    $sdate = $_POST['artdate'];
                    $condition .= 'publishdate > '.strtotime($sdate).' ';
                    $searchStr .= 'Date Range: '.$_POST['artdate'].', ';
            }
            
            $condition .= ' AND ';
    }
    
    if($_POST['catid']!='')
    {
        $cat = new category();
        
        $searchStr .= 'Category: '.$cat->returncategory($_POST['catid']);
        $condition .= 'categoryid = '.$_POST['catid'];
    }
    
    if($condition!='')
    {
        $condition = ' WHERE '.rtrim($condition,' AND ');
    }
    else
    {
        $searchStr = 'Search All Parameters';
    }
    
    $getArticles = $this->runquery("SELECT * FROM articles ".$condition,'multiple','all');
    $getCount = $this->getcount($getArticles);
   
    $this->inlinemessage($searchStr,'valid');
    
    if($getCount>=1)
    {
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
                    'overlayColor' : '#ccc',
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

            echo '<table width="40%" border="0" style="float: right;">
                    <tr align="center">
                    <td><a href="#" class="searchopen"><img src="' . STYLES_PATH . 'ichatemplate/images/icons/search.png" width="50" height="50" /></a>
                      </td>
                      <td><a href="' . $link->urlreplace('file=sendarticles', 'file=processarticles') . '&alert=yes" class="subs checkItem" ><img src="' . STYLES_PATH . 'ichatemplate/images/icons/email_subscribers.png" width="50" height="50" /></a>
                      </td>
                    </tr>
                    <tr align="center">
                      <td>
                      <a href="#" class="searchopen">
                      Continue Search
                      </a>
                      </td>
                      <td>
                      <a href="' . $link->urlreplace('file=sendarticles', 'file=processarticles') . '&alert=yes" class=" subs checkItem">
                      Send Selected Articles
                      </a>
                      </td>
                    </tr>
                  </table>';
    
            echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
              <tr align="center" class="tabletitle">
                    <td width="4%"><input type="checkbox" name="itemlist" class="checkall" ></td>
                    <td width="36%">Title</td>
                    <td width="10%">Category</td>
                    <td width="10%">Publish Date</td>
              </tr>';
            
            for($i=1; $i<=$getCount; $i++)
            {
                    $article = $this->fetcharray($getArticles);

                    echo '<tr class="item" rel="'.$this->shortentxt($article['title'],30).'" id="test'.$i.'">
                            <td><input type="checkbox" name="item[]" value="' . $article['articleid'] . '" id="' . $i . '" class="chkItem"></td>
                            <td><a href="?admin=com_admin&folder=articles&file=addedit&artid='.$article['articleid'].'&alert=yes" class="edit">'.$this->shortentxt($article['title'],50).'</a></td>
                            <td align="center">'.$cat->returncategory($article['categoryid']).'</td>
                            <td>'.date('d-m-Y H:i',$article['publishdate']).'</td>
                      </tr>';
            }
            echo '</table>';
    }
    else
    {
        $this->inlinemessage('No articles found','error');
    }
}
else
{
    $searchform->render();
}
?>