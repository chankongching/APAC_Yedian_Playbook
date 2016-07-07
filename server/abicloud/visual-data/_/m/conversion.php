<?php
(INAPP !== true) && die('Error !');

$a = (isset($_GET['a']) && in_array(trim($_GET['a']), array('pastWeek', 'pastMonth', 'byRange'))) ? trim($_GET['a']) : 'pastWeek';
$t = (isset($_GET['t']) && in_array(trim($_GET['t']), array('reservation', 'channel', 'visit', 'user'))) ?         trim($_GET['t']) : 'reservation';

$conversion = new Conversion($a, $t);

// print_r($conversion->data);exit;

require_once V.$m.'.php';

Class Conversion {
    public $range;
    
    public function __construct($a, $t) {
        $this->Init($a, $t);
    }
    public function Init($a, $t) {
        global $C;
        $this->C = $C;
        $this->t = $t;
        if(method_exists(__CLASS__, $t)) {
            if($a == 'byRange') {
                $range = (isset($_GET['range']) && !empty($_GET['range'])) ? $_GET['range'] : exit('Date range not exists.');
                $range = explode('/', $range);
                if(!is_array($range) || ($range[0] !== date('Y-m-d', strtotime($range[0]))) || ($range[1] !== date('Y-m-d', strtotime($range[1]))) || ($range[0] > $range[1])) {
                    exit('Date format incorrect');
                }
                $this->range = $range;
            }
            $this->db['yedian'] = MySQLi($this->C['db']['yedian']);
            if(method_exists(__CLASS__, $a)) {
                $this->$a();
            }
            $this->$t();
        }
    }
    public function user() {
        $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_order` where `create_time` between '%s 00:00:00' and '%s 23:59:59' group by `date`", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $users[$row['date']] = intval($row['count']);
            }
        }
        $this->db['wechat'] = MySQLi($this->C['db']['wechat']);
        $query = sprintf("select `date`, `count` from `letsktv_logs_subscribe_statistics` where `date` between '%s' and '%s';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['wechat']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $wechatUsers[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_browse_statistics` where `date` between '%s' and '%s' and `type` = 'uv' and `url` = 'all';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $uvAll[$row['date']] = intval($row['count']);
            }
        }
        
        $chartReservedFans = array(
            'label' => 'Reservation User Rate (Total Fans)', 
            'desc'  => '# of Daily reservation user/ # of total fans', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartReservedUv = array(
            'label' => 'Reservation User Rate (Total UV)', 
            'desc'  => '# of Daily reservation user/ # of total UV', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );

        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $chartReservedFans['data'][] = array($date, (round(($users[$date]/$wechatUsers[$date])*10000, 3) ? round(($users[$date]/$wechatUsers[$date])*10000, 3) : 0));
            $chartReservedUv['data'][] = array($date, (round(($users[$date]/$uvAll[$date])*100, 3) ? round(($users[$date]/$uvAll[$date])*100, 3) : 0));
        }

        $this->data = array(
            array($chartReservedFans), 
            array($chartReservedUv)
        );
/*
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_order` where `create_time` <= '%s 23:59:59';", 
                $date, 
                $date
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
            if($source->num_rows > 0) {
                while($row = $source->fetch_assoc()) {
                    $count = intval($row['count']);
                }
            } else {
                $count = 0;
            }
            $query = sprintf("insert into `ac_order_statistics` (`date`, `count`) values ('%s', %d) on duplicate key update `count` = %d;", 
                $date, 
                $count, 
                $count
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
        }
*/
    }
    public function visit() {
        $query = sprintf("select `date`, `count` from `ac_tongji_browse_statistics` where `date` between '%s' and '%s' and `type` = 'uv' and `url` = '/ktv';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $uvList[$row['date']] = intval($row['count']);
            }
        }
        $this->db['wechat'] = MySQLi($this->C['db']['wechat']);
        $query = sprintf("select `date`, `count` from `letsktv_logs_subscribe_statistics` where `date` between '%s' and '%s';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['wechat']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $wechatUsers[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_browse_statistics` where `date` between '%s' and '%s' and `type` = 'uv' and `url` = 'all';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $uvAll[$row['date']] = intval($row['count']);
            }
        }
        
        $chartUvFans = array(
            'label' => 'KTV Visit Rate (Total Fans)', 
            'desc'  => 'Daily UV of KTV List / # of total fans', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartUvAllUv = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'desc'  => 'Daily UV of KTV List / # of total UV', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );

        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $chartUvFans['data'][] = array($date, (round(($uvList[$date]/$wechatUsers[$date])*1000, 3) ? round(($uvList[$date]/$wechatUsers[$date])*1000, 3) : 0));
            $chartUvAllUv['data'][] = array($date, (round(($uvList[$date]/$uvAll[$date])*100, 3) ? round(($uvList[$date]/$uvAll[$date])*100, 3) : 0));
        }

        $this->data = array(
            array($chartUvFans), 
            array($chartUvAllUv)
        );
/*
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_browse` where `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
                $date, 
                $date
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
            if($source->num_rows > 0) {
                while($row = $source->fetch_assoc()) {
                    $count = intval($row['count']);
                }
            } else {
                $count = 0;
            }
            $query = sprintf("insert into `ac_tongji_browse_statistics` (`date`, `count`, `type`, `url`) values ('%s', %d, 'uv', 'all') on duplicate key update `count` = %d;", 
                $date, 
                $count, 
                $count
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
        }
*/

/*
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_browse` where `url` = '/ktv' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
                $date, 
                $date
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
            if($source->num_rows > 0) {
                while($row = $source->fetch_assoc()) {
                    $count = intval($row['count']);
                }
            } else {
                $count = 0;
            }
            $query = sprintf("insert into `ac_tongji_browse_statistics` (`date`, `count`) values ('%s', %d) on duplicate key update `count` = %d;", 
                $date, 
                $count, 
                $count
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
        }
*/

