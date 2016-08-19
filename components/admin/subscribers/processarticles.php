<?php
//prevents direct access of these files
//defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

set_time_limit(0);
ignore_user_abort(1);

$this->loadstyles('backoffice');

if(isset($_GET['ids']))
{
    $link = new navigation;
    
    echo '<h1>Select Subscriber Category to send articles to:</h1>';
    
    $this->loadplugin('classForm/class.form');
    
    $cat = new category();
    $catArray = array( '0'=>'All Updates',
                        $cat->returncategory('Vaccine and Vaccination') => 'Vaccine and Vaccination', 
                        $cat->returncategory('Malaria')=>'Malaria');
    
    $articleform = new form();

    $articleform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'100%',
                                      "preventJQueryLoad" => false,
                                      "preventJQueryUILoad" => false,
                                      "action"=>''
                                      ));
    
    $articleform->addHidden('send', 'send');
    $articleform->addHidden('ids', ltrim($_GET['ids'],','));
    
    $articleform->addSelect("Subscriber Category:",'catid','',$catArray);
    
    $articleform->addButton('Send Articles', 'submit');
    
    $articleform->render();
    
    if(isset($_POST['send']))
    {
        $subno = $this->runquery("SELECT count(*) FROM subscribers WHERE catidsubscribed='".$_POST['catid']."'",'single');
        
        //calculate iterations
        //$rpg = 200;
        //$allrows = $subno['count(*)'];
        $rpg = 100;
        $allrows = 200;
        
         //calculate and round down final value
        $pages = round(($allrows/$rpg), PHP_ROUND_HALF_DOWN);
        
        //get the surplus value
        $leftover = $allrows%$rpg;
        
        //first loop for going through all the 5000+ subscribers
        $email_report = 1;
        for($pageno=1; $pageno<=$pages; $page++)
        {
            //overwrite the rpg value on the last page so that it include the additional rows
            if($pageno==$pages)
            {
                $rpg = $rpg+$leftover;
            }
            
            $subscribers = $this->runquery("SELECT * FROM subscribers WHERE catidsubscribed='".$_POST['catid']."' ORDER BY subid DESC",'multiple',$rpg,$pageno);
            $subscribercount = $this->getcount($subscribers);
            
            //second loop to each $rpg group of subscribers
            for($groupcount=1; $groupcount<=$subscribercount; $groupcount++)
            {
                $ids = explode(',', $_POST['ids']);
                
                //get the subscriber
                $subscriber = $this->fetcharray($subscribers);

                foreach ($ids as $value) 
                {
                    $article = $this->runquery("SELECT * FROM articles WHERE articleid = '$value'",'single');

                    $articleinfo = '<h1>'.$article['title'].'</h1>';
                    $articleinfo .= $article['body'];

                    $html = '<p> Greetings '.$subscriber['name'].'</p>';
                    $html .= $articleinfo;
                    $html .= '<p>Thanks,</p>';
                    $html .= '<p>'.SITENAME.'</p>';

                    $text = strip_tags($html);

                    //send article to subscriber
                    if(filter_var($subscriber['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $this->multiPartMail($subscriber['email'],'ICHA Article: '.$article['title'],$html,$text,MAILFROM,SITENAME);
                        $email_report++;
                    }
                }
            }
        }
        
        $htmlreport = '<p>Emails successfully sent to '.$email_report.' subscribers';
        $txtreport = strip_tags($htmlreport);
        
        $this->multiPartMail(MAILADMIN,'Email Report dated '.date('d-m-Y H:i',time()),$htmlreport,$txtreport,MAILFROM,SITENAME);
        
        //$this->multiPartMail('sngumo@gmail.com','Email Report dated '.date('d-m-Y H:i',time()),$htmlreport,$txtreport,MAILFROM,SITENAME);
        
        //list the sent articles
        echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
              <tr align="center" class="tabletitle">
                    <td width="36%">Title</td>
                    <td width="10%">Category</td>
                    <td width="10%">Publish Date</td>
              </tr>';
        
        foreach ($ids as $value) 
        {
            $article = $this->runquery("SELECT * FROM articles WHERE articleid = '$value'",'single');

            echo '<tr class="item">
                            <td>'.$this->shortentxt($article['title'],50).'</td>
                            <td align="center">'.$cat->returncategory($article['categoryid']).'</td>
                            <td>'.date('d-m-Y',$article['publishdate']).'</td>
                      </tr>';
        }
        
        echo '</table>';
    }
}
else
{
    $this->inlinemessage('No articles sent','error');
}
?><?php
//prevents direct access of these files
//defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

set_time_limit(0);
ignore_user_abort(1);

$this->loadstyles('backoffice');

if(isset($_GET['ids']))
{
    $link = new navigation;
    
    echo '<h1>Select Subscriber Category to send articles to:</h1>';
    
    $this->loadplugin('classForm/class.form');
    
    $cat = new category();
    $catArray = array( '0'=>'All Updates',
                        $cat->returncategory('Vaccine and Vaccination') => 'Vaccine and Vaccination', 
                        $cat->returncategory('Malaria')=>'Malaria');
    
    $articleform = new form();

    $articleform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'100%',
                                      "preventJQueryLoad" => false,
                                      "preventJQueryUILoad" => false,
                                      "action"=>''
                                      ));
    
    $articleform->addHidden('send', 'send');
    $articleform->addHidden('ids', ltrim($_GET['ids'],','));
    
    $articleform->addSelect("Subscriber Category:",'catid','',$catArray);
    
    $articleform->addButton('Send Articles', 'submit');
    
    $articleform->render();
    
    if(isset($_POST['send']))
    {
        $subno = $this->runquery("SELECT count(*) FROM subscribers WHERE catidsubscribed='".$_POST['catid']."'",'single');
        
        //calculate iterations
        //$rpg = 200;
        //$allrows = $subno['count(*)'];
        $rpg = 100;
        $allrows = 200;
        
         //calculate and round down final value
        $pages = round(($allrows/$rpg), PHP_ROUND_HALF_DOWN);
        
        //get the surplus value
        $leftover = $allrows%$rpg;
        
        //first loop for going through all the 5000+ subscribers
        $email_report = 1;
        for($pageno=1; $pageno<=$pages; $page++)
        {
            //overwrite the rpg value on the last page so that it include the additional rows
            if($pageno==$pages)
            {
                $rpg = $rpg+$leftover;
            }
            
            $subscribers = $this->runquery("SELECT * FROM subscribers WHERE catidsubscribed='".$_POST['catid']."' ORDER BY subid DESC",'multiple',$rpg,$pageno);
            $subscribercount = $this->getcount($subscribers);
            
            //second loop to each $rpg group of subscribers
            for($groupcount=1; $groupcount<=$subscribercount; $groupcount++)
            {
                $ids = explode(',', $_POST['ids']);
                
                //get the subscriber
                $subscriber = $this->fetcharray($subscribers);

                foreach ($ids as $value) 
                {
                    $article = $this->runquery("SELECT * FROM articles WHERE articleid = '$value'",'single');

                    $articleinfo = '<h1>'.$article['title'].'</h1>';
                    $articleinfo .= $article['body'];

                    $html = '<p> Greetings '.$subscriber['name'].'</p>';
                    $html .= $articleinfo;
                    $html .= '<p>Thanks,</p>';
                    $html .= '<p>'.SITENAME.'</p>';

                    $text = strip_tags($html);

                    //send article to subscriber
                    if(filter_var($subscriber['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $this->multiPartMail($subscriber['email'],'ICHA Article: '.$article['title'],$html,$text,MAILFROM,SITENAME);
                        $email_report++;
                    }
                }
            }
        }
        
        $htmlreport = '<p>Emails successfully sent to '.$email_report.' subscribers';
        $txtreport = strip_tags($htmlreport);
        
        $this->multiPartMail(MAILADMIN,'Email Report dated '.date('d-m-Y H:i',time()),$htmlreport,$txtreport,MAILFROM,SITENAME);
        
        //$this->multiPartMail('sngumo@gmail.com','Email Report dated '.date('d-m-Y H:i',time()),$htmlreport,$txtreport,MAILFROM,SITENAME);
        
        //list the sent articles
        echo '<table width="100%" border="0" cellpadding="7" class="tablelist">
              <tr align="center" class="tabletitle">
                    <td width="36%">Title</td>
                    <td width="10%">Category</td>
                    <td width="10%">Publish Date</td>
              </tr>';
        
        foreach ($ids as $value) 
        {
            $article = $this->runquery("SELECT * FROM articles WHERE articleid = '$value'",'single');

            echo '<tr class="item">
                            <td>'.$this->shortentxt($article['title'],50).'</td>
                            <td align="center">'.$cat->returncategory($article['categoryid']).'</td>
                            <td>'.date('d-m-Y',$article['publishdate']).'</td>
                      </tr>';
        }
        
        echo '</table>';
    }
}
else
{
    $this->inlinemessage('No articles sent','error');
}
?>
