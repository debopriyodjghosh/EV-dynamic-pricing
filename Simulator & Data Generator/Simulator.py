from ast import While
import xlwt
import threading
import Car as car
import Charging_Station as pw
from datetime import datetime
import time
import random
import xlwt
from xlwt import Workbook
class Simulator:
    book=None
    e_name=None
    stop_threads=None
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
import random
import datetime
import Car as Car
import Charging_Station as cs
import pymongo
class t:
    thred_Exit_Variable=None
    thred_Pause_Variable=None
    thread_synchronize_arrival=None
    thread_synchronize_dis=None
    def __init__(self):
        self.thred_Pause_Variable=False
        self.thred_Exit_Variable=0
        self.thread_sychronize_arrival=True
        self.thread_synchronize_dis=True
#connect
try:
    client = pymongo.MongoClient("mongodb://localhost:27017/")
    print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")
my_Db=client.ElectricVehicle
my_Collection=my_Db.Car
def arrival_Thread(charging_station,thread_object,rand_List,starting_Time,my_Collection):
    i=1
    while (i<60):
        thread_object.thread_synchronize_arrival=True
        starting_Time=starting_Time+datetime.timedelta(minutes=1)
        index=random.randint(0,14)
        no_Of_Cars=rand_List[index]
        while(no_Of_Cars!=0):

            while(thread_object.thred_Pause_Variable):
                continue
            if(charging_station.no_Of_Ports_Available>0):
                c=Car.Car(starting_Time,"Charging")
                charging_station.no_Of_Ports_Available-=1

            elif(charging_station.no_Of_Ports_Available==0):
                #print("sfsgfsgf")
                #continue
                c=Car.Car(starting_Time,"Wait")

            charging_station.arrival_rate+=1
            rec = my_Collection.insert_one({
                "station_id" : charging_station.id,
                "reg_id":c.reg_id,
                "b_status":c.b_status,
                "arr_Time":c.arr_Time,
                "dis_Time":c.dis_Time,
                "charge_Start_Time":c.charge_Start_Time,
                "need_charge":c.need_charge,
                "charge_status":c.charge_status,
                })

            no_Of_Cars-=1
        thread_object.thread_synchronize_arrival=False
        while(thread_object.thread_synchronize_arrival or thread_object.thread_synchronize_dis):
            continue
        time.sleep(.5)
        print(str(charging_station.no_Of_Ports_Available)+"*****")
        i+=1

def Dispatch_Thread(charging_station,thread_object,current_Time,my_Collection):         
            i=1
            j=0
            while (i<=59):
                thread_object.thread_synchronize_dis=True
                current_Time=current_Time+datetime.timedelta(minutes=1)
                dispatch_Result=my_Collection.find({"charge_status":"Charging","dis_Time":{ '$gte':current_Time+datetime.timedelta(seconds=-1), '$lt':(current_Time+datetime.timedelta(minutes=1)) }})
                for dispatch_information in dispatch_Result:
                    thread_object.thred_Pause_Variable=True
                    dis_id=dispatch_information["reg_id"]
                    my_Collection.update_many({"reg_id":dis_id},{"$set":{"charge_status":"Finish",}})
                    j+=1
                    #print(j)
                    charging_station.no_Of_Ports_Available+=1
                    charging_station.service_Rate+=1
                    wait_Result=my_Collection.find({"charge_status":"Wait"}).limit(1).sort("arr_Time",1)
                    for wait_information in wait_Result:
                        #print("hhhh")
                        wait_id=wait_information["reg_id"]
                        need_charge=wait_information["need_charge"]
                        set_dis_time=current_Time+datetime.timedelta(seconds=(.5*need_charge*60))
                        my_Collection.update_many({"reg_id":wait_id},{"$set":{"charge_status":"Charging","dis_Time":set_dis_time,"charge_Start_Time":current_Time}})
                        charging_station.no_Of_Ports_Available-=1
                thread_object.thred_Pause_Variable=False
                thread_object.thread_synchronize_dis=False
                while(thread_object.thread_synchronize_arrival or thread_object.thread_synchronize_dis):
                    continue
                time.sleep(.5)
                print(str(charging_station.no_Of_Ports_Available)+"+++++")
                i+=1

jun=cs.Charging_Station(1,15,8,9)
th=t()
rand_List=[0,1,0,4,0,1,0,1,0,0,0,2,0,1,0]
starting_Time="2022-3-22 17:00:00"
date_format_str = '%Y-%m-%d %H:%M:%S'
given_time = datetime.datetime.strptime(starting_Time, date_format_str)
for i in range(2):
    t1 = threading.Thread(target = arrival_Thread, args=(jun,th,rand_List,given_time,my_Collection))
    t2= threading.Thread(target = Dispatch_Thread,args=(jun,th,given_time,my_Collection))
    t1.start()
    t2.start()
    t2.join()
    t1.join()
    given_time+=datetime.timedelta(hours=1)
    print("Arrival Rate=",jun.arrival_rate)
    print("Service Rate=",jun.service_Rate)
    queue_Size=my_Collection.count_documents({"charge_status":{"$in":["Charging","Wait"]}})
    print("queue size"+str(queue_Size))
    jun.service_Rate=0
    jun.arrival_rate=0

