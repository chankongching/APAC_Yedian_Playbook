# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('wxapi', '0002_auto_20141121_0505'),
    ]

    operations = [
        migrations.AlterField(
            model_name='jobseqs',
            name='job_description',
            field=models.CharField(max_length=200),
            preserve_default=True,
        ),
    ]
