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


// not bind
$query = sprintf("select `name`, `assistant` from `promogirls_userdata` where `status` = 0 and `name` not in ('GGK', 'MG', '小花', '何飞梅', '蔡碧娟', '赖翠玲', '陈秋燕', '何雪影', '麦慧贞', '黎红红', '肖梦', '黄静芹');");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $bind_no[] = $row;
    }
}

$query = sprintf("select `name`, `openid`, `assistant` from `promogirls_users` where `status` = 1 and `name` not in ('GGK', 'MG', '小花', '何飞梅', '蔡碧娟', '赖翠玲', '陈秋燕', '何雪影', '麦慧贞', '黎红红', '肖梦');");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $bind_yes[] = $row;
    }
}

foreach($bind_yes as $k=>$bind) {
    $query = sprintf("select `dateline_expire`, `point`, `limit`, `ticket` from `promogirls_qrcodes` where `openid` = '%s';", $bind['openid']);
    $source = $PG->query($query);
    if($source->num_rows > 0) {
        while($row = $source->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['dateline_expire']) - 86400);
            $bind_yes[$k]['qr'][$date] = $row;
            $query = sprintf("select count(1) as `count` from `letsktv_promogirls_scan_log` where `Ticket` = '%s';", $row['ticket']);
            $source_1 = $WX->query($query);
            if($source_1->num_rows > 0) {
                while($row_1 = $source_1->fetch_assoc()) {
                   $bind_yes[$k]['qr'][$date]['count'] = $row_1['count'];
                   $bind_yes[$k]['qr'][$date]['point_get'] = ($row_1['count'] > $bind_yes[$k]['qr'][$date]['limit']) ? $bind_yes[$k]['qr'][$date]['limit'] * $bind_yes[$k]['qr'][$date]['point'] : $bind_yes[$k]['qr'][$date]['count'] * $bind_yes[$k]['qr'][$date]['point'];
                }
            }
            $bind_yes[$k]['point'] += $bind_yes[$k]['qr'][$date]['point_get'];
            unset($bind_yes[$k]['qr'][$date]['limit'], $bind_yes[$k]['qr'][$date]['point'], $bind_yes[$k]['qr'][$date]['point_get'], $bind_yes[$k]['qr'][$date]['dateline_expire'], $bind_yes[$k]['qr'][$date]['ticket']);
        }
    }
}

$query = sprintf("select `u`.`openid`, sum(`o`.`point`) as `point` from `promogirls_orders` as `o`, `promogirls_users` as `u` where `o`.`uid` = `u`.`id` group by `u`.`openid`;");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $used_point[$row['openid']] = $row['point'];
    }
}
// print_r($used_point);

// print_r($bind_yes);

$begin      = new DateTime('2015-12-07');
$end        = new DateTime(date('Y-m-d'));
$interval   = new DateInterval('P1D');
$daterange  = new DatePeriod($begin, $interval ,$end);

echo '<pre><style tyle="text/css">table{border-spacing: 0;border-collapse:collapse;} table,th, td {border:1px solid #ccc;}</style>';
echo '表格从头复制到尾可以直接贴到excel里，带格式<br /><br />';
echo '促销员邀请数<br />';
echo '<table><tr><td>姓名</td><td>助理</td>';
foreach($daterange as $date){
    echo '<td>'.$date->format("Y-m-d")."</td>";
}
echo "<td>总积分</td><td>已用积分</td><td>剩余积分</td></tr>";
foreach($bind_yes as $bind) {
    echo '<tr><td>'.$bind['name'].'</td><td>'.$bind['assistant'].'</td>';
    foreach($daterange as $date){
        if(isset($bind['qr']) && isset($bind['qr'][$date->format("Y-m-d")])) {
            echo '<td>'.$bind['qr'][$date->format("Y-m-d")]['count'].'</td>';
        } else {
            echo '<td>0</td>';
        }
    }
    if(isset($bind['point'])) {
        echo '<td>'.$bind['point'].'</td>';
    } else {
        echo '<td>0</td>';
    }
    if(isset($used_point[$bind['openid']])) {
        echo '<td>'.$used_point[$bind['openid']].'</td>';
        echo '<td>'.($bind['point'] - $used_point[$bind['openid']]).'</td>';
    } else {
        echo '<td>0</td><td>'.max(0, $bind['point']).'</td>';
    }
    echo "</tr>";
}
foreach($bind_no as $bind_no_name) {
    echo '<tr><td>'.$bind_no_name['name'].'</td><td>'.$bind_no_name['assistant'].'</td>';
    foreach($daterange as $date){
        echo '<td>n/a</td>';
    }
    echo '<td>n/a</td><td>n/a</td><td>n/a</td></tr>';
}
echo '</table>';

echo '<br />';

$query = "select DATE_FORMAT(`dateline`, '%Y-%m-%d %H') as `hour`, count(1) as `count` from `letsktv_logs_subscribe` group by `hour` order by `hour`;";
$source = $WX->query($query);
echo '时段邀请数（需要手动补足空缺的时段，空缺的时段数量为0）<br />';
echo '<table>';
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $byhours[] = $row;
        echo '<tr><td>'.$row['hour'].'</td><td>'.$row['count'].'</td></tr>';
    }
}
echo '</table>';

echo '<br />';
echo '取消关注数：';
$query = "select count(1) as `count` from `letsktv_logs_subscribe` where `subscribe` = 0;";
$source = $WX->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        echo $row['count'];
    }
}

//print_r($bind_yes);