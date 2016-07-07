<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Customized DBHttpSession
 * Add uid and ip address to session records
 *
 * @author wingsun
 */
class DBHttpSession extends CDbHttpSession {

    const SESSION_TIMEOUT = 3600;

    /**
     * Initializes the application component.
     * This method is required by IApplicationComponent and is invoked by application.
     */
    public function init() {
        parent::init();
        $this->setTimeout(self::SESSION_TIMEOUT);
    }

    /**
     * Creates the session DB table.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    protected function createSessionTable($db, $tableName) {
        switch ($db->getDriverName()) {
            case 'mysql':
                $blob = 'LONGBLOB';
                break;
            case 'pgsql':
                $blob = 'BYTEA';
                break;
            case 'sqlsrv':
            case 'mssql':
            case 'dblib':
                $blob = 'VARBINARY(MAX)';
                break;
            default:
                $blob = 'BLOB';
                break;
        }
        $db->createCommand()->createTable($tableName, array(
            'id' => 'CHAR(32) PRIMARY KEY',
            'uid' => 'integer',
            'ip' => 'CHAR(64)',
            //'lasttime' => 'integer',
            'expire' => 'integer',
            'data' => $blob,
        ));

        //try {
        //    $db->createCommand()->createIndex('lasttime', $tableName, 'lasttime');
        //} catch (Exception $ex) {
        //}

        try {
            $db->createCommand()->createIndex('expire', $tableName, 'expire');
        } catch (Exception $ex) {
            
        }

        try {
            $db->createCommand()->createIndex('uid', $tableName, 'uid');
        } catch (Exception $ex) {
            
        }
    }

    /**
     * Session read handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @return string the session data
     */
    public function readSession($id) {
        $db = $this->getDbConnection();
        if ($db->getDriverName() == 'sqlsrv' || $db->getDriverName() == 'mssql' || $db->getDriverName() == 'dblib')
            $select = 'CONVERT(VARCHAR(MAX), data)';
        else
            $select = 'data';
        $data = $db->createCommand()
                ->select($select)
                ->from($this->sessionTableName)
                ->where('expire>=:expire AND id=:id', array(':expire' => time(), ':id' => $id))
                ->queryScalar();
        return $data === false ? '' : $data;
    }

    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id, $data) {
        // exception must be caught in session write handler
        // http://us.php.net/manual/en/function.session-set-save-handler.php
        try {
            $lasttime = time();
            $expire = $lasttime + $this->getTimeout();
            $ip = $this->getRealIpAddr();
            $uid = Yii::app()->user->getId();
            $uid = empty($uid) ? 0 : intval($uid);
            $db = $this->getDbConnection();
            if ($db->getDriverName() == 'sqlsrv' || $db->getDriverName() == 'mssql' || $db->getDriverName() == 'dblib')
                $data = new CDbExpression('CONVERT(VARBINARY(MAX), ' . $db->quoteValue($data) . ')');
            if ($db->createCommand()->select('id')->from($this->sessionTableName)->where('id=:id', array(':id' => $id))->queryScalar() === false)
                $db->createCommand()->insert($this->sessionTableName, array(
                    'id' => $id,
                    'uid' => $uid,
                    'ip' => $ip,
                    'data' => $data,
                    //'lasttime' => $lasttime,
                    'expire' => $expire,
                ));
            else
                $db->createCommand()->update($this->sessionTableName, array(
                    'data' => $data,
                    'uid' => $uid,
                    'ip' => $ip,
                    //'lasttime' => $lasttime,
                    'expire' => $expire
                        ), 'id=:id', array(':id' => $id));
        } catch (Exception $e) {
            if (YII_DEBUG)
                echo $e->getMessage();
            // it is too late to log an error message here
            return false;
        }
        return true;
    }

    /**
     * Get client ip address
     * @return string Client ip address
     */
    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '0.0.0.0';
        }
        return $ip;
    }

}
