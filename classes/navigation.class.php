<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

class navigation extends database {
    
    var $php_self;
    var $rows_per_page = 10; //Number of records to display per page
    var $total_rows = 0; //Total number of rows returned by the query
    var $links_per_page = 5; //Number of links to display per page
    var $append = ""; //Paremeters to append to navigation links
    var $sql = "";
    var $debug = false;
    var $conn = false;
    var $page = 1;
    var $max_pages = 0;
    var $offset = 0;

    /*
     * Constructor
     *
     * @param resource $connection Mysql connection link
     * @param string $sql SQL query to paginate. Example : SELECT * FROM users
     * @param integer $rows_per_page Number of records to display per page. Defaults to 10
     * @param integer $links_per_page Number of links to display per page. Defaults to 5
     * @param string $append Parameters to be appended to navigation links 
     */

    public function __construct(){
        
        if(isset($_GET['admin'])&&!isset($_SESSION['logid'])&&$_GET['admin']!='mod_login')
        {
                redirect('?content=com_frontpage');
        }
    }

    public function buildMenu($accessalias){

        $linksquery = $this->runquery("SELECT * FROM menus "
                . "WHERE menugroup = ".$this->getgroupid($accessalias)." "
                . "AND parentid='0' "
                . "ORDER BY linkorder ASC",'multiple','all');
        
        $linkscount = $this->getcount($linksquery);

        if($linkscount>=1){
            
            $parentmenu = '<div class="navbar" role="navigation">'
                    . '<div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                        </button>
                      </div>';

            $parentmenu .= '<div class="navbar-collapse collapse">'
                    . '<ul class="sm navbar-nav" id="main-menu">';
            
            for($i=1; $i<=$linkscount; $i++){

                $linksarray = $this->fetcharray($linksquery);
                $linksstrip = strtolower($this->strClean(str_replace(' ','',$linksarray['linkname'])));
                
                if($linksarray['menulink']==$this->geturl()){
                    
                    $current = ' current';
                }
                
                $sublinkquery = $this->runquery("SELECT * FROM menus WHERE parentid='".$linksarray['menuid']."' ORDER BY linkorder ASC");
                $subcatcount = $this->getcount($sublinkquery);
                
                if($subcatcount >= 1){

                    $parentmenu .= '<li>'
                            //. 'class="'.$linksstrip.$current.'" '
                            . '<a href="'.$linksarray['menulink'].'">'
                                . '<span>'.$linksarray['linkname'].'</span>'
                            . '<b class="caret caret-right"></b>'
                            . '</a>';
                    
                    $parentmenu .= $this->buildSubMenu($sublinkquery,$linksstrip);
                    $parentmenu .= '</li>';
                    
                    
                }
                else{
                    
                    $parentmenu .= '<li>'
                            . '<a class="mainlinks '.$current.'" href="'.$linksarray['menulink'].'"><span>'.$linksarray['linkname'].'</span></a>'
                            . '</li>';
                }

                unset($current);
            }

            $parentmenu .= '</ul>'
                    . '</div>'
                    . '</div>';

            //return the main menu
            echo $parentmenu;
        }
    }

