drop table if exists Rights;
drop table if exists Auth_Item_Child;
drop table if exists Auth_Item;
drop table if exists Auth_Assignment;

create table Auth_Item
(
   name varchar(64) not null,
   type integer not null,
   description text,
   bizrule text,
   data text,
   primary key (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table Auth_Item_Child
(
   parent varchar(64) not null,
   child varchar(64) not null,
   primary key (parent,child),
   foreign key (parent) references Auth_Item (name) on delete cascade on update cascade,
   foreign key (child) references Auth_Item (name) on delete cascade on update cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table Auth_Assignment
(
   itemname varchar(64) not null,
   userid varchar(64) not null,
   bizrule text,
   data text,
   primary key (itemname,userid),
   foreign key (itemname) references Auth_Item (name) on delete cascade on update cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table Rights
(
	itemname varchar(64) not null,
	type integer not null,
	weight integer not null,
	primary key (itemname),
	foreign key (itemname) references Auth_Item (name) on delete cascade on update cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;