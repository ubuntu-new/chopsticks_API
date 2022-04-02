<?php
$unique_id =$_POST["unique_id"];
$dir = str_replace("-","/", $_POST["directory"]);
$files = scandir($dir);
foreach ($files as $file) {
  if (strstr($file, $unique_id)) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < 10; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      ;
      $name = "$randomString.ogg";
        $f = $dir."/".$file;
        $output = shell_exec("sox $f -r 8000 -c 1 /var/www/html/monitor/playfiles/$name 2>&1");
        echo $name;

    }
}
?>