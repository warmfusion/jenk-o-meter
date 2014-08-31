<?php
//We want to control servos right...?
include("SerialServo.class.php");


define("ARDUINIO_URL", "http://192.168.1.201/");
define("HUDSON_URL", "http://192.168.6.151:8082");
//define("VIEW", "/view/Jenk-O-Meter");
define("VIEW", "/view/Jenk-O-Meter");

$xml=file_get_contents(HUDSON_URL . VIEW . "/api/xml?tree=jobs[color]");

// This defines the position each colour should be pointing towards when
// all builds are of that color.
// 0 is LEFT, 180 is RIGHT - More or less....
//Calibrated
$keywords= array (
	"blue" => 25, 
	"yellow" => 105,
	"red" => 175);

$counts = array();

$servo = new SerialServo("/dev/ttyUSB0");

if (substr_count($xml,"red") > 0){
	$servo->set($keywords['red']);
}elseif( substr_count($xml,"yellow") > 0 ){
        $servo->set($keywords['yellow']);
}elseif(substr_count($xml,"blue") > 0){
        $servo->set($keywords['blue']);
}else{
        $servo->set(0);
}

?>
