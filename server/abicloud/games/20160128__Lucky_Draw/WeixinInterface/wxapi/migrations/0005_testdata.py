# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('wxapi', '0004_auto_20141121_0508'),
    ]

    operations = [
        migrations.CreateModel(
            name='testData',
            fields=[
                ('weixinuser_ptr', models.OneToOneField(parent_link=True, auto_created=True, primary_key=True, serialize=False, to='wxapi.WeixinUser')),
                ('testarea', models.CharField(max_length=10)),
            ],
            options={
            },
            bases=('wxapi.weixinuser',),
        ),
    ]
