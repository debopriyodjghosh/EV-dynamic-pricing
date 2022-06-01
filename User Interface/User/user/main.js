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

//static station data
var res;

        function change() {

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
            //console.log(data);
            // for (var i = 0; i < data.length; i++) {
            //     var datum = input[i];
            //     if (!d[datum.key]) {
            //         d[datum.key] = [];
            //     }
            //     d[datum.key].push(datum.val);
            // }
            //add marker on map
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
        a = setInterval(change, 300); //lane highlight
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
//lane highlight
// var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
// }).addTo(map);

let lat, long, dlat, dlan = 0.0;
L.Control.geocoder().addTo(map);
//get location gps
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

    var html = "";

    //console.log(marker);
    //console.log(latitude, longitude);
    //console.log(dlat, dlan);
    //calculate distance
    function distance(lat1, lon1, lat2, lon2, unit) {
        var radlat1 = Math.PI * lat1 / 180
        var radlat2 = Math.PI * lat2 / 180
        var theta = lon1 - lon2
        var radtheta = Math.PI * theta / 180
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist)
        dist = dist * 180 / Math.PI
        dist = dist * 60 * 1.1515
        if (unit == "K") {
            dist = dist * 1.609344
        }
        if (unit == "N") {
            dist = dist * 0.8684
        }
        return dist
    }
    //nearest charging station
    for (var i = 0; i < data.length; i++) {
        // if this location is within 5 KM of the user, add it to the list
        if (distance(latitude, longitude, data[i].lat, data[i].lng, "K") <= 5) {
            html += '<p>' + data[i].location + ' - ' + data[i].code + '</p>';
            dlat = parseFloat(data[i].lat);
            dlan = parseFloat(data[i].lng);
            lat = latitude;
            long = longitude;
            //console.log(dlat, dlan);

        }
    }
    $('#nearbystops').append(html);





    //routing
    L.Routing.control({
            waypoints: [
                L.latLng(latitude, longitude),
                L.latLng(dlat, dlan)
            ],
            routeWhileDragging: true,
            geocoder: L.Control.Geocoder.nominatim(),
        })
        .addTo(map);


    // button type = "submit"

})


function r() {
    var a = 'https://www.google.com/maps/dir/?api=1&origin=';
    var b = a + lat + ',' + long + '&destination=' + dlat + ',' + dlan;
    console.log(b);
    var win = window.open(b, '_blank');
    win.focus();
}
//on click lat long
// map.on('click', function(e) {
//     console.log(e.latlng); //So you can see if it's working
//     lat = e.latlng.lat;
//     lng = e.latlng.lng;
// });