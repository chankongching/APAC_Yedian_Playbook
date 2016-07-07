# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='pz_items',
            fields=[
                ('pz_code', models.CharField(max_length=100, unique=True, serialize=False, primary_key=True)),
                ('weixinuser_id', models.CharField(max_length=50, unique=True, null=True)),
                ('creation_time', models.DateTimeField(auto_now_add=True, null=True)),
                ('pz_s_time', models.DateTimeField(null=True)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
    ]
