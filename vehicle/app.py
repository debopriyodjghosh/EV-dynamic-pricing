from logging import root
from operator import truediv
from flask import Flask, jsonify
import math
import random
from flask_cors import CORS
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
    lamb = 15
    arrival=[4,5,4,3,4]
    sum=0
    lowersum=0
    for rate in arrival:
        x = math.sqrt(arrival[0]*rate)-rate
        sum = sum+x
        lower=rate//arrival[0]
        lower=math.sqrt(lower)
        lowersum=lowersum+lower
    upper_side=lamb+sum
    lambdaOptimal=upper_side//lowersum
    return lambdaOptimal

def getPredictedLambda():
    list1 = [ 4, 5, 6]
    predicted = random.choice(list1)
    return predicted 

def getPreviousPrice():
    prices = [ 10,11,12,13,14,15]
    prevPrice = random.choice(prices)
    return prevPrice

def getGamma():
    gamma = 0.5
    return gamma