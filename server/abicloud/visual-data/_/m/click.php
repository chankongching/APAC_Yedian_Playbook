<?php
(INAPP !== true) && die('Error !');

$a = (isset($_GET['a']) && in_array(trim($_GET['a']), array('pastWeek', 'pastMonth', 'byRange'))) ? trim($_GET['a']) : 'pastWeek';
$t = (isset($_GET['t']) && in_array(trim($_GET['t']), array('click'))) ?         trim($_GET['t']) : 'click';

$click = new Click($a, $t);

// print_r($conversion->data);exit;

require_once V.$m.'.php';

Class Click {
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
    public function click() {
        $query = sprintf("select `date`, `count` from `ac_tongji_click_statistics` where `date` between '%s' and '%s' and `url` = '/order';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $order[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_click_statistics` where `date` between '%s' and '%s' and `url` = '/events';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $event[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_click_statistics` where `date` between '%s' and '%s' and `url` = '/ktv';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $ktv[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_click_statistics` where `date` between '%s' and '%s' and `url` = '/store';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $store[$row['date']] = intval($row['count']);
            }
        }
        $query = sprintf("select `date`, `count` from `ac_tongji_click_statistics` where `date` between '%s' and '%s' and `url` = '/user';", 
            $this->start, 
            $this->end
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $user[$row['date']] = intval($row['count']);
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

        $chartOrder = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartEvent = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartKtv = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartStore = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $chartUser = array(
            'label' => 'KTV Visit Rate (Total UV)', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );

        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $chartOrder['data'][]   = array($date, (round(($order[$date]/$uvAll[$date])*100, 3) ? round(($order[$date]/$uvAll[$date])*100, 3) : 0));
            $chartEvent['data'][]   = array($date, (round(($event[$date]/$uvAll[$date])*100, 3) ? round(($event[$date]/$uvAll[$date])*100, 3) : 0));
            $chartKtv['data'][]     = array($date, (round(($ktv[$date]/$uvAll[$date])*100, 3) ? round(($ktv[$date]/$uvAll[$date])*100, 3) : 0));
            $chartStore['data'][]   = array($date, (round(($store[$date]/$uvAll[$date])*100, 3) ? round(($store[$date]/$uvAll[$date])*100, 3) : 0));
            $chartUser['data'][]    = array($date, (round(($user[$date]/$uvAll[$date])*100, 3) ? round(($user[$date]/$uvAll[$date])*100, 3) : 0));
        }

        $this->data = array(
            array($chartOrder), 
            array($chartEvent), 
            array($chartKtv), 
            array($chartStore), 
            array($chartUser)
        );
/*
        $start = new DateTime('2016-01-22');
        $end = new DateTime('2016-02-28');
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end);
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_click` where `url` = '/ktv' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
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
            $query = sprintf("insert into `ac_tongji_click_statistics` (`date`, `count`, `url`) values ('%s', %d, '/ktv') on duplicate key update `count` = %d;", 
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
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_click` where `url` = '/store' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
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
            $query = sprintf("insert into `ac_tongji_click_statistics` (`date`, `count`, `url`) values ('%s', %d, '/store') on duplicate key update `count` = %d;", 
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
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_click` where `url` = '/user' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
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
            $query = sprintf("insert into `ac_tongji_click_statistics` (`date`, `count`, `url`) values ('%s', %d, '/user') on duplicate key update `count` = %d;", 
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
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_click` where `url` = '/order' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
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
            $query = sprintf("insert into `ac_tongji_click_statistics` (`date`, `count`, `url`) values ('%s', %d, '/order') on duplicate key update `count` = %d;", 
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
            $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, count(distinct `create_user_id`) as `count` from `ac_tongji_click` where `url` = '/events' and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
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
            $query = sprintf("insert into `ac_tongji_click_statistics` (`date`, `count`, `url`) values ('%s', %d, '/events') on duplicate key update `count` = %d;", 
                $date, 
                $count, 
                $count
            );
            echo $query."\n";
            $source = $this->db['yedian']->query($query);
        }
*/
//         exit;
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