/*
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $query = sprintf("select date_format(`dateline`, '%%Y-%%m-%%d') as `date`, count(1) as `count` from `letsktv_logs_subscribe` where `dateline` <= '%s 23:59:59' and `subscribe` = 1;", 
                $date
            );
            echo $query."\n";
            $source = $this->db['wechat']->query($query);
            if($source->num_rows > 0) {
                while($row = $source->fetch_assoc()) {
                    $count = intval($row['count']);
                }
            } else {
                $count = 0;
            }
            $query = sprintf("insert into `letsktv_logs_subscribe_statistics` (`date`, `count`) values ('%s', %d) on duplicate key update `count` = %d;", 
                $date, 
                $count, 
                $count
            );
            echo $query."\n";
            $source = $this->db['wechat']->query($query);
        }
*/
    }
    public function channel() {
        $this->ChartTitle = 'Reservation Channel';
        $ordersCountRangeCallcenter = $this->getOrdersCount('callcenter', true);
        $ordersCountRangeBiz        = $this->getOrdersCount('biz', true);
        $UvListRange                = $this->getUvList(true);
        $chartCallcenter = array(
            'label' => '电话中心预订率', 
            'desc'  => '# of call center reservation / Daily UV of KTV List', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartBiz = array(
            'label' => '商户版预订率', 
            'desc'  => '# of WeChat reservation / Daily UV of KTV List', 
            'color' => '#f5994e', 
            'data'  => array()
        );
        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $chartCallcenter['data'][]  = array($date, round(($ordersCountRangeCallcenter[$date]/$UvListRange[$date])*100, 3));
            $chartBiz['data'][]         = array($date, round(($ordersCountRangeBiz[$date]/$UvListRange[$date])*100, 3));
        }
        $reservation = array(
            $chartCallcenter, 
            $chartBiz
        );
        $data = array(
            'reservationall'        => round(($ordersCount/$UvListAll)*100, 3), 
            'reservationbyRange'    => $reservation
        );
        $this->data = $data;
    }
    public function reservation() {
        $this->ChartTitle   = 'Reservation Rate';
        $ordersCount        = $this->getOrdersCount('all');
        $UvListAll          = $this->getUvList();
        $ordersCountRange   = $this->getOrdersCount('all', true);
        $UvListRange        = $this->getUvList(true);
        $reservation = array(
            'label' => 'Reservation Rate', 
            'desc'  => '# of reservation / Daily UV of KTV List',
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $chart[]  = array($date, round(($ordersCountRange[$date]/$UvListRange[$date])*100, 3));
        }
        $reservation['data'] = $chart;
        $data = array(
            'reservationall'        => round(($ordersCount/$UvListAll)*100, 3), 
            'reservationbyRange'    => array($reservation)
        );
        $this->data = $data;
    }
    private function pastWeek() {
        $this->start    = date('Y-m-d', strtotime('-7 day'));
        $this->end      = date('Y-m-d', strtotime('-1 day'));
    }
    private function pastMonth() {
        $this->start    = date('Y-m-d', strtotime('-30 day'));
        $this->end      = date('Y-m-d', strtotime('-1 day'));
    }
    private function byRange() {
        $this->start    = $this->range[0];
        $this->end      = $this->range[1];
    }
    private function getOrdersCount($type = 'all', $range = false) {
        $this->getCallcenter($type);
        if($range === true) {
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(1) as `count` from `ac_order` where %s and `create_time` between '%s 00:00:00' and '%s 23:59:59' group by `date`;", 
                $this->ignore, 
                $this->db['yedian']->real_escape_string($this->start), 
                $this->db['yedian']->real_escape_string($this->end)
            );
        } else {
            $query = sprintf("select count(1) as `count` from `ac_order` where %s;", 
                $this->ignore
            );
        }
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                if($range === true) {
                    $count[$row['date']] = intval($row['count']);
                } else {
                    $count = intval($row['count']);
                }
            }
            return $count;
        } else {
            return 0;
        }
    }
    private function getUvList($range = false) {
        if($range === true) {
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_browse` where `url` = '/ktv' and `create_time` between '%s 00:00:00' and '%s 23:59:59' group by `date`;", 
                $this->db['yedian']->real_escape_string($this->start), 
                $this->db['yedian']->real_escape_string($this->end)
            );
        } else {
            $query = sprintf("select count(1) as `count` from `ac_tongji_browse` where `url` = '/ktv';");
        }
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                if($range === true) {
                    $count[$row['date']] = intval($row['count']);
                } else {
                    $count = intval($row['count']);
                }
            }
            return $count;
        } else {
            return 0;
        }
    }
    private function getCallcenter($type = 'all') {
        $ignore = array('XKTV00000');
        if($type !== 'all') {
            $query = sprintf("select `xktvid` from `ac_xktv` where `type` != 2;");
            $source = $this->db['yedian']->query($query);
            if($source->num_rows > 0) {
                while ($row = $source->fetch_assoc()) {
                    $ignore[] = $row['xktvid'];
                }
            }
        }
        $ignore = implode("', '", $ignore);
        $ignore = "('".$ignore."')";
        if($type === 'callcenter') {
            $ignore = '`ktvid` in '.$ignore;
        } elseif($type === 'biz') {
            $ignore = '`ktvid` not in '.$ignore;
        } else {
            $ignore = '`ktvid` not in '.$ignore;
        }
        $ignore = "`ktvid` != 'XKTV01158' and ".$ignore;
        $this->ignore = $ignore;
    }
}
