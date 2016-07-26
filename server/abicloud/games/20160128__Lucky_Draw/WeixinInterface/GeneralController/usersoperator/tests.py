# -*- coding: utf-8 -*-
from django.test import TestCase

from GeneralController.usersoperator.UsersOperator import UsersOperator
from GeneralBaseDataModels.models import WeixinUser, DbOwnersManagerTable

#import exception
from GeneralController.usersoperator.UsersOperator import *
from protos.base import user_information_pb2
from django.core.cache import cache
import copy
import time

class obj(object):
    pass

class UserOperatorTest(TestCase):
    def setUp(self):
        self.dbo = DbOwnersManagerTable(customer_name='looker_kitchen')
        self.dbo.save()

        #create WxUser
        self.testwxuser = WeixinUser.objects.create(weixinuser_id='test1wxuser')
        pass

    """
    ToDo
    Do unit tset fro UpdateUserDeliverInformations
    """

    def test_update_user_object_via_fields(self):
        print '^^^^^^^^test user operator update user func^^^^^^^^^'
        opera = UsersOperator(self.testwxuser)
        self.assertRaises(FieldsListError, opera._update_user_object_via_fields, [])
        self.assertRaises(Exception, opera._update_user_object_via_fields, ['abcd'])

        self.testwxuser.menu_flag = 5
        self.testwxuser.person_loate = '123'
        opera._update_user_object_via_fields(['menu_flag'])

        item_from_db = WeixinUser.objects.get(pk = self.testwxuser.pk)
        self.assertEqual(item_from_db.menu_flag, self.testwxuser.menu_flag)
        self.assertNotEqual(item_from_db.person_loate, self.testwxuser.person_loate)
        print '^^^^^^^^test user operator update user func^^^^^^^^^'

    def test_UpdateUserDeliverInformation(self):
        print '^^^^^^^^test user operator update user func^^^^^^^^^'
        print '^^^^^^^^test user operator update user func^^^^^^^^^'


    def test_get_unique_seed(self):
        print '^^^^^^^^test Getrandom seed^^^^^^^^^'
        opera = UsersOperator(self.testwxuser)
        ret_seed = opera._get_a_unique_seed()
        self.assertNotEqual(ret_seed, 0)

        addr_collect_fake = []
        for i in range(1,190):#means 1-189!!!!
            item = obj()
            #setattr(item,'addr_id', 'ss')
            item.addr_id = i
            addr_collect_fake.append(item)

        res = opera._get_a_unique_seed(addr_collect_fake);
        self.assertTrue(res >= 190)
        res = opera._get_a_unique_seed(addr_collect_fake);
        self.assertTrue(res >= 190)
        res = opera._get_a_unique_seed(addr_collect_fake);
        self.assertTrue(res >= 190)
        print '^^^^^^^^test Getrandom seed^^^^^^^^^'

    def test_update_item_in_pb2_from_request(self):
        print '^^^^^^^^test Update item in Pb2 from request^^^^^^^^^'
        opera = UsersOperator(self.testwxuser)
        self.assertRaises(BadRequestError, opera._update_item_in_pb2_from_request, (None,None,None))
        userinfo_pb2 = user_information_pb2.UserDeliverInformationCollection.UserInfo()
        request = {}
        request[KEY_PERSON_NAME] = 'name'

        self.assertRaises(BadRequestError, opera._update_item_in_pb2_from_request, (userinfo_pb2, 222, request))
        self.assertRaises(BadRequestError, opera._update_item_in_pb2_from_request, (userinfo_pb2, '222', request))
        request[KEY_PERSON_NAME] = 'name'
        request[KEY_PERSON_ADDR] = 'addr'
        request[KEY_PERSON_PHONE] = 'phone'
        request[KEY_PERSON_AREA] = '1111'

        opera._update_item_in_pb2_from_request(userinfo_pb2, 222, request)
        self.assertEqual(userinfo_pb2.addr_id, 222)
        self.assertEqual(userinfo_pb2.is_default_item, False)
        self.assertEqual(userinfo_pb2.name, 'name')
        self.assertEqual(userinfo_pb2.phone, 'phone')
        self.assertEqual(userinfo_pb2.city, '')

        request[KEY_PERSON_AREA] = 'area'
        opera._update_item_in_pb2_from_request(userinfo_pb2, 222, request)
        self.assertEqual(userinfo_pb2.city, '')
        self.assertEqual(userinfo_pb2.area, 'area')
        print '^^^^^^^^test Update item in Pb2 from request^^^^^^^^^'

    def test_get_user_info_collection(self):
        print '^^^^^^^^test Get user info collect^^^^^^^^^'
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        testwxuser3 = WeixinUser.objects.create(weixinuser_id='test3wxuser')
        userinfo_pb2_col = user_information_pb2.UserDeliverInformationCollection()
        userinfo_pb2 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2.addr_id = 2
        userinfo_pb2.is_default_item = False
        userinfo_pb2.name = 'name2'
        userinfo_pb2.phone = '22222222'
        userinfo_pb2.address = '22222222'

        userinfo_pb2_1 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2_1.addr_id = 3
        userinfo_pb2_1.name = 'name3'
        userinfo_pb2_1.is_default_item = False
        userinfo_pb2_1.phone = '33333'
        userinfo_pb2_1.address = '33333'
        str1 = userinfo_pb2_col.SerializeToString()
        testwxuser2.person_deliver_info = str1
        testwxuser2.save()

        pb2_from_models = testwxuser2.user_deliver_info_pb2
        self.assertEqual(len(pb2_from_models.user_info_collection), 2)
        self.assertEqual(pb2_from_models.user_info_collection[1].phone, '33333')
        self.assertEqual(len(testwxuser3.user_deliver_info_pb2.user_info_collection), 0)
        print '^^^^^^^^test Get user info collect^^^^^^^^^'
        pass

    def test_insert_new_deliver_information_fromrequest(self):
        print '^^^^^^^^test set item into db^^^^^^^^^'
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        userinfo_pb2_col = user_information_pb2.UserDeliverInformationCollection()
        userinfo_pb2 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2.addr_id = 2
        userinfo_pb2.is_default_item = False
        userinfo_pb2.name = 'name2'
        userinfo_pb2.phone = '22222222'
        userinfo_pb2.address = '22222222'

        testwxuser2.user_deliver_info_pb2 = userinfo_pb2
        testwxuser2.save()
        self.assertEqual(testwxuser2.person_deliver_info, '')
        testwxuser2.user_deliver_info_pb2 = userinfo_pb2_col
        testwxuser2.save()
        print '^^^^^^^^test set item into db^^^^^^^^^'

    def test_insert_new_deliver_info(self):
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        opera = UsersOperator(testwxuser2)
        request = {}
        request[KEY_PERSON_NAME] = 'name'
        request[KEY_PERSON_ADDR] = 'addr'
        request[KEY_PERSON_PHONE] = 'phone'
        request[KEY_PERSON_AREA] = '1111'
        res = opera.InserNewDeliverInformationFromRequest(request)
        self.assertTrue(testwxuser2.person_deliver_info is not None)
        self.assertEqual(len(testwxuser2.user_deliver_info_pb2.user_info_collection), 1)
        testwxuser2 = WeixinUser.objects.get(weixinuser_id='test2wxuser')
        #self.assertEqual(res, 1)
        self.assertEqual(res, testwxuser2.user_deliver_info_pb2.user_info_collection[0].addr_id)
        res = opera.InserNewDeliverInformationFromRequest(request)

        testwxuser2 = WeixinUser.objects.get(weixinuser_id='test2wxuser')
        self.assertEqual(len(testwxuser2.user_deliver_info_pb2.user_info_collection), 2)
        self.assertEqual(res, testwxuser2.user_deliver_info_pb2.user_info_collection[1].addr_id)
        #self.assertEqual(res, 2)

    def test_modify_deliver_info(self):
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        opera = UsersOperator(testwxuser2)

        userinfo_pb2_col = user_information_pb2.UserDeliverInformationCollection()
        userinfo_pb2 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2.addr_id = 2
        userinfo_pb2.is_default_item = False
        userinfo_pb2.name = 'name2'
        userinfo_pb2.phone = '22222222'
        userinfo_pb2.address = '22222222'

        userinfo_pb2_1 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2_1.addr_id = 3
        userinfo_pb2_1.name = 'name3'
        userinfo_pb2_1.is_default_item = False
        userinfo_pb2_1.phone = '33333'
        userinfo_pb2_1.address = '33333'
        str1 = userinfo_pb2_col.SerializeToString()
        testwxuser2.person_deliver_info = str1
        testwxuser2.save()

        testwxuser2.person_deliver_info = userinfo_pb2_col.SerializeToString()
        testwxuser2.save()

        request = {}
        """
        by the way test chinese
        """
        request[KEY_PERSON_NAME] = u'名字'
        request[KEY_PERSON_ADDR] = u'地址'
        request[KEY_PERSON_PHONE] = u'电话'
        request[KEY_PERSON_AREA] = u'滨湖区'
        res = opera.ModifyDeliverInformationFromRequest(2, request)
        self.assertEqual(res, 2)

        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].addr_id, 2)
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].name.encode('utf8'), '名字')
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].address.encode('utf8'), '地址')
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].area.encode('utf8'), '滨湖区')

    def test_delete_deliver_info(self):
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        opera = UsersOperator(testwxuser2)

        userinfo_pb2_col = user_information_pb2.UserDeliverInformationCollection()
        userinfo_pb2 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2.addr_id = 2
        userinfo_pb2.is_default_item = False
        userinfo_pb2.name = 'name2'
        userinfo_pb2.phone = '22222222'
        userinfo_pb2.address = '22222222'

        userinfo_pb2_1 = userinfo_pb2_col.user_info_collection.add()
        userinfo_pb2_1.addr_id = 3
        userinfo_pb2_1.name = 'name3'
        userinfo_pb2_1.is_default_item = False
        userinfo_pb2_1.phone = '33333'
        userinfo_pb2_1.address = '33333'
        str1 = userinfo_pb2_col.SerializeToString()
        testwxuser2.person_deliver_info = str1
        testwxuser2.save()

        testwxuser2.person_deliver_info = userinfo_pb2_col.SerializeToString()
        testwxuser2.save()

        self.assertEqual(len(testwxuser2.user_deliver_info_pb2.user_info_collection), 2)
        opera.DeleteDeliverInformationFromCollection(2)

        self.assertEqual(len(testwxuser2.user_deliver_info_pb2.user_info_collection), 1)
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].addr_id, 3)
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].name, u'name3')
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].address, u'33333')
        self.assertEqual(testwxuser2.user_deliver_info_pb2.user_info_collection[0].address, u'33333')

    def test_update_users_wx_info(self):
        cache.clear()
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        test_json_raw_str = r'{\
    "subscribe": 1,\
    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",\
    "nickname": "Band",\
    "sex": 1,\
    "language": "zh_CN",\
    "city": "广州",\
    "province": "广东",\
    "country": "中国",\
    "headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",\
    "subscribe_time": 1382694957,\
    "unionid":" o6_bmasdasdsad6_2sgVt7hMZOPfL",\
    "remark":"ss",\
    "groupid": 0\
}'
        dict_ros = eval(test_json_raw_str)
        opera = UsersOperator(testwxuser2)
        opera._update_user_wx_info(dict_ros)
        self.assertEqual(opera._user_object.user_wx_nickname, 'Band')
        self.assertEqual(opera._user_object.user_wx_img, 'http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0')
        self.assertEqual(opera._user_object.user_wx_group_id, 0)
        self.assertEqual(opera._user_object.user_wx_sex, 1)
        self.assertEqual(opera._user_object.user_wx_remark, 'ss')

    def test_user_update_user_info(self):
        cache.clear()
        testwxuser2 = WeixinUser.objects.create(weixinuser_id='test2wxuser')
        test_json_raw_str = r'{\
    "subscribe": 1,\
    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",\
    "nickname": "Band",\
    "sex": 1,\
    "language": "zh_CN",\
    "city": "广州",\
    "province": "广东",\
    "country": "中国",\
    "headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",\
    "subscribe_time": 1382694957,\
    "unionid":" o6_bmasdasdsad6_2sgVt7hMZOPfL",\
    "remark":"ss",\
    "groupid": 0\
}'

        dict_ros = eval(test_json_raw_str)
        opera = UsersOperator(testwxuser2)
        opera._update_user_wx_for_time_escape(dict_ros, True)
        self.assertEqual(opera._user_object.user_wx_nickname, 'Band')
        self.assertEqual(opera._user_object.user_wx_img, 'http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0')
        self.assertEqual(opera._user_object.user_wx_group_id, 0)
        self.assertEqual(opera._user_object.user_wx_sex, 1)
        self.assertEqual(opera._user_object.user_wx_remark, 'ss')

        time.sleep(2)
        test_json_raw_str1 = r'{\
    "subscribe": 1,\
    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",\
    "nickname": "Band2",\
    "sex": 1,\
    "language": "zh_CN",\
    "city": "广州",\
    "province": "广东",\
    "country": "中国",\
    "headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",\
    "subscribe_time": 1382694957,\
    "unionid":" o6_bmasdasdsad6_2sgVt7hMZOPfL",\
    "remark":"ss",\
    "groupid": 0\
}'
        dict_ros = eval(test_json_raw_str1)
        opera._update_user_wx_for_time_escape(dict_ros, False, 10)
        self.assertNotEqual(opera._user_object.user_wx_nickname, 'Band2')

        time.sleep(1)
        test_json_raw_str2 = r'{\
    "subscribe": 1,\
    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",\
    "nickname": "Band3",\
    "sex": 1,\
    "language": "zh_CN",\
    "city": "广州",\
    "province": "广东",\
    "country": "中国",\
    "headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",\
    "subscribe_time": 1382694957,\
    "unionid":" o6_bmasdasdsad6_2sgVt7hMZOPfL",\
    "remark":"ss",\
    "groupid": 0\
}'
        dict_ros = eval(test_json_raw_str2)
        import ipdb;ipdb.set_trace()
        opera._update_user_wx_for_time_escape(dict_ros, False, 2)
        self.assertEqual(opera._user_object.user_wx_nickname, 'Band3')



