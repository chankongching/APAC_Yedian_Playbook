# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations
import uuidfield


class Migration(migrations.Migration):

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='DbOwnersManagerTable',
            fields=[
                ('customer_id', uuidfield.UUIDField(primary_key=True, serialize=False, editable=False, max_length=32, blank=True, unique=True)),
                ('customer_name', models.CharField(unique=True, max_length=100)),
                ('customer_description', models.CharField(max_length=500)),
                ('db_manager_settings', models.CharField(max_length=2000)),
                ('creation_time', models.DateTimeField(auto_now_add=True, null=True)),
                ('last_update_time', models.DateTimeField(auto_now=True, null=True)),
                ('wx_appid', models.CharField(max_length=200)),
                ('wx_appsecret', models.CharField(max_length=200)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.CreateModel(
            name='WeixinUser',
            fields=[
                ('weixinuser_id', models.CharField(max_length=50, serialize=False, primary_key=True)),
                ('nick_name', models.CharField(max_length=150)),
                ('creation_time', models.DateTimeField(auto_now_add=True, null=True)),
                ('last_message_time', models.DateTimeField(auto_now=True, null=True)),
                ('menu_flag', models.SmallIntegerField(default=0)),
                ('person_loate', models.CharField(max_length=100)),
                ('person_deliver_info', models.BinaryField(max_length=1000)),
                ('user_is_subscribe', models.IntegerField(default=0)),
                ('user_wx_nickname', models.BinaryField(max_length=200)),
                ('user_wx_sex', models.IntegerField(default=0)),
                ('user_wx_img', models.CharField(max_length=500)),
                ('user_wx_subscribe_time', models.IntegerField(default=0)),
                ('user_wx_remark', models.CharField(max_length=500)),
                ('user_wx_group_id', models.IntegerField(default=0)),
                ('user_last_wx_info_update_time', models.DateTimeField(null=True)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
    ]
