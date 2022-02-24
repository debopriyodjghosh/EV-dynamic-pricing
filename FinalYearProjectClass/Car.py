
import random
class Car:
    reg_id=None
    b_status=None
    def __init__(self):
        self.reg_id=random.randint(1,1000)
        self.b_status=random.randint(1,60)
    def r_reg_id(self):
        return self.reg_id
    def r_b_status(self):
        return self.b_status
