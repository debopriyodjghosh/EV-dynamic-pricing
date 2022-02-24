import time
import random
import datetime
from turtle import delay
from datetime import datetime
from datetime import timedelta
# Get current time in local timezone
current_time = datetime.now()
n = 1
future_time = current_time + timedelta(minutes=n)
dict={}
while future_time>=datetime.now():
    dict.update({str(datetime.now()):random.uniform(1,5)})
    time.sleep(5)
print(dict)