#-*- coding:utf-8 -*-
import urllib
from django.shortcuts import render
from django.core.urlresolvers import reverse

#from django.shortcuts import render
#
#from django.http import HttpResponse,HttpResponseRedirect
#from django.template import RequestContext

#from django.db.models import get_model
#from GeneralController.usersoperator.UsersOperator import UsersOperator
#from GeneralController.ordersoperator.OrdersOperator import OrdersOperator
#from CartGeneral.cart import CartOperator, KEY_REQUEST_ITEM_DETAIL_ID, KEY_REQUEST_ITEM_ID, KEY_REQUEST_ITEM_NUM
#from CartGeneral.models import _get_mixid_by_separate_id
#from GeneralBaseDataModels.models import DbOwnersManagerTable
#from GeneralAbstarctModels.models import Order
#from GeneralController.itemsoperator.ItemsOperator import ItemsOperator,ITEM_DETAIL_INFORMATION_KEY_IN_ITEM_OBJECT_PB2_STRUCT,SINGLETON_ITEM_DETAIL_INFORMATION_KEY_IN_ITEM_OBJECT_PB2_STRUCT
#
#from GeneralController.models import GeneralController
from wxapi.views import redir_to_oauth_update_openid_url, send_admin_order_template_msg, send_order_status_change_template_msg
from django.shortcuts import redirect
import datetime

#from django.http import Http404
from django.http.response import HttpResponse

from django.views.decorators.csrf import csrf_exempt
import json
from Budweiser.models import pz_items

#from GeneralController.privilegeoperator.PrivilegeOperator import PrivilegeOperator
# Imaginary function to handle an uploaded file.

# Create your views here.

#current_app_name = 'luke'
#SITE_RECONIZE_NAME_IN_URL = 'looker-kitchen/'
current_dboid = '711e59604c1146a387135f7d20275645'
#ORDER_SET_NAME = 'looker_order_set'
#ORDER_OPERATOR_HISTORY_NAME = 'looker_order_history'
#CART_ITEM_SET_NAME = 'looker_cart_items_set'

import logging
logger = logging.getLogger(__name__)
from django.core.cache import cache
from django.db import transaction
import random
from GeneralController.models import GeneralController
genl_operator = GeneralController()
from GeneralBaseDataModels.models import DbOwnersManagerTable
from GeneralController.usersoperator.UsersOperator import UsersOperator

dbo, result = DbOwnersManagerTable.objects.get_or_create(customer_id=current_dboid)

AVAL_CNT_KEY = 'avail_cnt'

"""
initializing area
"""
from django.core.cache import cache
from Budweiser.models import pz_items


def get_expiry_age():
    date_forward = datetime.date.today() + datetime.timedelta(days=1)
    date_time_tomorrow = datetime.datetime.combine(date_forward, datetime.datetime.min.time())
    sp = date_time_tomorrow - datetime.datetime.now()
    return int(sp.total_seconds())

def check_cache_item_available(request):
    """
    Get UserObject from session and cache
    QAuth Area
    """
    #try:
    #    openid = request.session['openid']
    #except:
    #    openid = None
    wx_object_from_session = genl_operator.get_wxuserobject_from_session_openid(request, current_dboid)
    if not wx_object_from_session:
        oauth_url = redir_to_oauth_update_openid_url(current_dboid, __name__ + '.' + index.__name__)
        return None, oauth_url
    """
    display item
    """
    current_available_cnt = cache.get(str(wx_object_from_session.pk) + '_cnt', None)
    return wx_object_from_session, current_available_cnt


def index(request):


    wx_obj, param = check_cache_item_available(request)
    if not wx_obj:
        return redirect(param)
    else:
        if param is None:
            cache.set(str(wx_obj.pk) + '_cnt', 3, get_expiry_age())

    #check cache
    aval_key = cache.get(AVAL_CNT_KEY, None)
    if aval_key is None:
        aval_key = pz_items.objects.filter(weixinuser_id = None).count()
        if not aval_key:
            aval_key = 0
            #logger.error('available cnt is 0, when initializing ,cache val is %s' % aval_key)
    cache.set(AVAL_CNT_KEY, aval_key, None)
