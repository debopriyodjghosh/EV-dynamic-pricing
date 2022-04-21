import time
def foo():
  print (time.ctime())

while True:
  foo()
  time.sleep(10)