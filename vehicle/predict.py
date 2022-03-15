import pandas as pd
#importing Random forest
from sklearn.ensemble import RandomForestRegressor
from datetime import datetime
import pickle
# get date
def get_dom(dt):
	return dt.day

# get week day
def get_weekday(dt):
	return dt.weekday()

# get hour
def get_hour(dt):
	return dt.hour

# get year
def get_year(dt):
	return dt.year

# get month
def get_month(dt):
	return dt.month

# get year day
def get_dayofyear(dt):
	return dt.dayofyear

# get year week
def get_weekofyear(dt):
	return dt.weekofyear


for i in range(1,11):
	file="C:/xampp/htdocs/vehicle/"+"JUN"+str(i)+".csv"
	train = pd.read_csv(file)
	train = train.drop(['Junction'],axis=1)
	train = train.drop(['ID'],axis=1)
	# function to get all data from time stamp
	train['DateTime'] = train['DateTime'].map(pd.to_datetime)
	train['date'] = train['DateTime'].map(get_dom)
	train['weekday'] = train['DateTime'].map(get_weekday)
	train['hour'] = train['DateTime'].map(get_hour)
	train['month'] = train['DateTime'].map(get_month)
	train['year'] = train['DateTime'].map(get_year)
	train['dayofyear'] = train['DateTime'].map(get_dayofyear)
	train['weekofyear'] = train['DateTime'].map(get_weekofyear)
	# display
	train = train.drop(['DateTime'], axis=1)
	# separating class label for training the data
	train1 = train.drop(['Vehicles'], axis=1)
	# class label is stored in target
	target = train['Vehicles']
	train1 = train1.drop(['year'],axis =1)
	#defining the RandomForestRegressor
	m1=RandomForestRegressor()

	m1.fit(train1.values,target)
	file1="C:/xampp/htdocs/vehicle/"+"JunModel"+str(i)+".pkl"
	pickle.dump(m1,open(file1,'wb'))

	#testing
	# m1.predict([[1,4,0,1,1,53]])
	# dt=datetime.now()
	# print(dt)
