import Car as car
import Charging_Station as pw
from datetime import datetime
import time
import random
#from xlwt import Workbook
class Charging_Station:
    id=None
    no_Of_Ports=None
    no_Of_Ports_Available=None
    starting_Hour=None
    ending_Hour=None
    arrival_rate=None
    service_Rate=None
    def __init__(self,id,no_Of_Ports,no_of_port_available,s_H,e_H):
        self.id=id
        self.no_Of_Ports=no_Of_Ports
        self.no_Of_Ports_Available=no_of_port_available
        self.starting_Hour=s_H
        self.ending_Hour=e_H
        self.arrival_rate=0
        self.service_Rate=0