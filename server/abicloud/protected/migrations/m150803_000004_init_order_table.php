<?php
class m150803_000004_init_order_table extends CDbMigration {

    public function up() {
        //create the xktv list table
        $this->createTable('{{order}}', array(
            'id' => 'pk',
            'status' => 'int(11) NOT NULL DEFAULT 0',
            'invoice' => 'text',
            'code' => 'text',
            'amount' => 'float NOT NULL DEFAULT 0',
            'time' => 'long NOT NULL',
            'confirm_time' => 'int(11) NOT NULL DEFAULT 0',
            'roomtype' => 'int(11) NOT NULL DEFAULT 0',
            'roomid' => 'int(11) NOT NULL DEFAULT 0',
            'ktvid' => 'text',
            'userid' => 'int(11) NOT NULL DEFAULT 0',
            'name' => 'text',
            'description' => 'text',
            'smallpicurl' => 'text',
            'bigpicurl' => 'text',
            'starttime' => 'long NOT NULL',
            'endtime' => 'long NOT NULL',
            'members' => 'int(11) NOT NULL DEFAULT 1',
            'reason' => 'text',
            'content' => 'text',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addColumn('{{xktv}}', 'spic1', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'bpic1', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'spic2', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'bpic2', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'spic3', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'bpic3', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'spic4', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'bpic4', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'spic5', 'string NOT NULL');
        $this->addColumn('{{xktv}}', 'bpic5', 'string NOT NULL');

    }

    public function down() {
        $this->dropTable('{{order}}');
        $this->dropColumn('{{xktv}}', 'spic1');
        $this->dropColumn('{{xktv}}', 'bpic1');
        $this->dropColumn('{{xktv}}', 'spic2');
        $this->dropColumn('{{xktv}}', 'bpic2');
        $this->dropColumn('{{xktv}}', 'spic3');
        $this->dropColumn('{{xktv}}', 'bpic3');
        $this->dropColumn('{{xktv}}', 'spic4');
        $this->dropColumn('{{xktv}}', 'bpic4');
        $this->dropColumn('{{xktv}}', 'spic5');
        $this->dropColumn('{{xktv}}', 'bpic5');
    }

}

