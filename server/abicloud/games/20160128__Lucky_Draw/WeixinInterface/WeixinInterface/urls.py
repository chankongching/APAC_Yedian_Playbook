from django.conf.urls import patterns, include, url
#from django.contrib import admin
#from WeixinInterface.views import hello
#from WeixinInterface.view import hello
#from WeixinInterface.view import current_datetime
from wxapi.views import weixin, get_redir_to_oauth_to_update_openid
#from luke.views import *

#from django.http import HttpResponse
#import datetime


from Budweiser.views import *

urlpatterns = patterns('',
    # Examples:
    # url(r'^$', 'WeixinInterface.views.home', name='home'),
    # url(r'^blog/', include('blog.urls')),

    url('^weixin/$', weixin),
    #url('^oautuhredir/$', weixin_oauth),
    #url('^oauth/$', weixin_wxuser_auth),
    #url('^back/$', back_get_main_information),
    url('^gate_keeper/$', get_redir_to_oauth_to_update_openid),
    url('^index/$', index),
    url('^act/$', act),
    url('^report/$', report),
    #url('^image_upload/$', upload_file),
    #url('^assign_nick_name/$', assign_nick_name_to_wx_user),
    #url('^work_order/$', work_order_reidr),
    #url('^uuuuu/$', assign_user_to_admin),
    #url(r'', include('luke.urls')),
    #url(r'', include('vkcookie.urls')),

)
