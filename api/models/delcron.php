<?php
date_default_timezone_set('Asia/Tbilisi');
// PHP program to delete a file named gfg.txt 
// using unlike() function

$date = date("Y/m/d",strtotime('-6 month'));

$files = glob('/var/www/html/monitor/playfiles/*'); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file)) {
       if( date("Y-m-d H", filemtime($file)) < date('Y-m-d H')) {
           unlink($file);
       }

        //// delete file
    }
}






?> 