    function buildSubMenu($menuquery, $subname){
        
        //build the submenu
        $id = $subname;
        $subcatcount = $this->getcount($menuquery);
        
        //$menu['class'] = '_parent rootVoice {menu: \'menu_'.$subname.'\'}';                        
        $submenu = '<ul class="dropdown-menu" id="menu_'.$id.'">';

        for($j=1; $j<=$subcatcount; $j++){
            
            $subarray = $this->fetcharray($menuquery);
            $ssubname = strtolower(str_replace(' ','',$subarray['linkname']));
            
            //$submenu .= '<li>';
            
            switch($subarray['linkname']){

                case 'Training and Education':

                    $cats = $this->runquery("SELECT DISTINCT categoryid FROM courses WHERE enabled='Yes' AND parentid='0' ORDER BY categoryid ASC");
                    $catno = $this->getcount($cats);

                    $training = $this->getByAlias('training-and-education');
                    
                    $submenu .= '<li>';
                    $submenu .= '<a href="'.$training['menulink'].'" >'.$subarray['linkname']
                            . '<b class="caret caret-right"></b>'
                            . '</a>';
                    
                    $submenu .= '<ul class="dropdown-menu">';
                    $submenu .= '<li class="submenutitle" >Available Course Categories</li>';

                    for($t=1; $t<=$catno; $t++)
                    {
                        $catid = $this->fetcharray($cats);

                        //$alink = SITE_PATH.'?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];
                        $tlink = SITE_PATH.'?content=com_courses&folder=same&file=showcourses&catid='.$catid['categoryid'];
                        
                        $category = $this->runquery("SELECT name FROM categories WHERE categoryid = '".$catid['categoryid']."'",'single');
                        $submenu .= '<li>'
                                    . '<a href="'.$tlink.'" class="'.$ssubname.'">'.$category['name'].'</a>'
                                . '</li>';
                    }
                    $submenu .= '</ul>';

                    $submenu .= '</li>';                    
                break;

                default :

                    $sublinkquery = $this->runquery("SELECT * FROM menus WHERE parentid='".$subarray['menuid']."' ORDER BY linkorder ASC");
                    $ssubcatcount = $this->getcount($sublinkquery);
                    
                    if($ssubcatcount >= 1){

                        $alink = $subarray['menulink'];
                        $submenu .= '<li>'
                                . '<a href="'.$alink.'" class="dropdown-toggle">'.$subarray['linkname'].'</a>';
                        $submenu .= $this->buildSubMenu($sublinkquery,$ssubname)
                                    . '</li>';
                    }
                    else{

                        $alink = $subarray['menulink'];
                        $submenu .= '<li>'
                                . '<a href="'.$alink.'" class="'.$current.'">'.$subarray['linkname'].'</a>'
                                . '</li>';
                    }
                break;
            }

            //$submenu .= '</li>';
            unset($current);
        }

        $submenu .= '</ul>';

        //echo $submenu;
        //unset($subcatcount,$submenu);

        return $submenu;
    }

    function getByAlias($alias){
        
        $link = $this->runquery("SELECT * FROM menus WHERE linkalias='$alias'",'single');
        return $link;
    }
    
    //load the parent menu links
    function loadParents($name)
    {
            
        $menu = $this->rawquery("SELECT * FROM menus WHERE menugroup='".navigation::getgroupid($name)."' AND parentid='0' ORDER BY linkorder,linkname ASC");				
        $menucount = $this->getcount($menu);

        $menulink = '<ul>';
        
        for($i=1; $i<=$menucount; $i++){
            
            $menuarray = $this->fetcharray($menu);

            $menulink .= '<li>';
            
            if(substr_count($menuarray['menulink'],'http')==0)
            {
                if($menuarray['menulink']==$this->geturl())
                {
                    $current = ' current';
                }

                $menulink .= '<a href="'.SITE_PATH.$menuarray['menulink'].$menuadd.'" class="'.strtolower(str_replace(' ','',$name)).'links'.$current.'">'.$icon.'<p>'.$namedata.$menuarray['linkname'].'</p></a>';
            }
            else
            {
                if($menuarray['menulink']==$this->geturl())
                {
                    $current = ' current';
                }

                $menulink .= '<a href="'.$menuarray['menulink'].$menuadd.'" class="'.strtolower(str_replace(' ','',$name)).$current.'">'.$icon.'<p>'.$namedata.$menuarray['linkname'].'</p></a>';
            }
            
            $menulink .= '</li>';

            unset($current);
        }
        
        $menulink .= '</ul>';

        echo $menulink;
    }
    
