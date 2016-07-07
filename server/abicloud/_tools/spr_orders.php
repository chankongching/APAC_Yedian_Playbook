<?php

header("Content-Type: text/html; charset=utf-8");

$PGDBCONFIG = array('db_host'=> '127.0.0.1', 'db_user' => 'letsktv_biz', 'db_pswd' => 'OBjhe7UF3IsMIwPK', 'db_char' => 'utf8', 'db_name' => 'letsktv_biz_promogirls', 'db_tpre' => 'promogirls_', 'db_port' => 3306);
$PG = new mysqli($PGDBCONFIG['db_host'], $PGDBCONFIG['db_user'], $PGDBCONFIG['db_pswd'], $PGDBCONFIG['db_name'], $PGDBCONFIG['db_port']);
$PG->connect_errno && exit('DB Connection Error.');
$PG->query("SET character_set_connection=" . $PGDBCONFIG['db_char'] . ", character_set_results=" . $PGDBCONFIG['db_char'] . ", character_set_client=binary");

$query = sprintf("select `o`.`id`, `o`.`dateline`, `u`.`name`, `u`.`phone`, `o`.`gid`, `o`.`point`, `o`.`captcha`, `o`.`oid`, `o`.`sms` from `promogirls_orders` as `o`, `promogirls_users` as `u` where `o`.`id` >= 0 and `o`.`uid` = `u`.`id`;");
$source = $PG->query($query);
if($source->num_rows > 0) {
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://letsktv.chinacloudapp.cn/gift/giftlist?type=2');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "X-KTV-Application-Name: eec607d1f47c18c9160634fd0954da1a",
      "X-KTV-Vendor-Name: 1d55af1659424cf94d869e2580a11bf8",
     ]
    );
    $resp = curl_exec($ch);
    !$resp && die('error: 获取礼品列表出错。');
    $rs = json_decode($resp, true);
    curl_close($ch);
    if (isset($rs['result']) && $rs['result'] != 0) {
        die('error: '.$rs['msg']);
    }

    echo '<pre><style tyle="text/css">table{border-spacing: 0;border-collapse:collapse;} table,th, td {border:1px solid #ccc;}</style>';
    echo "<table><tr>
        <td>ID</td>
        <td>时间</td>
        <td>姓名</td>
        <td>电话</td>
        <td>礼品</td>
        <td>所耗积分</td>
        <td>兑换码</td>
        <td>订单号</td>
        <td>短信</td>
    </tr>";
    while($row = $source->fetch_assoc()) {
        $row['oid'] = json_decode($row['oid'], true);
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['dateline']}</td>
            <td>{$row['name']}</td>
            <td>{$row['phone']}</td>
            <td>{$rs['list'][$row['gid']]['productsale_name']}</td>
            <td>{$row['point']}</td>
            <td>{$row['captcha']}</td>
            <td>{$row['oid']}</td>
            <td>{$row['sms']}</td>
        </tr>";
    }
    echo "</table>";
}
