#!/usr/bin/env python
# -*- coding: utf-8 -*-
import logging
logger = logging.getLogger(__name__)

from django.http import HttpResponse,HttpResponseRedirect
from django.views.decorators.csrf import csrf_exempt
#from django.template import RequestContext, Template
from django.utils.encoding import smart_str
import hashlib
from xml.etree import ElementTree as etree

from GeneralController.models import GeneralController, GeneralMethod
from GeneralController.usersoperator.UsersOperator import UsersOperator
from django.shortcuts import redirect
from django.conf import settings
import urllib

from django.http import Http404
import json
import datetime
#import urllib2

#from GeneralBaseDataModels.models import WeixinUser
#from django.conf import settings

#from GeneralBaseDataModels.models import DbOwnersManagerTable
"""
clean the cache first
"""

"""
serverinfo area
"""
OPENIDURL_MODEL = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&grant_type=authorization_code&code='
QAUTH_URL_MODEL = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect'

#operator
gencOperator = GeneralController()

KEY_REFER_URL = 'refer-url'
KEY_VIEW_NAME = 'refer-view'
KEY_VIEW_PARA = 'para'

KEY_IDENTIFY_CODE = 'identify_code'
KEY_REGISTER_KEY = 'reigister_key'
KEY_REQUEST_LEVEL = 'reql'



#weixin_info_class
#use nametuple


@csrf_exempt
def weixin(request):
    if request.method=='GET':
        response=HttpResponse(checkSignature(request))
        return response
    else:
       # this is post form weixin server
       #chkstatus = checkSignature(request)
       xmlstr = smart_str(request.body)
       xml = etree.fromstring(xmlstr)
       #import ipdb;ipdb.set_trace()

       ToUserName = xml.find('ToUserName').text
       FromUserName = xml.find('FromUserName').text
       CreateTime = xml.find('CreateTime').text
       #MsgType = xml.find('MsgType').text
       #Content = xml.find('Content').text
       #MsgId = xml.find('MsgId').text
       #reply_xml = """<xml>
       #<ToUserName><![CDATA[%s]]></ToUserName>
       #<FromUserName><![CDATA[%s]]></FromUserName>
       #<CreateTime>%s</CreateTime>
       #<MsgType><![CDATA[text]]></MsgType>
       #<Content><![CDATA[%s]]></Content>
       #</xml>"""%(FromUserName,ToUserName,CreateTime,Content + "Welcome to YiXin Tech")

       reply_xml_transferToKefu = """<xml>
       <ToUserName><![CDATA[%s]]></ToUserName>
       <FromUserName><![CDATA[%s]]></FromUserName>
       <CreateTime>%s</CreateTime>
       <MsgType><![CDATA[transfer_customer_service]]></MsgType>
       </xml>"""%(FromUserName,ToUserName,CreateTime)
       return HttpResponse(reply_xml_transferToKefu)

def checkSignature(request):
    signature=request.GET.get('signature',None)
    timestamp=request.GET.get('timestamp',None)
    nonce=request.GET.get('nonce',None)
    echostr=request.GET.get('echostr',None)
    #...token...setting...........
    token="yourtoken"

    tmplist=[token,timestamp,nonce]
    tmplist.sort()
    tmpstr="%s%s%s"%tuple(tmplist)
    tmpstr=hashlib.sha1(tmpstr).hexdigest()
    if tmpstr==signature:
        return echostr
    else:
        return None

#def get_url_json_response(url):
#    login_request = urllib2.Request(url)
#
#    login_response = urllib2.urlopen(login_request).read()
#    login_dict = eval(login_response)
#    return login_dict


#######################################################
"""
OAuth 2.0 for weixin
"""
#def get_auth_token(dbo_obj):
#    if dbo_obj:
#        url = ACCESS_TOKEN_URL_MODEL % (dbo_obj.wx_appid, dbo_obj.wx_appsecret)
#        login_dict = get_url_json_response(url)
#        try:
#            token = login_dict[KEY_ACCESS_TOKEN]
#            return token
#        except:
#            return None


"""
move to general method
"""
def build_oauth_url(IdentifyCode, redir_url):
    dbo_obj = gencOperator.GetDboObject(IdentifyCode)
    if dbo_obj:
        ret = QAUTH_URL_MODEL % (dbo_obj.wx_appid, redir_url)
        return ret
    else:
        return None


