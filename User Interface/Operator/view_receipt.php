<?php
session_start();
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT t.*,p.name as petrol FROM `transaction_list` t inner join `petrol_type_list` p on t.petrol_type_id = p.petrol_type_id where t.transaction_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
$customer_qry = $conn->query("SELECT * FROM customer_list where customer_id = '{$customer_id}'");
@$cust_res = $customer_qry->fetchArray();
if($cust_res){
    $customer = $cust_res['customer_code'] .' - ' . $cust_res['fullname'];
}else{
    $customer = "N//A";
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div id="outprint_receipt">
        <div class="text-center fw-bold lh-1">
            <span>Charging Station</span><br>
            <small>Receipt</small>
        </div>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Date:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo date("Y-m-d",strtotime($date_added)) ?></span>
        </div>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Receipt No:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo $receipt_no ?></span>
        </div>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Customer:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo $customer ?></span>
        </div>
        <table class="table table-striped">
            <colgroup>
                <col width="15%">
                <col width="65%">
                <col width="20%">
            </colgroup>  
            <thead>
            <tr class="bg-dark bg-opacity-75 text-light">
                <th class="py-0 px-1">Unit</th>
                <th class="py-0 px-1">Charging</th>
                <th class="py-0 px-1">Amount</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-1 py-0 align-middle"><?php echo $liter ?></td>
                    <td class="px-1 py-0 align-middle">
                        <div class="fw light lh-1">
                            <small><?php echo $petrol ?></small><br>
                            <small>(<?php echo number_format($price,2) ?>)</small>
                        </div>
                    </td>
                    <td class="px-1 py-0 align-middle text-end"><?php echo number_format($amount,2) ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="px-1 py-0" colspan="2">Discount <span class="fw-lighter">(<?php echo $discount ?>%)</span></th>
                    <th class="px-1 py-0 text-end"><?php echo number_format($amount * ($discount / 100),2) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Total</th>
                    <th class="px-1 py-0 text-end"><?php echo number_format($total,2) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Tendered Amount</th>
                    <th class="px-1 py-0 text-end"><?php echo number_format($tendered_amount,2) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Change</th>
                    <th class="px-1 py-0 text-end"><?php echo number_format($change,2) ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Payment Status:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo $type == 1 ? "Cash" : "Credit" ?></span>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-end mt-2">
        <button class="btn btn-sm btn-success me-2 rounded-0" type="button" id="print_receipt"><i class="fa fa-print"></i> Print</button>
        <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
    </div>
</div>
<script>
    $(function(){
        $("#print_receipt").click(function(){
            var h = $('head').clone()
            var p = $('#outprint_receipt').clone()
            var el = $('<div>')
            el.append(h)
            el.append(p)
            var nw = window.open("","","width=1200,height=900,left=150")
                nw.document.write(el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()

                        $('#uni_modal').on('hide.bs.modal',function(){
                            if($(this).find('#outprint_receipt').length > 0 && '<?php echo !isset($_GET['view_only']) ?>' == 1){
                                location.reload()
                            }
                        })
                        if('<?php echo !isset($_GET['view_only']) ?>' == 1)
                        $('#uni_modal').modal('hide')
                    }, 150);
                }, 200);
        })
        $('#uni_modal').on('hide.bs.modal',function(){
            if($(this).find('#outprint_receipt').length > 0){
                location.reload()
            }
        })
        $('#uni_modal').modal('hide')
        $('#delete_data').click(function(){
            _conf("Are you sure to delete <b>"+<?php echo $receipt_no ?>+"</b>?",'delete_data',['<?php echo $transaction_id ?>'])
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_transaction',
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