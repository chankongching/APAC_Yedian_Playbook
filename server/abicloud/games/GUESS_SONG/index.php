<?php
define('INAPP', true);
define('ROOT', __DIR__);

require_once ROOT.'/_/_inc.php';

switch($m) {
    case 'authorization':
        if(isset($_GET['code']) && !empty(trim($_GET['code'])) && (!isset($_SESSION['game_uid']) || intval($_SESSION['game_uid']) < 1)) {
            $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
            $getAuthorizationCode = $WX->getAuthorizationCode(trim($_GET['code']));
            $getAuthorizationCode['unionid'] = (isset($getAuthorizationCode['unionid']) && !empty(trim($getAuthorizationCode['unionid']))) ? trim($getAuthorizationCode['unionid']) : '';
            $query = sprintf("select `id`, `nickname`, `headimgurl`, `lastlogon` from `%s%s` WHERE `openid` = '%s' LIMIT 1;",
                $C['db']['pfix'],
                'users',
                $getAuthorizationCode['openid']
            );
            $refilluserinfo = false;
            $source = $DB->query($query);
            $DB->errno > 0 && die('Database error: '.$DB->errno);
            if($source->num_rows > 0) {
                while($row = $source->fetch_assoc()) {
                    $user = $row;
                }
                $query = sprintf("UPDATE `%s%s` SET `lastlogon`='%s', `lastaddress`=%d WHERE `id`=%d LIMIT 1;",
                    $C['db']['pfix'],
                    'users',
                    DATETIME,
                    ADDRESS,
                    $user['id']
                );
                $DB->query($query);
                $DB->errno > 0 && die('Database error: '.$DB->errno);
                $_SESSION['game_uid']        = intval($user['id']);
                $_SESSION['game_openid']     = trim($getAuthorizationCode['openid']);
                $_SESSION['game_nickname']   = trim($user['nickname']);
                $_SESSION['game_headimgurl'] = trim($user['headimgurl']);
                if($_SERVER['REQUEST_TIME'] - strtotime($user['lastlogon']) > 1440) {
                    $refilluserinfo = true;
                }
            } else {
                $query = sprintf("INSERT INTO `%s%s` SET `dateline`='%s', `address`=%d, `openid`='%s', `unionid`='%s', `access_token`='%s', `refresh_token`='%s';",
                    $C['db']['pfix'],
                    'users',
                    DATETIME,
                    ADDRESS,
                    $DB->real_escape_string(trim($getAuthorizationCode['openid'])),
                    $DB->real_escape_string(trim($getAuthorizationCode['unionid'])),
                    $DB->real_escape_string(trim($getAuthorizationCode['access_token'])),
                    $DB->real_escape_string(trim($getAuthorizationCode['refresh_token']))
                );
                $DB->query($query);
                $DB->errno > 0 && die('Database error: '.$DB->errno);
                $_SESSION['game_uid']        = intval($DB->insert_id);
                $_SESSION['game_openid']     = trim($getAuthorizationCode['openid']);
                $refilluserinfo = true;
            }
            if($refilluserinfo == true) {
                $userinfo = $WX->getUserInfo(trim($getAuthorizationCode['access_token']), trim($getAuthorizationCode['openid']));
                $query = sprintf("UPDATE `%s%s` SET `nickname`='%s', `sex`=%d, `province`='%s', `city`='%s', `country`='%s', `headimgurl`='%s', `privilege`='%s', `rawdata`='%s' WHERE `id`=%d LIMIT 1;",
                    $C['db']['pfix'],
                    'users',
                    $DB->real_escape_string(trim($userinfo['nickname'])),
                    $DB->real_escape_string(trim($userinfo['sex'])),
                    $DB->real_escape_string(trim($userinfo['province'])),
                    $DB->real_escape_string(trim($userinfo['city'])),
                    $DB->real_escape_string(trim($userinfo['country'])),
                    $DB->real_escape_string(trim($userinfo['headimgurl'])),
                    $DB->real_escape_string(json_encode($userinfo['privilege'])),
                    $DB->real_escape_string(json_encode($userinfo)),
                    $_SESSION['game_uid']
                );
                $DB->query($query);
                $DB->errno > 0 && die('Database error: '.$DB->errno);
                $_SESSION['game_nickname']      = trim($userinfo['nickname']);
                $_SESSION['game_headimgurl']    = trim($userinfo['headimgurl']);
            }
        }
        header('Location: '.URL);
    break;
    case 'api':
        header("Access-Control-Allow-Origin: *");
        header('Content-type: application/json');
        if(!isset($_SESSION['game_uid']) || intval($_SESSION['game_uid']) < 1) {
            $_SESSION = array();
            session_destroy();
            exit(json_encode(array('status' => 0, 'msg' => '会话已过期，请刷新页面重新登录。')));
//             $_SESSION['game_uid'] = 1;
//             $_SESSION['game_openid'] = 'o98BEs3IiWstL9c81XgO4ZimMXoc';
//             $_SESSION['game_nickname'] = '▓刮▓刮▓卡';
//             $_SESSION['game_headimgurl'] = 'http://wx.qlogo.cn/mmopen/icFTnRoibgibpibpnM9NXF8Ds26ibzQN9VP1ue4k3Tk0mz5P4IYTIIgMZZ0UbfjNry2EtGwic3dvIZ5gPhqmcTZ3K1CCEjL2UWT66O/0';
        }
        switch($a) {
            case 'toplist':
                // 排行
                $query = sprintf("SELECT `nickname`, `headimgurl`, `score` FROM `%s%s` WHERE `score` IS NOT NULL ORDER BY `score` ASC LIMIT 100",
                    $C['db']['pfix'],
                    'users'
                );
                $result = $DB->query($query);
                $DB->errno > 0 && exit(json_encode(array(
                    'status'    => 0,
                    'msg'       => '服务器内部错误'
                )));
                $toplist = array();
                while($row = $result->fetch_assoc()) {
                    $toplist[] = $row;
                }
                /*
                // 我的资料
                $query = sprintf("SELECT `nickname`, `headimgurl`, `score` FROM `%s%s` WHERE `id`=%s",
                    $C['db']['pfix'],
                    'users',
                    $_SESSION['game_uid']
                );
                $result = $DB->query($query);
                $DB->errno > 0 && exit(json_encode(array(
                    'status'    => 0,
                    'msg'       => '服务器内部错误'
                )));
                $me = $result->fetch_assoc();

                // 我的名次
                if ($me['score'] == NULL) {
                    $me['rank'] = -1;
                } else {
                    $query = sprintf("SELECT COUNT(*) as total FROM `%s%s` WHERE `score`<%d",
                        $C['db']['pfix'],
                        'users',
                        $me['score']
                    );
                    $result = $DB->query($query);
                    $DB->errno > 0 && die(json_encode(array(
                        'status'    => 0
                    )));
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $me['rank'] = $row['total'] + 1;
                }
                */
                exit(json_encode(array(
                    'status'    => 1,
                    'list'      => $toplist,
                    //'me'      => $me
                )));
            break;
            case 'submit':
                $score = (isset($_REQUEST['score']) && intval($_REQUEST['score']) > 0) ? floatval($_REQUEST['score']) : exit(json_encode(array('status' => 0, 'msg' => '参数错误，score 参数为空或不是正整数。')));
                
                // 记录分数
                $query = sprintf("INSERT INTO `%s%s` SET `dateline`='%s', `openid`='%s', `score`='%f'",
                    $C['db']['pfix'],
                    'logs',
                    DATETIME,
                    $_SESSION['game_openid'],
                    $score
                );
                $DB->query($query);
                $DB->errno > 0 && die(json_encode(array(
                    'status'    => 0
                )));

                // 提交分数
                $query = sprintf("UPDATE `%s%s` SET `score`=%f WHERE `id`=%d AND (`score`>%f OR `score` IS NULL)",
                    $C['db']['pfix'],
                    'users',
                    $score,
                    $_SESSION['game_uid'],
                    $score
                );
                $DB->query($query);
                $DB->errno > 0 && die(json_encode(array(
                    'status'    => 0
                )));

                // 总人数
                $query = sprintf("SELECT COUNT(*) as total FROM `%s%s` WHERE `score` IS NOT NULL",
                    $C['db']['pfix'],
                    'users'
                );
                $result = $DB->query($query);
                $DB->errno > 0 && die(json_encode(array(
                    'status'    => 0
                )));
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $total = $row["total"];

                // 名次
                $query = sprintf("SELECT COUNT(*) as total FROM `%s%s` WHERE `score`<%f AND `id`!=%d",
                    $C['db']['pfix'],
                    'users',
                    $score,
                    $_SESSION['game_uid']
                );
                $result = $DB->query($query);
                $DB->errno > 0 && die(json_encode(array(
                    'status'    => 0
                )));
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $rank = $row["total"] + 1;

                exit(json_encode(array(
                    'status'  => 1,
                    'score'   => $score,
                    'rank'    => $rank,
                    'total'    => $total,
                    'pct'     => intval($rank / $total * 100)
                )));
            break;
            case 'getToken':
//                 $WX = new WX(array('appid' => WECHAT_APPID, 'secret' => WECHAT_APPSECRET));
//                 echo $WX->access_token;
            break;
            default :
                exit;
            break;
        }
    break;
    case 'index':
        if(!isset($_SESSION['game_uid']) || intval($_SESSION['game_uid']) < 1) {
            $curr_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
            if(strpos($curr_url, 'ellehomme') !== false) {
                $_SESSION['game_referer'] = $curr_url;
            }
            $snsapi_userinfo_url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect',
                WECHAT_APPID,
                urlencode(URL.'?m=authorization')
            );
            header('Location: '.$snsapi_userinfo_url);exit;
        } else {
            $query = sprintf("select 1 from `%s%s` WHERE `id` = %d LIMIT 1;",
                $C['db']['pfix'],
                'users',
                $_SESSION['game_uid']
            );
            $source = $DB->query($query);
            $DB->errno > 0 && die('Database error: '.$DB->errno);
            if($source->num_rows < 1) {
                $_SESSION = array();
                session_destroy();
                exit('没有此用户，请重新打开微信登录');
            }
            $nickname = $_SESSION['game_nickname'];
            $avatar = $_SESSION['game_headimgurl'];
            if(isset($_SESSION['game_referer']) && !empty(trim($_SESSION['game_referer']))) {
                header('Location: '.$_SESSION['game_referer']);
                unset($_SESSION['game_referer']);
                exit;
            } else {
                require_once ROOT.'/home.html';
            }
        }
    break;
}
exit;
