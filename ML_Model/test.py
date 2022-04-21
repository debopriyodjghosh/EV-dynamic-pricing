import math

from pandas import array
sum =1.0
lambSum = sum
serviceRate=[.5,.3,.25]
#print(serviceRate)
for i in range(3):
  uppersum=0.0
  lowersum=0.0
  for rate in range(3):
    x = math.sqrt(serviceRate[i]*serviceRate[rate])-serviceRate[rate]
    uppersum = uppersum+x
    lower=serviceRate[rate]//serviceRate[i]
    lower=math.sqrt(lower)
    lowersum=lowersum+lower
  print(uppersum)
  upper_side=lambSum+uppersum
  lambdaOptimal=upper_side//lowersum
  #print(lambdaOptimal)