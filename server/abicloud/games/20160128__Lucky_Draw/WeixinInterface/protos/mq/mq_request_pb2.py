# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protos/mq/mq_request.proto

import sys
_b=sys.version_info[0]<3 and (lambda x:x) or (lambda x:x.encode('latin1'))
from google.protobuf import descriptor as _descriptor
from google.protobuf import message as _message
from google.protobuf import reflection as _reflection
from google.protobuf import symbol_database as _symbol_database
from google.protobuf import descriptor_pb2
# @@protoc_insertion_point(imports)

_sym_db = _symbol_database.Default()




DESCRIPTOR = _descriptor.FileDescriptor(
  name='protos/mq/mq_request.proto',
  package='',
  serialized_pb=_b('\n\x1aprotos/mq/mq_request.proto\"\xc9\x01\n\x13MessageQueueRequest\x12\x32\n\x08msg_type\x18\x01 \x02(\x0e\x32 .MessageQueueRequest.MessageType\x12\x14\n\x0cmsg_datetime\x18\x02 \x02(\t\x12$\n\x08ord_reqs\x18\x03 \x03(\x0b\x32\x12.PrintOrderRequest\x12\x1e\n\x08user_req\x18\x04 \x01(\x0b\x32\x0c.UserRequest\"\"\n\x0bMessageType\x12\t\n\x05PRINT\x10\x00\x12\x08\n\x04\x41UTH\x10\x01\"\x9e\x01\n\x11PrintOrderRequest\x12\x0f\n\x07orderid\x18\x01 \x02(\t\x12\x11\n\titem_name\x18\x02 \x01(\t\x12\x18\n\x10item_detail_name\x18\x03 \x01(\t\x12\x1f\n\x17item_buy_count_and_unit\x18\x04 \x01(\t\x12\x14\n\x0c\x64\x65liver_date\x18\x05 \x01(\t\x12\x14\n\x0c\x64\x65liver_time\x18\x06 \x01(\t\"s\n\x0bUserRequest\x12\x11\n\tuser_name\x18\x01 \x02(\t\x12\x13\n\x0buser_mobile\x18\x02 \x01(\t\x12\x14\n\x0cuser_address\x18\x03 \x01(\t\x12\x14\n\x0cuser_comment\x18\x04 \x01(\t\x12\x10\n\x08location\x18\x05 \x01(\t')
)
_sym_db.RegisterFileDescriptor(DESCRIPTOR)



_MESSAGEQUEUEREQUEST_MESSAGETYPE = _descriptor.EnumDescriptor(
  name='MessageType',
  full_name='MessageQueueRequest.MessageType',
  filename=None,
  file=DESCRIPTOR,
  values=[
    _descriptor.EnumValueDescriptor(
      name='PRINT', index=0, number=0,
      options=None,
      type=None),
    _descriptor.EnumValueDescriptor(
      name='AUTH', index=1, number=1,
      options=None,
      type=None),
  ],
  containing_type=None,
  options=None,
  serialized_start=198,
  serialized_end=232,
)
_sym_db.RegisterEnumDescriptor(_MESSAGEQUEUEREQUEST_MESSAGETYPE)


_MESSAGEQUEUEREQUEST = _descriptor.Descriptor(
  name='MessageQueueRequest',
  full_name='MessageQueueRequest',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='msg_type', full_name='MessageQueueRequest.msg_type', index=0,
      number=1, type=14, cpp_type=8, label=2,
      has_default_value=False, default_value=0,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='msg_datetime', full_name='MessageQueueRequest.msg_datetime', index=1,
      number=2, type=9, cpp_type=9, label=2,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='ord_reqs', full_name='MessageQueueRequest.ord_reqs', index=2,
      number=3, type=11, cpp_type=10, label=3,
      has_default_value=False, default_value=[],
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='user_req', full_name='MessageQueueRequest.user_req', index=3,
      number=4, type=11, cpp_type=10, label=1,
      has_default_value=False, default_value=None,
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
    _MESSAGEQUEUEREQUEST_MESSAGETYPE,
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=31,
  serialized_end=232,
)


