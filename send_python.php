<?php
$get_username = $_POST['username'];
$get_password = $_POST['password'];
$get_latitude = $_POST['latitude'];
$get_longitude = $_POST['longitude'];
$mode = $_POST['mode'];
$python ="D:\program\python.exe";
$pyscript = 'D:\test_php\send_data.py';
$json = array(
    "tw"=>"taiwan",
    'johnny' => 'ugly',
    'henry'=>'handsome guy'
);
 $json = json_encode($json);


if($get_account and $get_password){
    $json = iconv('utf-8', 'utf-8', $json);
    echo ($json);
}
else{
    $data = exec("$python $pyscript $get_latitude $get_longitude $mode");
    $data = iconv('utf-8', 'utf-8', $data);
    echo ($data);
}

?>