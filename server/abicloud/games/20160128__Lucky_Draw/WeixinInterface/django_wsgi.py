#!/usr/bin/env python
# coding: utf-8

import os
import sys

# 将系统的编码设置为UTF8
reload(sys)
sys.setdefaultencoding('utf8')

sys.path.append('/opt/myapps_git/abicloud/games/20160128__Lucky_Draw/WeixinInterface')
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "WeixinInterface.settings")
#sys.path.append('/opt/myapps_git/abicloud/games/20160128__Lucky_Draw/WeixinInterface/GeneralTemplate')

from django.core.wsgi import get_wsgi_application
application = get_wsgi_application()

#from django.core.handlers.wsgi import WSGIHandler
#application = WSGIHandler()
