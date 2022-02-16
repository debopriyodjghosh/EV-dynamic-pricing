<?php
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `petrol_type_list` where petrol_type_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="col-12">
        <div class="w-100 mb-1">
            <div class="fs-6 text-info"><b>Name:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($name) ? $name : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6 text-info"><b>Price:</b></div>
            <div class="fs-6 ps-4"><?php echo isset($price) ? number_format($price,2) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6 text-info"><b>Status:</b></div>
            <div class="fs-5 ps-4">
                <?php 
                    if(isset($status) && $status == 1){
                        echo "<small><span class='badge rounded-pill bg-success'>Active</span></small>";
                    }else{
                        echo "<small><span class='badge rounded-pill bg-danger'>Inactive</span></small>";
                    }
                ?>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-end">
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>