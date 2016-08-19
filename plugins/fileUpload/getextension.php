  <?php
 // This function reads the extension of the file. 
 // It is used to determine if the file is an image by checking the extension. 
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
?>