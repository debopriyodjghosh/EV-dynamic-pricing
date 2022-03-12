from pymongo import MongoClient
#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")

#client = MongoClient("mongodb://localhost:27017/")
#client = MongoClient()
#client = MongoClient(‘host’, port_number)
#mydatabase = client.name_of_the_database
#mycollection = mydatabase.myTable
#collection = db.myTable

#insert
mydb = client.EV
mytab = mydb.test

rec1 = mytab.insert_one({
"id" : "11",
"Arrival_rate" : "6",
"Previous_price" :"60",
"cur_price" : "50",
"timestamp" : "1/1/2"
})

#print
cursor = mytab.find()
for record in cursor:
   print(record)

#query
for i in mytab.find({"id": "11"}):
    print(i)

#

