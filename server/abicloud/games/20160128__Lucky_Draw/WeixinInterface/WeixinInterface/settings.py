"""
Django settings for WeixinInterface project.

For more information on this file, see
https://docs.djangoproject.com/en/1.7/topics/settings/

For the full list of settings and their values, see
https://docs.djangoproject.com/en/1.7/ref/settings/
"""
# -*- coding: utf-8 -*-

#MYSERVERSETTINGS
#MY_SERVER_DOMAIN = 'http://a.evri.me:443/'
MY_SERVER_DOMAIN = 'http://letsktv.chinacloudapp.cn/games/20160128__Lucky_Draw/'
#MY_SERVER_DOMAIN = 'http://wx.anertuo.com/'
ALLOWED_HOSTS = ['wx.carpenter-cn.com', 'letsktv.chinacloudapp.cn',]
#MY_SERVER_DOMAIN = 'http://a.evri.me/'
#import _init the memcached! temp:::::

# Build paths inside the project like this: os.path.join(BASE_DIR, ...)
import os
BASE_DIR = os.path.dirname(os.path.dirname(__file__))

DEFAULT_CHARSET = 'utf-8'


# Quick-start development settings - unsuitable for production
# See https://docs.djangoproject.com/en/1.7/howto/deployment/checklist/

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = 'j0(x*1l7_dcow_uv7w4y%sz7g8xq652bfhgbudhgkve%=*b7k&'

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = False

TEMPLATE_DEBUG = False

# Application definition
TEMPLATE_DIRS = (
        BASE_DIR + '/GeneralTemplate/',
        )

INSTALLED_APPS = (
    #'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    'wxapi',
    'GeneralBaseDataModels',
    'Budweiser',
)

MIDDLEWARE_CLASSES = (
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.auth.middleware.SessionAuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
    #cache
    #'django.middleware.cache.UpdateCacheMiddleware',
    #'django.middleware.cache.FetchFromCacheMiddleware',
)

ROOT_URLCONF = 'WeixinInterface.urls'

WSGI_APPLICATION = 'WeixinInterface.wsgi.application'

LOGGING = {
        'version': 1,
        'disable_existing_loggers': False,

        'formatters': {
            'verbose': {
                'format': '[%(levelname)s] -- %(asctime)s -- %(module)s %(message)s '
                #'format': '[%(levelname)s] %(asctime)s %(module)s %(process)d %(thread)d %(message)s'
                },
            'simple': {
                'format': '[%(levelname)s] %(message)s'
                },
            },

        #'filters': {
        #    'special': {
        #        '()': 'project.logging.SpecialFilter',
        #        'foo': 'bar',
        #        }
        #    },

        'handlers': {
            'null': {
                'level': 'DEBUG',
                'class': 'logging.NullHandler',
                },
            'console': {
                'level': 'DEBUG',
                'class': 'logging.StreamHandler',
                'formatter': 'simple'
                },
            #'mail_admins': {
            #    'level': 'ERROR',
            #    'class': 'django.utils.log.AdminEmailHandler',
            #    'filters': ['special']
            #    },

            'file': {
                'level': 'DEBUG',
                'class': 'logging.FileHandler',
                'filename': '/opt/myapps_git/abicloud/games/20160128__Lucky_Draw/WeixinInterface/wxapi.log',
                'formatter': 'verbose'
                },
            },

        'loggers': {
            'django.request': {
                'handlers': ['file'],
                'level': 'DEBUG',
                'propagate': True,
                },
            'GeneralBaseDataModels': {
                'handlers': ['file'],
                'level': 'DEBUG',
                'propagate': True,
                },
            'GeneralController': {
                'handlers': ['file'],
                'level': 'DEBUG',
                'propagate': True,
                },
            'CacheHelper': {
                'handlers': ['file', 'console'],
                'level': 'INFO',
                'propagate': True,
                },
            'Budweiser': {
                'handlers': ['file', 'console'],
                'level': 'DEBUG',
                'propagate': True,
                },
            'CartGeneral': {
                'handlers': ['file', 'console'],
                'level': 'INFO',
                'propagate': True,
                },
            },
        }



# Database
# https://docs.djangoproject.com/en/1.7/ref/settings/#databases

DATABASES = {
    'default': {
        #'ENGINE': 'django.db.backends.sqlite3',
        #'NAME': os.path.join(BASE_DIR, 'db.sqlite3'),
        'ENGINE': 'django.db.backends.mysql',
        'NAME': 'letsktv_games',
        'USER': 'letsktv',
        'PASSWORD': 'OBjhe7UF3IsMIwPK',
        'HOST': '127.0.0.1',
        'PORT': '3306',
    }
}
CACHES = {
    'default': {
        #'BACKEND': 'django.core.cache.backends.memcached.MemcachedCache',
        #'LOCATION': '127.0.0.1:12000',
        'BACKEND': 'redis_cache.RedisCache',
        'LOCATION': '127.0.0.1:6379',
        'OPTIONS': {
            'DB': 2,
            #'PASSWORD': 'yadayada',
            #'PARSER_CLASS': 'redis.connection.HiredisParser',
            #'CONNECTION_POOL_CLASS': 'redis.BlockingConnectionPool',
            #'CONNECTION_POOL_CLASS_KWARGS': {
            #    'max_connections': 50,
            #    'timeout': 20,
            #},
            #'MAX_CONNECTIONS': 1000,
            #'PICKLE_VERSION': -1,
        },
    }
}
#session part
SESSION_ENGINE = 'django.contrib.sessions.backends.cached_db'

# Internationalization
# https://docs.djangoproject.com/en/1.7/topics/i18n/

LANGUAGE_CODE = 'en-us'

#TIME_ZONE = 'UTC'
TIME_ZONE = 'Asia/Shanghai'

USE_I18N = True

USE_L10N = True

USE_TZ = True

#STATIC FILE COLLECT PROBLEM
FILE_CHARSET = 'utf-8'
#STATICFILES_STORAGE = 'myproject.storage.S3Storage'

# Static files (CSS, JavaScript, Images)
# https://docs.djangoproject.com/en/1.7/howto/static-files/

#STATIC_ROOT = os.path.join(os.path.dirname(__file__),'static')
STATIC_ROOT = '/opt/myapps_git/abicloud/games/20160128__Lucky_Draw/static/'
STATIC_URL = 'http://letsktv.chinacloudapp.cn/games/20160128__Lucky_Draw/static/'
#STATIC_URL = '/static/'
STATICFILES_DIRS = (
        os.path.join(BASE_DIR, "static"),
)


#STATIC_URL = '/static/'

#MEDIA FILE SETTING

MEDIA_ROOT = (
        #os.path.join(BASE_DIR, "static/media"),
        "/pywww/static/media",
        )
#MEDIA_URL = 'http://static.anertuo.com/static/media/'
MEDIA_URL = '/static/media/'


