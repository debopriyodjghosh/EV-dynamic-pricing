<html>

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
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>



</head>

<body>
    <style>
        #map {
            height: 600px;
        }
    </style>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([22.555, 88.371], 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
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
        //static marker
        /*Mahindra Charging Station, Near Tank No 3, Opp Goutams, Street Number 24, Action Area I, New Town, West Bengal 700156
        L.marker([22.5783965, 88.4523585], {
            icon: greenIcon
        }).addTo(map);
        //EESL Charging Station 22.5788384,88.4629932  Hatgacha, ECI CHURCH New Town, Sub-Central Business District(Action Area 1), Action Area I, Newtown, Kolkata, West Bengal 700156
        L.marker([22.5788384, 88.4629932], {
            icon: greenIcon
        }).addTo(map);
        //Electric Vehicle Charging Station  NH34, Jatragachhi, Deshbandhu Nagar, Newtown, Kolkata, West Bengal 700156
        L.marker([22.5872191, 88.469606], {
            icon: greenIcon
        }).addTo(map);
        //NKDA Charging Station Unnamed Road, Jatragachhi, Deshbandhu Nagar, Rekjuani, Kolkata, West Bengal 700156
        L.marker([22.5778496, 88.4558645], {
            icon: greenIcon
        }).addTo(map);*/

        //class marker
        L.marker([22.5783965, 88.4523585], {
            icon: greenIcon
        }).addTo(map).bindPopup("1 Mahindra Charging Station");

        L.marker([22.5485285, 88.2904633], {
            icon: greenIcon
        }).addTo(map).bindPopup("2 Hero Electric Charging Station");

        L.marker([22.5615108, 88.3665607], {
            icon: redIcon
        }).addTo(map).bindPopup("3 Electric Vehicle Charging Station");

        L.marker([22.5389976, 88.3288476], {
            icon: redIcon
        }).addTo(map).bindPopup("4 Electric Vehicle Charging Station");

        L.marker([22.5298197, 88.2942399], {
            icon: blackIcon
        }).addTo(map).bindPopup("5 Electric Vehicle Charging Station");

        L.marker([22.5788384, 88.4629932], {
            icon: redIcon
        }).addTo(map).bindPopup("6 EESL Charging Station");

        L.marker([22.5110668, 88.3465815], {
            icon: greenIcon
        }).addTo(map).bindPopup("7 Hero Electric Charging Station");

        L.marker([22.5126164, 88.3300567], {
            icon: blackIcon
        }).addTo(map).bindPopup("8 Electric Vehicle Charging Station");

        /* L.marker([22.5409597, 88.2979774], {
             icon: blackIcon
         }).addTo(map).bindPopup("9 Electric Vehicle Charging Station");*/

        L.marker([22.5656085, 88.3923503], {
            icon: greenIcon
        }).addTo(map).bindPopup("9 Electric Vehicle Charging Point");

        L.marker([22.581631, 88.4261743], {
            icon: greenIcon
        }).addTo(map).bindPopup("10 Electric Vehicle Charging Station");

        L.marker([22.5462715, 88.311114], {
            icon: redIcon
        }).addTo(map).bindPopup("11 Electric Vehicle Charging Station");





        // var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(map);


        L.Control.geocoder().addTo(map);
        //navigate to current location 
        // if (!navigator.geolocation) {
        //     console.log("Your browser doesn't support geolocation feature!")
        // } else {
        //     setInterval(() => {
        //         navigator.geolocation.getCurrentPosition(getPosition)
        //     }, 5000);
        // };
        // var marker, circle, lat, long, accuracy;

        // function getPosition(position) {
        //     // console.log(position)
        //     lat = position.coords.latitude
        //     long = position.coords.longitude
        //     accuracy = position.coords.accuracy

        //     if (marker) {
        //         map.removeLayer(marker)
        //     }

        //     if (circle) {
        //         map.removeLayer(circle)
        //     }

        //     marker = L.marker([lat, long])
        //     circle = L.circle([lat, long], {
        //         radius: accuracy
        //     })

        //     var featureGroup = L.featureGroup([marker, circle]).addTo(map)

        //     map.fitBounds(featureGroup.getBounds())

        //     console.log("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy)
        // }


        navigator.geolocation.getCurrentPosition(position => {
            const {
                coords: {
                    latitude,
                    longitude
                }
            } = position;
            var marker = new L.marker([latitude, longitude], {
                draggable: true,
                autoPan: true
            }).addTo(map);

            //console.log(marker);
            console.log(latitude, longitude);

            //routing
            L.Routing.control({
                    waypoints: [
                        L.latLng(latitude, longitude),
                        L.latLng(22.5485285, 88.2904633)
                    ],
                    routeWhileDragging: true,
                    geocoder: L.Control.Geocoder.nominatim(),
                })
                .addTo(map);



        })
    </script>

</body>

</html>