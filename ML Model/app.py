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

#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    #print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")
mydb = client.ElectricVehicle

model=[]
for i in range (1,11):
   file='F:\\xampp\\htdocs\\EV-dynamic-pricing\\ML_Model\\'+'JunModel'+str(i)+'.pkl'
   m1=pickle.load(open(file,'rb'))
   model.append(m1)


app = Flask(__name__)
CORS(app)

@app.route('/')
def price():
    while True:
        getprice()
        time.sleep(3600)
    
    
if __name__=="__main__":
    app.run(debug=True)

def getprice():
    predictedLambda=getPredictedLambda()
    predictedSum=0
    for predicted in predictedLambda:
        predictedSum = predictedSum + predicted
    for i in range(1,11):
        id="jun"+str(i)+"_"+str(datetime.datetime.now().date)+str(datetime.datetime.now().hour)
        previousPrice=getPreviousPrice(i)
        optimalLambda=getLambdaOptimal(predictedSum,i)
        difference=predictedLambda[i] - optimalLambda
        nextPrice = previousPrice + getGamma()*difference
        collection_name = "jun_price" + str(i)
        my_collection=mydb[collection_name]
        my_collection.insert_one(
            {
                "id":id,
                "date":datetime.datetime.now().date,
                "time":datetime.datetime.now().hour,
                "price":nextPrice
            }
        )
      
def getPredictedLambda():
    #arr = np.array([[11,6,0,1,2015,11,2]])
    predicted=[]
    for i in range(1,11):
        dt = datetime.now()
        arr=np.array([[dt.day, dt.weekday(), dt.hour, dt.month, dt.year, datetime.now().timetuple().tm_yday, datetime.utcnow().isocalendar()[1]]])
        
        predict = model[i].predict(arr)
        predicted.append(predict)
        
    return predicted 

def getLambdaOptimal(sum,i):
    lambSum = sum
    serviceRate=getServicerate()
    uppersum=0
    lowersum=0
    for rate in serviceRate:
        x = math.sqrt(serviceRate[i]*rate)-rate
        uppersum = uppersum+x
        lower=rate//serviceRate[i]
        lower=math.sqrt(lower)
        lowersum=lowersum+lower
    upper_side=lambSum+uppersum
    lambdaOptimal=upper_side//lowersum
    return lambdaOptimal


# check this part again
def getPreviousPrice(i):
    collection_name = "jun_price" + str(i)
    my_collection=mydb[collection_name]
    #mytab = mydb.test
    new=dict(my_collection.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['Previous_price']
    return prevPrice

def getGamma():
    gamma = 0.5
    return gamma


def getServicerate():
    li=[]
    for i in range(1,11):
        ports=getNumberofPorts(i)
        chargetime=getchargeTime()
        serviceRate=(60/chargetime)*ports
        li.append(serviceRate)

    return li

def getNumberofPorts(i):
    collection_name = "jun" + str(i)
    my_collection=mydb[collection_name]
    result=dict(my_collection.find().limit(1))
    return result['no_of_port']
    #   get from database
    #NumberofPorts=5 #change this
    #return NumberofPorts

def getchargeTime(i):
    collection_name = "jun" + str(i)
    my_collection=mydb[collection_name]
    result=dict(my_collection.find().limit(1))
    return result['charging_time']
    #   get from database
    #chargeTime=20 #change this
    #return chargeTime
