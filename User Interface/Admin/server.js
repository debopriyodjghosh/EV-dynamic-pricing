var MongoClient = require('mongodb').MongoClient;
var url = "mongodb://localhost:27017/";
var data1=0;
var data2=0;
MongoClient.connect(url, function(err, db) {
if (err) throw err;
var dbo = db.db("ElectricVehicle");
var query = { charge_status: "Charging", station_id:0 };
dbo.collection("car").find(query).toArray(function(err, result) {
if (err) throw err;
// console.log(result);
console.log(result.length);
data1=result.length;

});
var query2 = { charge_status: "wait", station_id:0 };
dbo.collection("car").find(query2).toArray(function(err, result) {
if (err) throw err;
// console.log(result);
console.log(result.length);
data2=result.length;
db.close();
});
});
