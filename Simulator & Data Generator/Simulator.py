import threading
import Car as car
import Charging_Station as pw
from datetime import datetime
import time
import random
import random
import datetime
import Car as Car
import Charging_Station as cs
import pymongo
from csv import writer
import numpy as np
import pickle
import math

"""Simulator Class"""
class Simulator:
    book=None
    e_name=None
    stop_threads=None


"""Thread Class For synchronization"""  
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


"""Arrival Thread this Thread Control the new Comming Cars and Communicate with MongoDb"""
def arrival_Thread(charging_station,thread_object,rand_List,starting_Time,my_Collection1,my_Collection2,c_time):
    i=1

    """i increment as minutes"""
    while (i<60):
        
        
        """synchronizer of two thread"""
        thread_object.thread_synchronize_arrival=True

        starting_Time=starting_Time+datetime.timedelta(minutes=1)
        
        """select no of cars genarate at that minute from the list"""
        index=random.randint(0,19)
        no_Of_Cars=rand_List[index]

        """create the Cars"""
        while(no_Of_Cars!=0):
            

            """If some Car are dispatchd from the station it will wait"""
            while(thread_object.thred_Pause_Variable):
                continue

            """If no. of port available the create the cars with the status charging and Update the datbase of the Station"""
            if(charging_station.no_Of_Ports_Available>0):
                c=Car.Car(starting_Time,c_time,"Charging")
                charging_station.no_Of_Ports_Available-=1

                """update database"""
                status = "available" if charging_station.no_Of_Ports_Available!=0 else "occupied"
                icon= "greenIcon" if charging_station.no_Of_Ports_Available!=0 else "redIcon"
                my_Collection2.update_many({"date":str(starting_Time.date()),"time":starting_Time.hour},{"$set":{"no_of_port_available":charging_station.no_Of_Ports_Available,"status": status,"icon":icon,}})

            #If no port available the create the cars with the status wait
            elif(charging_station.no_Of_Ports_Available==0):
                c=Car.Car(starting_Time,c_time,"Wait")
                print("wait")

            """update The Car data base with new Car"""
            charging_station.arrival_rate+=1
            rec = my_Collection1.insert_one({
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

        """synchronizer of two thread"""
        thread_object.thread_synchronize_arrival=False
        
        """wait for other thread Completion of the minute"""
        while(thread_object.thread_synchronize_arrival or thread_object.thread_synchronize_dis):
            continue
        time.sleep(.5)
        
        
        print(str(charging_station.no_Of_Ports_Available)+"*****")
        i+=1


"""Dispatch Thread this Thread Control the available Carsb in the station and Communicate with MongoDb"""
def Dispatch_Thread(charging_station,thread_object,current_Time,my_Collection1,my_Collection2,c_time):         
            i=1
            j=0

            """i increment as minutes"""
            while (i<=59):

                """synchronizer of two thread"""
                thread_object.thread_synchronize_dis=True

                current_Time=current_Time+datetime.timedelta(minutes=1)

                """search the cars that are need to dispatch at the minute"""
                dispatch_Result=my_Collection1.find({"station_id":jun.id,"charge_status":"Charging","dis_Time":{ '$gte':current_Time+datetime.timedelta(seconds=-1), '$lt':(current_Time+datetime.timedelta(minutes=1)) }})
                
                """update the database with sa=tatus charging to Finish"""
                for dispatch_information in dispatch_Result:
                    thread_object.thred_Pause_Variable=True
                    dis_id=dispatch_information["reg_id"]
                    my_Collection1.update_many({"reg_id":dis_id},{"$set":{"charge_status":"Finish",}})
                    j+=1
                    charging_station.no_Of_Ports_Available+=1
                    

                    """update the station database"""
                    status = "available" if charging_station.no_Of_Ports_Available!=0 else "occupied"
                    icon= "greenIcon" if charging_station.no_Of_Ports_Available!=0 else "redIcon"
                    my_Collection2.update_many({"date":str(current_Time.date()),"time":current_Time.hour},{"$set":{"no_of_port_available":charging_station.no_Of_Ports_Available,"status": status,"icon":icon,}})
                    
                    charging_station.service_Rate+=1


                    """after dispatchng check any cars are in waiting stage then start Charging them"""
                    wait_Result=my_Collection1.find({"charge_status":"Wait","station_id":jun.id}).limit(1).sort("arr_Time",1)
                    for wait_information in wait_Result:
                        print("wait to charging")
                        print(wait_information)
                        wait_id=wait_information["reg_id"]
                        need_charge=wait_information["need_charge"]
                        set_dis_time=current_Time+datetime.timedelta(seconds=((c_time/100)*need_charge*60))
                        my_Collection1.update_many({"reg_id":wait_id},{"$set":{"charge_status":"Charging","dis_Time":set_dis_time,"charge_Start_Time":current_Time}})
                        charging_station.no_Of_Ports_Available-=1
                        

                        """update station database"""
                        status = "available" if charging_station.no_Of_Ports_Available!=0 else "occupied"
                        icon= "greenIcon" if charging_station.no_Of_Ports_Available!=0 else "redIcon"
                        my_Collection2.update_many({"date":str(current_Time.date()),"time":current_Time.hour},{"$set":{"no_of_port_available":charging_station.no_Of_Ports_Available,"status": status,"icon":icon,}})
                
                """synchronizer of two thread"""
                thread_object.thred_Pause_Variable=False
                thread_object.thread_synchronize_dis=False
                while(thread_object.thread_synchronize_arrival or thread_object.thread_synchronize_dis):
                    continue
                time.sleep(.5)
                print(str(charging_station.no_Of_Ports_Available)+"+++++")
                i+=1



def getprice(t):
    predictedLambda=getPredictedLambda(t)
    predictedSum=0
    for predicted in predictedLambda:
        print("predicted: "+str(predicted))
        predictedSum = predictedSum + predicted
    for i in range(1,11):
        previousPrice=getPreviousPrice(i-1)
        optimalLambda=getLambdaOptimal(predictedSum,i)
        difference=predictedLambda[i] - optimalLambda
        print(difference)
        nextPrice = previousPrice + getGamma()*difference
        print(nextPrice[0])
        return nextPrice[0]



def getPredictedLambda(t):
    #arr = np.array([[11,6,0,1,2015,11,2]])
    predicted=[]
    predicted.append(0)
    for i in range(1,11):
        dt = t
        arr=np.array([[dt.day, dt.weekday(), dt.hour, dt.month, dt.timetuple().tm_yday, t.isocalendar()[1]]])
        predict = model[i].predict(arr)
        predicted.append(predict)
    return predicted



def getPreviousPrice(i):
    collection_name = "jun" + str(i)
    my_collection=my_Db[collection_name]
    #mytab = mydb.test
    #letest price fetch
    new=dict(my_collection.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['price']
    print("previous: "+str(prevPrice))
    return prevPrice



def getLambdaOptimal(sum,i):
    lambSum = sum[0]
    serviceRate=getServicerate()
    #print(serviceRate)
    uppersum=0
    lowersum=0
    for rate in range(1,11):
        x = math.sqrt(serviceRate[i]*serviceRate[rate])-serviceRate[rate]
        uppersum = uppersum+x
        lower=serviceRate[rate]/serviceRate[i]
        lower=math.sqrt(lower)
        lowersum=lowersum+lower
    upper_side=lambSum+uppersum
    lambdaOptimal=upper_side/lowersum
    print("optimal: "+str(lambdaOptimal))
    return lambdaOptimal



def getGamma():
    gamma = 1/10.0
    return gamma


def getServicerate():
    li=[]
    li.append(0)
    for i in range(1,11):
        ports=getNumberofPorts(i-1)
        chargetime=getchargeTime(i-1)
        serviceRate=(60/chargetime)*ports
        li.append(serviceRate)

    return li



def getNumberofPorts(i):
    no_of_port=0
    collection_name = "jun" + str(i)
    my_collection=my_Db[collection_name]
    result=my_collection.find().limit(1)
    for r in result:
        no_of_port=r['no_of_port']
    return no_of_port



def getchargeTime(i):
    charging_time=0
    collection_name = "jun" + str(i)
    my_collection=my_Db[collection_name]
    result=my_collection.find().limit(1)
    for r in result:
        charging_time=r['charging_time']
    return charging_time


"""MongoDb Connection"""
try:
    client = pymongo.MongoClient("mongodb://localhost:27017/")
    print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")
my_Db=client.ElectricVehicle
my_Collection1=my_Db.Car


"""load the machine Learning Model"""
model=[]
model.append(0)
for i in range (1,11):
   file='F://xampp//htdocs//EV-dynamic-pricing//ML_Model//'+'JunModel'+str(i)+'.pkl'
   m1=pickle.load(open(file,'rb'))
   model.append(m1)

"""Set Simulator Date Time"""
starting_Time=datetime.datetime.now()
starting_Time-=datetime.timedelta(minutes=starting_Time.minute,seconds=starting_Time.second,microseconds=starting_Time.microsecond)
given_time =starting_Time

"""Number of Port in each Charging Station"""
no_of_port=[10,10,10,5,7,3,7,7,7,4]

"""Data Genarator pattern"""
rand_List=[[0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0],[0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0],[0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0],[0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,1,0],[0,0,0,1,0,0,0,0,0,0,1,0,0,0,1,0,0,0,1,0],[0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0]]


"""Data Genarator Loop Count Hour"""
for j in range(4):

    """Object of Thread Class"""
    th=t()

    """Data Genarator Loop Count Charging Stations"""
    for i in range(10):
        """Station name According to MongoDb"""
        station="jun"+str(i)

        """MongoDb Station Collection Selection"""
        mytab=my_Db[station]

        """Fetch last data enter in MongoDb For Collectect Previous Information"""
        output=dict(mytab.find().limit(1).sort([('$natural', -1)]).next())

        """Create Charging Station Object"""
        jun=cs.Charging_Station(output['id'],output['no_of_port'],output['no_of_port_available'],starting_Time.hour,starting_Time.hour+1)
        
        """Status of current situation of the Station"""
        status = "available" if jun.no_Of_Ports_Available!=0 else "occupied"
        icon= "greenIcon" if jun.no_Of_Ports_Available!=0 else "redIcon"


        """predict the price"""
        price=getprice(given_time)

        """Insert New Time Data to the Charging Station"""
        mytab.insert_one({
                            "id":i,
                            "Name": "Station"+str(i),
                            "lat": "",
                            "long":"",
                            "location":"",
                            "date":str(given_time.date()),
                            "time":given_time.hour,
                            "no_of_port_available":jun.no_Of_Ports_Available,
                            "status": status,
                            "icon":icon,
                            "no_of_port":jun.no_Of_Ports,
                            "charging_time":output['charging_time'],
                            "price":price
                        })
        
        """Select How many data will genarate Now according to the Time"""
        h=int(given_time.hour/4)
        print(rand_List[h])
        
        """Call the Thread for Genarting Car"""
        t1 = threading.Thread(target = arrival_Thread, args=(jun,th,rand_List[h],given_time,my_Collection1,mytab,output['charging_time']))
        t2= threading.Thread(target = Dispatch_Thread,args=(jun,th,given_time,my_Collection1,mytab,output['charging_time']))
        t1.start()
        t2.start()
        t2.join()
        t1.join()

        """After 1 hour Print the Arrival rate & Service rate"""
        print("Arrival Rate=",jun.arrival_rate)
        print("Service Rate=",jun.service_Rate)

        """Count the current Queue Size"""
        queue_Size=my_Collection1.count_documents({"charge_status":{"$in":["Charging","Wait"]},"station_id":jun.id})
        print("queue size"+str(queue_Size))
        
        """update data to CSV File"""
        row=[given_time,i+1,jun.arrival_rate,jun.id]
        csvfilename="Simulator & Data Generator/jun"+str(i)+".csv"
        with open(csvfilename, 'a') as f_object:
            writer_object = writer(f_object)
            writer_object.writerow(row)
            f_object.close()


        """Initialize Arrival & service Rate For next Ittaration"""
        jun.service_Rate=0
        jun.arrival_rate=0


    """Update time By 1 Hour"""
    given_time+=datetime.timedelta(hours=1)

