<?php

class m150812_000006_points_table extends CDbMigration {

    public function safeUp() {
        $this->createTable('{{user_points}}', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT 0',
            'status' => 'tinyint(1) DEFAULT 1',
            'points' => 'integer DEFAULT 0',
            'rewards' => 'integer DEFAULT 0',
            'redeems' => 'integer DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=MyISAM DEFAULT CHARSET=utf8');

        $this->execute('ALTER TABLE ' . '{{user_points}}' . ' ADD UNIQUE (`user_id`)');

        $this->createTable('{{points_history}}', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT 0',
            'activity_id' => 'int(11) DEFAULT 0',
            'code' => 'string NOT NULL',
            'points_before' => 'integer DEFAULT 0',
            'points' => 'integer DEFAULT 0',
            'points_after' => 'integer DEFAULT 0',
            'duetime' => 'integer DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=MyISAM DEFAULT CHARSET=utf8');

        try {
            $this->addColumn('{{user}}', 'status', "tinyint(1) DEFAULT 1");
        } catch (Exception $ex) {
            
        }
    }

    public function safedown() {
        $this->dropTable('{{user_points}}');
        $this->dropTable('{{points_history}}');
        $this->dropColumn('{{user}}', 'status');
    }

}
