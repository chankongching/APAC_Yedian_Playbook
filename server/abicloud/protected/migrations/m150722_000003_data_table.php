<?php

class m150722_000003_data_table extends CDbMigration {

    public function safeUp() {
        //create the auth item table
        $this->createTable('{{sms_verify}}', array(
            'mobile' => 'varchar(64) NOT NULL',
            'code' => 'string NOT NULL',
            'userid' => 'int(11) NOT NULL',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
            'PRIMARY KEY (`mobile`)',
                ), 'ENGINE=MyISAM DEFAULT CHARSET=utf8');

        $this->createTable('{{area_code}}', array(
            'id' => 'pk',
            'pid' => 'int(11) NOT NULL DEFAULT 0',
            'code' => 'varchar(20) NOT NULL',
            'name' => 'string NOT NULL',
            'sort' => 'int(11) NOT NULL DEFAULT 0',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createTable('{{xktv}}', array(
            'id' => 'pk',
            'area_id' => 'int(11) NOT NULL DEFAULT 0',
            'code' => 'varchar(20) NOT NULL',
            'xktvid' => 'string NOT NULL',
            'name' => 'string NOT NULL',
            'description' => 'string NOT NULL',
            'content' => 'TEXT DEFAULT NULL',
            'smallpicurl' => 'string NOT NULL',
            'bigpicurl' => 'string NOT NULL',
            'address' => 'string NOT NULL',
            'telephone' => 'string NOT NULL',
            'openhours' => 'string NOT NULL',
            'lat' => 'float NOT NULL',
            'lng' => 'float NOT NULL',
            'price' => 'float NOT NULL',
            'rate' => 'int(11) NOT NULL DEFAULT 4',
            'roomtotal' => 'int(11) NOT NULL DEFAULT 0',
            'roombig' => 'int(11) NOT NULL DEFAULT 0',
            'roommedium' => 'int(11) NOT NULL DEFAULT 0',
            'roomsmall' => 'int(11) NOT NULL DEFAULT 0',
            'responsetime' => 'int(11) NOT NULL DEFAULT 0',
            'distance' => 'float NOT NULL',
            'roombigprice' => 'int(11) NOT NULL DEFAULT 0',
            'roommediumprice' => 'int(11) NOT NULL DEFAULT 0',
            'roomsmallprice' => 'int(11) NOT NULL DEFAULT 0',
            'create_time' => 'datetime DEFAULT NULL',
            'create_user_id' => 'int(11) DEFAULT NULL',
            'update_time' => 'datetime DEFAULT NULL',
            'update_user_id' => 'int(11) DEFAULT NULL',
            'int01' => 'int(11) NOT NULL DEFAULT 0',
            'int02' => 'int(11) NOT NULL DEFAULT 0',
            'str01' => 'string NOT NULL',
            'str02' => 'string NOT NULL',
            'str03' => 'string NOT NULL',
            'str04' => 'string NOT NULL',
            'str05' => 'string NOT NULL',
            'num01' => 'float NOT NULL',
            'num02' => 'float NOT NULL',
            'num03' => 'float NOT NULL',
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        try {
            $this->createTable('{{statistics}}', array(
                'id' => 'pk',
                'calltime' => 'integer',
                'call_api' => 'varchar(64) DEFAULT NULL',
                'call_id' => 'integer DEFAULT 0',
                'call_ip' => 'varchar(64) DEFAULT NULL',
                'req_param' => 'text',
                'head_param' => 'text',
                    ), 'ENGINE=MyISAM DEFAULT CHARSET=utf8');

            $this->createTable('{{statistics_day}}', array(
                'id' => 'pk',
                'calltime' => 'integer',
                'call_api' => 'varchar(64) DEFAULT NULL',
                'call_type' => 'varchar(64) DEFAULT NULL',
                'call_count' => 'integer',
                'comment' => 'text',
                    ), 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
        } catch (Exception $ex) {
            echo $ex->getMessage() . "\n";
        }

        try {
            $this->execute('ALTER TABLE ' . '{{statistics_day}}' . ' ADD INDEX (`calltime`)');
            $this->execute('ALTER TABLE ' . '{{statistics_day}}' . ' ADD INDEX (`call_api`, `call_type`)');
        } catch (Exception $ex) {
            
        }
    }

    public function safedown() {
        $this->dropTable('{{sms_verify}}');
        $this->dropTable('{{area_code}}');
        $this->dropTable('{{xktv}}');
        $this->dropTable('{{statistics}}');
        $this->dropTable('{{statistics_day}}');
    }

}
