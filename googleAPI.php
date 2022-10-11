<?php 
function google_api(){
    $origin = $_POST['origin'];
    $Directions_API_KEY = 'AIzaSyAwmCjKvDJAeGXmM8PIev9F5ML_vxMY52c';
    $destination = $_POST['destination'];
    $travelMode = $_POST['travelMode'];
    $URL = 'https://maps.googleapis.com/maps/api/directions/json?origin='. $origin . "&destination=" . $destination . "&key=" . $Directions_API_KEY.'&mode='.$travelMode;
    $uri = file_get_contents($URL);
    //echo $uri;
    $json = json_decode($uri, true);

    //$json = iconv('utf-8', 'utf-8', $json);
    //echo ($json);
    $resonpe = $json["routes"][0]["overview_polyline"]["points"];
    $GOOGLE_RE = array("road" => $resonpe);
    $GOOGLE_RE = json_encode($GOOGLE_RE);
    $GOOGLE_RE = iconv('utf-8', 'utf-8', $GOOGLE_RE);
    echo ($GOOGLE_RE);}
?>