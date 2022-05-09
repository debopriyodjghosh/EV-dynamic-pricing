<?php require 'vendor/autoload.php';
$server = "mongodb://localhost:27017";
try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$collection = $db->jun_price2;
$result=$collection->find([],['sort' => ['$natural'=>-1],'limit'=>1]);
$data=array();
foreach($result as $r){
    $data[]=$r;
}

echo json_encode($data);
?>