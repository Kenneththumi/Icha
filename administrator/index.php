<?php 
define('LOAD_POINT',1);

define('DS', DIRECTORY_SEPARATOR);

//system root
define('ROOT', __DIR__);

//app root
define('APP', ROOT .DS. 'app');

//shell root
define('SHELL', ROOT .DS. 'shell');

//project path
define('PROJECT_PATH', ROOT .DS. 'project');

//load the user defined configurations
if(file_exists( PROJECT_PATH .DS. 'config.php' ))
    require_once PROJECT_PATH .DS. 'config.php';

//load the necessary files for the environment
if(file_exists( SHELL .DS. 'environment.php' ))
    require SHELL .DS. 'environment.php';

//initialize the App
$app = $DI->get('\Jenga\App');
$app->initialize();

/********* CREATING THE Model USING METHODS *******************
 
$record = new Model();
$level = $record->setTable('accesslevels');

$level->relateTo('users')
      ->relateOn(array(
          'users' => array(
              'localKey' => 'accessid',
              'foreignKey' => 'accessid',
              'onUpdate' => 'NO',
              'onDelete' => 'NO'
          )
      ));

foreach($level->show() as $results){
    var_dump($results);
}

******* TABLE RELATIONS (ONE-TO-ONE & ONE-TO-MANY) VIA EXTENDED CLASS ***********

*/  


/**

CODE EXAMPLES

**********************RUN FUNCTION **************************
 
$rows = $users->select('username');
$rows->select('password');

$rows->select('displayname');

$rows->join('accesslevels', TABLE_PREFIX."accesslevels.accessid = ".TABLE_PREFIX."users.accessid",'LEFT');

$rows->orderBy('username', 'ASC');
$rows->where('password','muchiri');
var_dump($rows->run());
 
*********************END OF RUN FUNCTION**********************


*********************SAVE FUNCTION USING OBJECTS**************
 
$users->username = 'Pithon';
$users->password = 'kamau';
$users->sourceid = '2';
$users->accessid = '1';
$users->enabled = 'Yes';

$users->where('userid',97)->save();

*********************END OF SAVE FUNCTION********************* 
 * 
 */