<?php require 'vendor/autoload.php';
$server = "mongodb://localhost:27017";
$dt =(int) $_POST['data'];
try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$collection = $db->car;

$result=$collection->find(array("charge_status"=>"Wait","station_id"=>$dt));
$data=array();
foreach($result as $r){
    $data[]=$r;
}
$count=count($data);
echo ($count);
?>