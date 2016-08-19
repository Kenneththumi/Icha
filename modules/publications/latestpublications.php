<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadscripts('jquery.vTicker','yes');
$this->wrapscript("$(function(){
                                    $('#latestpublications').vTicker({ 
                                            speed: 1500,
                                            pause: 13000,
                                            animation: 'fade',
                                            mousePause: true,
                                            height: 0,
                                            showItems: 3
                                    });
                                });");

echo '<h1 class="articleTitle">Latest Publications '.($_GET['pageno']!=''? ' pg '.$_GET['pageno'] : '').'</h1>';
    
    //show the latest ICHA publications
    $publications = $this->runquery("SELECT * FROM publications ORDER BY publishdate DESC",'multiple','all');
    $publicationcount = $this->getcount($publications);
    
    echo '<div id="latestpublications">
	 <ul>';
    
    for($r=1; $r<=$publicationcount; $r++)
    {
        $publication = $this->fetcharray($publications);
        
        echo '<li><table width="100%" border="0" cellpadding="0">
              <tr>
                <td class="ptitle"><a href="?content=com_publications&folder=same&file=publicationdetails&pid='.$publication['publicationid'].'">'.$this->shortentxt($publication['title'],50).'</a></td>
              </tr>
            </table>';
        
        echo '<table width="100%" border="0" cellpadding="5" class="content">
              <tr>
                <td>'.$this->shortentxt($publication['body'],100).'</td></tr>
              <tr>
                <td colspan="2" class="pdetails">Published on: '.date('M d, Y',$publication['publishdate']).'</td>
              </tr>
              <tr>
                <td class="lowerdetails"></td>
              </tr>
              </table></li>';
    }
    echo '</ul></div>';
?>
