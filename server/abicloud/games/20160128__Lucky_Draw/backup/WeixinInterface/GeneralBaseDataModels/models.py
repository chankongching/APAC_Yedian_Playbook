#-*- coding:utf-8 -*-
from django.db import models
#uuid filed
#import uuid
from uuidfield import UUIDField
import logging
logger = logging.getLogger(__name__)

#protos
#from protos.base.shoppingcart_order_pb2 import shoppingcart_order_pb2
#from protos.base import user_information_pb2

# Create your models here.
class WeixinUser(models.Model):
    weixinuser_id = models.CharField(max_length=50, primary_key=True)
    nick_name = models.CharField(max_length=150)
    creation_time = models.DateTimeField(auto_now_add=True, null=True)
    last_message_time = models.DateTimeField(auto_now=True,null=True)
    menu_flag = models.SmallIntegerField(default = 0)
    #admin_group = models.ManyToManyField('DbOwnersAdminGroup', null=True)

    person_loate = models.CharField(max_length=100)

    person_deliver_info = models.BinaryField(max_length=1000)
    user_is_subscribe = models.IntegerField(default = 0)
    user_wx_nickname = models.BinaryField(max_length=200)
    user_wx_sex = models.IntegerField(default = 0)
    user_wx_img = models.CharField(max_length=500)
    user_wx_subscribe_time = models.IntegerField(default = 0)
    user_wx_remark = models.CharField(max_length=500)
    user_wx_group_id = models.IntegerField(default = 0)
    user_last_wx_info_update_time = models.DateTimeField(null=True)

    #def get_person_deliver_info_to_pb2(self):
    #    try:
    #        item_col = user_information_pb2.UserDeliverInformationCollection()
    #        item_col.ParseFromString(self.person_deliver_info)
    #        return item_col
    #    except Exception as err:
    #        logger.error('ERROR when get addr from db and parse to pb2 \n' + err.message)
    #        return None

    #def set_person_deliver_info_from_pb2(self, usr_delv_strc):
    #    if not isinstance(usr_delv_strc, user_information_pb2.UserDeliverInformationCollection):
    #        logger.warn('usr_delv_strc is not pb2 struct, item type is %s' % type(usr_delv_strc))
    #        return

    #    if usr_delv_strc.IsInitialized():
    #        self.person_deliver_info = usr_delv_strc.SerializeToString()
    #    else:
    #        logger.error('Address pb2stru is not Initialized')
    #user_deliver_info_pb2 = property(get_person_deliver_info_to_pb2, set_person_deliver_info_from_pb2)

    def __unicode__(self):
        return 'wxid = %s\n last message time = %s' % (self.weixinuser_id, self.last_message_time)



class DbOwnersManagerTable(models.Model):
    customer_id = UUIDField(auto=True,primary_key=True)
    customer_name = models.CharField(max_length=100,unique=True)
    customer_description = models.CharField(max_length=500)
    db_manager_settings = models.CharField(max_length=2000)
    #operator =  #operator do not implement
    creation_time = models.DateTimeField(auto_now_add=True, null=True)
    last_update_time = models.DateTimeField(auto_now=True,null=True)
    wx_appid = models.CharField(max_length = 200)
    wx_appsecret = models.CharField(max_length = 200)

    def __unicode__(self):
        return 'dbo_customer_name = %s ,creationtime = %s' % (self.customer_name,self.creation_time)

