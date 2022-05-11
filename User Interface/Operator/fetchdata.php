<?php require 'C:/xampp/htdocs/EV-dynamic-pricing/User Interface/Admin/vendor/autoload.php';
$server = "mongodb://localhost:27017";
try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$data=array();
for ($i=0; $i < 10; $i++) { 
    $collection_name='jun'.$i;
    $collection = $db->$collection_name;
    $result=$collection->find([],['sort' => ['$natural'=>-1],'limit'=>1]);
    foreach($result as $r){
        $data[]=$r;
    }
}
echo json_encode($data);
?>