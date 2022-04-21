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

model=[]
for i in range (1,11):
   file='C://Users//Admin//Desktop//EV-dynamic-pricing//ML Model'+'JunModel'+str(i)+'.pkl'
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
    allprices=[]
    for predicted in predictedLambda:
        predictedSum = predictedSum + predicted
    for i in range(1,11):
        previousPrice=getPreviousPrice(i)
        optimalLambda=getLambdaOptimal(predictedSum,i)
        difference=predictedLambda[i] - optimalLambda
        nextPrice = previousPrice + getGamma()*difference
        allprices.append(nextPrice)
    #save to database this part needs to be change
        # jun='jun'+str(i)
        # dt = datetime.now()
        # id= jun+"_"+str(dt.year)+str(dt.month)+str(dt.day)+str(dt.hour)
        # mydb = client.EV
        # mytab = mydb[jun]
        
        # rec = mytab.insert_one({
        # "id" : id,
        # "Arrival_rate" : 0,
        # "Service_rate" : 0,
        # "Previous_price" :0,
        # "cur_price" : nextPrice,
        # "time" : dt
        # })
    return jsonify(allprices)
      
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
    a = "jun" + str(i)
    mydb = client.EV
    #mytab = mydb.test
    new=dict(mydb.a.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['Previous_price']
    return prevPrice

def getGamma():
    gamma = 0.5
    return gamma


def getServicerate():
    li=[]
    for i in range(1,11):
        ports=getNumberofPorts()
        chargetime=getchargeTime()
        serviceRate=(60/chargetime)*ports
        li.append(serviceRate)

    return li

def getNumberofPorts():
    #   get from database
    NumberofPorts=5 #change this
    return NumberofPorts

def getchargeTime():
    #   get from database
    chargeTime=20 #change this
    return chargeTime
