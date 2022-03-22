
import math as math
import random
import datetime
class Car:
    reg_id=None
    b_status=None
    arr_Time=None
    dis_Time=None
    charge_Start_Time=None
    need_charge=None
    charge_status=None

    #constructtor
    def __init__(self,arr_Time,charge_status):
        self.reg_id=random.randint(1,1000)
        self.arr_Time=arr_Time
        self.b_status=random.randint(1,75)
        self.charge_status=charge_status
        if(self.b_status>65):
            self.need_charge=100-self.b_status
        else:
            self.need_charge=random.randint((self.b_status+1),100)-self.b_status
        if(self.charge_status=="Charging"):
            self.Set_Dispatch_Time_And_Charging_Start_Time(arr_Time)
    
    #Set Dispatch Time and Charging Starting Time
    def Set_Dispatch_Time_And_Charging_Start_Time(self,charge_Start_Time):
        self.charge_Start_Time=charge_Start_Time
        self.dis_Time=self.charge_Start_Time+datetime.timedelta(seconds=(.5*self.need_charge*60))

    #Return Registration Id
    def r_reg_id(self):
        return self.reg_id
    
    #return Battery Status
    def r_b_status(self):
        return self.b_status