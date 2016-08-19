<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if(isset($_GET['catid']))
{
	$this->wrapscript("$(document).ready(function(){
                                $(\"a.catsearch\").fancybox({
                                    'width': 650,
                                    'height': 400,
                                    'autoDimensions': false,
                                    'autoScale'			: false,
                                    'transitionIn'		: 'elastic',
                                    'transitionOut'		: 'elastic',
                                    'enableEscapeButton' : false,
                                    'overlayShow' : true,
                                    'overlayColor' : '#fff',
                                    'overlayOpacity' : 0,
                                    'scrolling': 'auto'
                                });
                            });");
	
	$catid = $_GET['catid'];
	
	$cat = $this->runquery("SELECT * FROM categories WHERE catid='$catid'",'single');	
	
	$art = $this->runquery("SELECT * FROM articles WHERE categoryid='$catid' AND lead!='yes' ORDER BY publishdate ASC",'multiple',8,$_GET['pageno']);
	$artCount = $this->getcount($art);
	
	if($artCount>=1)
	{
            echo '<a href="?content=mod_search&folder=same&file=advancedsearch&alert=yes&stype=catsearch&catid='.$catid.'" class="catsearch">Search within '.ucfirst($cat['categoryname']).'</a>';
	}
	
	echo '<h1 class="fullarticleTitle">Updates under '.ucfirst($cat['categoryname']).'</h1>';
	
	$link->createPgNav("SELECT * FROM articles WHERE categoryid='$catid' AND lead!='yes' ORDER BY publishdate ASC",8);	
	
	if($artCount>=1)
	{
            for($j=1; $j<=$artCount; $j++)
            {
                $artArray = $this->fetcharray($art);
                $artid = $artArray['articleid'];

                $link->trackHits($artid);

                echo '<a href="?content=com_articles&artid='.$artid.'"><p class="articleTitle">'.$this->shortentxt($artArray['title'],50).'</p></a>';
                //echo '<p class="articleDate">'.date('l, jS \of F Y',$artArray['publishdate']).'</p>';
                echo $this->shortentxt(strip_tags($artArray['body']),250);	

                echo '<a href="?content=com_articles&artid='.$artid.'" class="readall"> read more </a>';
            }
	}
	else
	{
            echo '<p class="articleTitle" align="center">There are no articles at the moment. Please check later</p>';
	}
}
elseif(isset($_GET['artid'])||isset($_GET['butid'])){
    
	if(isset($_GET['artid'])){
            
            $artid = $_GET['artid'];
	}
	elseif(isset($_GET['butid'])){
            
            $butid = $_GET['butid'];
	}
        
	$link->trackHits();	
	$this->wrapscript("$(document).ready(function(){            
                                $(\".email\").fancybox({
                                        'width': 520,
                                        'height': 300,
                                        'autoDimensions': false,
                                        'autoScale'			: false,
                                        'transitionIn'		: 'elastic',
                                        'transitionOut'		: 'elastic',
                                        'enableEscapeButton' : false,
                                        'overlayShow' : true,
                                        'overlayColor' : '#fff',
                                        'overlayOpacity' : 0,
                                        'type' : 'iframe',
                                        'scrolling': 'auto'
                                });
                            });");
	
	if(isset($_GET['artid'])){
            
            $article = $this->runquery("SELECT * FROM articles WHERE articleid='$artid'",'single');
	}
	elseif(isset($_GET['butid'])){
            
            $article = $this->runquery("SELECT * FROM bulletins WHERE bulletinid='$butid'",'single');
	}
        
        //check if the id falls within the given range
        if((($article['articleid']>='1110')&&($article['articleid']<='1114')))
        {
           echo '<div class="splitcontent">';
        }
        else
        {
            echo '<div class="articlecontent row">';
        }
        
        echo '<h1 class="fullarticleTitle col-xs-12">'.$article['title'].'</h1>';
        
	if(!isset($_GET['alert'])){
		echo '<span class="articledate">
		<table border="0">
		  <tr>
			<td>Last Updated: '.date('l, jS \of F Y',$article['publishdate']).'</td>
			<td>
			<a rel="nofollow" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="Print" href="'.$link->geturl().'&alert=yes"><img alt="Print" src="styles/df_template/images/print.png" width="16" height="16" /></a></td>
			<td><a href="?content=com_articles&folder=same&file=emailarticle&alert=yes&article='.$artid.'" class="email"><img  src="styles/df_template/images/emailButton.png" width="16" height="16" /></a></td>
		  </tr>
		</table>
		</span>';
	}
	else{
		echo '<span class="date">
		<table border="0">
		  <tr>
			<td>Printable Copy</td>
			<td>
			<a onclick="window.print();return false;" href="#"><img src="styles/df_template/images/print.png" width="16" height="16" /></a></td>			
		  </tr>
		</table>
		</span>';
	}
	
        if($article['articleid']=='1129'){
            
            echo '<div class="policy_left">';            
            echo '<a class="linkblock" href="?content=com_publications&folder=same&file=showpublications&show=policy">'
                    . 'Policy Briefs'
                . '</a>';
            echo '<a class="linkblock" href="?content=com_news&folder=same&file=shownews&filter=policy">'
                    . 'Policy Events'
                . '</a>';            
            echo '</div>';
            
            echo '<div class="policy_right">';
                echo $article['body'];
            echo '</div>';
        }
        else{
            echo $article['body'];
        }
        
        echo '</div>';
        
       switch($article['articleid'])
       {
           case '8':
                echo '<div class="sidepanes">';

                //enter the current experts
                $params = array('items'=>'3');
                //$this->loadmodule('showexperts','profiles',$params);

                echo '</div>';
               break;
           
           case '1111':
                echo '<div class="sidepanes">';

                //enter the current available courses
                $params = array('items'=>'6');
                $this->loadmodule('availablecourses','courses',$params);

                echo '</div>';
               break;
           
           case '1112':
                echo '<div class="sidepanes">';

                //enter the current available courses
                $params = array('items'=>'4');
                $this->loadmodule('upcomingevents','events',$params);

                echo '</div>';
               break;
           
           case '1113':
               echo '<div class="sidepanes">';
               
               $this->loadmodule('publicationspanel','relatedpublications');
               
               echo '</div>';
               break;
           
           case '1114':
               echo '<div class="sidepanes">';
               
               $this->loadmodule('archivedconsultancies','consultancies');
               
               echo '</div>';
               break;
           
           case '1130':
               echo '<div class="researchlisting">';
                    
                    $this->loadmodule('filter-research','research',['type'=>'research']);
                    
               echo '</div>';
               break;
       }
}
