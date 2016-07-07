<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbiStat
 *
 * @author SUNJOY
 */
class AbiStat {

    //put your code here
    public static function apiDayCountStat($api_name, $call_type = '') {
        // TODO record to download file log
        $current_day = strtotime(date('Y-m-d', time()));
        $call_api = $api_name;
        $sql = "select * from {{statistics_day}} where calltime = '$current_day' AND call_api like '$call_api'";
        if (!empty($call_type)) {
            $sql .= " AND call_type like '$call_type'";
        }
        $result = Yii::app()->db->createCommand($sql);
        $query = $result->queryAll();
        if (!empty($query) && !empty($query[0])) {
            $stat_id = $query[0]['id'];
            $call_count = intval($query[0]['call_count']) + 1;
            Yii::app()->db->createCommand("update {{statistics_day}} set call_count = '$call_count' where id = '$stat_id'")->execute();
        } else {
            Yii::app()->db->createCommand("insert into {{statistics_day}} (calltime,call_api,call_count,call_type) values('$current_day', '$call_api', '1','$call_type')")->execute();
        }
    }

}
