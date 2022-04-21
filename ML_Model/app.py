from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
import pickle
from flask_cors import CORS
import numpy as np
from pymongo import MongoClient
import datetime
import time
 

#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    #print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")

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
        getprice(datetime.datetime.now,33,21)
        time.sleep(60)
    
    
if __name__=="__main__":
    app.run(debug=True)

def getprice(p_time,Sumoflambda,Servicerate):
    for i in range(1,11):
        pre=p_time-datetime.timedelta(hours=1)
        previousPrice=getPreviousPrice(i,pre)
        optimalLambda=getLambdaOptimal(i,p_time,Sumoflambda,Servicerate)
        predictedLambda=getPredictedLambda(i)
        difference=predictedLambda-optimalLambda
        nextPrice = previousPrice + getGamma()*difference
    #save to database
        jun='jun_price'+str(i)
        dt = datetime.now()
        id= jun+"_"+str(dt.year)+str(dt.month)+str(dt.day)+str(dt.hour)
        mydb = client.EV
        mytab = mydb[jun]
        rec=mytab.insert_one(
            {
                "id":id,
                "time":p_time,
                "price":nextPrice
            }
        )
        print("nextPrice of "+str(i)+" --> "+str(nextPrice))

        '''rec = mytab.insert_one({
        "id" : id,
        "Arrival_rate" : 0,
        "Service_rate" : 0,
        "Previous_price" :0,
        "cur_price" : nextPrice,
        "time" : dt
        })'''
    
        #return jsonify(nextPrice)
        #fuck previous price
        #fuck Arrival rate

def getLambdaOptimal(i,p_time,Sumoflambda,Servicerate):
    lambSum = Sumoflambda
    serviceRate=Servicerate
    sum=0
    lowersum=0
    for rate in serviceRate:
        x = math.sqrt(serviceRate[i]*rate)-rate
        sum = sum+x
        lower=rate//serviceRate[i]
        lower=math.sqrt(lower)
        lowersum=lowersum+lower
    upper_side=lambSum+sum
    lambdaOptimal=upper_side//lowersum
    return lambdaOptimal

def getPredictedLambda(i):
    #arr = np.array([[11,6,0,1,2015,11,2]])
    dt = datetime.datetime.now()
    arr=np.array([[dt.day, dt.weekday, dt.hour, dt.month, dt.year, dt.timetuple().tm_yday, datetime.datetime.utcnow().isocalendar()[1]]])
    predicted = model[i].predict(arr)
    predicted = float(predicted)
    return predicted 


def getPreviousPrice(i,t):
    a="jun_price"+str(i)
    mydb=client.ElectricVehicle
    result=mydb.a.find({"time":t})
    for r in result:
        return r['price']
    """#a = "jun" + str(i)
    a = "jun_price" + str(i)
    mydb = client.EV
    #mytab = mydb.test
    new=dict(mydb.a.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['Previous_price']
    #print(new['Previous_price'])
    #prices = [ 10,11,12,13,14,15]
    #prevPrice = random.choice(prices)"""

    return 30
#done already
def getGamma():
    gamma = 0.5
    return gamma
#done
def getSumoflambda():
    sum=0
    for i in range(1,11):
        jun='jun'+str(i)
        mydb = client.EV
        mytab = mydb[jun]
        new=dict(mydb[jun].find().limit(1).sort([('$natural', -1)]).next())

        sum=sum+new['Arrival_rate']

    return sum
#done
def getServicerate():
    li=[]
    for i in range(1,11):
        jun='jun'+str(i)
        mydb = client.EV
        mytab = mydb[jun]
        new=dict(mydb[jun].find().limit(1).sort([('$natural', -1)]).next())
        print(new['Service_rate'])
        li.append(new['Service_rate'])

    return li
   