<?php
$search = $_GET['articlesearch'];

$link = new navigation;

if(isset($_POST['cmd'])){
    
	$searchtxt = $_POST['searchbox'];
	$search = [];
        
        //add courses table
	$qString = "SELECT courseid,coursename,description,publishdate FROM courses WHERE coursename LIKE '%$searchtxt%' ORDER BY publishdate DESC";
        
        $courseSearch = $this->runquery($qString,'multiple','all');
        $courseCount = $this->getcount($courseSearch);
        
        if($courseCount >= 1){
            
            $search['courses'] = $courseSearch;
            $search['courses_count'] = $courseCount;
        }
        
        //add articles table
        $qString = "SELECT articleid,title,body,publishdate FROM articles WHERE title LIKE '%$searchtxt%' ORDER BY publishdate DESC";
        
        $articleSearch = $this->runquery($qString,'multiple','all');
        $articleCount = $this->getcount($articleSearch);
        
        if($articleCount >= 1){
            
            $search['articles'] = $articleSearch;
            $search['articles_count'] = $articleCount;
        }
        
        //add events table
        $qString = "SELECT eventid,title,description,startdate FROM events WHERE title LIKE '%$searchtxt%' ORDER BY startdate DESC";
        
        $eventsSearch = $this->runquery($qString,'multiple','all');
        $eventsCount = $this->getcount($eventsSearch);
        
        if($eventsCount >= 1){
            
            $search['events'] = $eventsSearch;
            $search['events_count'] = $eventsCount;
        }
        
        //add publications table
        $qString = "SELECT publicationid,title,body,publishdate FROM publications WHERE title LIKE '%$searchtxt%' ORDER BY publishdate DESC";
       
        $pubsSearch = $this->runquery($qString,'multiple','all');
        $pubsCount = $this->getcount($pubsSearch);
        
        if($pubsCount >= 1){
            
            $search['pubs'] = $pubsSearch;
            $search['pubs_count'] = $pubsCount;
        }
}

echo '<h1 class="fullarticleTitle">'.$rawCount.' Search Results for "'.$searchtxt.'"</h1>';

echo '<div id="itemContainer">';
echo '<button onclick="window.history.go(-1)" class="btn btn-warning pull-left" role="button">Back</button>'
        . '<div class="row clear"></div>';

$searchqry = $this->runquery($qString,'multiple','all',$_GET['pageno']);
$srchCount = $this->getcount($searchqry);

//search results
if(array_key_exists('courses',$search)){           

    $courseCount = $search['courses_count'];    
    echo '<h2 class="fullpage">Courses ('.$courseCount.')</h2>';
    
    for($i=1; $i<=$courseCount; $i++){

        $course = $this->fetcharray($search['courses']);
        
        $alink = '?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];
        $decription = $this->shortentxt(strip_tags($course['description']), 150);

        echo '<div class="itemList">';   

        echo '<header>';
            echo '<h2><a href="'.$alink.'">'.$course['coursename'].'</a></h2>';
        echo '</header>';    

        echo '<div class="itemBody">';
            echo $decription;

            echo '<p>';

            if($course['brochure'] != ''){

                $brochure = json_decode($course['brochure']);

                $mlink = MEDIA_PATH.'pdf/'.$brochure->filename;
                echo '<a href="'.$mlink.'" target="_blank" class="download">Download Brochure</a>';
            }

            echo '<a href="'.$alink.'" class="read_more">Read More</a></p>';
            echo '</div>';
        echo '</div>';
    }
}

//article table results
if(array_key_exists('articles',$search)){           

    $articlesCount = $search['articles_count'];
    
    echo '<h2 class="fullpage">Pages ('.$articleCount.')</h2>';
    
    for($i=1; $i<=$articlesCount; $i++){
        
        $article = $this->fetcharray($search['articles']);
        
        $alink = '?content=com_articles&artid='.$article['articleid'];
        
        echo '<div class="itemList">';   

        echo '<header>';
            echo '<h2><a href="'.$alink.'">'.$article['title'].'</a></h2>';
        echo '</header>';    

        echo '<div class="itemBody">';
            echo $this->shortentxt(strip_tags($article['body']), 150);

            echo '<p>';

            echo '<a href="'.$alink.'" class="read_more">Read More</a></p>';
            echo '</div>';
        echo '</div>';
    }
}

//events table results
if(array_key_exists('events',$search)){           

    $eventsCount = $search['events_count'];
    
    echo '<h2 class="fullpage">Events ('.$eventsCount.')</h2>';
    
    for($i=1; $i<=$eventsCount; $i++){
        
        $event = $this->fetcharray($search['events']);
        
        $alink = '?content=com_events&folder=same&file=eventdetails&id='.$event['eventid'];
        
        echo '<div class="itemList">';   

        echo '<header>';
            echo '<h2><a href="'.$alink.'">'.$event['title'].'</a></h2>';
        echo '</header>';    

        echo '<div class="itemBody">';
            echo $this->shortentxt(strip_tags($event['description']), 150);

            echo '<p>'
            .'<a href="'.$alink.'" class="read_more">Read More</a>'
            .'</p>';
            
            echo '</div>';
        echo '</div>';
    }
}

//publications table results
if(array_key_exists('pubs',$search)){           

    $pubsCount = $search['pubs_count'];
    
    echo '<h2 class="fullpage">Publications & Papers ('.$pubsCount.')</h2>';
    
    for($i=1; $i<=$pubsCount; $i++){
        
        $pub = $this->fetcharray($search['pubs']);
        
        $alink = '?content=com_publications&folder=same&file=publicationdetails&pid='.$pub['publicationid'];
        
        echo '<div class="itemList">';   

        echo '<header>';
            echo '<h2><a href="'.$alink.'">'.$pub['title'].'</a></h2>';
        echo '</header>';    

        echo '<div class="itemBody">';
            echo $this->shortentxt(strip_tags($pub['body']), 150);

            echo '<p>'
            .'<a href="'.$alink.'" class="read_more">Read More</a>'
            .'</p>';
            
            echo '</div>';
        echo '</div>';
    }
}

echo '</div>';
