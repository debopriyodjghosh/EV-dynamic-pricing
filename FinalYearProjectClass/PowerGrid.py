import Car as car
import PowerGrid as pw
from datetime import datetime
import time
import random
from xlwt import Workbook
class PowerGrid:
    def __init__(self,id):
        pg_id=id
    def start_grid(self,id,book,stop):
        row=1
        self.e_name="sheet"+str(id)
        sheet = book.add_sheet(self.e_name)
        while True:
            if(stop()):
                break
            c=car.Car()
            c_time=str(datetime.now())
            sheet.write(row,0,id)
            sheet.write(row, 1, c_time)
            sheet.write(row, 2, c.reg_id)
            sheet.write(row, 3, c.b_status)
            time.sleep(random.randint(1,3))
            print(row)
            row+=1