<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$nav = new navigation();

$training = $nav->getByAlias('training-and-education');
$research = $nav->getByAlias('research');
$policy = $nav->getByAlias('policy-and-advocacy');

echo '<ul>'
    . '<li><a href="'.$training['menulink'].'">'.$training['linkname'].'</a></li>'
    . '<li><a href="'.$research['menulink'].'">'.$research['linkname'].'</a></li>'
    . '<li><a href="'.$policy['menulink'].'">'.$policy['linkname'].'</a></li>'
. '</ul>';