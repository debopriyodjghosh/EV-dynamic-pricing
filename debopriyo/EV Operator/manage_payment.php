
<?php
require_once("DBConnection.php");
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `customer_list` where customer_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
    $debts = $conn->query("SELECT SUM(amount) FROM `debt_list` where customer_id = '{$_GET['id']}'")->fetchArray()[0];
    $payments = $conn->query("SELECT SUM(amount) FROM `payment_list` where customer_id = '{$_GET['id']}'")->fetchArray()[0];
    $debts =$debts > 0 ? $debts : 0;
    $payments =$payments > 0 ? $payments : 0;
    $balance = $debts - $payments;
}
?>
<div class="container-fluid">
    <form action="" id="payment-form">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="customer_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
        <div class="form-group">
            <label for="balance" class="control-label">Remaining Balance</label>
            <input type="text"  id="balance" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($balance) ? number_format($balance,2) : 0 ?>" disabled>
        </div>
        <div class="form-group">
            <label for="amount" class="control-label">Payment Amount</label>
            <input type="number" step="any" name="amount"  id="amount" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($amount) ? $amount : 0 ?>">
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#payment-form').submit(function(e){
            e.preventDefault();
            if($('#balance').val() == 0){
                alert("Customer do not have a pending payable balance.")
                return false;
            }
            if($('#amount').val() > parseFloat($('#balance').val().replace(/\,/gi,''))){
                alert("Invalid Payment Amount.")
                return false;
            }
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./Actions.php?a=save_payment',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        uni_modal("Payable Balances of <b><?php echo $customer_code.' - '.$fullname ?></b>",'view_balance_details.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>&paid=true','large')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            if($(this).find('#customer-balance_details').length > 0){
                                location.reload()
                            }
                        })
                        if("<?php echo isset($payment_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>