"""
move to general method
"""
def build_OpenIdUrl(IdentifyCode):
    dbo_obj = gencOperator.GetDboObject(IdentifyCode)
    if dbo_obj:
        ret = OPENIDURL_MODEL % (dbo_obj.wx_appid, dbo_obj.wx_appsecret)
        return ret
    else:
        return None

"""
get openid from weixin oauth api
type:
    snap_base
"""
"""
move to general method
"""
def get_openid_from_url_and_code(openidUrl, code):
    if openidUrl is None or code is None:
        return None

    requesturi = openidUrl + code
    login_dict = GeneralMethod.get_url_json_response(requesturi)

    #jdata = json.dumps(login_response,ensure_ascii=False)#.encode('utf-8')
    openid = login_dict['openid']
    return openid


"""
get openid from request calling
"""
"""
move to general method
"""
def get_openid_from_request(request_calling):
    if request_calling.method=='GET':
        code=request_calling.GET.get('code',None)
        identify_code = request_calling.GET.get(KEY_IDENTIFY_CODE, None)

        openidUrl = build_OpenIdUrl(identify_code)
        openid = get_openid_from_url_and_code(openidUrl, code)
        return openid



def get_redir_to_oauth_to_update_openid(request):
    """
    ToDo: prevent loop forever logic
    """
    refer_view = request.GET.get(KEY_VIEW_NAME)
    refer_url = request.GET.get(KEY_REFER_URL)
    openid = get_openid_from_request(request)
    if openid:
        request.session['openid'] = openid

    if refer_view and refer_view != 'None':
        return redirect(refer_view)

    if refer_url and refer_url != 'None':
        return redirect(refer_url)


"""
move to general method
"""
def redir_to_oauth_update_openid_url(identify_code , refer_view = None, refer_url = None):
    if not identify_code:
        return None

    if refer_view or refer_url:
        oauth_url = settings.MY_SERVER_DOMAIN + 'gate_keeper/'
        params = urllib.urlencode({KEY_VIEW_NAME:refer_view, KEY_REFER_URL:refer_url, KEY_IDENTIFY_CODE:identify_code})
        request_url = oauth_url + '?' + params
        url = build_oauth_url(identify_code, urllib.quote_plus(request_url))
        return url
    else:
        """
        if no refer_parameters that means we do not need redir
        """
        oauth_url = settings.MY_SERVER_DOMAIN + 'gate_keeper/'
        params = urllib.urlencode({KEY_IDENTIFY_CODE:identify_code})
        request_url = oauth_url + '?' + params
        url = build_oauth_url(identify_code, urllib.quote_plus(request_url))
        return url


def home_page_redirect(request):
    openid = get_openid_from_request(request)
    return_uri = '/act?openid=%s' % openid
    #gencOperator.get_or_create_weixinuser_object(openid, False)

    return HttpResponseRedirect(return_uri)

