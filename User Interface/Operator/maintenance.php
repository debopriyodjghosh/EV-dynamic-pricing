<?php
require 'vendor/autoload.php';
   // connect to mongodb
   //echo "Connection to EV database successfully";
   // select a database
   //$db = $m->ElectricVehicle;
   //echo "Database mydb selected";

// $collection = $db->jun_price1;
// $query = ['time' => '12' ];
// $updateResult = $collection->findOne($query);
// echo $updateResult;
//foreach ($cursor as $document) {
// $cursor = $collection->find().limit(1).sort'({'$natural:-1'})';
require_once('DBConnection.php');

$m = new MongoDB\Client;
$server = "mongodb://localhost:27017";
try{// Connecting to server
$c = new MongoDB\Client($server);}
catch(MongoConnectionException $connectionException){
print $connectionException;
exit;}
$db = $c->ElectricVehicle;
$collection = $db->jun0;
$cursor= $collection->findOne([],['sort' => ['price' => -1],'projection' => ['price' => 1]]);
$prev = $collection->find();
        

//echo $cursor["price"];


?>
<div class="card h-100 d-flex flex-column">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Maintenance</h3>
        <div class="card-tools align-middle">
            <!-- <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button> -->
        </div>
    </div>
    <div class="card-body flex-grow-1">
        <div class="col-12 h-100">
            <div class="row h-100">
                <div class="col-md-6 h-100 d-flex flex-column">
                    <div class="w-100 d-flex border-bottom border-dark py-1 mb-1">
                        <div class="fs-5 col-auto flex-grow-1"><b>Charging Type List</b></div>
                        <div class="col-auto flex-grow-0 d-flex justify-content-end">
                            <a href="javascript:void(0)" id="new_petrol_type" class="btn btn-dark btn-sm bg-gradient rounded-2" title="Add Charging Type"><span class="fa fa-plus"></span></a>
                        </div>
                    </div>
                    <div class="h-100 overflow-auto border rounded-1 border-dark">
                        <ul class="list-group">
                            <?php
                            $cat_qry = $conn->query("SELECT * FROM `petrol_type_list` order by `name` asc");
                            while($row = $cat_qry->fetchArray()):
                            ?>
                            <li class="list-group-item d-flex align-items-center">
                                <div class="col-8 flex-grow-1">
                                    <?php echo $row['name'] ?> <small><span class="text-muted">Current Price: </span><?php echo number_format($row['price'],2) ?></small>
                                </div>
                                <div class="col-2 pe-2 text-end">
                                    <?php
                                        if(isset($row['status']) && $row['status'] == 1){
                                            echo "<small><span class='badge rounded-pill bg-success'>Active</span></small>";
                                        }else{
                                            echo "<small><span class='badge rounded-pill bg-danger'>Inactive</span></small>";
                                        }
                                    ?>
                                </div>
                                <div class="col-2 d-flex justify-content-end">
                                    <a href="javascript:void(0)" class="view_petrol_type btn btn-sm btn-info text-light bg-gradient py-0 px-1 me-1" title="View Charging Type Details" data-id="<?php echo $row['petrol_type_id'] ?>" ><span class="fa fa-th-list"></span></a>
                                    <a href="javascript:void(0)" class="edit_petrol_type btn btn-sm btn-primary bg-gradient py-0 px-1 me-1" title="Edit Charging Type Details" data-id="<?php echo $row['petrol_type_id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-edit"></span></a>
                                    <a href="javascript:void(0)" class="delete_petrol_type btn btn-sm btn-danger bg-gradient py-0 px-1" title="Delete Charging Type" data-id="<?php echo $row['petrol_type_id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-trash"></span></a>
                                </div>
                            </li>
                            <?php endwhile; ?>
                            <?php if(!$cat_qry->fetchArray()): ?>
                                <li class="list-group-item text-center">No data listed yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
<!-- connect price -->
            <div class="col-md-6 h-100 d-flex flex-column">
              <!-- <a>price</a> -->
              <!-- <button class="btn-btn-small-warning" type="submit">Fetch price</button> -->
              <!-- <div class="col-md-6 h-150 d-flex flex-column">
              <button type="button" class="btn btn-warning">Fetch price</button>
              <label></label>
            </div> -->
            <div class="card">
  <div class="card-header">
    Dynamic Price of EV Charging
  </div>
  <div class="card-body">
    <h5 class="card-title">Price Now</h5>
    <p class="card-text"><?php echo $cursor["price"] ?></p>
    <a href="#" onClick="window.location.reload();" class="btn btn-primary">Fetch Price</a>

  </div>
</div>
<br>
<div class="card">
  <div class="card-header">
    Dynamic Price of EV Charging
  </div>
  <div class="card-body">
    <h5 class="card-title">Previous Price</h5>
    <p class="card-text">
        <?php 
    foreach ($prev as $document) {
        echo $document["date"] ;
        echo " : ";
        echo $document["time"] ;
        echo " > ";
        echo $document["price"] ;
        echo nl2br("\n");
        //echo " ";
        }
    ?>
        </p>
  </div>
</div>
              
            </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        // Pertol Type Functions
        $('#new_petrol_type').click(function(){
            uni_modal('Add New Pertol Type',"manage_petrol_type.php")
        })
        $('.edit_petrol_type').click(function(){
            uni_modal('Edit Pertol Type Details',"manage_petrol_type.php?id="+$(this).attr('data-id'))
        })
        $('.view_petrol_type').click(function(){
            uni_modal('Pertol Type Details',"view_petrol_type.php?id="+$(this).attr('data-id'))
        })
        $('.delete_petrol_type').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Pertol Type List?",'delete_petrol_type',[$(this).attr('data-id')])
        })
    })
    function delete_petrol_type($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_petrol_type',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }

</script>
