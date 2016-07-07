<?php
exit;
header("Content-Type: text/html; charset=utf-8");

$WXDBCONFIG = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_wechat', 'db_tpre' => 'letsktv_', 'db_port' => 3306);
$WX = new mysqli($WXDBCONFIG['db_host'], $WXDBCONFIG['db_user'], $WXDBCONFIG['db_pswd'], $WXDBCONFIG['db_name'], $WXDBCONFIG['db_port']);
$WX->connect_errno && exit('DB Connection Error.');
$WX->query("SET character_set_connection=" . $WXDBCONFIG['db_char'] . ", character_set_results=" . $WXDBCONFIG['db_char'] . ", character_set_client=binary");


$PGDBCONFIG = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv_biz', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_biz_promogirls', 'db_tpre' => 'promogirls_', 'db_port' => 3306);
$PG = new mysqli($PGDBCONFIG['db_host'], $PGDBCONFIG['db_user'], $PGDBCONFIG['db_pswd'], $PGDBCONFIG['db_name'], $PGDBCONFIG['db_port']);
$PG->connect_errno && exit('DB Connection Error.');
$PG->query("SET character_set_connection=" . $PGDBCONFIG['db_char'] . ", character_set_results=" . $PGDBCONFIG['db_char'] . ", character_set_client=binary");

$query = sprintf("select `l`.`id`, `l`.`uid`, `l`.`count`, `q`.`ticket` from `promogirls_qrcodes_scan_log` as `l`, `promogirls_qrcodes` as `q` where `l`.`tid` = `q`.`id` and `l`.`dateline_expire` = '2016-01-31 00:59:59';");
$source = $PG->query($query);
while($row = $source->fetch_assoc()) {
    $query = sprintf("select `scaned` from `letsktv_qrcodes` where `ticket` = '%s' limit 1;", $row['ticket']);
    $source_true = $WX->query($query);
    while($row_true = $source_true->fetch_assoc()) {
        $row['true'] = $row_true['scaned'];
    }
    if($row['count'] !== $row['true']) {
        if($row['count'] >= 100) {
/*
            $query = sprintf("update `promogirls_qrcodes_scan_log` set `count` = %d where `id` = %d and `count` = %d limit 1;", $row['true'], $row['id'], $row['count']);
            $row['query'] = $query;
            $PG->query($query);
            $fake['over'][] = $row;
*/
        } else {
            if($row['count'] <= 30 && $row['true'] <= 30) {
/*
                $query_count = sprintf("update `promogirls_qrcodes_scan_log` set `count` = %d where `id` = %d and `count` = %d limit 1;", $row['true'], $row['id'], $row['count']);
                $PG->query($query_count);
                $query_point = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `id` = %d limit 1;", 50 * ($row['true'] - $row['count']), $row['uid']);
                $PG->query($query_point);
                $row['query_count'] = $query_count;
                $row['query_point'] = $query_point;
*/
            }
            if($row['count'] > 30 && $row['true'] > 30) {
/*
                $query_count = sprintf("update `promogirls_qrcodes_scan_log` set `count` = %d where `id` = %d and `count` = %d limit 1;", $row['true'], $row['id'], $row['count']);
                $PG->query($query_count);
                $query_point = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `id` = %d limit 1;", 100 * ($row['true'] - $row['count']), $row['uid']);
                $PG->query($query_point);
                $row['query_count'] = $query_count;
                $row['query_point'] = $query_point;
*/
            }
            if($row['count'] <= 30 && $row['true'] > 30) {
/*
                $query_count = sprintf("update `promogirls_qrcodes_scan_log` set `count` = %d where `id` = %d and `count` = %d limit 1;", $row['true'], $row['id'], $row['count']);
                $PG->query($query_count);
                $query_point = sprintf("update `promogirls_users` set `point__all` = `point__all` + %d where `id` = %d limit 1;", (100 * ($row['true'] - 30)) + (50 * (30 - $row['count'])), $row['uid']);
                $PG->query($query_point);
                $row['query_count'] = $query_count;
                $row['query_point'] = $query_point;
*/
            }
            $fake['normal'][] = $row;
        }
    }
}
print_r($fake);