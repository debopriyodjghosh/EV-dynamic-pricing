import xlwt
import threading
import Car as car
import PowerGrid as pw
from datetime import datetime
import time
import random
import xlwt
from xlwt import Workbook
class Simulator:
    book=None
    e_name=None
    stop_threads=None
    '''def start_grid(self,id,stop):
        row=1
        self.e_name="sheet"+str(id)
        sheet = self.book.add_sheet(self.e_name)
        while True:
            if(stop()):
                break
            c=car.Car()
            c_time=str(datetime.now())
            sheet.write(row,0,id)
            sheet.write(row, 1, c_time)
            sheet.write(row, 2, c.reg_id)
            sheet.write(row, 3, c.b_status)
            time.sleep(random.randint(1,30))
            print(row)
            row+=1'''
    def start(self):
        self.book = Workbook()
        power_grid = list()
        t=list()
        self.stop_threads=False
        for i in range(10):
            power_grid.append(pw.PowerGrid(i))
            t.append(i)
            t[i] = threading.Thread(target=power_grid[i].start_grid, args=(i,self.book,lambda : self.stop_threads))
            #t2 = threading.Thread(target=self.start_grid, args=(2,self.book,lambda : self.stop_threads))
            t[i].start()
            #t2.start()
        time.sleep(30)
        for i in range(10):
            self.stop_threads=True
            t[i].join()
            #t2.join()
            e="data.xls"
            self.book.save(e)
s=Simulator()
s.start()