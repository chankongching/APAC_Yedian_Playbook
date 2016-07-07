# -*- coding: utf-8 -*-
from django.db import models
#from GeneralAbstarctModels.models import Order,OrderOperatorHistory
#from CartGeneral.models import CartItems
#import GeneralBaseDataModels.models

# Create your models here.
#class personalization(Personalization):
#    description = models.CharField(max_length=20)
#    def __unicode__(self):
#            return 'customer_name is %s,creationtime is %s , description is %s' % (self.customer_name,self.creation_time , self.description)
class pz_items(models.Model):
    pz_code = models.CharField(max_length=100,unique=True, primary_key = True)
    weixinuser_id = models.CharField(max_length=50, unique=True, null=True, default=None)
    creation_time = models.DateTimeField(auto_now_add=True, null=True, default=None)
    pz_s_time = models.DateTimeField(null=True,default=None)
    pass

