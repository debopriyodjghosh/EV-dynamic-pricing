
<?php 
$dfrom = isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d",strtotime(date("Y-m-d")." -1 week"));
$dto = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");
?>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Sales Report</h3>
    </div>
    <div class="card-body">
        <h5>Filter</h5>
        <div class="row align-items-end">
            <div class="form-group col-md-2">
                <label for="date_from" class="control-label">Date From</label>
                <input type="date" name="date_from" id="date_from" value="<?php echo $dfrom ?>" class="form-control rounded-0">
            </div>
            <div class="form-group col-md-2">
                <label for="date_to" class="control-label">Date To</label>
                <input type="date" name="date_to" id="date_to" value="<?php echo $dto ?>" class="form-control rounded-0">
            </div>
            <div class="form-group col-md-4 d-flex">
                <div class="col-auto">
                    <button class="btn btn-primary rounded-0" id="filter" type="button"><i class="fa fa-filter"></i> Filter</button>
                    <button class="btn btn-success rounded-0" id="print" type="button"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="clear-fix mb-2"></div>
        <div id="outprint">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="25%">
                <col width="25%">
                <col width="25%">
                <col width="25%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Date</th>
                    <th class="text-center p-0">Receipt No</th>
                    <th class="text-center p-0">Charging Info</th>
                    <th class="text-center p-0">Sales Details</th>
                    <th class="text-center p-0">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $user_where = "";
                if($_SESSION['type'] != 1){
                    $user_where = " and t.user_id = '{$_SESSION['user_id']}' ";
                }
                $sql = "SELECT t.*,p.name as petrol FROM  `transaction_list` t inner join petrol_type_list p on t.petrol_type_id = p.petrol_type_id where date(t.date_added) between '{$dfrom}' and '{$dto}' {$user_where}  order by strftime('%s',date_added) asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                ?>
                <tr>
                    <td class="text-center p-1"><?php echo $i++; ?></td>
                    <td class="py-1 px-2"><?php echo date("Y-m-d",strtotime($row['date_added'])) ?></td>
                    <td class="py-1 px-2"><a href="javascript:void(0)" class="view_data" data-id="<?php echo $row['transaction_id'] ?>"><?php echo $row['receipt_no'] ?></a></td>
                    <td class="py-1 px-2">
                        <div class="lh-1">
                            <span><?php echo $row['petrol'] ?></span><br>
                            <span><?php echo number_format($row['price'],2) ?></span>
                        </div>
                    </td>
                    <td class="py-1 px-2">
                        <div class="lh-1">
                            <span><span class="text-muted">Liters: </span><?php echo number_format($row['liter'],2) ?></span><br>
                            <span><span class="text-muted">Amount: </span><?php echo number_format($row['amount'],2) ?></span><br>
                            <span><span class="text-muted">Discount: </span><?php echo number_format($row['amount'] * ($row['discount'] / 100),2) ?> <small>(<?php echo $row['discount'] ?>%)</small></span><br>
                            <span><span class="text-muted">Type: </span><?php echo $row['type'] == 1? 'Cash' : 'Credit' ?></span>
                        </div>
                    </td>
                    <td class="py-1 px-2 text-end"><?php echo number_format($row['total'],2) ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if(!$qry->fetchArray()): ?>
                    <th colspan="6"><center>No Transaction listed in selected date.</center></th>
                <?php endif; ?>
               
            </tbody>
        </table>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal('Receipt',"view_receipt.php?view_only=true&id="+$(this).attr('data-id'),'')
        })
        $('#filter').click(function(){
            location.href="./?page=sales_report&date_from="+$('#date_from').val()+"&date_to="+$('#date_to').val();
        })
        
        $('table td,table th').addClass('align-middle')

        $('#print').click(function(){
            var h = $('head').clone()
            var p = $('#outprint').clone()
            var el = $('<div>')
            el.append(h)
            if('<?php echo $dfrom ?>' == '<?php echo $dto ?>'){
                date_range = "<?php echo date('M d, Y',strtotime($dfrom)) ?>";
            }else{
                date_range = "<?php echo date('M d, Y',strtotime($dfrom)).' - '.date('M d, Y',strtotime($dto)) ?>";
            }
            el.append("<div class='text-center lh-1 fw-bold'>Charging Station Sales Report<br/>As of<br/>"+date_range+"</div><hr/>")
            p.find('a').addClass('text-decoration-none')
            el.append(p)
            var nw = window.open("","","width=1200,height=900,left=150")
                nw.document.write(el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                    }, 150);
                }, 200);
        })
        // $('table').dataTable({
        //     columnDefs: [
        //         { orderable: false, targets:3 }
        //     ]
        // })
    })
    
</script>