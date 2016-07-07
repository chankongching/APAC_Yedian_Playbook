# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations
import uuidfield


class Migration(migrations.Migration):

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Device',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('device_name', models.CharField(max_length=100)),
                ('device_description', models.CharField(default=None, max_length=100)),
                ('register_time', models.DateTimeField(auto_now_add=True, null=True)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.CreateModel(
            name='Group',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('group_name', models.CharField(max_length=20)),
                ('group_description', models.CharField(max_length=150)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.CreateModel(
            name='JobSeqs',
            fields=[
                ('jobID', uuidfield.UUIDField(primary_key=True, serialize=False, editable=False, max_length=32, blank=True, unique=True)),
                ('job_creationTime', models.DateTimeField(auto_now_add=True)),
                ('job_content', models.CharField(max_length=200)),
                ('job_status', models.SmallIntegerField()),
                ('job_description', models.CharField(default=None, max_length=200)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.CreateModel(
            name='WeixinUser',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('weixinUserID', models.CharField(max_length=30)),
                ('creation_time', models.DateTimeField(auto_now_add=True, null=True)),
                ('lastmessagetime', models.DateTimeField(auto_now_add=True, null=True)),
                ('lastconnectiontime', models.DateTimeField(auto_now=True, null=True)),
                ('menu_flag', models.SmallIntegerField(default=0)),
                ('belong_group', models.ForeignKey(to='wxapi.Group', null=True)),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.AddField(
            model_name='jobseqs',
            name='operator_user',
            field=models.ForeignKey(to='wxapi.WeixinUser', null=True),
            preserve_default=True,
        ),
        migrations.AddField(
            model_name='jobseqs',
            name='target_device',
            field=models.OneToOneField(null=True, to='wxapi.Device'),
            preserve_default=True,
        ),
        migrations.AddField(
            model_name='device',
            name='belong_group',
            field=models.ForeignKey(to='wxapi.Group', null=True),
            preserve_default=True,
        ),
    ]
