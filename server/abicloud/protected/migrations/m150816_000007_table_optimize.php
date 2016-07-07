<?php

class m150816_000007_table_optimize extends CDbMigration {

    public function safeUp() {
        try {
            $this->execute('ALTER TABLE ' . '{{order}}' . ' ADD INDEX  order_userid (`userid`)');
            $this->execute('ALTER TABLE ' . '{{order}}' . ' ADD INDEX  order_ktvid (`ktvid`)');
        } catch (Exception $ex) {
            
        }
        try {
            $this->execute('ALTER TABLE ' . '{{order}}' . ' MODIFY COLUMN `ktvid` VARCHAR(255)');
            $this->execute('ALTER TABLE ' . '{{order}}' . ' MODIFY COLUMN `time` int(11)');
        } catch (Exception $ex) {
            
        }
        try {
            $this->execute('ALTER TABLE ' . '{{order}}' . ' ADD INDEX  order_ktvid_status_time (`ktvid`,`status`,`time`)');
        } catch (Exception $ex) {
            
        }

        try {
            $this->execute('ALTER TABLE ' . '{{platform_user}}' . ' ADD INDEX user_name_password (`username`,`password`)');
        } catch (Exception $ex) {
            
        }
    }

    public function safedown() {
        try {
            $this->execute('ALTER TABLE ' . '{{order}}' . ' DROP INDEX  order_userid');
            $this->execute('ALTER TABLE ' . '{{order}}' . ' DROP INDEX  order_ktvid');
        } catch (Exception $ex) {
            
        }
        try {
            $this->execute('ALTER TABLE ' . '{{order}}' . ' DROP INDEX  order_ktvid_status_time');
        } catch (Exception $ex) {
            
        }
    }

}
