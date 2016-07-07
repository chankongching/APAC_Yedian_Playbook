<?php

class m150810_000005_qrcode_table extends CDbMigration {

    public function safeUp() {
        $this->createTable('{{qrcode}}', array(
            'id' => 'pk',
            'activity_id' => 'int(11) DEFAULT 0',
            'code' => 'string NOT NULL',
            'name' => 'string NOT NULL',
            'description' => 'string NOT NULL',
            'status' => 'tinyint(1) DEFAULT 1',
            'points' => 'integer DEFAULT 0',
            'usages' => 'integer DEFAULT 0',
            'userlimit' => 'integer DEFAULT 0',
            'userdaylimit' => 'integer DEFAULT 0',
            'usagelimit' => 'integer DEFAULT 0',
            'daylimit' => 'integer DEFAULT 0',
            'usedtotal' => 'integer DEFAULT 0',
            'usagetotal' => 'integer DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');


        $this->execute('ALTER TABLE ' . '{{qrcode}}' . ' ADD UNIQUE (`activity_id`,`name`)');
        $this->execute('ALTER TABLE ' . '{{qrcode}}' . ' ADD UNIQUE (`code`)');
    }

    public function safedown() {
        $this->dropTable('{{qrcode}}');
    }

}
