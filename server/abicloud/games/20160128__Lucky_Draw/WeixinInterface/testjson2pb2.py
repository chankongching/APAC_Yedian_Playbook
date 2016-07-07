#!/usr/bin/env python
# -*- coding: utf-8 -*-
json_raw = r'{"event":"create_comment","ticket":{"id":171687,"status":"open","title":"主题","description":null,"tags":null,"user_id":45909,"via":{"channel":"web"},"comments":[{"id":171689,"body":"/..??","user_id":7094,"public":true,"created_at":"2015-05-16T15:00:21Z"},{"id":171700,"body":null,"user_id":45909,"public":true,"created_at":"2015-05-16T23:22:02Z"},{"id":171701,"body":null,"user_id":45909,"public":true,"created_at":"2015-05-16T23:22:26Z"},{"id":171704,"body":null,"user_id":45909,"public":true,"created_at":"2015-05-17T00:14:35Z"},{"id":171705,"body":"yes love you\r\n","user_id":7094,"public":true,"created_at":"2015-05-17T00:15:42Z"},{"id":171706,"body":"test2","user_id":7094,"public":true,"created_at":"2015-05-17T00:16:41Z"},{"id":171707,"body":"test3","user_id":7094,"public":true,"created_at":"2015-05-17T00:17:21Z"},{"id":171708,"body":"123","user_id":7094,"public":true,"created_at":"2015-05-17T00:18:15Z"},{"id":171709,"body":"hi","user_id":7094,"public":true,"created_at":"2015-05-17T00:18:39Z"},{"id":171710,"body":"love\r\n","user_id":7094,"public":true,"created_at":"2015-05-17T00:21:03Z"},{"id":171711,"body":"www\r\n","user_id":7094,"public":true,"created_at":"2015-05-17T00:22:58Z"},{"id":171712,"body":"123","user_id":7094,"public":true,"created_at":"2015-05-17T00:26:31Z"},{"id":171713,"body":"23333","user_id":7094,"public":true,"created_at":"2015-05-17T00:28:00Z"}],"created_at":"2015-05-16T14:58:24Z","updated_at":"2015-05-17T00:28:00Z"},"comment_id":171713}'
import json
json_obj = json.loads(json_raw)
import ipdb;ipdb.set_trace()
from protos.workorder import daike_struct_pb2
from protobuf2json import protobuf_json
pb = protobuf_json.json2pb(daike_struct_pb2.DaikeApp(), json_obj)
