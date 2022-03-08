import pandas as pd
import pickle
train = pd.read_csv('train.csv')
def get_dom(dt):
	return dt.day

def get_weekday(dt):
	return dt.weekday()

def get_hour(dt):
	return dt.hour

def get_year(dt):
	return dt.year

def get_month(dt):
	return dt.month

def get_dayofyear(dt):
	return dt.dayofyear

def get_weekofyear(dt):
	return dt.weekofyear


train['DateTime'] = train['DateTime'].map(pd.to_datetime)
train['date'] = train['DateTime'].map(get_dom)
train['weekday'] = train['DateTime'].map(get_weekday)
train['hour'] = train['DateTime'].map(get_hour)
train['month'] = train['DateTime'].map(get_month)
train['year'] = train['DateTime'].map(get_year)
train['dayofyear'] = train['DateTime'].map(get_dayofyear)
train['weekofyear'] = train['DateTime'].map(get_weekofyear)

train = train.drop(['DateTime'], axis=1)
train = train.drop(['Junction'], axis=1)
train = train.drop(['ID'], axis=1)
# separating class label for training the data
train1 = train.drop(['Vehicles'], axis=1)

# class label is stored in target
target = train['Vehicles']

from sklearn.ensemble import RandomForestRegressor

#defining the RandomForestRegressor
m1=RandomForestRegressor()

m1.fit(train1,target)

pickle.dump(m1,open('prediction.pkl','wb'))
