# -*- coding: utf-8 -*-
from django.test import TestCase
from GeneralController.models import *
from GeneralBaseDataModels.models import *
from TestDBStruct.models import *

import time

from datetime import datetime

from django.core.cache import cache


#do some items operator test
#from GeneralController.itemsoperator.tests import ItemsOperatorTest

# Create your tests here.
class GeneralControllerTest(TestCase):
    def setUp(self):
        self.dbo = DbOwnersManagerTable(customer_name='looker_kitchen')
        self.dbo.save()
        self.genc = GeneralController(self.dbo, test_db_order,None,None,None)

        #create WxUser
        self.testwxuser = WeixinUser.objects.create(weixinuser_id='test1wxuser')
        self.order = test_db_order(content='testarea',customer_address='testaddr',related_weixin_id=self.testwxuser, total_price = 520.13, personalization=self.dbo)
        self.order.save()

    #def test_protocolbuffer(self):
    #    #print '..................Test GOOGLE PROTOBUF............'
    #    #from protos import shoppingcart_order_pb2
    #    #itembincode = item.SerializeToString()
    #    #print 'SerializeToString --> %s' % item.SerializeToString()
    #    #print 'getback'
    #    #print itemback

    #    #from protos.background_proto import main_information_pb2
    #    #main_infopb = shoppingcart_order_pb2.SubProducts()
    #    print '..................Test GOOGLE PROTOBUF............'
    def test_getDboObject(self):
        print '..................Test GetDboObject............'
        cache.clear()
        cache_obj = self.genc.GetDboObject(str(self.dbo.pk))
        self.assertEqual(cache_obj.customer_name, 'looker_kitchen')
        #test for cache logic
        #change the value from db and do not refresh test value must same like earlier value
        self.dbo.customer_name = 'lok'
        self.dbo.save()
        self.assertEqual(DbOwnersManagerTable.objects.all()[0].customer_name, 'lok')

        cache_obj = self.genc.GetDboObject(str(self.dbo.pk))
        self.assertEqual(cache_obj.customer_name, 'looker_kitchen')

        self.genc.ExpireDboObject(str(self.dbo.pk))
        cache_obj = self.genc.GetDboObject(str(self.dbo.pk))
        self.assertEqual(cache_obj.customer_name, 'lok')
        cache.clear()
        print '..................Test GetDboObject............'

    def test_getWeixinObject(self):
        obj1 = self.genc.GetWeixinObject(str(self.testwxuser.pk))
        self.assertEqual(obj1.pk, self.testwxuser.pk)

        obj2 = self.genc.GetWeixinObject('test3')
        self.assertEqual(obj2.pk, 'test3')
        self.assertEqual(WeixinUser.objects.all()[1].pk, 'test3')

        oldpk = self.testwxuser.pk

        self.testwxuser.pk = '1414'
        self.testwxuser.save()

        obj1 = self.genc.GetWeixinObject(oldpk)
        self.assertEqual(obj1.pk, oldpk)
        self.assertEqual(WeixinUser.objects.all()[0].pk, '1414')

    def test_UpdateUserActionTimeInDatabase(self):
        print '..................Test UserLastTimeUpdate............'
        print 'first update user time which is exist -> test1wxuser'
        print 'before update'
        print WeixinUser.objects.all()[0]
        print 'delayFor 2 Second'
        time.sleep(2)
        dtn = datetime.now().time()
        print dtn
        self.genc.get_or_create_weixinuser_object(self.testwxuser.weixinuser_id, True)
        print WeixinUser.objects.all()[0]

        print 'step 2'
        print 'test for createnew item'
        self.genc.get_or_create_weixinuser_object('test2wxuser', True)
        print WeixinUser.objects.all()
        print '..................Test UserLastTimeUpdate............'

