<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PSActiveRecord
 *
 * @author SUNJOY
 */
abstract class PSActiveRecord extends CActiveRecord {

    /**
     * Prepares create_user_id and update_user_id attributes before saving.
     */
    protected function beforeSave() {
        if (isset(Yii::app()->user) && null !== Yii::app()->user)
            $id = Yii::app()->user->id;
        else
            $id = 0;
        if ($this->isNewRecord)
            $this->create_user_id = $id;
        $this->update_user_id = $id;
        return parent::beforeSave();
    }

    /**
     * Attaches the timestamp behavior to update our create and update times
     */
    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /**
     * array(
     *      array('id' => 1, 'name' => 'John'),
     *      array('id' => 2, 'name' => 'Mark')
     * );
     *  
     * @param type $array_columns
     * @return integer number of rows affected by the execution.
     * @throws CDbException execution failed
     */
    public function insertSeveral($array_columns) {
        $sql = '';
        $params = array();
        $i = 0;
        foreach ($array_columns as $columns) {
            $names = array();
            $placeholders = array();
            foreach ($columns as $name => $value) {
                if (!$i) {
                    $names[] = $this->getDbConnection()->quoteColumnName($name);
                }
                if ($value instanceof CDbExpression) {
                    $placeholders[] = $value->expression;
                    foreach ($value->params as $n => $v)
                        $params[$n] = $v;
                } else {
                    $placeholders[] = ':' . $name . $i;
                    $params[':' . $name . $i] = $value;
                }
            }
            if (!$i) {
                $sql = 'INSERT INTO ' . $this->tableName()
                        . ' (' . implode(', ', $names) . ') VALUES ('
                        . implode(', ', $placeholders) . ')';
            } else {
                $sql .= ',(' . implode(', ', $placeholders) . ')';
            }
            $i++;
        }
        Yii::trace($sql);
        Yii::trace(print_r($params, true));
        $command = $this->getDbConnection()->createCommand();
        return $command->setText($sql)->execute($params);
    }

    /**
     * Logs a message.
     *
     * @param string $message Message to be logged
     * @param string $level Level of the message (e.g. 'error', 'info', 'trace', 'warning',
     * 'error', 'info', see CLogger constants definitions)
     */
    public static function log($message, $level = 'info') {
        Yii::log($message, $level, __CLASS__);
    }

    /**
     * 创建多级文件目录
     * @param string $path 路径名称
     * @return void
     */
    public function _createFolder($path) {
        if (!is_dir($path)) {
            $this->_createFolder(dirname($path));
            mkdir($path, 0777, true);
        }
    }

}
