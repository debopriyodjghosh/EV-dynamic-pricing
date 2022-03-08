from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
import pickle
from flask_cors import CORS
import numpy as np
model = pickle.load(open('prediction.pkl','rb'))
app = Flask(__name__)
CORS(app)

@app.route('/')
def fun():
    return "Access denied, lol kidding"
    
@app.route('/getprice')
def getprice():
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
    arr = np.array([[11,6,0,1,2015,11,2]])
    predicted = model.predict(arr)
    predicted = int(predicted)
    return predicted 


def getPreviousPrice():
    prices = [ 10,11,12,13,14,15]
    prevPrice = random.choice(prices)
    return prevPrice

def getGamma():
    gamma = 0.5
    return gamma