    //all variable after name have been converted to array(type,showname,showicons,icopath,combine_menu,namedata)
    function loadmenu($name,$params=array())
    {
            if(count($params)==0)
            {
                //set the default values
                $params = array(
                            'type' => 'dbased',
                            'showname' => 'no',
                            'showicons' => 'no',
                            'combine_menu' => 'no',
                            'namedata' => ''
                        );
            }
            else {
                //check if certain default values are set or not
                foreach($params as $key=>$value)
                {
                    if(!isset($params['type']))
                    {
                        $params['type'] = 'dbased';
                    }

                    if(!isset($params['combine_menu']))
                    {
                        $params['combine_menu'] = 'no';
                    }
                }
            }

            //combine assign the array values to variables
            $type = $params['type'];
            $showname = $params['showname'];
            $showicons = $params['showicons'];
            $icopath = $params['icopath'];
            $icosuffix = $params['icosuffix'];
            $combine_menu = $params['combine_menu'];
            $namedata = $params['namedata'];                

            if($showname=='yes')
            {
                echo '<h3>'.$name.'</h3>';
            }

            if($type=='dbased')
            {
                    $menu = $this->rawquery("SELECT * FROM menus WHERE menugroup='".navigation::getgroupid($name)."' AND enabled='Yes' ORDER BY linkorder,linkname ASC");				
                    $menucount = $this->getcount($menu);

                    for($i=1; $i<=$menucount; $i++)
                    {
                        $menuarray = $this->fetcharray($menu);

                        if($showicons=='yes')
                        {
                            $icon = '<img src="'.$icopath.strtolower(str_replace(' ','_',trim($menuarray['linkname']))).$icosuffix.'.png"  />';
                        }

                        if(substr_count($menuarray['menulink'],'http')==0)
                        {
                            if($menuarray['menulink']==$this->geturl())
                            {
                                $current = ' current';
                            }

                            $menulink = '<a href="'.SITE_PATH.$menuarray['menulink'].$menuadd.'" class="'.strtolower(str_replace(' ','',$name)).'links'.$current.'">'.$icon.'<p>'.$namedata.$menuarray['linkname'].'</p></a>';
                        }
                        else
                        {
                            if($menuarray['menulink']==$this->geturl())
                            {
                                $current = ' current';
                            }

                            $menulink = '<a href="'.$menuarray['menulink'].$menuadd.'" class="'.strtolower(str_replace(' ','',$name)).$current.'">'.$icon.'<p>'.$namedata.$menuarray['linkname'].'</p></a>';
                        }

                        unset($current);

                        if($combine_menu=='no')
                        {
                                echo $menulink;
                        }
                        else
                        {
                                $fullmenu .= $menulink;
                        }
                    }

                    if($combine_menu=='yes')
                    {
                        return $fullmenu;
                    }
            }
            else if($type=='file')
            {
                    include('modules/menus/'.$name.'.php');
            }
    }

    //redirect to the specified link based on the user type
    function urlreturn($linkname,$additionaldata='',$addSession='yes')
    {
        $menu = $this->runquery("SELECT menulink FROM menus WHERE linkname='".$linkname."' AND menugroup='".navigation::getgroupid($_SESSION['usertype'])."'","single",'all','','read');

        return SITE_PATH.$menu['menulink'].$additionaldata;
    }
    
    function returnByAlias($linkalias)
    {
        $menu = $this->runquery("SELECT menulink FROM menus WHERE linkalias='".$linkalias."'","single",'all','','read');

        return SITE_PATH.$menu['menulink'];
    }

