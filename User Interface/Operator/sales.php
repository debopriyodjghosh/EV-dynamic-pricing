<div class="w-100 h-100 d-flex flex-column">
    <div class="row">
        <div class="col-8">
            <h3>Transaction</h3>
        </div>
        <div class="col-4 d-flex justify-content-end">
            <button class="btn btn-sm btn-primary rounded-0 " id="transaction-save-btn" type="button">Save Transaction</button>
        </div>
        <div class="clear-fix mb-1"></div>
        <hr>
    </div>
    <style>
        #plist .item,#item-list tr{
            cursor:pointer
        }
        .petrol-item{
            transition: transform 10s easein;
        }
        .petrol-item:hover{
            transform:scale(.98);
        }
    </style>
    <div class="col-12 flex-grow-1">
    <form action="" class="h-100" id="transaction-form">
        <div class="w-100 h-100 mx-0 row row-cols-2 bg-dark">
            <div class="col-8 h-100 pb-2 d-flex flex-column">
                <div>
                    <h3 class="text-light">Generate Charging Price</h3>
                </div>
                <div class="flex-grow-1 d-flex flex-column bg-light bg-opacity-50">
                    <div class="form-group py-2 d-flex border-bottom col-auto pb-1">
                        <label for="search" class="col-auto px-2 fw-bolder text-light">Search</label>
                        <div class="flex-grow-1 col-auto pe-2">
                            <input type="search" autocomplete="off" class="form-control form-control-sm rounded-0" id="search">
                        </div>
                    </div>
                    <div class="col-auto flex-grow-1 overflow-auto">
                            <div class="row row-cols-sm-1 row-cols-md-2 row-cols-xl-3 gx-2 gy-2 my-2 mx-1">
                                <?php 
                                require_once('DBConnection.php');

                                $petrol = $conn->query("SELECT * FROM `petrol_type_list` where `status` = 1 order by `name` asc");
                                while($row=$petrol->fetchArray()):
                                ?>
                                <div class="col">
                                    <a class="card h-100 rounded-0 petrol-item text-dark text-decoration-none" href="javascript:void(0)" data-id="<?php echo $row['petrol_type_id'] ?>" data-price="<?php echo $row['price'] ?>">
                                        <div class="card-body rounded-0 d-flex flex-column position-relative">
                                            <span class="badge bcheck bottom-0 bg-transparent border border-info rounded-circle position-absolute" style="display:none"><i class="fa fa-check text-info"></i></span>
                                            <div class="fw-bold petrol-item-name col-auto flex-grow-1">
                                                <?php echo $row['name'] ?>
                                            </div>
                                            <div class="text-end fw-bold petrol-item-price col-auto ">
                                                <?php echo number_format($row['price'],2) ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endwhile; ?>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-4 h-100 py-2">
                <div class="h-100 d-flex">
                    <div class="w-100">
                        <div class="form-group">
                            <label for="customer_id" class="control-label fw-bold text-light">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select form-select-sm rounded-0" required>
                                <option value="" disabled selected></option>
                                <?php 
                                $customer = $conn->query("SELECT * FROM `customer_list` where `status` = 1 order by `fullname`");
                                while($row = $customer->fetchArray()):
                                ?>
                                <option value="<?php echo $row['customer_id'] ?>" ><?php echo "{$row['customer_code']} - {$row['fullname']}" ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="liter" class="control-label fw-bold text-light">Unit</label>
                            <input type="number" value="0" step="any" name="liter" id="liter" class="form-control form-control-sm rounded-0 text-end" required>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="control-label fw-bold text-light">Amount</label>
                            <input type="number" value="0" step="any" name="amount" id="amount" class="form-control form-control-sm rounded-0 text-end" required>
                        </div>
                        <div class="form-group">
                            <label for="discount" class="control-label fw-bold text-light">Discount %</label>
                            <input type="number" min="0" max="100" step="any" name="discount" id="discount" class="form-control form-control-sm rounded-0 text-end" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="total" class="control-label fw-bold text-light">Total</label>
                            <input type="number" value="0"  step="any" name="total" id="total" class="form-control form-control-sm rounded-0 text-end" readonly>
                        </div>
                        <div class="form-group">
                            <label for="type" class="control-label fw-bold text-light">Payment Type</label>
                            <select name="type" id="type" class="form-select form-select-sm rounded-0" required>
                                <option value="1" selected>Cash</option>
                                <option value="2" >Credit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tendered_amount" class="control-label fw-bold text-light">Tendered Amount</label>
                            <input type="number" step="any" name="tendered_amount" id="tendered_amount" class="form-control form-control-sm rounded-0 text-end" value="0">
                        </div>
                        <div class="form-group">
                            <label for="change" class="control-label fw-bold text-light">Change</label>
                            <input type="number" step="any" name="change" id="change" class="form-control form-control-sm rounded-0 text-end"  value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="petrol_type_id" value="">
        <input type="hidden" name="price" value="">
    </form>
    </div>
