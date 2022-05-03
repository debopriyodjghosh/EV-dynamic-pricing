from calendar import weekday
from hashlib import new
from pymongo import MongoClient
import datetime
import numpy as np

curret_Time= datetime.datetime.now()
curret_Time-=datetime.timedelta(minutes=curret_Time.minute,seconds=curret_Time.second)
print (curret_Time)


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
id=id=str(datetime.datetime.now().date)+str(datetime.datetime.now().hour)
my_Db.jun1.insert_one({
    "no_of_port":13,
    "charging_time":20
})
my_Db.jun2.insert_one({
    "no_of_port":10,
    "charging_time":25
})
my_Db.jun3.insert_one({
    "no_of_port":14,
    "charging_time":20
})
my_Db.jun4.insert_one({
    "no_of_port":5,
    "charging_time":25
})
my_Db.jun5.insert_one({
    "no_of_port":7,
    "charging_time":20
})
my_Db.jun6.insert_one({
    "no_of_port":3,
    "charging_time":30
})
my_Db.jun7.insert_one({
    "no_of_port":7,
    "charging_time":30
})
my_Db.jun8.insert_one({
    "no_of_port":7,
    "charging_time":30
})
my_Db.jun9.insert_one({
    "no_of_port":7,
    "charging_time":25
})
my_Db.jun10.insert_one({
    "no_of_port":4,
    "charging_time":30
})

my_Db.jun_price10.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price1.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price2.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price3.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price4.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price5.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price6.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price7.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price8.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
my_Db.jun_price9.insert_one({
    "id":id,
    "date":str(datetime.datetime.now().date()),
    "time":datetime.datetime.now().hour,
    "price":8
})
'''dt = datetime.datetime.now()
arr=np.array([[dt.day, dt.weekday, dt.hour, dt.month, dt.year, datetime.datetime.now().timetuple().tm_yday, datetime.datetime.utcnow().isocalendar()[1]]])
r=my_Db.jun_price1.find({"time":t+datetime.timedelta(hours=1)})
for rr in r:
    print(rr['price'])
print("sdfsf")
weekday0=[0,1,0,0,0,1,0,1,0,0,0,0,0,1,0]
weekday1=[0,1,0,0,0,1,0,0,0,0,1,0,0,0,0]
weekday2=[0,1,0,0,0,0,0,0,0,0,1,0,0,0,0]
print(weekday2[5])'''
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

'''def start_grid(self,id,stop):
        row=1
        self.e_name="sheet"+str(id)
        sheet = self.book.add_sheet(self.e_name)
        while True:
            if(stop()):
                break
            c=car.Car()
            c_time=str(datetime.now())
            sheet.write(row,0,id)
            sheet.write(row, 1, c_time)
            sheet.write(row, 2, c.reg_id)
            sheet.write(row, 3, c.b_status)
            time.sleep(random.randint(1,30))
            print(row)
            row+=1'''
'''    def start(self):
        self.book = Workbook()
        power_grid = list()
        t=list()
        self.stop_threads=False
        for i in range(10):
            power_grid.append(pw.PowerGrid(i))
            t.append(i)
            t[i] = threading.Thread(target=power_grid[i].start_grid, args=(i,self.book,lambda : self.stop_threads))
            #t2 = threading.Thread(target=self.start_grid, args=(2,self.book,lambda : self.stop_threads))
            t[i].start()
            #t2.start()
        time.sleep(30)
        for i in range(10):
            self.stop_threads=True
            t[i].join()
            #t2.join()
            e="data.xls"
            self.book.save(e)
s=Simulator()
s.start()'''