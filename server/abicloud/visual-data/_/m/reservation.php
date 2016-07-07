<?php
(INAPP !== true) && die('Error !');

$a = (isset($_GET['a']) && in_array(trim($_GET['a']), array('pastWeek', 'pastMonth', 'byRange'))) ? trim($_GET['a']) : 'pastWeek';
$t = (isset($_GET['t']) && in_array(trim($_GET['t']), array('all', 'callcenter', 'biz'))) ?         trim($_GET['t']) : 'all';

$reservation = new Reservation($a, $t);

require_once V.$m.'.php';

Class Reservation {
    private $C;
    public $range;
    private $db = array();
    public  $data;
    private $summary = array(
        'waiting'   => 0, 
        'success'   => 0, 
        'rejected'  => 0, 
        'cancled'   => 0, 
        'expired'   => 0, 
        'total'     => 0, 
    );
    
    public function __construct($a, $t) {
        $this->Init($a, $t);
    }
    
    public function Init($a, $t) {
        global $C;
        $this->C = $C;
        $this->t = $t;
        if(method_exists(__CLASS__, $a)) {
            if($a == 'byRange') {
                $range = (isset($_GET['range']) && !empty($_GET['range'])) ? $_GET['range'] : exit('Date range not exists.');
                $range = explode('/', $range);
                if(!is_array($range) || ($range[0] !== date('Y-m-d', strtotime($range[0]))) || ($range[1] !== date('Y-m-d', strtotime($range[1]))) || ($range[0] > $range[1])) {
                    exit('Date format incorrect');
                }
                $this->range = $range;
            }
            $this->db['yedian'] = MySQLi($this->C['db']['yedian']);
            $this->$a();
            $this->getOrdersbyRange();
        }
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
    private function getOrdersbyRange() {
        $this->getCallcenter();
        $query = sprintf("select date_format(`create_time`, '%%Y-%%m-%%d') as `date`, `status`, count(1) as `count` from `ac_order` where %s and `create_time` between '%s 00:00:00' and '%s 23:59:59' group by `date`, `status`;", 
            $this->ignore, 
            $this->db['yedian']->real_escape_string($this->start), 
            $this->db['yedian']->real_escape_string($this->end)
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                $data[$row['date']][(string)$row['status']] = intval($row['count']);
            }
            $this->formatData($data);
        } else {
            return null;
        }
    }
    private function getOrdersAll() {
        $query = sprintf("select count(1) as `count` from `ac_order` where %s;", 
            $this->ignore
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                return intval($row['count']);
            }
        } else {
            return 0;
        }
    }
    private function getOrdersPastDay() {
        $query = sprintf("select count(1) as `count` from `ac_order` where %s and `create_time` between '%s 00:00:00' and '%s 23:59:59';", 
            $this->ignore, 
            $this->db['yedian']->real_escape_string(date('Y-m-d', strtotime('-1 day'))), 
            $this->db['yedian']->real_escape_string(date('Y-m-d', strtotime('-1 day')))
        );
        $source = $this->db['yedian']->query($query);
        if($source->num_rows > 0) {
            while($row = $source->fetch_assoc()) {
                return intval($row['count']);
            }
        } else {
            return 0;
        }
    }
    private function getCallcenter() {
        $ignore = array('XKTV00000');
        if($this->t !== 'all') {
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
        if($this->t === 'callcenter') {
            $ignore = '`ktvid` in '.$ignore;
        } elseif($this->t === 'biz') {
            $ignore = '`ktvid` not in '.$ignore;
        } else {
            $ignore = '`ktvid` not in '.$ignore;
        }
        $ignore = "`ktvid` != 'XKTV01158' and ".$ignore;
        $this->ignore = $ignore;
    }
    private function formatData($source) {
        $waiting = array(
            'label' => '等待处理', 
            'color' => '#5ab1ef', 
            'data'  => array()
        );
        $success = array(
            'label' => '预订成功', 
            'color' => '#f5994e', 
            'data'  => array()
        );
        $rejected = array(
            'label' => '无房', 
            'color' => '#d87a80', 
            'data'  => array()
        );
        $cancled = array(
            'label' => '用户取消', 
            'color' => '#5ab10f', 
            'data'  => array()
        );
        $expired = array(
            'label' => '超时', 
            'color' => '#000000', 
            'data'  => array()
        );
        $waiting_summary   = array(
            'label' => '等待处理', 
            'color' => '#5ab1ef', 
            'data'  => 0
        );
        $success_summary   = array(
            'label' => '预订成功', 
            'color' => '#f5994e', 
            'data'  => 0
        );
        $rejected_summary  = array(
            'label' => '无房', 
            'color' => '#d87a80', 
            'data'  => 0
        );
        $cancled_summary   = array(
            'label' => '用户取消', 
            'color' => '#5ab10f', 
            'data'  => 0
        );
        $expired_summary   = array(
            'label' => '超时', 
            'color' => '#000000', 
            'data'  => 0
        );
        $start = new DateTime($this->start);
        $end = new DateTime($this->end);
        $daterange  = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
        foreach($daterange as $date){
            $date = $date->format("Y-m-d");
            $waiting['data'][]  = array($date, max(0, @$source[$date]['1']));
            $success['data'][]  = array($date, max(0, @$source[$date]['3']) + @$source[$date]['5']);
            $rejected['data'][] = array($date, max(0, @$source[$date]['4']));
            $cancled['data'][]  = array($date, max(0, @$source[$date]['7']));
            $expired['data'][]  = array($date, max(0, @$source[$date]['14']));
            $waiting_summary['data']    = $waiting_summary['data']  + @$source[$date]['1'];
            $success_summary['data']    = $success_summary['data']  + @$source[$date]['3'] + @$source[$date]['5'];
            $rejected_summary['data']   = $rejected_summary['data'] + @$source[$date]['4'];
            $cancled_summary['data']    = $cancled_summary['data']  + @$source[$date]['7'];
            $expired_summary['data']    = $expired_summary['data']  + @$source[$date]['14'];
            $data['chart_total_perday'][$date] = @$source[$date]['1'] + @$source[$date]['3'] + @$source[$date]['5'] + @$source[$date]['4'] + @$source[$date]['7'] + @$source[$date]['14'];
            $data['total'] = @$data['total'] + @$source[$date]['1'] + @$source[$date]['3'] + @$source[$date]['5'] + @$source[$date]['4'] + @$source[$date]['7'] + @$source[$date]['14'];
        }
        $data['chart'] = array(
            $waiting, 
            $success, 
            $rejected, 
            $cancled, 
            $expired
        );
        $data['summary'] = array(
            $waiting_summary, 
            $success_summary, 
            $rejected_summary, 
            $cancled_summary, 
            $expired_summary
        );
        $data['all'] = $this->getOrdersAll();
        $data['pastDay'] = $this->getOrdersPastDay();
        $this->data = $data;
//         print_r($data);exit;
    }
}
