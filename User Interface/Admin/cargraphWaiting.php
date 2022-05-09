<?php require 'vendor/autoload.php';
$server = "mongodb://localhost:27017";
try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$collection = $db->car;

$result=$collection->find(array("charge_status"=>"waiting","station_id"=>0));
$data=array();
foreach($result as $r){
    $data[]=$r;
}
$count=count($data);
echo ($count);
?>