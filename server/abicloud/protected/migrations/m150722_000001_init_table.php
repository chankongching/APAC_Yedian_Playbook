<?php

class m150722_000001_init_table extends CDbMigration {

    public function safeUp() {
        try {
            $this->createTable('{{YiiSession}}', array(
                'id' => 'char(32) NOT NULL',
                'uid' => 'integer',
                'ip' => 'char(64)',
                //'lasttime' => 'integer',
                'expire' => 'integer',
                'data' => 'text',
                    ), 'ENGINE=MyISAM');
        } catch (Exception $ex) {
            
        }

        try {
            $this->execute('ALTER TABLE ' . '{{YiiSession}}' . ' ADD PRIMARY KEY (`id`)');
            //$this->execute('ALTER TABLE ' . '{{YiiSession}}' . ' ADD INDEX (`lasttime`)');
            $this->execute('ALTER TABLE ' . '{{YiiSession}}' . ' ADD INDEX (`expire`)');
            $this->execute('ALTER TABLE ' . '{{YiiSession}}' . ' ADD INDEX (`uid`)');
        } catch (Exception $ex) {
            
        }

        $this->createTable('{{vendor}}', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'content' => 'string NOT NULL',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createTable('{{application}}', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'content' => 'string NOT NULL',
            'vendor_id' => 'int(11) DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');


        $this->createTable('{{platform_user}}', array(
            'id' => 'pk',
            'username' => 'string NOT NULL',
            'email' => 'string DEFAULT NULL',
            'mobile' => 'string DEFAULT NULL',
            'password' => 'string NOT NULL',
            'role' => 'varchar(64) DEFAULT NULL',
            'openid' => 'string DEFAULT NULL',
            'token' => 'string DEFAULT NULL',
            'type' => 'int(11) NOT NULL DEFAULT 0',
            'auth_type' => 'varchar(32) DEFAULT NULL',
            'profile_data' => 'TEXT DEFAULT NULL',
            'display_name' => 'string DEFAULT NULL',
            'avatar_url' => 'string DEFAULT NULL',
            'last_login_time' => 'datetime DEFAULT NULL',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');


        $this->createTable('{{version}}', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'content' => 'string DEFAULT NULL',
            'os' => 'string DEFAULT NULL',
            'file_name' => 'string DEFAULT NULL',
            'version' => 'string NOT NULL',
            'version_code' => 'string DEFAULT NULL',
            'app_type' => 'tinyint(1) DEFAULT 0',
            'force_update' => 'int(11) DEFAULT 0',
            'download_url' => 'string DEFAULT NULL',
            'application_id' => 'int(11) DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createTable('{{user}}', array(
            'id' => 'pk',
            'username' => 'string NOT NULL',
            'email' => 'string NOT NULL',
            'mobile' => 'string DEFAULT NULL',
            'password' => 'string NOT NULL',
            'role' => 'varchar(64) DEFAULT NULL',
            'last_login_time' => 'datetime DEFAULT NULL',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->execute('ALTER TABLE ' . '{{vendor}}' . ' ADD UNIQUE (`name`)');
        $this->execute('ALTER TABLE ' . '{{application}}' . ' ADD UNIQUE (`name`)');
        $this->execute('ALTER TABLE ' . '{{user}}' . ' ADD UNIQUE (`username`)');
        $this->execute('ALTER TABLE ' . '{{platform_user}}' . ' ADD UNIQUE (`username`,`auth_type`)');
        //$this->execute('ALTER TABLE ' . '{{version}}' . ' ADD UNIQUE (`application_id`,`name`)');
        $this->execute('ALTER TABLE ' . '{{version}}' . ' ADD UNIQUE (`application_id`,`name`,`version`,`app_type`)');

        // Vendor content UUID
        $this->execute('INSERT INTO ' . '{{vendor}}' . "(`id`,`name`,`content`) VALUES (1, 'Accenture', '" . md5('Accenture') . "') ");
        // Application content UUID
        $this->execute('INSERT INTO ' . '{{application}}' . "(`id`,`name`,`content`,`vendor_id`) VALUES (1, 'AbiKtv', '" . md5('Capelabs AbiKtv') . "' , 1) ");
        // password: 12345678
        $this->execute('INSERT INTO ' . '{{user}}' . "(`id`,`username`,`email`,`password`) VALUES (1, 'admin', 'admin@abiktv.com' , '" . PasswordHash::create_hash('12345678') . "') ");

        $this->addForeignKey("fk_application_vendor", '{{application}}', "vendor_id", '{{vendor}}', "id", "SET NULL", "CASCADE");
        //$this->addForeignKey("fk_version_application", '{{version}}', "application_id", '{{application}}', "id", "SET NULL", "CASCADE");
        $this->addForeignKey("fk_version_application", '{{version}}', "application_id", '{{application}}', "id", "NO ACTION", "CASCADE");
    }

    public function safedown() {
        //$this->truncateTable('{{user}}');
        //$this->truncateTable('{{version}}');
        //$this->truncateTable('{{platform_user}}');

        $this->dropTable('{{version}}');
        $this->dropTable('{{platform_user}}');
        $this->dropTable('{{user}}');
        $this->dropTable('{{application}}');
        $this->dropTable('{{vendor}}');
        $this->dropTable('{{YiiSession}}');
    }

}
