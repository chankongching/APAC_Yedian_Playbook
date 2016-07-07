<?php
namespace Business\Controller;
use Think\Controller;

class StatisticsController extends CommonController {
	public function index() {

	}
	public function giftorder() {
		if (IS_AJAX && IS_POST) {
			$table = 'ac_gift_order';
			$primaryKey = 'id';
			$columns = array(
				array('db' => 'id', 'dt' => 'id'),
				array('db' => 'order_no', 'dt' => 'order_no'),
				array('db' => 'order_status', 'dt' => 'order_status'),
				array('db' => 'userid', 'dt' => 'userid'),
				array('db' => 'sellorder_datetime', 'dt' => 'sellorder_datetime'),
				array('db' => 'sellordergoods_id', 'dt' => 'sellordergoods_id'),
				array('db' => 'sellordergoods_name', 'dt' => 'sellordergoods_name'),
				array('db' => 'sellorder_pointdeduction', 'dt' => 'sellorder_pointdeduction'),
				array('db' => 'id', 'dt' => 'do', 'formatter' => function ($d, $row) {
					return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
				}),
			);

			$this->ssp_lists_ajax($_POST, $table, $columns);
		} else {
			$this->display('order_list');
		}
	}

	public function giftorder_list_by_day() {
		if (IS_POST) {
			$date = I('day');
			$result = M()->query('select sellordergoods_goodsid,count(sellordergoods_goodsid) as pcount,gift.`productsale_points`,count(sellordergoods_goodsid)*gift.`productsale_points` as totals from `ac_gift_order` join `ac_gifts` gift on gift.`product_id`=ac_gift_order.sellordergoods_goodsid where order_no like \'YC160' . $date . '%\' group by sellordergoods_goodsid;');
//            $result['sql']=M()->getLastSql();
			echo json_encode($result, true);
		} else {
			$this->display();
		}
	}

	public function points_by_week() {
		if (IS_POST && IS_AJAX) {

		} else {
			$this->display();
		}
	}

