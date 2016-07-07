<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends CommonController {
	public function __construct() {
		parent::__construct();
		if (!session('?ktv_list')) {
			$ktv_list = M('xktv', 'ac_')->where(array('status' => 1))->select();
			if ($ktv_list != null) {
				$ktv_lists = array();
				foreach ($ktv_list as $key => $value) {
					$ktv_lists[$value['id']] = $value;
				}
				session('ktv_list', $ktv_lists);
			}
		}

	}
	public function index() {
		$this->redirect('Index/shenqingjilu');
	}
	public function main() {
		if (session('rbacid') == 1) {
			$this->display();
		} else {
			$this->display('hexiaoyuan_main');
		}

	}
	public function lists() {
		if (session('rbacid') == 1) {
			$this->display();
		} else {
			$this->display('hexiaoyuan_lists');
		}
	}

//     CREATE TABLE `ydsjb_sj_record` (
	//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	//   `couponid` int(11) NOT NULL,
	//   `count` int(11) NOT NULL,
	//   `emp_openid` varchar(50) NOT NULL DEFAULT '',
	//   `userid` int(11) NOT NULL,
	//   `status` tinyint(5) NOT NULL DEFAULT '1',
	//   `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	//   `ktvid` int(11) NOT NULL,
	//   PRIMARY KEY (`id`)
	// ) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

	public function lists_ajax() {
		$table = 'ydsjb_sj_record';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'couponid', 'dt' => 1),
			array('db' => 'emp_openid', 'dt' => 2, 'formatter' => function ($d, $row) {
				return $this->getEmpName($d, 'name');
			}),
			array('db' => 'userid', 'dt' => 3),
			array('db' => 'count', 'dt' => 4),
			array('db' => 'status', 'dt' => 5, 'formatter' => function ($d, $row) {
				if ($d == 0) {
					return "已核销";
				} else {
					return "未核销";
				}
			}),
			array('db' => 'ktvid', 'dt' => 6, 'formatter' => function ($d, $row) {
				return $this->getKtvName($d, 'name');
			}),
			// array('db' => 'type', 'dt' => 6,
			// 	'formatter' => function ($d, $row) {
			// 		return $this->getKtvManger($row['id'], 'name');
			// 	}),
			// array('db' => 'type', 'dt' => 7,
			// 	'formatter' => function ($d, $row) {
			// 		return $this->getKtvManger($row['id'], 'phone');
			// 	}),
			// array('db' => 'id', 'dt' => 8, 'formatter' => function ($d, $row) {
			// 	return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
			// }),

		);

		$this->ssp_lists_ajax($_POST, $table, $columns, null, null);

	}

	public function hexiaolists() {
		$this->display();
	}

	public function verify_lists_ajax() {
		$table = 'ac_xktv';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'name', 'dt' => 1),
			array('db' => 'id', 'dt' => 2,
				'formatter' => function ($d, $row) {
					return $this->getKtvHexiaoCount($d, 'yihexiao');
				}),
			array('db' => 'id', 'dt' => 3,
				'formatter' => function ($d, $row) {
					return $this->getKtvHexiaoCount($d, 'weihexiao');
				}),
			array('db' => 'id', 'dt' => 4,
				'formatter' => function ($d, $row) {
					return '<a href="/Verify/Index/ktv_detail?kid=' . $d . '"><button class="btn btn-info">详情</button></a>';
				}),
			// array('db' => 'type', 'dt' => 6,
			//  'formatter' => function ($d, $row) {
			//      return $this->getKtvManger($row['id'], 'name');
			//  }),
			// array('db' => 'type', 'dt' => 7,
			//  'formatter' => function ($d, $row) {
			//      return $this->getKtvManger($row['id'], 'phone');
			//  }),
			// array('db' => 'id', 'dt' => 8, 'formatter' => function ($d, $row) {
			//  return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
			// }),

		);

		$this->ssp_lists_ajax($_POST, $table, $columns, null, 'type=2');
	}

	public function hexiao_record_lists_ajax() {
		$table = 'ydsjb_hexiao_record';
		$ktvid = intval(I('xktvid'));
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'id', 'dt' => 'id'),
			array('db' => 'count', 'dt' => 'count'),
			array('db' => 'create_time', 'dt' => 'create_time'),
			array('db' => 'status', 'dt' => 'status',
				'formatter' => function ($d, $row) {
					return $d == 0 ? '已核销' : "未核销";
				}),
			array('db' => 'id', 'dt' => 4,
				'formatter' => function ($d, $row) {
					return '<a href="/Verify/Index/hexiao_detail?kid=' . $d . '"><button class="btn btn-info">详情</button></a>';
				}),
			// array('db' => 'type', 'dt' => 6,
			//  'formatter' => function ($d, $row) {
			//      return $this->getKtvManger($row['id'], 'name');
			//  }),
			// array('db' => 'type', 'dt' => 7,
			//  'formatter' => function ($d, $row) {
			//      return $this->getKtvManger($row['id'], 'phone');
			//  }),
			// array('db' => 'id', 'dt' => 8, 'formatter' => function ($d, $row) {
			//  return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
			// }),

		);

		$this->ssp_lists_ajax($_POST, $table, $columns, null, '`ktvid`=' . $ktvid);
	}

	public function ktv_detail() {
		if (IS_GET) {
			$ktvid = I('kid');
			$ktvinfo = M('xktv', 'ac_')->where(array('id' => $ktvid))->find();
			$this->count_total = M('sj_record', 'ydsjb_')->where(array('ktvid' => $ktvid))->sum('count');
			$this->count_total = $this->count_total == null ? 0 : $this->count_total;
			$this->count_yihexiao_total = M('hexiao_record', 'ydsjb_')->where(array('ktvid' => $ktvid))->sum('count');
			$this->count_yihexiao_total = $this->count_yihexiao_total == null ? 0 : $this->count_yihexiao_total;
			$this->count_weihexiao_total = $this->count_total - $this->count_yihexiao_total;
			if ($ktvinfo != null) {
				$this->assign('ktvid', $ktvid);
				$this->assign('ktvname', $ktvinfo['name']);
			}
			$this->display();
		}
	}

	public function add_ktv_hexiao() {
		if (session('rbacid') == '1') {
			if (IS_POST) {
				$ktvid = I('post.ktvid');
				if ($ktvid == null) {
					$this->error('URL错误');
				}
				$count = intval(I('post.count'));
				$total_count = $this->count_total = M('sj_record', 'ydsjb_')->where(array('ktvid' => $ktvid))->sum('count');
				$total_count = $total_count == null ? 0 : $total_count;
				$count_yihexiao_total = M('hexiao_record', 'ydsjb_')->where(array('ktvid' => $ktvid))->sum('count');
				$count_yihexiao_total = $count_yihexiao_total == null ? 0 : $count_yihexiao_total;
				// echo $total_count;
				// echo $count_yihexiao_total;die();
				if ($total_count - $count_yihexiao_total < $count) {
					$this->error('核销数量有误');
				}
				$create_time = I('post.create_time');
				if (M('hexiao_record', 'ydsjb_')->add(array(
					'count' => $count, 'ktvid' => $ktvid, 'create_time' => $create_time))) {
					$this->success('添加成功', U('Index/hexiaolists'));
				}
			} else {
				if (IS_GET) {
					$ktvid = I('kid');
					if ($ktvid == null) {
						$this->error('URL错误');
					}
					$ktvinfo = M('xktv', 'ac_')->where(array('id' => $ktvid))->find();
					$this->assign('ktvname', $ktvinfo['name']);
					$this->ktvid = $ktvid;
				}
				$this->display();
			}
		} else {
			$this->error('非法请求');
		}

	}

	protected function getKtvHexiaoCount($id, $type) {
		$count_total = M('sj_record', 'ydsjb_')->where(array('ktvid' => $id))->sum('count');
		$count_total = $count_total == null ? 0 : $count_total;
		$count_total_yihexiao = M('hexiao_record', 'ydsjb_')->where(array('ktvid' => $id))->sum('count');
		$count_total_yihexiao = $count_total_yihexiao == null ? 0 : $count_total_yihexiao;
		if ($type == 'yihexiao') {
			return $count_total_yihexiao;
		} elseif ($type == 'weihexiao') {
			return $count_total - $count_total_yihexiao;
		}
	}

	protected function getKtvName($id, $name) {
		$manger = M('xktv', 'ac_')->where(array('id' => $id, 'status' => '1'))->find();
		return $manger[$name];
	}

	protected function getEmpName($d, $name) {
		$manger = M('ktvemp', 'ydsjb_')->where(array('openid' => $d))->find();
		return $manger[$name];
	}

	public function shenqingjilu() {
		if (IS_GET) {
			$types = M('songjiushenqing', 'ydsjb_')->distinct(true)->field('sj_type')->select();
			$data = array();
			foreach ($types as $key => $value) {
				$data[$value['sj_type']]['total_peisong'] = M('songjiushenqing', 'ydsjb_')->where(array('status' => 1, 'sj_type' => $value['sj_type']))->sum('count');
				$data[$value['sj_type']]['total_yipeisong'] = M('songjiushenqing', 'ydsjb_')->where(array('status' => 2, 'sj_type' => $value['sj_type']))->sum('count');
				$data[$value['sj_type']]['total_shenqing'] = M('songjiushenqing', 'ydsjb_')->where(array('sj_type' => $value['sj_type']))->sum('count');
				$data[$value['sj_type']]['sj_type'] = $this->getTypeOfBeer($value['sj_type']);
			}
			$this->assign('total_data', $data);
			$this->display();
		}

	}

	public function shenqing_lists_ajax() {
		$table = 'ydsjb_songjiushenqing';
		$primaryKey = 'id';
		$columns = array(
			array('db' => 'id', 'dt' => 'id'),
			array('db' => 'ktvid', 'dt' => 'ktvid', 'formatter' => function ($d, $row) {
				return $this->getKtvName($d, 'name');
			}),
			array('db' => 'count', 'dt' => 'count', 'formatter' => function ($d, $row) {

				return floor($d / 24) . '箱(' . $d . '瓶)';
			}),
			array('db' => 'status', 'dt' => 'status', 'formatter' => function ($d, $row) {
				if ($d == 0) {
					return '未核销';
				} elseif ($d == 1) {
					return '配送中';
				} elseif ($d == 2) {
					return '已完成';
				}
			}),
			array('db' => 'id', 'dt' => 'todo_id', 'formatter' => function ($d, $row) {
				return '<a href="' . U('shenqingdetail', array('id' => $d)) . '">查看</a>';
			}),
			array('db' => 'update_time', 'dt' => 'update_time'),
			array('db' => 'create_time', 'dt' => 'create_time'),
			array('db' => 'type', 'dt' => 'type', 'formatter' => function ($d, $row) {
				if ($d == 0) {
					return 'ktv提交';
				} elseif ($d == 1) {
					return '后台添加';
				}
			}),
			array('db' => 'sj_type', 'dt' => 'sj_type', 'formatter' => function ($d, $row) {
				if ($d == 1) {
					return '铝瓶';
				} elseif ($d == 2) {
					return '罐装';
				}
			}),
		);

		$this->ssp_lists_ajax($_POST, $table, $columns);
	}

	public function shenqingdetail() {
		if (IS_GET) {
			$sid = intval(I('get.id'));
			if ($sid > 0) {
				$info = M('songjiushenqing', 'ydsjb_')->where(array('id' => $sid))->find();
				if ($info != null) {
					$this->assign('ktvname', $this->getKtvName($info['ktvid'], 'name'));
					$this->assign('shenqing', $info);
					$this->display();
				}
			}
		}

	}
	public function queren_shenqing() {
		if (IS_GET) {
			$id = I('get.id') == null ? -1 : I('get.id');
			if ($id != -1) {
				if (M('songjiushenqing', 'ydsjb_')->where(array('id' => $id))->data(array('status' => 1, 'update_time' => date("Y-m-d H:i:s", time())))->save()) {
					$msg = '您本次提交的申请已经确认，酒水将于每月1-3日配送。';
					$msg = $this->sendNotfiy($this->getMangerOpenid($id), $msg);
					$this->success('更新成功' . json_decode($msg), U('shenqingjilu'));
				} else {
					$this->error('非法操作');
				}
			} else {
				$this->error('非法操作');
			}
		} else {
			$this->error('非法操作');
		}
	}
	public function complete_shenqing() {
		if (IS_GET) {
			$id = I('get.id') == null ? -1 : I('get.id');
			if ($id != -1) {
				if (M('songjiushenqing', 'ydsjb_')->where(array('id' => $id))->data(array('status' => 2, 'update_time' => date("Y-m-d H:i:s", time())))->save()) {
					$msg = '您本次申请核销的酒水已经配送完成。该啤酒仅用于夜点项目在您的KTV使用，如发现其他用途如流货，则会取消您的KTV活动资格。';
					$this->sendNotfiy($this->getMangerOpenid($id), $msg);
					$this->success('更新成功', U('shenqingjilu'));
				} else {
					$this->error('非法操作');
				}
			} else {
				$this->error('非法操作');
			}
		} else {
			$this->error('非法操作');
		}
	}

	public function addshenqing() {
		if (IS_POST) {

		} else {
			$ktvids = M('sj_record', 'ydsjb_')->distinct(true)->field('ktvid')->select();
			$ktv_list = array();
			$ktv_list_session = session('ktv_list');
			foreach ($ktvids as $key => $value) {
				$ktv_list[] = $ktv_list_session[$value['ktvid']];
			}
			$this->assign('ktv_list', $ktv_list);
			$this->display();
		}

	}

	public function getTatal() {
		if (IS_AJAX && IS_POST) {
			$ktvid = I('post.ktvid');
			M('sj_record', 'ydsjb_')->where(array('ktvid' => $ktvid))->sum('count');
		}
	}

	protected function sendNotfiy($openid, $msg) {
		$data = array("msg" => $msg, "openid" => $openid);
		$data_string = json_encode($data);
		$ch = curl_init('http://letsktv.chinacloudapp.cn/wechatshangjia/WeChat/sendCustomMessageByApi');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		$result = curl_exec($ch);
		return $result;
	}

	protected function getMangerOpenid($id) {
		$rs = M('songjiushenqing', 'ydsjb_')->where(array('id' => $id))->find();
		$ktvid = $rs['ktvid'];
		$maninfo = M('ktvmanager', 'ydsjb_')->where(array('ktvid' => $ktvid, 'status' => '1'))->find();
		return $maninfo['openid'];
	}

	protected function getTypeOfBeer($id = '') {
		if ($id == 1) {
			return '铝罐';
		} elseif ($id == 2) {
			return '罐装';
		}
	}

}