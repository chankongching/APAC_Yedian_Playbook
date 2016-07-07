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

$query = sprintf("select `id`, `name`, `assistant`, `point__all`, `point__used` from `promogirls_users` where `status` = 1 and `name` not in ('GGK', 'MG', '小花', '何飞梅', '蔡碧娟', '赖翠玲', '陈秋燕', '何雪影', '麦慧贞', '黎红红', '肖梦');");
$source = $PG->query($query);
if($source->num_rows > 0) {
    while($row = $source->fetch_assoc()) {
        $bind_yes[] = $row;
    }
}

// $query = sprintf("select * from `promogirls_qrcodes_scan_log` order by `uid` asc, ;");

foreach($bind_yes as $k=>$bindyes) {
    $query = sprintf("select `count`, `dateline_expire` from `promogirls_qrcodes_scan_log` where `uid` = %d and `dateline_expire` between '2015-12-21 01:00:00' and '2015-12-28 00:59:59' order by `dateline_expire` asc;", 
        $bindyes['id']
    );
    $source = $PG->query($query);
    if($source->num_rows > 0) {
        while($row = $source->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['dateline_expire']) - 86400);
            $bind_yes[$k]['qr'][$date] = $row;
        }
    }
}

$begin      = new DateTime('2015-12-21');
$end        = new DateTime('2015-12-28');
$interval   = new DateInterval('P1D');
$daterange  = new DatePeriod($begin, $interval ,$end);

echo '<pre><style tyle="text/css">table{border-spacing: 0;border-collapse:collapse;} table,th, td {border:1px solid #ccc;}</style>';
echo '表格从头复制到尾可以直接贴到excel里，带格式<br /><br />';
echo '促销员邀请数<br />';
echo '<table><tr><td>姓名</td><td>助理</td>';
foreach($daterange as $date){
    echo '<td>'.$date->format("y-m-d")."</td>";
}
echo "<td>总积分</td><td>基础积分</td><td>奖励积分</td></tr>";
foreach($bind_yes as $bind) {
    echo '<tr><td>'.$bind['name'].'</td><td>'.$bind['assistant'].'</td>';
    $point_1 = $point_2 = $point_all = 0;
    foreach($daterange as $date){
        if(isset($bind['qr']) && isset($bind['qr'][$date->format("Y-m-d")])) {
            echo '<td>'.$bind['qr'][$date->format("Y-m-d")]['count'].'</td>';
            $point_1 += (min($bind['qr'][$date->format("Y-m-d")]['count'], 30) * 50);
            $point_2 += (max($bind['qr'][$date->format("Y-m-d")]['count'] - 30, 0) * 100);
            $point_all = $point_1 + $point_2;
        } else {
            echo '<td>0</td>';
        }
    }
    echo '<td>'.$point_all.'</td>';
    echo '<td>'.$point_1.'</td>';
    echo '<td>'.$point_2.'</td>';
    echo "</tr>";
}
foreach($bind_no as $bind_no_name) {
    echo '<tr><td>'.$bind_no_name['name'].'</td><td>'.$bind_no_name['assistant'].'</td>';
    foreach($daterange as $date){
        echo '<td>n/a</td>';
    }
    echo '<td>n/a</td><td>n/a</td><td>n/a</td></tr>';
}
echo '</table>'."\n";