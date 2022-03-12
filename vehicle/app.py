from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
import pickle
from flask_cors import CORS
import numpy as np
from pymongo import MongoClient
from datetime import datetime

#connect
try:
    client = MongoClient("mongodb://localhost:27017/")
    #print("Connected successfully!!!")
except:  
    print("Could not connect to MongoDB")

model[10]
for i in range (1,11):
    file='prediction'+str(i)+'.pkl'
    print(file)
    model[i] = pickle.load(open(file,'rb'))


app = Flask(__name__)
CORS(app)

@app.route('/')
def fun():
    return "Access denied, lol kidding"
    
@app.route('/getprice1')
def getprice1():
    previousPrice=getPreviousPrice('jun1')
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)
    
@app.route('/getprice2')
def getprice2():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice3')
def getprice3():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice4')
def getprice4():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice5')
def getprice5():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice6')
def getprice6():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice7')
def getprice7():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice8')
def getprice8():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)    
@app.route('/getprice9')
def getprice9():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)

@app.route('/getprice10')
def getprice10():
    previousPrice=getPreviousPrice()
    optimalLambda=getLambdaOptimal()
    predictedLambda=getPredictedLambda()
    difference=predictedLambda-optimalLambda
    nextPrice = previousPrice + getGamma()*difference
    return jsonify(nextPrice)
    
if __name__=="__main__":
    app.run(debug=True)
    
def getLambdaOptimal():
    lamb = 50
    serviceRate=[6,7,10,9,11]
    sum=0
    lowersum=0
    for rate in serviceRate:
        x = math.sqrt(serviceRate[0]*rate)-rate
        sum = sum+x
        lower=rate//serviceRate[0]
        lower=math.sqrt(lower)
        lowersum=lowersum+lower
    upper_side=lamb+sum
    lambdaOptimal=upper_side//lowersum
    return lambdaOptimal

def getPredictedLambda():
    #arr = np.array([[11,6,0,1,2015,11,2]])
    dt = datetime.now()
    arr=np.array([[dt.day, dt.weekday(), dt.hour, dt.month, dt.year, datetime.now().timetuple().tm_yday, datetime.utcnow().isocalendar()[1]]])
    
    predicted = model.predict(arr)
    predicted = int(predicted)
    return predicted 


def getPreviousPrice(a):
    
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