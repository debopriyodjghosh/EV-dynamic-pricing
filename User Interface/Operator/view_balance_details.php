<?php require_once('./DBConnection.php') ?>
<?php 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `customer_list` where customer_id = '{$_GET['id']}'");
foreach($qry->fetchArray() as $k => $v){
    $$k = $v;
}
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid" id="customer-balance_details">
    <?php if(isset($_GET['paid'])): ?>
        <div class="alert alert-success">
            <p class="m-0">Payment successfully added.</p>
        </div>
    <?php endif; ?>
    <div class="col-12">
        <div id="outprint_receipt">
            <div class="row">
                <div class="col-6">
                    <fieldset>
                        <legend class="text-info">Debts List</legend>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="bg-primary bg-gradient text-light">
                                    <th class="py-1 text-center">DateTime</th>
                                    <th class="py-1 text-center">Transaction Code</th>
                                    <th class="py-1 text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $dtotal = 0;
                                $debts = $conn->query("SELECT d.*,t.receipt_no FROM `debt_list` d inner join transaction_list t on d.transaction_id = t.transaction_id where d.customer_id = '{$_GET['id']}' order by strftime('%s',d.date_added) asc");
                                while($row = $debts->fetchArray()):
                                    $row['date_added'] = new DateTime($row['date_added'], new DateTimeZone(dZone));
                                    $row['date_added']->setTimezone(new DateTimeZone(tZone));
                                    $row['date_added'] = $row['date_added']->format('Y-m-d h:i A');
                                    $dtotal += $row['amount'];
                                ?>
                                <tr>
                                    <td class="px-2 py-1"><?php echo $row['date_added'] ?></td>
                                    <td class="px-2 py-1"><?php echo $row['receipt_no'] ?></td>
                                    <td class="px-2 py-1 text-end"><?php echo number_format($row['amount'],3) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(!$debts->fetchArray()): ?>
                                    <tr>
                                        <td class="text-center py-1" colspan="3">No Data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class='bg-dark bg-gradient text-light'>
                                    <th class="py-1 text-center" colspan="2">Total</th>
                                    <th class="py-1 text-end"><?php echo number_format($dtotal,2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </fieldset>
                </div>
                <div class="col-6">
                    <fieldset>
                        <legend class="text-info">Payment List</legend>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr class="bg-primary bg-gradient text-light">
                                    <th class="py-1 text-center">DateTime</th>
                                    <th class="py-1 text-center">Payment Code</th>
                                    <th class="py-1 text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $ptotal = 0;
                                $payments = $conn->query("SELECT * FROM `payment_list`  where customer_id = '{$_GET['id']}' order by strftime('%s',date_added) asc");
                                while($row = $payments->fetchArray()):
                                    $row['date_added'] = new DateTime($row['date_added'], new DateTimeZone(dZone));
                                    $row['date_added']->setTimezone(new DateTimeZone(tZone));
                                    $row['date_added'] = $row['date_added']->format('Y-m-d h:i A');
                                    $ptotal += $row['amount'];
                                ?>
                                <tr>
                                    <td class="px-2 py-1"><?php echo $row['date_added'] ?></td>
                                    <td class="px-2 py-1"><?php echo $row['payment_code'] ?></td>
                                    <td class="px-2 py-1 text-end"><?php echo number_format($row['amount'],3) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if(!$payments->fetchArray()): ?>
                                    <tr>
                                        <td class="text-center py-1" colspan="3">No Data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class='bg-dark bg-gradient text-light'>
                                    <th class="py-1 text-center" colspan="2">Total</th>
                                    <th class="py-1 text-end"><?php echo number_format($ptotal,2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="fs-5 fw-bold text-center">Remaining Balance</div>
                <center><span class="fs-4 fw-bold"><?php echo number_format($dtotal - $ptotal,2) ?></span></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-end">
                <button class="btn btn-sm btn-primary me-2 rounded-0" type="button" id="payment"><i class="fa fa-plus"></i> Add New Payment</button>
                <button class="btn btn-sm btn-success me-2 rounded-0" type="button" id="print_receipt"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#payment').click(function(){
            uni_modal('New Payment of <b><?php echo $customer_code.' - '.$fullname ?></b>',"manage_payment.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>",'')
        })
        $("#print_receipt").click(function(){
            var h = $('head').clone()
            var p = $('#outprint_receipt').clone()
            var el = $('<div>')
            p.find(".col-md-6").css({width:"50%",margin:"5px"})
            el.append(h)
            el.append('<h3 class="text-center"><?php echo $customer_code.' - '.$fullname ?>\'s Balance Report</h3>')
            el.append(p)
            var nw = window.open("","","width=1200,height=900,left=150")
                nw.document.write(el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    nw.close()
                }, 200);
        })
    })