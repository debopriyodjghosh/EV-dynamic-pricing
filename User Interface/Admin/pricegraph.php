<?php require 'vendor/autoload.php';
$server = "mongodb://localhost:27017";
$dt = $_POST['data'];

try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$data=array();

    $collection_name='jun'.$dt;
    $collection = $db->$collection_name;
    $result=$collection->find([],['sort' => ['$natural'=>-1],'limit'=>1]);
    foreach($result as $r){
        $data[]=$r;
    }

echo json_encode($data);
?>