"""
move to general method
"""
def send_admin_order_template_msg(dbo_obj = None, order_info = None, redir_relative_url = None, templateid = None):
    #dbo_obj = gencOperator.GetDboObject('fb28de37484247efbff8ab5f1728bf2e')
    tmp_msg_controller = TemplateMessageController(dbo_obj)
    po = PrivilegeOperator(dbo_obj)
    admin_lst = po.GetDboAdministrators(3)

    #wx_object_from_session = gencOperator.get_wxuserobject_from_session_openid(request)
    for wx_object in admin_lst:
        #if not wx_object:
        #    oauth_url = redir_to_oauth_update_openid_url('fb28de37484247efbff8ab5f1728bf2e', None)
        #    return redirect(oauth_url)
        if not order_info:
            return

        item_info = ''
        for itemp in order_info.item_info_pb2.items_collection:
            item_info += itemp.item_chinese_name + '_'
            if itemp.item_detail_info.size:
                item_info += unicode(itemp.item_detail_info.size)
            else:
                item_info += unicode(itemp.item_detail_info.unit) + '-X' + unicode(itemp.buy_count) + ','

        #we use datetime .now instead of ordercreation time because of utc +8 time
        #lst = [TemplateDataValue(order_info.order_creation_time.strftime('%Y-%m-%d %H:%M:%S'), '#173177'),
        lst = [TemplateDataValue(datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S'), '#173177'),
               TemplateDataValue(str(order_info.total_price), '#173177'),
               TemplateDataValue(item_info, '#173177'),
               TemplateDataValue(order_info.customer_address, '#173177'),
               ]
        #for users in WeixinUser.objects.all():
        #ret = tmp_msg_controller.send_order_create_push(str(wx_object.pk), lst)
        redir_url = settings.MY_SERVER_DOMAIN + redir_relative_url + 'oa_ad?order_id=' + str(order_info.pk)
        tmp_msg_controller.send_order_create_push(str(wx_object.pk), lst, redir_url, templateid)
        #return HttpResponse(ret)

"""
move to general method
"""
def send_order_status_change_template_msg(dbo_obj = None, order_info = None, redir_relative_url = None, templateid = None, remark_data = None):
    tmp_msg_controller = TemplateMessageController(dbo_obj)
    wxid = order_info.related_weixin_id
    if wxid:
        lst = [TemplateDataValue(order_info.short_order_id, '#173177'),
               TemplateDataValue(order_info.get_order_status_display(), '#173177'),
               TemplateDataValue(remark_data, '#173177'),
               ]
        tmp_msg_controller.send_order_state_change_push(str(wxid.pk), lst ,templateid)

@csrf_exempt
def assign_nick_name_to_wx_user(request):
    nick_name = request.POST.get('nick_name', None)
    openid = request.POST.get('openid', None)

    if openid and nick_name:
        wx_user_obj = gencOperator.GetWeixinObject(openid)
        usr_operat = UsersOperator(wx_user_obj)
        usr_operat.AssignNickNameForWeixin(nick_name)

        gencOperator.ExpireWeixinObject(openid)

        return  HttpResponse(json.dumps({"msg":''}))
    else:
        raise Http404

def assign_user_to_admin(request):
    raise Http404
    #from GeneralBaseDataModels.models import DbOwnersAdminGroup
    ##dboid = 'fb28de37484247efbff8ab5f1728bf2e'
    ##vk
    ##dboid = '19f34792b3f24cb5ac639a6adeb4f8b9'
    #dboid = 'f8a3d348731c4824b66da9dbfb4fc8ae'
    #genl_operator = GeneralController()
    #wx_object_from_session = genl_operator.get_wxuserobject_from_session_openid(request, dboid)
    #if not wx_object_from_session:
    #    oauth_url = redir_to_oauth_update_openid_url(dboid, __name__ + '.' + assign_user_to_admin.__name__)
    #    return redirect(oauth_url)

    #dbo_obj = gencOperator.GetDboObject(dboid)
    #privOperator = PrivilegeOperator(dbo_obj)
    ##privOperator.DisAssignUserToGroup(wx_object_from_session, DbOwnersAdminGroup.ADMIN)
    #privOperator.AssignUserToGroup(wx_object_from_session, DbOwnersAdminGroup.ADMIN)
    #from django.core.cache import cache
    #cache.clear()
    #return  HttpResponse('successful')

def work_order_reidr(request):
    #back_to_async_call = request.GET.get('back_to', None)
    #logger.error(back_to_async_call)
    #if  back_to_async_call is not None:
    #    return redirect(back_to_async_call)

    current_dboid = request.GET.get('dboid', None)
    if not current_dboid:
        raise ValueError('bad dboid')

    genl_operator = GeneralController()
    wx_object_from_session = genl_operator.get_wxuserobject_from_session_openid(request, current_dboid)
    if not wx_object_from_session:
        oauth_url = redir_to_oauth_update_openid_url(current_dboid, __name__ + '.' + work_order_reidr.__name__)
        return redirect(oauth_url)

    import jwt
    user_data = {
            'name': '',
            'external_id': str(wx_object_from_session.pk),
            'email': ''
            }

    # 你的API Secret
    api_secret = "W_zWrmcryxs_cnjnOiCnLQ"

    # 创建JWT请求
    jwt_data = jwt.encode(user_data, api_secret)

    # 定义返回网址
    back_to_url = "http://evri.daikeapp.com/?jwt=%s" % jwt_data

    # 跳转回待客
    return redirect(back_to_url)

def ticket_handler(request):
    import json
    json_obj = json.loads(request.body)
    from protos.workorder import daike_struct_pb2
    from protobuf2json import protobuf_json
    pb = protobuf_json.json2pb(daike_struct_pb2.DaikeApp(), json_obj)
    if pb.event == 'create_ticket':
        #send to administrators
        pass
    if pb.event == 'create_comment':
        #send to users
        pass
    if pb.event == 'update_ticket_status':
        #send to users ticket status change
        pass
    return HttpResponse()

