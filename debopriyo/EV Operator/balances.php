
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Customer Payable Balances</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="25%">
                <col width="25%">
                <col width="25%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Customer Code</th>
                    <th class="text-center p-0">Customer</th>
                    <th class="text-center p-0">Remaining Balance</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM `customer_list` order by `fullname` asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                        $debts = $conn->query("SELECT SUM(amount) FROM `debt_list` where customer_id = '{$row['customer_id']}'")->fetchArray()[0];
                        $payments = $conn->query("SELECT SUM(amount) FROM `payment_list` where customer_id = '{$row['customer_id']}'")->fetchArray()[0];
                        $debts =$debts > 0 ? $debts : 0;
                        $payments =$payments > 0 ? $payments : 0;
                        $row['balance'] = $debts - $payments;
                ?>
                <tr class="">
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-1 px-2"><?php echo $row['customer_code'] ?></td>
                    <td class="py-1 px-2"><?php echo $row['fullname'] ?></td>
                    <td class="py-1 px-2 text-end"><?php echo number_format($row['balance']) ?></td>
                    <td class="text-center py-1 px-2">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['customer_id'] ?>' data-title= '<?php echo $row['customer_code'].' - '.$row['fullname'] ?>' href="javascript:void(0)">View Details</a></li>
                            <li><a class="dropdown-item payment_data" data-id = '<?php echo $row['customer_id'] ?>' data-title= '<?php echo $row['customer_code'].' - '.$row['fullname'] ?>' href="javascript:void(0)">Add Payment</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
               
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal('Payable Balances of <b>'+$(this).attr('data-title')+'</b>',"view_balance_details.php?id="+$(this).attr('data-id'),'large')
        })
        $('.payment_data').click(function(){
            uni_modal('New Payment of <b>'+$(this).attr('data-title')+'</b>',"manage_payment.php?id="+$(this).attr('data-id'),'')
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:3 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_stock',
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