</div>
<script>
    function calc_total(){
        var amount =  $('#amount').val()
        var discount =  $('#discount').val()
        if(discount > 0){
            $('#total').val(amount - (amount * (discount / 100)))

        }else{
            $('#total').val(amount)
        }
    }
    $(function(){
        $('input,select').each(function(){
            if($(this).attr('id') != "search")
            $(this).attr('disabled',true)
        })
        $('#search').on('input',function(){
            var _search = $(this).val().toLowerCase()
            $('.petrol-item').each(function(){
                var _text = $(this).text().toLowerCase()
                if(_text.includes(_search) === true){
                    $(this).parent().toggle(true)
                }else{
                    $(this).parent().toggle(false)
                }
            })
        })
        $('.petrol-item').click(function(){
            $('.petrol-item .bcheck').hide()
            $(this).find('.bcheck').show()
            $('[name="petrol_type_id"]').val($(this).attr('data-id'))
            $('[name="price"]').val($(this).attr('data-price'))
            $('input,select').removeAttr('disabled')
        })
        $('#type').change(function(){
            if($(this).val() == 2){
                $('#tendered_amount,#change').parent().hide()
                $('#tendered_amount,#change').val('0')
            }else{
                $('#tendered_amount,#change').parent().show()
                $('#tendered_amount,#change').val('0')
            }
        })
        $('#liter').on('input',function(){
            console.log($(this).is(":focus"))
            if($(this).is(":focus") == false)
            return false;
            var liter = $(this).val()
                liter = liter > 0 ? liter : 0;
            var price = $('[name="price"]').val()
            $('#amount').val(parseFloat(price * liter))
            calc_total()
        })
        $('#amount').on('input',function(){
            if($(this).is(":focus") == false)
            return false;
            var amount = $(this).val()
                amount = amount > 0 ? amount : 0;
            var price = $('[name="price"]').val()
            $('#liter').val(parseFloat(amount / price))
            calc_total()
        })
        $('#discount').on('input',function(){
            calc_total()
        })
        $('#tendered_amount').on('input',function(){
            var total = $('#total').val()
            var tendered = $(this).val()
            $('#change').val(tendered-total)
        })
        $('#transaction-save-btn').click(function(){
            if($('[name="petrol_type_id"]').val() <= 0){
                alert("Please Fill the form First.")
                return false;
            }
            $('#transaction-form').submit()
        })
        $('#transaction-form').submit(function(e){
            e.preventDefault()
            if($('[name="change"]').val() < 0){
                alert("Tendered Amount is invalid.")
                $('#tendered_amount').focus()
                return false;
            }
            $('#transaction-save-btn').attr('disabled',true)
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $.ajax({
                url:'./Actions.php?a=save_transaction',
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
                    $('#transaction-save-btn').attr('disabled',false)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        setTimeout(() => {
                            uni_modal("RECEIPT","view_receipt.php?id="+resp.transaction_id)
                        }, 1000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#transaction-save-btn').attr('disabled',false)
                }
            })
        })
        // $('#transaction-form input').keydown(function(e){
        //     if(e.which == 13){
        //     e.preventDefault()
        //     return false
        //     }
        // })
    })
</script>