<?php
/*
 * The flowplayer setup
 */
//$this->loadplugin('flowplayer-6.0.3/flowplayer.min','js');

$trailer = 'https://www.youtube.com/watch?v=FAD3f2PKs30';
$extrailer = explode('?', $trailer);
            
            $url = str_replace('watch','embed',$extrailer[0]);
            $id = ltrim($extrailer[1], 'v=');
            
            $newtrailer = $url.'/'.$id;
            
echo '<div class="header">'
. '<h2>ICHA Videos</h2>'
. '</div>';
        
echo '<iframe width="270" height="200" src="'.$newtrailer.'" '
    . 'frameborder="0" allowfullscreen></iframe>';

echo '<p>Click to access our Youtube page</p>'
. '<a href="https://www.youtube.com/user/IchaRedCross" target="_blank" class="readmore">View More Videos</a>';