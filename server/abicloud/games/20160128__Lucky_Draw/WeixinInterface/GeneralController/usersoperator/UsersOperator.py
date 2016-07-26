# -*- coding: utf-8 -*-
import logging
logger = logging.getLogger(__name__)

#from GeneralBaseDataModels.models import WeixinUser

#proto import
#from protos.base import user_information_pb2
import random
import datetime

from django.utils import timezone

"""
KEY AREA FOR DICE
"""
KEY_PERSON_NAME = 'name'
KEY_PERSON_PHONE = 'mobile'
KEY_PERSON_AREA = 'qu'
KEY_PERSON_ADDR = 'addr'
KEY_PERSON_ADDR_DEFAULT = 'moren'

"""
Not Used now
"""
KEY_PERSON_LOCATE = 'person_locate'
KEY_PERSON_COUNTRY = 'person_country'
KEY_PERSON_PROVINCE = 'person_province'
KEY_PERSON_CITY = 'person_city'

"""
user info key
"""
KEY_USER_INFO_SUBSCRIBE = 'subscribe'
KEY_USER_WX_NICK_NAME = 'nickname'
KEY_USER_WX_SEX = 'sex'
KEY_USER_WX_HEAD_URL = 'headimgurl'
KEY_USER_WX_SUBSCRIBE_TIME = 'subscribe_time'
KEY_USER_WX_UNION_ID = 'unionid'
KEY_USER_WX_REMARK = 'remark'
KEY_USER_WX_GROUP_ID = 'groupid'



UPDATE_TIME_DAY_FREQ = 43200 #every one day update the user icon

class FieldsListError(Exception):
    pass
class BadRequestError(Exception):
    pass
class BadPb2StructInDatabaseError(Exception):
    pass

from GeneralController.models import GeneralController, GeneralMethod,USER_INFO_MODEL
genl_operator = GeneralController()

