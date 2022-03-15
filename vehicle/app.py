from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
import pickle
from flask_cors import CORS
import numpy as np
from pymongo import MongoClient
 

#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    #print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")

model=[]
for i in range (1,11):
   file='C://xampp//htdocs//vehicle//'+'JunModel'+str(i)+'.pkl'
   m1=pickle.load(open(file,'rb'))
   model.append(m1)


app = Flask(__name__)
CORS(app)

@app.route('/')
def price():
    while True:
        getprice()
        time.sleep(10)
    
    
if __name__=="__main__":
    app.run(debug=True)

def getprice():
    for i in range(1,11):
        previousPrice=getPreviousPrice(i)
        optimalLambda=getLambdaOptimal(i)
        predictedLambda=getPredictedLambda(i)
        difference=predictedLambda-optimalLambda
        nextPrice = previousPrice + getGamma()*difference
    #save to database
        jun='jun'+str(i)
        dt = datetime.now()
        id= jun+"_"+str(dt.year)+str(dt.month)+str(dt.day)+str(dt.hour)
        mydb = client.EV
        mytab = mydb[jun]
        
        rec = mytab.insert_one({
        "id" : id,
        "Arrival_rate" : 0,
        "Service_rate" : 0,
        "Previous_price" :0,
        "cur_price" : nextPrice,
        "time" : dt
        })
    
        #return jsonify(nextPrice)
        #fuck previous price
        #fuck Arrival rate

def getLambdaOptimal(i):
    lambSum = getSumoflambda();
    serviceRate=getServicerate();
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
    dt = datetime.now()
    arr=np.array([[dt.day, dt.weekday(), dt.hour, dt.month, dt.year, datetime.now().timetuple().tm_yday, datetime.utcnow().isocalendar()[1]]])
    
    predicted = model[i].predict(arr)
    predicted = float(predicted)
    return predicted 


def getPreviousPrice(i):
    a = "jun" + str(i)
    mydb = client.EV
    #mytab = mydb.test
    new=dict(mydb.a.find().limit(1).sort([('$natural', -1)]).next())
    prevPrice=new['Previous_price']
    #print(new['Previous_price'])
    #prices = [ 10,11,12,13,14,15]
    #prevPrice = random.choice(prices)
    return prevPrice

def getGamma():
    gamma = 0.5
    return gamma

def getSumoflambda():
    sum=0
    for i in range(1,11):
        jun='jun'+str(i)
        mydb = client.EV
        mytab = mydb[jun]
        new=dict(mydb[jun].find().limit(1).sort([('$natural', -1)]).next())

        sum=sum+new['Arrival_rate']

    return sum

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
   