	public function list_order_by_week() {
		if (IS_POST) {
			if (date('l', time()) == 'Monday') {
				$last_monday = date('Y-m-d', strtotime('last monday'));
			}

			$last_monday = date('Y-m-d', strtotime('-1 week last monday'));
			$days = array();
			$week = '1';
			$days[] = $last_monday;
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 1);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 2);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 3);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 4);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 5);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 6);
			$days[] = date('Y-m-d', strtotime($last_monday) + 3600 * 24 * 7);
			$this->lists = M()->query("select aor.id as id,aor.status as status,axk.name as ktvname, aor.`create_time` as create_time,axk.type as type
                from ac_order aor
                JOIN ac_xktv axk on axk.`xktvid`= aor.ktvid
                where axk.id not in(229) and
                (      aor.`create_time` BETWEEN '" . $days[0] . " 19:00:00' and '" . $days[1] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[1] . " 19:00:00' and '" . $days[2] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[2] . " 19:00:00' and '" . $days[3] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[3] . " 19:00:00' and '" . $days[4] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[4] . " 19:00:00' and '" . $days[5] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[5] . " 19:00:00' and '" . $days[6] . " 02:00:00'
                    or aor.`create_time` BETWEEN '" . $days[6] . " 19:00:00' and '" . $days[7] . " 02:00:00')
                and aor.status in(3,5) order by axk.type,aor.`create_time`");
			// echo M()->getlastsql();
			die(json_encode(array('data' => $this->lists)));
		}

		$this->display();
	}
	public function list_order_by_week_all() {
		if (IS_POST) {
			// var_dump($_POST);
			if (I('type') != null) {
				$num_week = intval(I('post.type'));
			} else {
				$num_week = 1;
			}
			// if (date('l', time()) == 'Monday') {
			// 	$last_monday = date('Y-m-d', strtotime('-1 monday'));
			// }
			$start_monday = date('Y-m-d', strtotime('-' . $num_week . ' monday'));
			$days = array();
			$week = '1';
			$days[] = $start_monday;
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 1);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 2);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 3);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 4);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 5);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 6);
			$days[] = date('Y-m-d', strtotime($start_monday) + 3600 * 24 * 7);
			$lists = M()->query("select aor.id as id,aor.status as status,axk.name as ktvname, aor.`create_time` as create_time,axk.type as type,apu.openid as openid
                from ac_order aor
                JOIN ac_xktv axk on axk.`xktvid`= aor.ktvid
                JOIN ac_platform_user apu on apu.`id`= aor.userid
                where axk.id not in(229) and
                aor.userid not in('46371','81571','153798','161717') and
                (      aor.`create_time` BETWEEN '" . $days[0] . " 02:00:00' and '" . $days[7] . " 02:00:00')
                and aor.status in(3,5) order by axk.type,aor.`create_time`");
			foreach ($lists as $key => $value) {
				if ($value['status'] == '3') {
					$lists[$key]['status'] = '有房';
				} elseif ($value['status'] == '5') {
					$lists[$key]['status'] = '到店确认';
				}

				if ($value['type'] == 0) {
					$lists[$key]['laiyuan'] = 'Call Center';
				} else {
					$lists[$key]['laiyuan'] = '商家版';
				}

				if ($this->getTypeBytime($value['create_time'])) {
					$lists[$key]['shiduan'] = 'SPR';
				} else {
					$lists[$key]['shiduan'] = 'Other';
				}
			}
			die(json_encode(array('data' => $lists)));
		}
		$this->assign('week', I('get.week', 1));
		$this->display();
	}
	public function list_order_by_week_all_314() {
		if (IS_POST) {
			$days = array();
			$lists = M()->query("select aor.id as id,aor.status as status,axk.name as ktvname, aor.`create_time` as create_time,axk.type as type,apu.openid as openid
                from ac_order aor
                JOIN ac_xktv axk on axk.`xktvid`= aor.ktvid
                JOIN ac_platform_user apu on apu.`id`= aor.userid
                where axk.id not in(229) and
                aor.userid not in('46371','81571','153798','161717') and
                (      aor.`create_time` BETWEEN '2016-03-14 02:00:00' and '2016-04-10 02:00:00')
                and aor.status in(3,5) order by axk.type,aor.`create_time`");
			foreach ($lists as $key => $value) {
				if ($value['status'] == '3') {
					$lists[$key]['status'] = '有房';
				} elseif ($value['status'] == '5') {
					$lists[$key]['status'] = '到店确认';
				}

				if ($value['type'] == 0) {
					$lists[$key]['laiyuan'] = 'Call Center';
				} else {
					$lists[$key]['laiyuan'] = '商家版';
				}

				if ($this->getTypeBytime($value['create_time'])) {
					$lists[$key]['shiduan'] = 'SPR';
				} else {
					$lists[$key]['shiduan'] = 'Other';
				}
			}
			die(json_encode(array('data' => $lists)));
		}
		$this->assign('week', I('get.week', 1));
		$this->display();
	}
// CREATE TABLE `ac_platform_user` (
	//   `id` int(11) NOT NULL AUTO_INCREMENT,
	//   `username` varchar(255) NOT NULL,
	//   `email` varchar(255) DEFAULT NULL,
	//   `mobile` varchar(255) DEFAULT NULL,
	//   `password` varchar(255) NOT NULL,
	//   `role` varchar(64) DEFAULT NULL,
	//   `openid` varchar(255) DEFAULT NULL,
	//   `token` varchar(255) DEFAULT NULL,
	//   `type` int(11) NOT NULL DEFAULT '0',
	//   `auth_type` varchar(32) DEFAULT NULL,
	//   `profile_data` text,
	//   `display_name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
	//   `avatar_url` varchar(255) DEFAULT NULL,
	//   `last_login_time` datetime DEFAULT NULL,
	//   `create_time` datetime DEFAULT NULL,
	//   `create_user_id` int(11) DEFAULT NULL,
	//   `update_time` datetime DEFAULT NULL,
	//   `update_user_id` int(11) DEFAULT NULL,
	//   `giftorderstatus` tinyint(2) DEFAULT '0',
	//   PRIMARY KEY (`id`),
	//   UNIQUE KEY `username` (`username`,`auth_type`)
	// ) ENGINE=InnoDB AUTO_INCREMENT=170867 DEFAULT CHARSET=utf8;
	// a:9:{s:3:"cid";s:0:"";s:6:"gender";s:1:"1";s:7:"address";s:62:"车陂街道东圃大马路东圃购物中心C座3楼金畅KTV";s:5:"sname";s:6:"方俊";s:4:"stel";s:11:"13022047454";s:4:"prov";s:9:"广东省";s:4:"city";s:9:"广州市";s:6:"county";s:9:"天河区";s:9:"real_name";s:9:"屎太溶";}
	public function AllUser() {
		$conn = mysql_connect("127.0.0.1", "website", "WebSite456");
		mysql_select_db("abicloud", $conn);
		// $filename = "toy_csv.csv";
		// $fp = fopen('php://output', 'w');
		// $header = array('id', 'username', 'email', 'mobile', 'openid', 'display_name', 'profile_data', 'avatar_url', 'create_time', 'address', 'sname', 'stel', 'prov', 'city', 'county', 'real_name');
		// header('Content-type: application/csv');
		// header('Content-Disposition: attachment; filename=' . $filename);
		// fputcsv($fp, $header);

		// $num_column = count($header);
		$query = "SELECT `id`, `username`, `email`, `mobile`, `openid`, `display_name`, `profile_data`, `avatar_url`, `create_time` FROM ac_platform_user where `auth_type`='wechat' limit 1000";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			foreach ($row as $i => $v) {
				if ($i == 6) {
					$info = unserialize($v);
					$info['address'] = $info['address'] == null ? '' : $info['address'];
					$info['sname'] = $info['sname'] == null ? '' : $info['sname'];
					$info['stel'] = $info['stel'] == null ? '' : $info['stel'];
					$info['prov'] = $info['prov'] == null ? '' : $info['prov'];
					$info['city'] = $info['city'] == null ? '' : $info['city'];
					$info['county'] = $info['county'] == null ? '' : $info['county'];
					$info['real_name'] = $info['real_name'] == null ? '' : $info['real_name'];

					$row[9] = iconv('utf-8', 'gbk', $info['address']);
					$row[10] = iconv('utf-8', 'gbk', $info['sname']);
					$row[11] = iconv('utf-8', 'gbk', $info['stel']);
					$row[12] = iconv('utf-8', 'gbk', $info['prov']);
					$row[13] = iconv('utf-8', 'gbk', $info['city']);
					$row[14] = iconv('utf-8', 'gbk', $info['county']);
					$row[15] = iconv('utf-8', 'gbk', $info['real_name']);
				}
				// $row[$i] = iconv('utf-8', 'gbk', $v);
				$row[$i] = $v;
				var_dump($row);
			}
			// fputcsv($fp, $row);
		}
		exit;

	}

	public function AlldjStatus() {
		if (IS_POST && IS_AJAX) {
			// $query = "select ac_xktv.name as ktvname,ydsjb_sj_record.ktvid as ktvid,ydsjb_sj_record.create_time as create_time,ydsjb_sj_record.count as count,ydsjb_ktvemp.`name` as name from `ydsjb_sj_record` join `ydsjb_ktvemp` on `ydsjb_ktvemp`.`openid` =ydsjb_sj_record.`emp_openid` join ac_xktv on ac_xktv.id=ydsjb_sj_record.ktvid where ydsjb_sj_record.ktvid not in(229) and ydsjb_sj_record.userid not in('46371','81571','153798','161717') order by ydsjb_sj_record.ktvid ";
			// $query = "select distinct ydsjb_sj_record.id as djid,ac_xktv.name as ktvname,ydsjb_sj_record.ktvid as ktvid,ydsjb_sj_record.create_time as create_time,ydsjb_sj_record.count as count,ydsjb_ktvemp.`name` as name from `ydsjb_sj_record` right join `ydsjb_ktvemp` on `ydsjb_ktvemp`.`openid` =ydsjb_sj_record.`emp_openid` right join ac_xktv on ac_xktv.id=ydsjb_sj_record.ktvid where ydsjb_sj_record.ktvid not in(229) and ydsjb_sj_record.userid not in('46371','81571','153798','161717') order by ydsjb_sj_record.ktvid";
			$query = "select
						ydsjb_sj_record.id as djid,
						ac_order.`create_time` as order_create_time,
						ydsjb_sj_record.create_time as scan_time,
						ac_xktv.name as ktvname,
						ydsjb_sj_record.emp_openid as emp_openid,
						ydsjb_ktvemp.name as emp_name,
						ydsjb_sj_record.count as sj_count,
						ac_coupon.`orderid`,
						ac_platform_user.openid as user_openid,
						ac_platform_user.display_name as user_display_name
						from ydsjb_sj_record
						left join ac_coupon on ac_coupon.id = ydsjb_sj_record.`couponid`
						left join ac_xktv on ac_xktv.id = ydsjb_sj_record.`ktvid`
						left join ydsjb_ktvemp on ydsjb_ktvemp.`openid`=ydsjb_sj_record.emp_openid
						left join ac_platform_user on ac_platform_user.id = ydsjb_sj_record.userid
						left join ac_order on ac_order.id=ac_coupon.orderid
						where ydsjb_sj_record.ktvid not in(229) and ydsjb_sj_record.userid not in('46371','81571','153798','161717')
						order by ydsjb_sj_record.ktvid";
			$lists = M()->query($query);
			die(json_encode(array('data' => $lists)));
		} else {
			$this->display();
		}

	}

	protected function getTypeBytime($times) {
		$time = date('G', strtotime($times));
		if (in_array($time, array(19, 20, 21, 22, 23, 0, 1))) {
			return true;
		}
		return false;
	}

	public function OrderListAll() {
		$query = "select
ac_order.id,
FROM_UNIXTIME(ac_order.time) as xdsj,
 CASE ac_order.status
  WHEN 3 THEN '有房'
  WHEN 4 THEN '无房'
  WHEN 5 THEN '到店确认'
  WHEN 7 THEN '取消'
  WHEN 14 THEN '过期'
   ELSE 'more' END as ddzt,
ac_xktv.name as ktvname,
if(ac_xktv.type=2,'商家版','Call Center') as qd,
CASE `ac_coupon`.`status`
    WHEN 0 THEN '已确认'
    WHEN 1 THEN '已兑酒'
    WHEN 2 THEN '已过期'
    WHEN 3 THEN '未确认'
    WHEN 4 THEN '已失效'
    ELSE '' END
    as jqzt,
`ac_coupon_type`.`count` as sjs,
`ac_coupon_type`.`name` as sjsm,
ydsjb_sj_record.`emp_openid` as fwyopenid,
ydsjb_sj_record.`create_time` as scan_time,
ydsjb_ktvemp.name as fwyname,
ac_platform_user.openid as yhopenid,
ydsjb_orderhistory.`create_time` as clsj,
ac_order.`update_time` as zhclsj,
ac_bd.name as 'BDname',
FROM_UNIXTIME(ac_order.starttime) as 'ksss'

from ac_order
left join ac_xktv on ac_xktv.xktvid=ac_order.ktvid
left join ac_coupon on `ac_order`.id=`ac_coupon`.`orderid`
left join ac_coupon_type on ac_coupon.`type`=ac_coupon_type.id
left join `ydsjb_sj_record` on ydsjb_sj_record.`couponid`=ac_coupon.id
left join `ydsjb_ktvemp` on ydsjb_ktvemp.`openid`=ydsjb_sj_record.emp_openid
left join `ac_platform_user` on ac_platform_user.id=ac_order.`userid`
left join `ydsjb_orderhistory` on ydsjb_orderhistory.oid=ac_order.id
left join `ac_bd_ktv` on ac_bd_ktv.`ktvid`=ac_xktv.id
left join ac_bd on ac_bd.`id`=ac_bd_ktv.`bdid`
where ac_xktv.id <> 229
order by ac_order.id";
		$result = M()->query($query);
		$this->assign('list_data', $result);
		header("Content-Type:application/vnd.ms-excel; charset=gb2312");
		header("Content-Disposition:attachment;filename=abc.xls"); //File name extension was wrong
		header("Expires:0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		echo '<meta charset="gb2312">
<table>';
		foreach ($result as $key => $vo) {
			echo '<tr><td>' . $vo['id'] . '</td>
			<td>' . $vo['xdsj'] . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['ddzt']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['jqzt']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['sjs']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['sjsm']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['fwyopenid']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['scan_time']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['fwyname']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['yhopenid']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['clsj']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['zhclsj']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['BDname']) . '</td>
			<td>' . iconv("UTF-8", "GBK", $vo['ksss']) . '</td></tr>';
		}
		echo '</table>';
		// $this->display();
	}
}