<?php
$file_path = 'D:\race\Police220712_2342_.csv';
$fp = fopen($file_path,'r');
/*while (($line = fgetcsv($fp)) !== FALSE) {
    echo '<pre>';
    print_r($line);
    echo '</pre>';*/

echo fgetcsv($fp)[4];
fclose($fp);
?>
