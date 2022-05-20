from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
import pickle
from flask_cors import CORS
import numpy as np
from pymongo import MongoClient
import datetime,time



def getprice():
    predictedLambda=getPredictedLambda()
    predictedSum=0
    for predicted in predictedLambda:
        #print("predicted: "+str(predicted))
        predictedSum = predictedSum + predicted
    for i in range(1,11):
        id="jun"+str(i)+"_"+str(datetime.datetime.now().date())+str(datetime.datetime.now().hour)
        previousPrice=getPreviousPrice(i)
        optimalLambda=getLambdaOptimal(predictedSum,i)
        #print("optimal "+str(optimalLambda))
        difference=predictedLambda[i] - optimalLambda
        print("difference "+str(difference))
        nextPrice = previousPrice + getGamma()*difference
        #print(nextPrice[0])
        collection_name = "jun_price" + str(i)
        my_collection=mydb[collection_name]
        my_collection.insert_one(
            {
                "id":id,
                "date":str(datetime.datetime.now().date()),
                "time":datetime.datetime.now().hour,
                "price":nextPrice[0]
            }
        )
      
def getPredictedLambda():
    #arr = np.array([[11,6,0,1,2015,11,2]])
    predicted=[]
    predicted.append(0)
    for i in range(1,11):
        dt = datetime.datetime.now()
        arr=np.array([[dt.day, dt.weekday(), dt.hour, dt.month, dt.timetuple().tm_yday, datetime.datetime.utcnow().isocalendar()[1]]])
        predict = model[i].predict(arr)
        predicted.append(predict) 
    #print("predicted "+str(predicted))  
    return predicted 

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
    return lambdaOptimal


# check this part again
def getPreviousPrice(i):
    collection_name = "jun_price" + str(i)
    my_collection=mydb[collection_name]
    #mytab = mydb.test
    #letest price fetch
    new=dict(my_collection.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['price']
    #print("previous: "+str(prevPrice))
    return prevPrice

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
    #print(serviceRate)
    return li

def getNumberofPorts(i):
    no_of_port=0
    collection_name = "jun" + str(i)
    my_collection=mydb[collection_name]
    result=my_collection.find().limit(1)
    for r in result:
        no_of_port=r['no_of_port']
    return no_of_port
    #   get from database
    #NumberofPorts=5 #change this
    #return NumberofPorts

def getchargeTime(i):
    charging_time=0
    collection_name = "jun" + str(i)
    my_collection=mydb[collection_name]
    result=my_collection.find().limit(1)
    for r in result:
        charging_time=r['charging_time']
        #print("charging Time "+str(charging_time))
    return charging_time
    #   get from database
    #chargeTime=20 #change this
    #return chargeTime


#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    #print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")
mydb = client.ElectricVehicle

model=[]
model.append(0)
for i in range (1,11):
   file='F:/xampp/htdocs/EV-dynamic-pricing/ML_Model/randomforest pickles/'+'JunModel'+str(i)+'.pkl'
   m1=pickle.load(open(file,'rb'))
   model.append(m1)
while(True):
    getprice()
    time.sleep(3600)