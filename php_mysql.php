<?php
$mode = $_POST['mode'];
$mode2 = $_POST['mode2'] ?? 'JAVA';
$server_name = '127.0.0.1:8889';
$username = 'root';
$password = '';
$db_name = 'test';
function select(){
    global $connection;
    $get_account = $_POST['account'];
    $get_password = $_POST['password'];
    $sqlQuery = "select  *  FROM `account` WHERE `newaccount` = '". $get_account ."';";
    if ($result = $connection->query($sqlQuery)) {
        $row = $result->fetch_row();
        if ($row){
            if($row[1] == $get_password){
                $json = array("state"=>"successed_login");
                $json = json_encode($json);
                $json = iconv('utf-8', 'utf-8', $json);
                echo ($json);
            }else{
                $json = array("state"=>"failded");
                $json = json_encode($json);
                $json = iconv('utf-8', 'utf-8', $json);
                echo ($json);
            }
        }else{
            $json = array("state"=>"failded");
            $json = json_encode($json);
            $json = iconv('utf-8', 'utf-8', $json);
            echo ($json);
        }   
        $result->close();
  } else {
    $json = array("state"=>"not connect DB");
    $json = json_encode($json);
    $json = iconv('utf-8', 'utf-8', $json);
    echo ($json);
  }}
function insert(){
    global $connection;
    $get_account = $_POST['account'];
    $get_password = $_POST['password'];
    $sqlQuery = "select  *  FROM `account` WHERE `newaccount` = '". $get_account ."';";
    if ($result = $connection->query($sqlQuery)) {
        $row = $result->fetch_row();
        if ($row){
            $json = array("state"=>"inused");
            $json = json_encode($json);
            $json = iconv('utf-8', 'utf-8', $json);
            echo ($json);}
        else{
            $sqlQuery = "insert INTO account (newaccount, newpassword) VALUES ('".$get_account."','" .$get_password."');";
            if ($connection->query($sqlQuery)){
                       $json = array("state"=>"successed_signup");
                       $json = json_encode($json);
                       $json = iconv('utf-8', 'utf-8', $json);
                       echo ($json);}
        }}}
function python(){ 
    global $mode ;
    global $mode2;
    $get_account = $_POST['account'];
    $get_latitude = $_POST['latitude'];
    $get_longitude = $_POST['longitude'];
    $python ="D:\program\python.exe";
    $pyscript = 'D:\test_php\send_data.py';
    $data = exec("$python $pyscript $get_account $get_latitude $get_longitude $mode");
    //$data = iconv('utf-8', 'utf-8', $data);
    $data = mb_convert_encoding($data, "UTF8", "big5");
    if($mode2 == 'JAVA'){
        echo ($data);}
    elseif($mode2 == 'python') {
        echo json_encode($data);}
    else{
        $json = array("state"=>"fuckout");
        $json = json_encode($json);
        $json = iconv('utf-8', 'utf-8', $json);
        echo ($json);
    }}
function leave_me_alone(){
    $json = array("state"=>"leave me alone");
    $json = json_encode($json);
    $json = iconv('utf-8', 'utf-8', $json);
    echo ($json);}
function update(){
    global $connection;
    $get_account = $_POST['account'];
    $get_password = $_POST['password'];
    $sqlQuery = "update account SET newpassword = '".$get_password."' WHERE newaccount = '". $get_account ."';";
    if ($connection->query($sqlQuery) === TRUE) {
        $json = array("state"=>"successed_updata");
        $json = json_encode($json);
        $json = iconv('utf-8', 'utf-8', $json);
        echo ($json);}}
function google_api(){
    $origin = $_POST['origin'];
    $origin =  str_replace(" ","",$origin);
    $Directions_API_KEY = 'AIzaSyArsyAf1qR_KqNhx0xPvuA6BjBFgnfJtOQ';
    $destination = $_POST['destination'];
    $destination =  str_replace(" ","",$destination);
    $travelMode = $_POST['travelMode']??'DRIVING';
    $URL = 'https://maps.googleapis.com/maps/api/directions/json?origin='. $origin . "&destination=" . $destination . "&key=" . $Directions_API_KEY.'&mode='.$travelMode."&alternatives=true";
    $uri = file_get_contents($URL);
    $json = json_decode($uri, true);
    if( $resonpe = $json["routes"][0]["overview_polyline"]["points"]){
        $count = count($json["routes"]);

        $String = "'".$json["routes"][0]["overview_polyline"]["points"]."'";
        $GOOGLE_RE = array("\"road[0]\"" => $String);

        for($i =1;$i<$count;$i++){  
            $String = "'".$json["routes"][$i]["overview_polyline"]["points"]."'";
            $arr = array("\"road[$i]\"" => $String);
            $GOOGLE_RE = $GOOGLE_RE+$arr;
        }
        $GOOGLE_RE = json_encode($GOOGLE_RE);
        $GOOGLE_RE = iconv('utf-8', 'utf-8', $GOOGLE_RE);
       //echo $GOOGLE_RE;
        call_java($GOOGLE_RE,$origin,$destination,$travelMode,$count);
    }elseif($resonpe2 = $json["status"]){
        $json = array("state"=>$resonpe2);
        $json = json_encode($json);
        $json = iconv('utf-8', 'utf-8', $json);
        echo ($json);
    }else{
        $json = array("state"=>"error_connect");
        $json = json_encode($json);
        $json = iconv('utf-8', 'utf-8', $json);
        echo ($json);}}
function call_java($GOOGLE_RE,$origin,$destination,$travelMode,$count){
    $java = "C:\Progra~1\Java\jdk-18.0.2\bin\java.exe";
    $javac = "C:\Progra~1\Java\jdk-18.0.2\bin\javac.exe";
    $java_json = "D:/test_php\json-20220320.jar";
    //$GOOGLE_RE = strval( $GOOGLE_RE);
    $JAVAAPI = shell_exec("$javac -classpath .;$java_json finial.java && $java -classpath .;$java_json getDir $GOOGLE_RE $origin $destination $travelMode $count");
    //$JAVAAPI = array("road" => $JAVAAPI);
    //$JAVAAPI = json_encode($JAVAAPI);
    //$JAVAAPI = iconv('utf-8', 'utf-8', $JAVAAPI);
    echo $JAVAAPI;
}
if($mode == 'login'){
    $connection = new mysqli($server_name, $username, $password,$db_name);
    select();
    $connection->close();
}elseif($mode == 'signup'){
    $connection = new mysqli($server_name, $username, $password,$db_name);
    insert();
    $connection->close();
}elseif($mode == 'weather' or $mode =='tomorrow' or $mode =='today'or $mode =='traffic' or $mode =='test'){
    python();
}elseif($mode == 'update'){
    $connection = new mysqli($server_name, $username, $password,$db_name);
    update();
    $connection->close();
}elseif($mode == 'directions'){
    google_api();
}else{
    leave_me_alone();
}