#check finish

    code = cache.get(str(wx_obj.pk) + '_zj', None)

    user_available_cnt = cache.get(str(wx_obj.pk) + '_cnt', None)

    logger.error('ak %s, code %s, us_a %s' %(aval_key, code, user_available_cnt))
    return render(request, 'index.html', {'available_cnt': aval_key, 'code' : code, 'user_available_cnt' : user_available_cnt})

    #else:
    #    zj_code = cache.get(str(wx_obj.pk) + '_zj', None)
    #    if zj_code:
    #        return('zj')

    #    if current_available_cnt <= 0:
    #        return('fail')
    #    else:
    #        return('can-action')


def act(request):
    wx_obj,current_available_cnt = check_cache_item_available(request)

    usr_opera = UsersOperator(wx_obj)
    usr_opera.UpdateUsersWxInfo(dbo)

    data_dict = {'status' : 1, 'win': 0, 'chance' : 0, 'code' :'null', 'message':''}

    if wx_obj is None or current_available_cnt is None:
        logger.error('bad request, none cnt, or none wx_obj')
        data_dict['status'] = 0
        data_dict['message'] = '非法请求，请按照正确操作'
        return HttpResponse(json.dumps(data_dict))

    if current_available_cnt <= 0:
        code = cache.get(str(wx_obj.pk) + '_zj')
        if code:
            data_dict['win'] = 1
            data_dict['code'] = code
        return HttpResponse(json.dumps(data_dict))

    pz_unassigned = pz_items.objects.filter(weixinuser_id = None)
    if not pz_unassigned.exists():
        logger.error('all pz sold out')
        data_dict['status'] = 0
        data_dict['message'] = '奖已经抽完'
        return HttpResponse(json.dumps(data_dict))

    cache.decr(str(wx_obj.pk) + '_cnt')
    try:
        #before dbaction check db result
        obj = pz_items.objects.get(weixinuser_id = str(wx_obj.pk))
        cache.set(str(wx_obj.pk) + '_cnt', 0, None)
        cache.set(str(wx_obj.pk) + '_zj', str(obj.pk),None)
        logger.error('db exist the record but cache missing')
        data_dict['win'] = 1
        data_dict['code'] = obj.pk

        return HttpResponse(json.dumps(data_dict))
    except:
        #conitune
        pass


    #pz_items.objects.get(weixinuser_id = openid)
    #dbaction
    #do rnd caculate
    #if rnd :
    #pz:

    if rnd_res():
        try:
            with transaction.atomic():
                item = pz_items.objects.select_for_update().filter(weixinuser_id = None).first()
                #cart = models.Cart(creation_time=datetime.datetime.now(), related_user = self.related_user, related_dbo = self.related_dbo)
                item.weixinuser_id = str(wx_obj.pk)
                item.pz_s_time = datetime.datetime.now()
                cache.decr(AVAL_CNT_KEY)
                #cache set
                item.save()
            cache.set(str(wx_obj.pk) + '_cnt', 0, None)
            cache.set(str(wx_obj.pk) + '_zj', str(item.pk),None)
            data_dict['win'] = 1
            data_dict['code'] = str(item.pk)
            return HttpResponse(json.dumps(data_dict))
        except Exception as err:
            logger.error(err.message)
    else:
        data_dict['chance'] = cache.get(str(wx_obj.pk) + '_cnt', 0)
        return HttpResponse(json.dumps(data_dict))

def report(request):
    pz_item_lst = pz_items.objects.all().order_by('-pz_s_time')
    for p in pz_item_lst:
        if p.weixinuser_id:
            p.wx_obj = genl_operator.GetWeixinObject(p.weixinuser_id)
    return render(request, 'report.html', {'pz_lst': pz_item_lst})

    pass


def rnd_res(percent=10): #50%percent
    return random.randrange(100) < percent


def exists(self, query_set, key):
    """
    heng:
        2015/3/5
        new logic here
        exists only find item if exists in database

    old logic:
        if old name collection cache list has this item it will return directly true.

        it think it will be good item exists in database...
    """
    #if key in self._name_collection:
    #    return True
    if query_set is None:
        return False

    try:
        query_set.get(pk = key)
        return True
    except Exception as err:
        logger.error(err.message)
        return False

