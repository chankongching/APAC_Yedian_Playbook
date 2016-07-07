import logging
from django.core.cache import cache

import uuid

logger = logging.getLogger(__name__)

class TimeAndCacheManagement(object):
    def __init__(self, DbManagerObject):
        self.TimeManagerKeyId = DbManagerObject.customer_id #pmk in dbo
        #it's a dict {item:cacheuuid, item2:cacheuuid}
        self.time_manager_cache = cache.get(self.TimeManagerKeyId)
        if not self.time_manager_cache :
            cache.set(self.TimeManagerKeyId,{})
            self.time_manager_cache = cache.get(self.TimeManagerKeyId)

    def GetItemFromCache(self, key):
        cache_obj = self.time_manager_cache[key]
        return cache_obj

    def UpdateItemInCache(self, key, cache_obj, args):
        key = str(uuid.uuid4())
        cache.set(key, cache_obj)