_PRINTORDERREQUEST = _descriptor.Descriptor(
  name='PrintOrderRequest',
  full_name='PrintOrderRequest',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='orderid', full_name='PrintOrderRequest.orderid', index=0,
      number=1, type=9, cpp_type=9, label=2,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='item_name', full_name='PrintOrderRequest.item_name', index=1,
      number=2, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='item_detail_name', full_name='PrintOrderRequest.item_detail_name', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='item_buy_count_and_unit', full_name='PrintOrderRequest.item_buy_count_and_unit', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='deliver_date', full_name='PrintOrderRequest.deliver_date', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='deliver_time', full_name='PrintOrderRequest.deliver_time', index=5,
      number=6, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=235,
  serialized_end=393,
)


_USERREQUEST = _descriptor.Descriptor(
  name='UserRequest',
  full_name='UserRequest',
  filename=None,
  file=DESCRIPTOR,
  containing_type=None,
  fields=[
    _descriptor.FieldDescriptor(
      name='user_name', full_name='UserRequest.user_name', index=0,
      number=1, type=9, cpp_type=9, label=2,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='user_mobile', full_name='UserRequest.user_mobile', index=1,
      number=2, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='user_address', full_name='UserRequest.user_address', index=2,
      number=3, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='user_comment', full_name='UserRequest.user_comment', index=3,
      number=4, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
    _descriptor.FieldDescriptor(
      name='location', full_name='UserRequest.location', index=4,
      number=5, type=9, cpp_type=9, label=1,
      has_default_value=False, default_value=_b("").decode('utf-8'),
      message_type=None, enum_type=None, containing_type=None,
      is_extension=False, extension_scope=None,
      options=None),
  ],
  extensions=[
  ],
  nested_types=[],
  enum_types=[
  ],
  options=None,
  is_extendable=False,
  extension_ranges=[],
  oneofs=[
  ],
  serialized_start=395,
  serialized_end=510,
)

_MESSAGEQUEUEREQUEST.fields_by_name['msg_type'].enum_type = _MESSAGEQUEUEREQUEST_MESSAGETYPE
_MESSAGEQUEUEREQUEST.fields_by_name['ord_reqs'].message_type = _PRINTORDERREQUEST
_MESSAGEQUEUEREQUEST.fields_by_name['user_req'].message_type = _USERREQUEST
_MESSAGEQUEUEREQUEST_MESSAGETYPE.containing_type = _MESSAGEQUEUEREQUEST
DESCRIPTOR.message_types_by_name['MessageQueueRequest'] = _MESSAGEQUEUEREQUEST
DESCRIPTOR.message_types_by_name['PrintOrderRequest'] = _PRINTORDERREQUEST
DESCRIPTOR.message_types_by_name['UserRequest'] = _USERREQUEST

MessageQueueRequest = _reflection.GeneratedProtocolMessageType('MessageQueueRequest', (_message.Message,), dict(
  DESCRIPTOR = _MESSAGEQUEUEREQUEST,
  __module__ = 'protos.mq.mq_request_pb2'
  # @@protoc_insertion_point(class_scope:MessageQueueRequest)
  ))
_sym_db.RegisterMessage(MessageQueueRequest)

PrintOrderRequest = _reflection.GeneratedProtocolMessageType('PrintOrderRequest', (_message.Message,), dict(
  DESCRIPTOR = _PRINTORDERREQUEST,
  __module__ = 'protos.mq.mq_request_pb2'
  # @@protoc_insertion_point(class_scope:PrintOrderRequest)
  ))
_sym_db.RegisterMessage(PrintOrderRequest)

UserRequest = _reflection.GeneratedProtocolMessageType('UserRequest', (_message.Message,), dict(
  DESCRIPTOR = _USERREQUEST,
  __module__ = 'protos.mq.mq_request_pb2'
  # @@protoc_insertion_point(class_scope:UserRequest)
  ))
_sym_db.RegisterMessage(UserRequest)


# @@protoc_insertion_point(module_scope)