class UsersOperator:
    ITEM_LIMIT_CNT = 10
    ERR_CODE_MAX_LENGTH = -1
    ERR_CODE_BAD_REQUEST = -2
    ERR_CODE_BAD_PB2_STRU = -3
    ERR_CODE_NAME_DUPLICATED_IN_LST = -4

    def __init__(self, userobject):
        self._user_object = userobject

    def _update_user_object_via_fields(self, fields_list = []):
        """
        ToDo:
            add function to make sure user object is concurrency
        """
        if self._user_object is not None and fields_list:
            try:
                self._user_object.save(update_fields = fields_list)
            except Exception as err:
                logger.error('ERROR when update_userobject\n' + err.message)
                raise
        else:
            raise FieldsListError

    def ExpireUserData(self):
        genl_operator.ExpireWeixinObject(str(self._user_object.pk))

    def UpdateUserDeliverInformations(self, usr_delv_pb2_obj_collection):
        """
        Return:
            return length of collection itmes
        Excetpion:
            return error code
        """
        usr_deliver_field = ['person_deliver_info']
        usr_delv_pb2 = usr_delv_pb2_obj_collection
        #check usr_delv_pb2 is validate
        try:
            if usr_delv_pb2.IsInitialized():
                self._user_object.user_deliver_info_pb2 = usr_delv_pb2
                self._update_user_object_via_fields(usr_deliver_field)
                """
                here just return addr_lst cnt
                """
                self.ExpireUserData()
                return len(usr_delv_pb2.user_info_collection)
            else:
                return self.ERR_CODE_BAD_PB2_STRU
        except Exception as err:
            logger.error('ERROR:' + err.message)
            return self.ERR_CODE_BAD_REQUEST


    def _get_random_seed(self):
        return random.randint(1, 200)

    def filter_item_by_keyid(self, keyid, collection):
        """
        f(x) used for filter the addr is same like seed
        """
        def f(x): return x.addr_id == keyid

        try:
            res_lst = filter(f, collection)
            return res_lst
        except Exception as err:
            logger.error('err when filter collection by key %s \n ex:%s' % (keyid, err.message))
            raise BadRequestError

    def _get_a_unique_seed(self, addr_collect = None):
        random_seed = self._get_random_seed()

        if addr_collect is None:
            return random_seed

        """
        f(x) used for filter the addr is same like seed
        """
        def f(x): return x.addr_id == random_seed

        while True:
            res_lst = filter(f, addr_collect)
            if not res_lst:
                break;
            random_seed = self._get_random_seed()

        return random_seed

    def _update_item_in_pb2_from_request(self, item_user_info_pb2 = None, itemid = None, request = None):
        """
        In:
            item_user_info_pb2
        out ref:
            item_user_info_pb2
        """
        if item_user_info_pb2 is None or itemid is None or request is  None:
            raise BadRequestError('Error! Invalid address info')
        if not isinstance(itemid, int):
            raise BadRequestError('Error! Invalid address info')


        #if KEY_PERSON_NAME in request and KEY_PERSON_ADDR in request and KEY_PERSON_PHONE in request:
        item_user_info_pb2.addr_id = itemid
        item_user_info_pb2.is_default_item = False
        item_user_info_pb2.name = request[KEY_PERSON_NAME]
        item_user_info_pb2.address = request[KEY_PERSON_ADDR]
        item_user_info_pb2.phone = request[KEY_PERSON_PHONE]
        if not item_user_info_pb2.IsInitialized():
            raise BadRequestError('Error! Invalid address info')

        #else:
        #    logger.error('bad request for insert new deliver information')
        #    raise BadRequestError

        if (KEY_PERSON_COUNTRY in request):
            item_user_info_pb2.country = request[KEY_PERSON_COUNTRY]
        if (KEY_PERSON_PROVINCE in request):
            item_user_info_pb2.province = request[KEY_PERSON_PROVINCE]
        if (KEY_PERSON_CITY in request):
            item_user_info_pb2.city = request[KEY_PERSON_CITY]
        if (KEY_PERSON_AREA in request):
            item_user_info_pb2.area = request[KEY_PERSON_AREA]

        if not item_user_info_pb2.name or not item_user_info_pb2.address or not item_user_info_pb2.phone or not item_user_info_pb2.addr_id or not item_user_info_pb2.area:
            raise BadRequestError('Error! Invalid address info')


    """
    This Function is used for unit test
    ToDo: write a unit test
    """
    def _get_user_info_collection_from_user_object(self):
        return self._user_object.user_deliver_info_pb2
    """
    This Function is used for unit test
    ToDo: write a unit test
    """
    def _set_user_info_collection_from_user_object(self, pb2):
        self._user_object.user_deliver_info_pb2 = pb2
        self._user_object.save()
    """
    todo:
        need unit test
    """
    def GetDefaultAddrObj(self):
        pb2_col = self._user_object.user_deliver_info_pb2
        lst = filter(lambda x:x.is_default_item == True, pb2_col.user_info_collection)
        if len(lst) != 1:
            return None
        else:
            return lst[0]

    """
    todo:
        need unit test
    """
    def GetAddrInfoViaAddrid(self,addr_id):
        if not isinstance(addr_id, int):
            raise BadRequestError

        if addr_id is None:
            raise BadRequestError
        #find item in orderoperator
        pb2_col = self._user_object.user_deliver_info_pb2

        res = self.filter_item_by_keyid(addr_id, pb2_col.user_info_collection)
        if res:
            if len(res) > 1:
                logger.warn('item key is duplicated in lst, key is %s' % addr_id)
                return res[0]
            else:
                return res[0]
        else:
            return None

    def InserNewDeliverInformationFromRequest(self, request):
        """
        Return:
            # old return length of items collection
            ----> new  return the new addr_id
        """
        addr_collect = self._user_object.user_deliver_info_pb2
        if addr_collect:
            if len(addr_collect.user_info_collection) > self.ITEM_LIMIT_CNT:
                return self.ERR_CODE_MAX_LENGTH
        """
        Check the required items from pb2stru
        """
        item_need_added = addr_collect.user_info_collection.add()
        unique_random_seed = self._get_a_unique_seed(addr_collect.user_info_collection)
        self._update_item_in_pb2_from_request(item_need_added, unique_random_seed, request)

        if (KEY_PERSON_ADDR_DEFAULT in request):
            self.SetDefaultAddrFromPb2Collection(addr_collect, unique_random_seed)
        #res = self.UpdateUserDeliverInformations(addr_collect)
        #return res
        self.UpdateUserDeliverInformations(addr_collect)
        return unique_random_seed

    def ModifyDeliverInformationFromRequest(self, addr_id, request):
        """
        Return:
            # old return length of items collection
            ----> new  return the modified addr_id
        """
        if not isinstance(addr_id, int):
            try:
                addr_id = int(addr_id)
            except:
                raise BadRequestError

        if addr_id is None or request is None:
            raise BadRequestError
        #find item in orderoperator
        pb2_col = self._user_object.user_deliver_info_pb2

        res = self.filter_item_by_keyid(addr_id, pb2_col.user_info_collection)
        try:
            if res:
                if len(res) > 1:
                    logger.warn('item key is duplicated in lst, key is %s' % addr_id)
                    return self.ERR_CODE_NAME_DUPLICATED_IN_LST
                else:
                    self._update_item_in_pb2_from_request(res[0], addr_id, request)
                    if (KEY_PERSON_ADDR_DEFAULT in request):
                        self.SetDefaultAddrFromPb2Collection(pb2_col, addr_id)
                    #res = self.UpdateUserDeliverInformations(pb2_col)
                    #return res
                    self.UpdateUserDeliverInformations(pb2_col)
                    return addr_id
        except:
            raise
        else:
            return 0 # means there's nothing need to be modified


    def SetDefaultAddrByAddrId(self, addr_id):
        if not isinstance(addr_id, int):
            raise BadRequestError

        pb2_col = self._user_object.user_deliver_info_pb2
        self.SetDefaultAddrFromPb2Collection(pb2_col, addr_id)
        res = self.UpdateUserDeliverInformations(pb2_col)
        return res
    """
    ToDo: set a unit test
    """
    def SetDefaultAddrFromPb2Collection(self, pb2_col, addr_id):
        try:
            #pb2_col = self._user_object.user_deliver_info_pb2.user_info_collection
            for i in pb2_col.user_info_collection:
                if i.addr_id == addr_id:
                    i.is_default_item = True
                else:
                    i.is_default_item = False
        except:
            logger.error('bad_addr_id to set default')


    def DeleteDeliverInformationFromCollection(self, addr_id):
        if not isinstance(addr_id, int):
            raise BadRequestError

        if addr_id is None:
            raise BadRequestError
        #find item in orderoperator
        pb2_col = self._user_object.user_deliver_info_pb2

        res = self.filter_item_by_keyid(addr_id, pb2_col.user_info_collection)
        if res:
            if len(res) > 1:
                logger.warn('item key is duplicated in lst, key is %s' % addr_id)
                return self.ERR_CODE_NAME_DUPLICATED_IN_LST
            else:
                pb2_col.user_info_collection.remove(res[0])
                res = self.UpdateUserDeliverInformations(pb2_col)
                return res
        else:
            return 0 # means there's nothing need to be modified

    def AssignNickNameForWeixin(self, name):
        """
        ToDo
        add SQL injection prevent

        ToDo
        add unit test
        """
        if name:
            nick_name_field = ['nick_name']
            self._user_object.nick_name = name
            self._update_user_object_via_fields(nick_name_field)
            self.ExpireUserData()


    def UpdateUsersWxInfo(self, dbo_obj,force = False):
        auth = GeneralMethod.get_auth_token(dbo_obj)
        #logger.debug(auth)
        req_url = USER_INFO_MODEL % (auth, str(self._user_object.pk))
        #logger.debug(req_url)
        res_dict = GeneralMethod.get_url_json_response(req_url)
        #logger.debug(res_dict)
        self._update_user_wx_for_time_escape(res_dict, force)

    def _update_user_wx_for_time_escape(self, res_dict, force = False, refresh_time_range = UPDATE_TIME_DAY_FREQ):
        is_scribe = res_dict[KEY_USER_INFO_SUBSCRIBE]
        if is_scribe:
            if self._user_object.user_last_wx_info_update_time:
                db_time = self._user_object.user_last_wx_info_update_time.replace(tzinfo=None)
                now_time = timezone.now().replace(tzinfo=None)

                time_delta = now_time - db_time
            else:
                force = True

            if force:
                self._update_user_wx_info(res_dict)
            elif time_delta.seconds > refresh_time_range:
                self._update_user_wx_info(res_dict)
            else:
                pass #nothing to do

    def _update_user_wx_info(self, res_dict):
        self._user_object.user_is_subscribe = res_dict[KEY_USER_INFO_SUBSCRIBE]
        self._user_object.user_wx_nickname = res_dict[KEY_USER_WX_NICK_NAME]
        self._user_object.user_wx_sex = res_dict[KEY_USER_WX_SEX]
        self._user_object.user_wx_img = res_dict[KEY_USER_WX_HEAD_URL].replace('\\', '')
        self._user_object.user_wx_subscribe_time = res_dict[KEY_USER_WX_SUBSCRIBE_TIME]
        self._user_object.user_wx_remark = res_dict[KEY_USER_WX_REMARK]
        self._user_object.user_wx_group_id = res_dict[KEY_USER_WX_GROUP_ID]
        self._user_object.user_last_wx_info_update_time = timezone.now()

        self._update_user_object_via_fields(['user_is_subscribe', 'user_wx_nickname', 'user_wx_sex', 'user_wx_img', 'user_wx_subscribe_time', 'user_wx_remark', 'user_wx_group_id', 'user_last_wx_info_update_time'])
        self.ExpireUserData()

    @staticmethod
    def refresh_all_users():
        from GeneralBaseDataModels.models import WeixinUser, DbOwnersManagerTable
        wx_us = WeixinUser.objects.all()
        for usr in wx_us:
            dbos = DbOwnersManagerTable.objects.all()
            for dbo in dbos:
                try:
                    uc = UsersOperator(usr)
                    uc.UpdateUsersWxInfo(dbo, True)
                except:
                    pass

