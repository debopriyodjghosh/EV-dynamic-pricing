<h3>Welcome to Charging Station</h3>
<hr>
<div class="col-12">
    <div class="row gx-3 row-cols-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-primary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Charging Types</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $petrol_type = $conn->query("SELECT count(petrol_type_id) as `count` FROM `petrol_type_list` where `status` = 1")->fetchArray()['count'];
                                echo $petrol_type > 0 ? number_format($petrol_type) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-user-friends fs-3 text-dark"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Customers</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $customer = $conn->query("SELECT count(customer_id) as `count` FROM `customer_list` where `status` = 1 ")->fetchArray()['count'];
                                echo $customer > 0 ? number_format($customer) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-hands fs-3 text-warning"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Total Receivable</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $debts = $conn->query("SELECT SUM(amount) FROM `debt_list`")->fetchArray()[0];
                                $payments = $conn->query("SELECT SUM(amount) FROM `payment_list`")->fetchArray()[0];
                                $debts =$debts > 0 ? $debts : 0;
                                $payments =$payments > 0 ? $payments : 0;
                                $balance = $debts - $payments;
                                echo $balance > 0 ? number_format($balance,2) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-calendar-day fs-3 text-info"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Today's Sales</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $transaction = $conn->query("SELECT sum(total) as `total` FROM `transaction_list` where date(date_added) = '".date("Y-m-d")."' ")->fetchArray()['total'];
                                echo $transaction > 0 ? number_format($transaction) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
    })
</script>