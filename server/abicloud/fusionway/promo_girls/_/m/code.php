<?php
(INAPP !== true) && die('Error !');

$url = sprintf(API.'index.php?m=userinfo&code=%s',
    $_GET['code']
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resp = curl_exec($ch);

!$resp && die('code: '.curl_errno($ch).', error: '.curl_error($ch));

$rs = json_decode($resp, true);
curl_close($ch);
if (isset($rs['status']) && $rs['status'] == 0) {
    die('code: '.$rs['code'].', error: '.$rs['error']);
}
$rs = $rs['data'];

$query = sprintf("select `id`, `openid`, `nickname`, `headimgurl`, `status` from `%s%s` where `openid` = '%s' limit 1;",
    $C['db']['pfix'],
    'users',
    $DB->real_escape_string($rs['openid'])
);
$source = $DB->query($query);
$DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
if ($source->num_rows < 1) {
    $query = sprintf("insert into `%s%s` set `openid`='%s', `nickname`='%s', `headimgurl`='%s';",
        $C['db']['pfix'],
        'users',
        $DB->real_escape_string(trim($rs['openid'])), 
        $DB->real_escape_string(trim($rs['nickname'])), 
        $DB->real_escape_string(trim($rs['headimgurl']))
    );
    $DB->query($query);
    $DB->errno > 0 && die('code: '.$DB->errno.', error: '.$DB->error);
}

$_SESSION['fusionway_promo_girls_openid'] = $rs['openid'];

if(isset($_SESSION['fusionway_promo_girls_http_referer']) && !empty($_SESSION['fusionway_promo_girls_http_referer'])) {
    $url = sprintf('%s', 
        $_SESSION['fusionway_promo_girls_http_referer']
    );
    $_SESSION['fusionway_promo_girls_http_referer'] == null;
    unset($_SESSION['fusionway_promo_girls_http_referer']);
} else {
    $url = sprintf('%s', 
        URL
    );
}
header('Location: '.$url);