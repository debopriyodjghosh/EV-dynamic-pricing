from hashlib import new
from pymongo import MongoClient
'''import random
import pickle as pkl
from uuid import uuid4

class Car:
    id=None
    def __init__(self):
        self.id=random.randint(1,10)'''
#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")
my_Db=client.ElectricVehicle
my_Collection=my_Db.Car
#my_Car=Car()
#picked_data = pkl.dumps(my_Car)
#id=my_Car.id
#print(id)
'''rec = my_Collection.insert_one({
        "id" : my_Car.id,
        "Car" : "picked_data",
        "Price": "12"
        })'''
my_Collection.delete_many({})
'''6239b052e4a2613ed1641a5d
station_id
:
1
reg_id
:
511
b_status
:
63
arr_Time
:
2022-03-22T15:02:00.000+00:00
dis_Time
:
2022-03-22T15:09:00.000+00:00
charge_Start_Time
:
2022-03-22T15:02:00.000+00:00
need_charge
:
14
charge_status
:
"Charging"'''

'''
from datetime import datetime
from datetime import timedelta
# Given timestamp in string
time_str = '28/2/2021 23:50:22.234513'
date_format_str = '%d/%m/%Y %H:%M:%S.%f'
# create datetime object from timestamp string
given_time = datetime.strptime(time_str, date_format_str)
print('Given timestamp: ', given_time)
n = 15
# Add 15 minutes to datetime object
final_time = given_time + timedelta(minutes=n)
print('Final Time (15 minutes after given time ): ', final_time)
# Convert datetime object to string in specific format 
final_time_str = final_time.strftime('%d/%m/%Y %H:%M:%S.%f')
print('Final Time as string object: ', final_time_str)'''

# Python program showing
# how to kill threads
# using set/reset stop
# flag
 
'''from calendar import c
import threading
import time
class a:
    v=None
    def __init__(self):
        self.v=True
def run(c):
    while True:
        print('thread running')
        global stop_threads
        if (c.v!=True):
            print(c.v)
            break


 
stop_threads = False
c=a()
t1 = threading.Thread(target = run, args=(c,))
t1.start()
time.sleep(10)
stop_threads = True
c.v=False
t1.join()
print('thread killed successfull')'''