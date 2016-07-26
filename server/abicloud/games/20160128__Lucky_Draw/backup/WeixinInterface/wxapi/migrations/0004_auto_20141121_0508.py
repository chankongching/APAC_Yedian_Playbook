# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('wxapi', '0003_auto_20141121_0507'),
    ]

    operations = [
        migrations.AlterField(
            model_name='device',
            name='device_description',
            field=models.CharField(max_length=100),
            preserve_default=True,
        ),
    ]
