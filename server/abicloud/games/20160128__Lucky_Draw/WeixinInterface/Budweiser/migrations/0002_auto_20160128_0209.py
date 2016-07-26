# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('Budweiser', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='pz_items',
            name='creation_time',
            field=models.DateTimeField(default=None, auto_now_add=True, null=True),
            preserve_default=True,
        ),
        migrations.AlterField(
            model_name='pz_items',
            name='pz_s_time',
            field=models.DateTimeField(default=None, null=True),
            preserve_default=True,
        ),
        migrations.AlterField(
            model_name='pz_items',
            name='weixinuser_id',
            field=models.CharField(default=None, max_length=50, unique=True, null=True),
            preserve_default=True,
        ),
    ]
