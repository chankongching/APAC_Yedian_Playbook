# -*- coding: utf-8 -*-
#from django.db import models

# Create your models here.

#from django.core.exceptions import ObjectDoesNotExist
#this function is not use for Database
#only for domain use
from GeneralBaseDataModels.models import WeixinUser, DbOwnersManagerTable
import logging
logger = logging.getLogger(__name__)
import urllib2

#test

#from datetime import datetime

#cache framework..maybe we need use memcached logic sometime
#from django.core.cache import cache

#ITEM_STATIC_DATA_TIMEOUT_VALUE_TIME = 120
#ITEM_PRICE_BUYCOUNT_VIEWCOUNT_TIMEOUT_VALUE_TIME = 120 #60sec
#ITEM_BUY_VIEW_CNT_UPDATE_DB_TIME_SEQ = 3
#
#
#ITEM_PRICE_SEPARATE_ITEM_CACHE_KEY = 'price'
#ITEM_BUYCOUNT_SEPARATE_ITEM_CACHE_KEY = 'buycount'
#ITEM_VIEWCOUNT_SEPARATE_ITEM_CACHE_KEY = 'viewcount'
#ITEM_COUNT_TIME_CACHE_KEY = 'updatetime'
#
DBO_AND_WXUSER_TIMEOUT_VALUE = 86400

"""
ToDo:
    Add cache logic
Comment:
    heng: Cannot use old cachebase logic
    because if we use this logic we need load all the wxusers to the cache.
    it's not good for cache all the users.
"""
from django.core.cache import cache
ACCESS_TOKEN_URL_MODEL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s'
USER_INFO_MODEL = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN'
KEY_ACCESS_TOKEN = 'access_token'

class GeneralMethod:
    @staticmethod
    def get_url_json_response(url):
        login_request = urllib2.Request(url)

        login_response = urllib2.urlopen(login_request).read()
        login_dict = eval(login_response)
        return login_dict
    @staticmethod
    def get_auth_token(dbo_obj):
        if dbo_obj:
            url = ACCESS_TOKEN_URL_MODEL % (dbo_obj.wx_appid, dbo_obj.wx_appsecret)
            login_dict = GeneralMethod.get_url_json_response(url)
            try:
                token = login_dict[KEY_ACCESS_TOKEN]
                return token
            except:
                return None


class GeneralController:
    def __init__(self, managerInfoObject= None, order = None, membership = None, shoppingcart =None, favourite = None):
        #self.membership = membership #model class
        #self.order = order #model class
        #self.managerInfo = managerInfoObject#model object
        #self.shoppingcart = shoppingcart#model class
        pass

    """
    Dbo object
    """
    def ExpireDboObject(self, dboid):
        cache.delete(dboid)

    def GetDboObject(self, dboid):
        obj_cache = cache.get(dboid)
        if obj_cache is None:
            try:
                obj_db = DbOwnersManagerTable.objects.get(pk = dboid)
                cache.set(str(obj_db.pk), obj_db, DBO_AND_WXUSER_TIMEOUT_VALUE)
                obj_cache = obj_db

            except Exception as err:
                logger.error('ERROR when get or create dbo object' + err.message)
                return None
        return obj_cache


    """
    WeixinUserObject
    """
    def ExpireWeixinObject(self, openid):
        cache.delete(openid)

    def GetWeixinObject(self, openid):
        obj_cache = cache.get(openid)
        if obj_cache is None:
            obj_db = self.get_or_create_weixinuser_object(openid, False)
            if obj_db is not None:
                cache.set(str(obj_db.pk), obj_db, DBO_AND_WXUSER_TIMEOUT_VALUE)
                obj_cache = obj_db
            else:
                return None

        return obj_cache

    def get_or_create_weixinuser_object(self,openid , refresh_operateTime = False):
        #Returns a tuple of (object, created), where object is the retrieved or created object and created is a boolean specifying whether a new object was created.
        try:
            obj, created =  WeixinUser.objects.get_or_create(weixinuser_id = openid)
            if (created == False):
                if refresh_operateTime:
                    obj.save()
            return obj
        except Exception as err:
            logger.error(err.message)
            return None
        # if there's no new obj created just update last_message_time

    def get_wxuserobject_from_session_openid(self, request, current_dbo):
        """
        Need unit test
        """
        ret = None
        try:
            current_dbo_session = request.session['current_dbo']
        except:
            current_dbo_session = None

        try:
            openid = request.session['openid']
        except:
            openid = None

        if current_dbo is None:
            request.session['current_dbo'] = current_dbo
        else:
            if current_dbo_session != current_dbo:
                if openid:
                    try:
                        del request.session['openid']
                    except:
                        pass
                request.session['current_dbo'] = current_dbo

        try:
            openid = request.session['openid']
            ret = self.GetWeixinObject(openid)
            if ret:
                return ret
            else:
                return None
        except:
            return None


    #def make_item_dynamic_item_key(self, key1, key2):
    #    key1 = str(key1)
    #    key2 = str(key2)
    #    return '_'.join([key1,key2])


