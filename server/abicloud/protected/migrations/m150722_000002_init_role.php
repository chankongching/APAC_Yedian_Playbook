<?php

class m150722_000002_init_role extends CDbMigration {

    public function safeUp() {
        //create the auth item table
        $this->createTable('{{auth_item}}', array(
            'name' => 'varchar(64) NOT NULL',
            'type' => 'integer NOT NULL',
            'description' => 'text',
            'bizrule' => 'text',
            'data' => 'text',
            'PRIMARY KEY (`name`)',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        //create the auth item child table
        $this->createTable('{{auth_item_child}}', array(
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (`parent`,`child`)',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        //the tbl_auth_item_child.parent is a reference to tbl_auth_item.name
        $this->addForeignKey("fk_auth_item_child_parent", '{{auth_item_child}}', "parent", '{{auth_item}}', "name", "CASCADE", "CASCADE");

        //the tbl_auth_item_child.child is a reference to tbl_auth_item.name            
        $this->addForeignKey("fk_auth_item_child_child", '{{auth_item_child}}', "child", '{{auth_item}}', "name", "CASCADE", "CASCADE");

        //create the auth assignment table
        $this->createTable('{{auth_assignment}}', array(
            'itemname' => 'varchar(64) NOT NULL',
            'userid' => 'int(11) NOT NULL',
            'bizrule' => 'text',
            'data' => 'text',
            'PRIMARY KEY (`itemname`,`userid`)',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        //the tbl_auth_assignment.itemname is a reference
        //to tbl_auth_item.name
        $this->addForeignKey("fk_auth_assignment_itemname", '{{auth_assignment}}', "itemname", '{{auth_item}}', "name", "CASCADE", "CASCADE");

        //the tbl_auth_assignment.userid is a reference
        //to tbl_user.id
        $this->addForeignKey("fk_auth_assignment_userid", '{{auth_assignment}}', "userid", '{{user}}', "id", "CASCADE", "CASCADE");
        //to tbl_platform_user.id
        //$this->addForeignKey("fk_auth_assignment_platformuserid", '{{auth_assignment}}', "userid", '{{platform_user}}', "id", "CASCADE", "CASCADE");

        $this->createTable('{{rights}}', array(
            'itemname' => 'varchar(64) NOT NULL',
            'type' => 'integer NOT NULL',
            'weight' => 'integer NOT NULL',
            'PRIMARY KEY (`itemname`)',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey("fk_auth_rights_itemname", '{{rights}}', "itemname", '{{auth_item}}', "name", "CASCADE", "CASCADE");

        // default roles
        $this->execute('INSERT INTO ' . '{{auth_item}}' . "(`name`,`type`,`description`) VALUES ('Super', 2, '超级用户') ");
        $this->execute('INSERT INTO ' . '{{auth_item}}' . "(`name`,`type`,`description`, `bizrule`) VALUES ('Member', 2, '普通用户', 'return !Yii::app()->user->isGuest;') ");
        $this->execute('INSERT INTO ' . '{{auth_item}}' . "(`name`,`type`,`description`, `bizrule`) VALUES ('Guest', 2, '访客', 'return Yii::app()->user->isGuest;') ");

        $this->execute("UPDATE " . '{{user}}' . " SET `role` = 'Super' where `id` = 1");

        //the user.role is a reference
        //to tbl_auth_item.name
        // default Super role to user id 1
        $this->execute('INSERT INTO ' . '{{auth_assignment}}' . "(`itemname`,`userid`) VALUES ('Super', 1) ");
        //$this->addForeignKey('fk_user_role', '{{user}}', 'role', '{{auth_item}}', 'name', 'SET NULL', 'CASCADE');
    }

    public function safedown() {
        //$this->truncateTable('{{rights}}');
        //$this->truncateTable('{{auth_assignment}}');
        //$this->truncateTable('{{auth_item_child}}');
        //$this->truncateTable('{{auth_item}}');

        $this->dropTable('{{rights}}');
        $this->dropTable('{{auth_assignment}}');
        $this->dropTable('{{auth_item_child}}');
        $this->dropTable('{{auth_item}}');
    }

}
