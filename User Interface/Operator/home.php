<?php
require "DBConnection.php";
?>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        EVUser
    </title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>




</head>
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
                                echo $petrol_type > 0 ? number_format($petrol_type) : 0;
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
                                echo $customer > 0 ? number_format($customer) : 0;
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
                                $debts = $debts > 0 ? $debts : 0;
                                $payments = $payments > 0 ? $payments : 0;
                                $balance = $debts - $payments;
                                echo $balance > 0 ? number_format($balance, 2) : 0;
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
                                $transaction = $conn->query("SELECT sum(total) as `total` FROM `transaction_list` where date(date_added) = '" . date("Y-m-d") . "' ")->fetchArray()['total'];
                                echo $transaction > 0 ? number_format($transaction) : 0;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 row-cols-4">
        <p>View Other Charging Station Pricing</p>
        <style>
            #map {
                position: absolute;
                left: 10%;
                width: 80%;
                top: 250px;
                bottom: 2.5%;
            }
        </style>
        <div id="map"></div>
        <!-- connect mongodb and fetch price -->

        <script>
            var map = L.map('map').setView([22.555, 88.371], 13);
            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 12,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoiZGVib3ByaXlvZGoiLCJhIjoiY2wyMWs4NGg0MTY0NzNibXRjbTVtazkzbSJ9.AZFISzXydyjlqVpY5IMlqQ'
            }).addTo(map);
            //https://www.mapbox.com/
            var LeafIcon = L.Icon.extend({
                options: {
                    //shadowUrl: 'iconb.png',
                    iconSize: [50, 54],
                    shadowSize: [50, 64],
                    iconAnchor: [22, 94],
                    shadowAnchor: [4, 62],
                    popupAnchor: [-3, -76]
                }
            });

            var greenIcon = new LeafIcon({
                    iconUrl: 'green.jpg'
                }),
                redIcon = new LeafIcon({
                    iconUrl: 'red.jpg'
                }),
                blackIcon = new LeafIcon({
                    iconUrl: 'black.png'
                });

            var res;
            function change(){

                $.ajax({
                    async: false,
                    type: "POST",
                    url: "fetchdata.php",
                    success: function(response) {
                        res = JSON.parse(response);
                        console.log(res);
                    }
                });
            
            //static station data

            var data = [{
                "code": "Mahindra Charging Station",
                "lat": "22.5783965",
                "lng": "88.4523585",
                "location": "1",
                "icon": res[0].icon,
                "price": res[0].price,
                "Available_ports": res[0].no_of_port_available
            }, {
                "code": "Hero Electric Charging Station",
                "lat": "22.5485285",
                "lng": "88.2904633",
                "location": "2",
                "icon": res[1].icon,
                "price": res[1].price,
                "Available_ports": res[1].no_of_port_available
            }, {
                "code": "Electric Vehicle Charging Station",
                "lat": "22.5615108",
                "lng": "88.3665607",
                "location": "3",
                "icon": res[2].icon,
                "price": res[2].price,
                "Available_ports": res[2].no_of_port_available
            }, {
                "code": "Electric Vehicle Charging Station",
                "lat": "22.5389976",
                "lng": "88.3288476",
                "location": "4",
                "icon": res[3].icon,
                "price": res[3].price,
                "Available_ports": res[3].no_of_port_available
            }, {
                "code": "Electric Vehicle Charging Station",
                "lat": "22.5298197",
                "lng": "88.2942399",
                "location": "5",
                "icon": String(res[4].icon),
                "price": res[4].price,
                "Available_ports": res[4].no_of_port_available
            }, {
                "code": "EESL Charging Station",
                "lat": "22.5788384",
                "lng": "88.4629932",
                "location": "6",
                "icon": res[5].icon,
                "price": res[5].price,
                "Available_ports": res[5].no_of_port_available
            }, {
                "code": "Hero Electric Charging Station",
                "lat": "22.5110668",
                "lng": "88.3465815",
                "location": "7",
                "icon": res[6].icon,
                "price": res[6].price,
                "Available_ports": res[6].no_of_port_available
            }, {
                "code": " Electric Vehicle Charging Station",
                "lat": "22.5126164",
                "lng": "88.3300567",
                "location": "8",
                "icon": res[7].icon,
                "price": res[7].price,
                "Available_ports": res[7].no_of_port_available
            }, {
                "code": "Electric Vehicle Charging Point",
                "lat": "22.5656085",
                "lng": "88.3923503",
                "location": "9",
                "icon": res[8].icon,
                "price": res[8].price,
                "Available_ports": res[8].no_of_port_available


            }, {
                "code": "Electric Vehicle Charging Station",
                "lat": "22.581631",
                "lng": "88.4261743",
                "location": "10",
                "icon": res[9].icon,
                "price": res[9].price,
                "Available_ports": res[9].no_of_port_available
            }];
            console.log(data);
            for (var i = 0; i < data.length; i++) {
                const popupContent =
                    '<h6>' + data[i].code + '</h6>' +
                    '<h5><strong>' + 'Price Now : ' + data[i].price + '/-' + '</strong>' + '</h5>' +
                    '<h6> Available ports:' + data[i].Available_ports + '</h6>';
                // add the marker and popup to the map.

                L.marker([data[i].lat, data[i].lng], {
                    icon: eval(data[i].icon)
                }).addTo(map).bindPopup(popupContent);

            }
        }
        var a;
        a=setInterval(change,300);
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);


            L.Control.geocoder().addTo(map);
        </script>
    </div>
</div>
<script>
    $(function() {})
</script>