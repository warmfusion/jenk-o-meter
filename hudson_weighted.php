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
	"blue" => 20, 
	"yellow" => 105,
	"red" => 175);

// Some things are more serious that others...
// Use the weight value here to multiply the
// number of failing builds to increase/decrease
// severity
$keywordWeight = array (
	"blue" => 1, 
	"yellow" => 4,
	"red" => 10);


$counts = array();

$total =0;
foreach ($keywords as $color => $targetAngle){
	$keyCount = substr_count($xml, $color);
	// Add the keyword weighting into concideration
	$keyCount *= $keywordWeight[$color];
	$counts[$color] = $keyCount;	
	$total = $total+$keyCount;
}

$weightedAngle = 0;

foreach ($counts as $color => $count){
	$weightedAngle += ($count / $total) * $keywords[$color];	
}

$weightedAngle=(int)$weightedAngle;

print("Setting guage to position: $weightedAngle\n");

$servo = new SerialServo("/dev/ttyUSB0");
$servo->set($weightedAngle);

?>
