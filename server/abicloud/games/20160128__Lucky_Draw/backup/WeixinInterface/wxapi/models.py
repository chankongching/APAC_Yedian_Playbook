from django.db import models
#uuuid field
import uuid
from uuidfield import UUIDField

# Create your models here.
class Group(models.Model):
    group_name = models.CharField(max_length=20)
    group_description = models.CharField(max_length=150)

    def __unicode__(self):
        return "Group id is %s , group description is %s" % (self.group_name, self.group_description)

class Device(models.Model):
    device_name = models.CharField(max_length=100)
    device_description = models.CharField(max_length=100)
    register_time = models.DateTimeField(auto_now_add=True,null=True)
    belong_group = models.ForeignKey(Group,null=True)

class JobSeqs(models.Model):
    jobID = UUIDField(auto=True,primary_key=True)
    job_creationTime = models.DateTimeField(auto_now_add=True)
    job_content = models.CharField(max_length=200)#to-do:design a protocol #maybe use protocolbuf by google #for test
    job_status = models.SmallIntegerField()
    job_description = models.CharField(max_length=200)
    target_device = models.OneToOneField(Device,null=True)
    operator_user = models.ForeignKey('WeixinUser', null=True)

    def __unicode__(self):
        return "Job id is %s , job description is %s" % (self.jobID, self.job_description)

class WeixinUser(models.Model):
    weixinUserID = models.CharField(max_length=30)
    creation_time = models.DateTimeField(auto_now_add=True,null=True)
    lastmessagetime = models.DateTimeField(auto_now_add=True,null=True)
    lastconnectiontime = models.DateTimeField(auto_now=True, null=True)
    menu_flag = models.SmallIntegerField()
    belong_group = models.ForeignKey(Group,null=True)


class testData(WeixinUser):
    testarea = models.CharField(max_length=10)


#this area is for third-part uuid field
#class AutoUUIDField(models.Model):
#    uuid = UUIDField(auto=True,primary_key=True)
#
#
#class HyphenatedUUIDField(models.Model):
#    uuid = UUIDField(auto=True, hyphenate=True)
#    name = models.CharField(max_length=16)
#
#
#class ManualUUIDField(models.Model):
#    uuid = UUIDField(auto=False)
#
#
#class NamespaceUUIDField(models.Model):
#    uuid = UUIDField(auto=True, namespace=uuid.NAMESPACE_URL, version=5)
#
#
#class BrokenNamespaceUUIDField(models.Model):
#    uuid = UUIDField(auto=True, namespace='lala', version=5)
#
#