    //function to check if a certain link is current bring viewed
    function checkURL($linkname)
    {
        $link = $this->runquery("SELECT menulink FROM menus WHERE linkname='".$linkname."' OR linkalias='".$linkname."'",'single');
        
        if($this->geturl()==$link['menulink']){
            
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    //removes some of the variables in the current url and inserts new one if need be
    function urlreplace($rmvData,$addData='',$stripmsg='no')
    {
            //this strips the messages variables sent at the end of the url
            if($stripmsg=='yes')
            {
                    $url = str_replace('?','',$this->geturl());

                    //parse string into array
                    parse_str($url,$urlarray);

                    //remove msg variable
                    if(array_key_exists('msgvalid',$urlarray)==true)
                    {
                            unset($urlarray['msgvalid']);
                    }
                    elseif(array_key_exists('msgerror',$urlarray))
                    {
                            unset($urlarray['msgerror']);
                    }

                    $rawUrl = '?'.$this->buildurl($urlarray);

                    //an amp; is added into the url so toa hiyo
                    $currentUrl = str_replace('amp;','',$rawUrl);
            }
            else
            {
                    $currentUrl = $this->geturl();
            }

            return str_replace($rmvData,$addData,$currentUrl);
    }

    function trackHits($artid='')
    {
            if(isset($_GET['artid']))
            {
                    $artid = $_GET['artid'];

            }

            $hitquery = $this->runquery("SELECT hits FROM articles WHERE articleid='$artid'",'single');
            $hitcount = $hitquery['hits'];

            if($hitcount==0)
            {
                    $hits = 1;
            }
            else
            {
                    $hits = ($hitcount + 1);
            }

            $hitarray = array('hits'=>$hits);

            $this->dbupdate('articles',$hitarray,"articleid='$artid'");
    }

    /**
     * Set debug mode
     *
     * @access public
     * @param bool $debug Set to TRUE to enable debug messages
     * @return void
     */
    function setDebug($debug) {
            $this->debug = $debug;
    }

    function buildurl($urlarray,$getvar='')
    {
        if($getvar!='')
        {
            return http_build_query($urlarray,$getvar);
        }
        else
        {
            return http_build_query($urlarray);
        }
    }

    public function getgroupid($name)
    {
            $aid = $this->runquery("SELECT * FROM accesslevels WHERE accesslevel='$name'",'single');
            $id = $this->runquery("SELECT groupid FROM menugroups WHERE accesslevelid LIKE '%".$aid['accessid']."%'",'single');

            return $id['groupid'];
    }

    public function getgroupname($name)
    {
            $name = $this->runquery("SELECT * FROM menugroups WHERE groupid = '".$name."'",'single');

            return $name['menuname'];
    }

    //return the previously executed url
    function geturl()
    {
            $url = '?';

            foreach($_GET as $var=>$value){
                
                $url .= $var.'='.$value.'&';
            }

            $trim_url = rtrim($url,'&');

            $url = str_replace('?','',$trim_url);

            //parse string into array
            parse_str($url,$urlarray);

            //remove msg variable
            if(array_key_exists('msgvalid',$urlarray)==true)
            {
                    unset($urlarray['msgvalid']);
            }
            elseif(array_key_exists('msgerror',$urlarray))
            {
                    unset($urlarray['msgerror']);
            }
            elseif(array_key_exists('order',$urlarray))
            {
                    unset($urlarray['order']);
            }
            elseif(array_key_exists('pageno',$urlarray))
            {
                    unset($urlarray['pageno']);
            }
            elseif(array_key_exists('task',$urlarray))
            {
                    unset($urlarray['task']);
            }

            $rawUrl = '?'.$this->buildurl($urlarray);

            //an amp; is added into the url so toa hiyo
            return $currentUrl = str_replace('amp;','',$rawUrl);
    }

    function createPgNav($sql,$rowsperpage)
    {

        $this->loadextraClass('paginate');		

            $paginator = new pagination;

            $squery = $this->rawquery($sql);
            $pCount = $this->getcount($squery);

            $pages = $paginator->calculatePages($pCount,$rowsperpage,$_GET['pageno']);

            if($rowsperpage > 0)
                $pagecount = ceil($pCount/$rowsperpage);

            if($pagecount > 1)
            {
                    //load the styles for the navigation
                    $skin = new template;
                    $skin->loadstyles('pgNav');

                    $pgNav = '<ul id="pgNav">';

                    if($pages['current']!=1)
                    {
                        $pgNav .= '<li class="firstpg">';
                        $pgNav .= '<a href="'.$this->geturl().'&pageno='.$pages['previous'].'">previous page </a></li>';

                        $pgNav .= '<li><a href="'.$this->geturl().'&pageno=1" class="pgNumbers">1</a></li>';

                        $pgNav .= '<li><span class="pgDots"> ... </span></li>';
                    }

                    $pgNumbers = $pages['pages'];

                    //set the start and stop ranges
                    $splitno = str_split($pages['current'],1);
                    //var_dump($splitno);

                    if(count($splitno)==1)
                    {
                        $range['start'] = 0; 
                    }
                    else
                    {
                        for($r=0; $r<=count($splitno)-2; $r++)
                        {
                            $preceder .= $splitno[$r];
                        }

                        $range['start'] = $preceder.'0';                               
                    }

                    $range['end'] = $range['start']+10;
                    //var_dump($range);

                    if($pages['current'] >= $range['start'] && $pages['current'] <= $range['end'] )
                    {//var_dump($range);
                        for($i=$range['start']; $i<=$range['end']-1; $i++)
                        {
                            if($pgNumbers[$i]!='')
                            {
                                if($pages['current']!=$pgNumbers[$i])
                                {
                                        $pgNav .= '<li><a href="'.$this->geturl().'&pageno='.$pgNumbers[$i].'" class="pgNumbers">'.$pgNumbers[$i].'</a></li>';
                                }
                                else
                                {
                                        $pgNav .= '<li class="pgNumbers">'.$pgNumbers[$i].'</li> ';
                                }
                            }
                        }
                    }

                    //show last page
                    if($pages['current']!=$pages['last'])
                    {
                        $pgNav .= '<li class="pgDots"> ... </li>';

                        $pgNav .= '<li><a href="'.$this->geturl().'&pageno='.$pages['last'].'" class="pgNumbers">'.$pages['last'].'</a></li>';

                        $pgNav .= '<li class="nextpg"><a href="'.$this->geturl().'&pageno='.$pages['next'].'">next page</a></li>';

                        $pgNav .= '<li class="lastpg"><a href="'.$this->geturl().'&pageno='.$pages['last'].'">last page</a></li>';	
                    }

                    $pgNav .= '</ul>';

                    echo $pgNav;
            }
    }
}