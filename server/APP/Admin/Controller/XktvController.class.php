<?php
namespace Admin\Controller;
use Think\Controller;

class XktvController extends CommonController {
	public function lists() {
		// var_dump($_SESSION);
		//		$model = M('xktv');
		//		$this->_list($model);
		$this->display();
	}

	public function lists_ajax() {
		$table = 'ac_xktv';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'xktvid', 'dt' => 0),
			array('db' => 'name', 'dt' => 1),
			array('db' => 'type', 'dt' => 2,
				'formatter' => function ($d, $row) {
//					var_dump($row);die();
					if ($d == '2') {
						return '商家版';
					} elseif ($d == '0') {
						return "CallCenter";
					}

				}),
			array('db' => 'address', 'dt' => 3),
			array('db' => 'type', 'dt' => 4,
				'formatter' => function ($d, $row) {
					return $this->getKtvManger($row['id'], 'name');
				}),
			array('db' => 'type', 'dt' => 5,
				'formatter' => function ($d, $row) {
					return $this->getKtvManger($row['id'], 'phone');
				}),

			array('db' => 'update_user_id', 'dt' => 6, 'formatter' => function ($d, $row) {
				return $this->getAdminName($d);
			}),
			array('db' => 'update_time', 'dt' => 7),
			array('db' => 'id', 'dt' => 8,
				'formatter' => function ($d, $row) {
					return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
				}),
		);

		$this->ssp_lists_ajax($_POST, $table, $columns);

	}
	public function update() {
//		$var=sprintf("%05d", 241);
		//		echo $var;//结果为0002
		//		die();
		if (IS_GET) {
			$kid = I('get.id');
			if ($kid == null) {
				$this->areas = M('area', 'ac_')->where(array('pid' => '440100'))->select();
				$this->display();
			} else {
				$this->assign('kid', $kid);
				$xktvinfo = M('xktv')->where(array('id' => $kid))->find();
//				var_dump($xktvinfo);
				if ($xktvinfo != NULL) {

					//综合评分
					// floatval($ktv['DecorationRating']) * 0.3 + floatval($ktv['SoundRating']) * 0.15 + floatval($ktv['ServiceRating']) * 0.15 + floatval($ktv['ConsumerRating']) * 0.2 + floatval($ktv['FoodRating']) * 0.2;
					$xktvinfo['totalrate'] = floatval($xktvinfo['decorationrating']) * 0.3 +
					floatval($xktvinfo['soundrating']) * 0.15 +
					floatval($xktvinfo['servicerating']) * 0.15 +
					floatval($xktvinfo['consumerrating']) * 0.2 +
					floatval($xktvinfo['foodrating']) * 0.2;
					$openhours = explode('-', $xktvinfo['openhours']);
					$openhours['openhours_s'] = $openhours[0];
					$openhours['openhours_e'] = $openhours[1];
					$this->assign('openhours', $openhours);

					$this->assign('xktvinfo', $xktvinfo);
				} else {
					$this->redirect('xktv/lists');
				}
//				if($xktvinfo['type']==2){
				$shangjia = M('ktvmanager', 'ydsjb_')->where(array('ktvid' => $kid, 'status' => '1'))->find();
				if ($shangjia['openid'] == '') {
					$this->assign('bind', '0');
				} else {
					$this->assign('bind', '1');
				}
				$this->areas = M('area', 'ac_')->where(array('pid' => '440100'))->select();
				$this->assign('shangjia', $shangjia);
				$ktvemps = M('ktvemp', 'ydsjb_')->where(array('ktvid' => $kid))->select();
				$yzm_avail = M('yzm', 'ydsjb_')->where(array('ktvid' => $kid, 'status' => 1))->select();
				$this->assign('yzm_avail', $yzm_avail);
				$this->assign('ktvemps', $ktvemps);
//				}
				$this->assign('typeids', M('coupon_type')->where(array('status' => 1))->select());
				// 套餐相关
				$shijianduan = M('taocan_shijianduan', 'ac_')->
					join('left join __TAOCAN_SHIJIANDUAN_TYPE__ on __TAOCAN_SHIJIANDUAN_TYPE__.id=__TAOCAN_SHIJIANDUAN__.shijianduantype')->
					where(array('ac_taocan_shijianduan.ktvid' => $kid))->
					field('ac_taocan_shijianduan.id as sid,ac_taocan_shijianduan_type.id as id,ac_taocan_shijianduan_type.name as name,ac_taocan_shijianduan.starttime as starttime,ac_taocan_shijianduan.endtime as endtime,ac_taocan_shijianduan.ciri as ciri')->
					order('starttime')->
					select();
				$this->assign('shijianduan', $shijianduan);
				$roomtype = M('taocan_roomtype', 'ac_')->where(array('ktvid' => $kid))->select();
				$this->assign('roomtype', $roomtype);
				// 条款相关
				$tiaokuan = M('taocan_tiaokuan', 'ac_')->where(array('ktvid' => $kid))->select();
				$this->shijianduan_type = M('taocan_shijianduan_type', 'ac_')->where(array('status' => 1))->select();
				$this->taocanshijianduan = M('taocan_shijianduan')->where(array('ktvid' => $ktvid))->select();
				$this->assign('tiaokuan', $tiaokuan);
				$this->display();
			}
		} elseif (IS_POST) {
			$id = (I('post.id') !== '') ? I('post.id') : '';
			$rebind = I('post.rebind');
			$pdata = $_POST;
			$managername = (I('post.managername') !== '') ? I('post.managername') : '';
			$managertelphone = (I('post.managertelphone') !== '') ? I('post.managertelphone') : '';
			if ($id == '') {
				$ktv = M('xktv');
				$data = $_POST;
				unset($data['id']);
				$new_id = $ktv->add($data);
				if ($new_id > 0) {
					$data['xktvid'] = 'XKTV' . sprintf("%05d", $new_id);
					M('xktv')->where(array('id' => $new_id))->data($data)->save();
					if ($_POST['type'] == '2') {
						$ktvmanger = M('ktvmanager', 'ydsjb_');
						$ktvmanger->ktvid = $new_id;
						$ktvmanger->status = '1';
						$ktvmanger->name = $managername;
						$ktvmanger->phone = $managertelphone;
						$ktvmanger->add();
					}
					$this->success('添加成功', U('lists'));
				}
				die();
			}
			$ktvmanger = M('ktvmanager', 'ydsjb_');
			$ktvmangerdata = array();
			$ktvmangerdata['name'] = $managername;
			$ktvmangerdata['phone'] = $managertelphone;
			$ktvmangerdata['update_time'] = date("Y-m-d H:i:s", time());
			if ($rebind == 'on') {
				$ktvmangerdata['openid'] = '';
			}
			$ktvmanager_tmp = $ktvmanger->where(array('ktvid' => $id, 'status' => '1'))->find();
			if ($ktvmanager_tmp == null) {
				$ktvmangerdata['status'] = 1;
				$ktvmangerdata['ktvid'] = $id;
				$ktvmangerdata['create_time'] = date("Y-m-d H:i:s", time());
				M('ktvmanager', 'ydsjb_')->add($ktvmangerdata);
			} else {
				if ($ktvmanger->where(array('ktvid' => $id, 'status' => '1'))->save($ktvmangerdata) === false) {
					$this->error('管理员信息更新失败');
				}
			}

			$pdata['district'] = $this->getdistrictbycode($pdata['area_id']);
			$pdata['openhours'] = $pdata['openhours_s'] . '-' . $pdata['openhours_e'];
			$pdata['update_user_id'] = session('rbacid');
			$pdata['update_time'] = date('Y-m-d H:i:s', time());
			$pdata['DecorationRating'] = $pdata['decorationrating'];
			$pdata['SoundRating'] = $pdata['soundrating'];
			$pdata['ServiceRating'] = $pdata['servicerating'];
			$pdata['FoodRating'] = $pdata['foodrating'];
			$ckt = M('xktv')->where(array('id' => $id))->data($pdata)->save();
			$this->redirect('Admin/Xktv/lists');
		}

	}

	public function updateM() {
//		$var=sprintf("%05d", 241);
		//		echo $var;//结果为0002
		//		die();
		if (IS_GET) {
			$kid = I('get.id');
			if ($kid == null) {
				$this->areas = M('area', 'ac_')->where(array('pid' => '440100'))->select();
				$this->display();
			} else {
				$this->assign('kid', $kid);
				$xktvinfo = M('xktv')->where(array('id' => $kid))->find();
//				var_dump($xktvinfo);
				if ($xktvinfo != NULL) {

					//综合评分
					// floatval($ktv['DecorationRating']) * 0.3 + floatval($ktv['SoundRating']) * 0.15 + floatval($ktv['ServiceRating']) * 0.15 + floatval($ktv['ConsumerRating']) * 0.2 + floatval($ktv['FoodRating']) * 0.2;
					$xktvinfo['totalrate'] = floatval($xktvinfo['decorationrating']) * 0.3 +
					floatval($xktvinfo['soundrating']) * 0.15 +
					floatval($xktvinfo['servicerating']) * 0.15 +
					floatval($xktvinfo['consumerrating']) * 0.2 +
					floatval($xktvinfo['foodrating']) * 0.2;
					$openhours = explode('-', $xktvinfo['openhours']);
					$openhours['openhours_s'] = $openhours[0];
					$openhours['openhours_e'] = $openhours[1];
					$this->assign('openhours', $openhours);

					$this->assign('xktvinfo', $xktvinfo);
				} else {
					$this->redirect('xktv/lists');
				}
//				if($xktvinfo['type']==2){
				$shangjia = M('ktvmanager', 'ydsjb_')->where(array('ktvid' => $kid, 'status' => '1'))->find();
				if ($shangjia['openid'] == '') {
					$this->assign('bind', '0');
				} else {
					$this->assign('bind', '1');
				}
				$this->areas = M('area', 'ac_')->where(array('pid' => '440100'))->select();
				$this->assign('shangjia', $shangjia);
				$ktvemps = M('ktvemp', 'ydsjb_')->where(array('ktvid' => $kid))->select();
				$this->assign('ktvemps', $ktvemps);
//				}
				$this->assign('typeids', M('coupon_type')->where(array('status' => 1))->select());
				// 套餐相关
				$shijianduan = M('taocan_shijianduan', 'ac_')->
					join('left join __TAOCAN_SHIJIANDUAN_TYPE__ on __TAOCAN_SHIJIANDUAN_TYPE__.id=__TAOCAN_SHIJIANDUAN__.shijianduantype')->
					where(array('ac_taocan_shijianduan.ktvid' => $kid))->
					field('ac_taocan_shijianduan.id as sid,ac_taocan_shijianduan_type.id as id,ac_taocan_shijianduan_type.name as name,ac_taocan_shijianduan.starttime as starttime,ac_taocan_shijianduan.endtime as endtime,ac_taocan_shijianduan.ciri as ciri')->
					select();
				$this->assign('shijianduan', $shijianduan);
				$roomtype = M('taocan_roomtype', 'ac_')->where(array('ktvid' => $kid))->select();
				$this->assign('roomtype', $roomtype);
				// 条款相关
				$tiaokuan = M('taocan_tiaokuan', 'ac_')->where(array('ktvid' => $kid))->select();
				$this->shijianduan_type = M('taocan_shijianduan_type', 'ac_')->where(array('status' => 1))->select();
				$this->taocanshijianduan = M('taocan_shijianduan')->where(array('ktvid' => $ktvid))->select();
				$this->assign('tiaokuan', $tiaokuan);
				$this->display();
			}
		} elseif (IS_POST) {
			$id = (I('post.id') !== '') ? I('post.id') : '';
			$rebind = I('post.rebind');
			$pdata = $_POST;
			$managername = (I('post.managername') !== '') ? I('post.managername') : '';
			$managertelphone = (I('post.managertelphone') !== '') ? I('post.managertelphone') : '';
			if ($id == '') {
				$ktv = M('xktv');
				$data = $_POST;
				unset($data['id']);
				$new_id = $ktv->add($data);
				if ($new_id > 0) {
					$data['xktvid'] = 'XKTV' . sprintf("%05d", $new_id);
					M('xktv')->where(array('id' => $new_id))->data($data)->save();
					if ($_POST['type'] == '2') {
						$ktvmanger = M('ktvmanager', 'ydsjb_');
						$ktvmanger->ktvid = $new_id;
						$ktvmanger->status = '1';
						$ktvmanger->name = $managername;
						$ktvmanger->phone = $managertelphone;
						$ktvmanger->add();
					}
					$this->success('添加成功', U('lists'));
				}
				die();
			}
			$ktvmanger = M('ktvmanager', 'ydsjb_');
			$ktvmangerdata = array();
			$ktvmangerdata['name'] = $managername;
			$ktvmangerdata['phone'] = $managertelphone;
			$ktvmangerdata['update_time'] = date("Y-m-d H:i:s", time());
			if ($rebind == 'on') {
				$ktvmangerdata['openid'] = '';
			}
			$ktvmanager_tmp = $ktvmanger->where(array('ktvid' => $id, 'status' => '1'))->find();
			if ($ktvmanager_tmp == null) {
				$ktvmangerdata['status'] = 1;
				$ktvmangerdata['ktvid'] = $id;
				$ktvmangerdata['create_time'] = date("Y-m-d H:i:s", time());
				M('ktvmanager', 'ydsjb_')->add($ktvmangerdata);
			} else {
				if ($ktvmanger->where(array('ktvid' => $id, 'status' => '1'))->save($ktvmangerdata) === false) {
					$this->error('管理员信息更新失败');
				}
			}

			$pdata['district'] = $this->getdistrictbycode($pdata['area_id']);
			$pdata['openhours'] = $pdata['openhours_s'] . '-' . $pdata['openhours_e'];
			$pdata['update_user_id'] = session('rbacid');
			$pdata['update_time'] = date('Y-m-d H:i:s', time());
			$pdata['DecorationRating'] = $pdata['decorationrating'];
			$pdata['SoundRating'] = $pdata['soundrating'];
			$pdata['ServiceRating'] = $pdata['servicerating'];
			$pdata['FoodRating'] = $pdata['foodrating'];
			$ckt = M('xktv')->where(array('id' => $id))->data($pdata)->save();
			$this->redirect('Admin/Xktv/lists');
		}

	}

	// 获取时间段信息
	public function getTimeInfo() {
		if (IS_GET) {
			$kid = I('get.kid');
			$shijianduan = M('taocan_shijianduan', 'ac_')->
				join('left join __TAOCAN_SHIJIANDUAN_TYPE__ on __TAOCAN_SHIJIANDUAN_TYPE__.id=__TAOCAN_SHIJIANDUAN__.shijianduantype')->
				where(array('ac_taocan_shijianduan.ktvid' => $kid))->
				field('ac_taocan_shijianduan.ciri_starttime as ciri_starttime,ac_taocan_shijianduan.status as status,ac_taocan_shijianduan.shouye as shouye,ac_taocan_shijianduan.ciri as ciri,ac_taocan_shijianduan.id as sid,ac_taocan_shijianduan_type.id as id,ac_taocan_shijianduan_type.name as name,ac_taocan_shijianduan.starttime as starttime,ac_taocan_shijianduan.endtime as endtime')->
				select();
			$shijianduantype = M('taocan_shijianduan_type', 'ac_')->where(array('status' => 1))->select();
			echo json_encode(array('shijianduan' => $shijianduan, 'shijianduantype' => $shijianduantype));
		}
	}

	public function getRoomInfo() {
		if (IS_GET) {
			$kid = I('get.kid');
			$roomtype = M('taocan_roomtype', 'ac_')->where(array('ktvid' => $kid))->select();
			echo json_encode($roomtype);
		}
	}
	public function gettiaokuanInfo() {
		if (IS_GET) {
			$kid = I('get.kid');
			$tiaokuan = M('taocan_tiaokuan', 'ac_')->where(array('ktvid' => $kid))->select();
			echo json_encode($tiaokuan);
		}
	}
	public function getTaocanInfo() {
		if (IS_GET) {
			$kid = I('get.kid');
			$taocan = M('taocan_content', 'ac_')
				->join('left join __TAOCAN_ROOMTYPE__ on __TAOCAN_ROOMTYPE__.id=__TAOCAN_CONTENT__.roomtype')
				->join('left join __TAOCAN_SHIJIANDUAN__ on __TAOCAN_SHIJIANDUAN__.id=__TAOCAN_CONTENT__.shijianduan')
				->join('left join __TAOCAN_SHIJIANDUAN_TYPE__ on __TAOCAN_SHIJIANDUAN_TYPE__.id=__TAOCAN_SHIJIANDUAN__.shijianduantype')
				->field('ac_taocan_content.id as id,ac_taocan_content.ktvid as kid,ac_taocan_shijianduan_type.name as shijianduan,ac_taocan_content.desc,ac_taocan_content.name,ac_taocan_roomtype.name as roomtype,ac_taocan_content.price,ac_taocan_content.mon,ac_taocan_content.tue,ac_taocan_content.wen,ac_taocan_content.thu,ac_taocan_content.fri,ac_taocan_content.sat,ac_taocan_content.sun,ac_taocan_content.shouye,ac_taocan_content.tiaokuan as tiaokuan,ac_taocan_content.member_price,ac_taocan_content.is_yd_price,ac_taocan_content.yd_price,ac_taocan_content.shichang,
					ac_taocan_content.roomtype as roomtypeid,
					ac_taocan_content.shijianduan as shijianduanid')
				->where(array('ac_taocan_content.ktvid' => $kid))
				->select();
			echo json_encode($taocan);
		}
	}
	public function getZonglan() {
		if (IS_GET) {
			$kid = I('get.kid');
			$taocan = M('taocan_content', 'ac_')
				->join('left join __TAOCAN_ROOMTYPE__ on __TAOCAN_ROOMTYPE__.id=__TAOCAN_CONTENT__.roomtype')
				->join('left join __TAOCAN_SHIJIANDUAN__ on __TAOCAN_SHIJIANDUAN__.id=__TAOCAN_CONTENT__.shijianduan')
				->join('left join __TAOCAN_SHIJIANDUAN_TYPE__ on __TAOCAN_SHIJIANDUAN_TYPE__.id=__TAOCAN_SHIJIANDUAN__.shijianduantype')
				->field('ac_taocan_content.ktvid as kid,
					ac_taocan_content.id as id,
					ac_taocan_shijianduan_type.name as shijianduan,
					ac_taocan_content.desc,
					ac_taocan_content.name,
					ac_taocan_roomtype.name as roomtype,
					ac_taocan_content.price as price,
					ac_taocan_content.mon as mon,
					ac_taocan_content.tue as tue,
					ac_taocan_content.wen as wen,
					ac_taocan_content.thu as thu,
					ac_taocan_content.fri as fri,
					ac_taocan_content.sat as sat,
					ac_taocan_content.sun as sun,
					ac_taocan_content.shouye as shouye,
					ac_taocan_content.member_price as member_price,
					ac_taocan_content.is_yd_price as is_yd_price,
					ac_taocan_content.yd_price as yd_price,
					ac_taocan_content.roomtype as roomtypeid,
					ac_taocan_content.shijianduan as shijianduanid')
				->where(array('ac_taocan_content.ktvid' => $kid))->select();
			echo json_encode($taocan);
		}
	}

	public function updateTimeinfo() {
		if (IS_POST) {
			$postData = file_get_contents('php://input', true);
			$postArray = json_decode($postData, true);
			if ($postArray['sid'] == '') {
				$data = array('starttime' => $postArray['starttime'],
					'endtime' => $postArray['endtime'],
					'ktvid' => $postArray['kid'],
					'ciri' => $postArray['ciri'],
					'ciri_starttime' => $postArray['ciri_starttime'],
					'shijianduantype' => $postArray['id'],
					'shouye' => $postArray['shouye'],
					'status' => $postArray['status'],
				);
				$nid = M('taocan_shijianduan', 'ac_')->add($data);
				if ($nid > 0) {
					die(json_encode(array('result' => '0', 'msg' => '添加成功', 'nid' => $nid)));
				} else {
					die(json_encode(array('result' => '1', 'msg' => '添加失败')));
				}
			} else {
				$result = M('taocan_shijianduan', 'ac_')->where(array('id' => $postArray['sid']))
					->save(array(
						'starttime' => $postArray['starttime'],
						'endtime' => $postArray['endtime'],
						'ciri' => $postArray['ciri'],
						'shijianduantype' => $postArray['id'],
						'ciri_starttime' => $postArray['ciri_starttime'],
						'shouye' => $postArray['shouye'],
						'status' => $postArray['status'],
					));
				if ($result) {
					die(json_encode(array('result' => '0', 'msg' => '更新成功')));
				} else {
					die(json_encode(array('sss' => $result, 'sql' => M()->getLastSql())));
				}
			}
		}
	}

	public function updateRoominfo() {
		if (IS_POST) {
			$postData = file_get_contents('php://input', true);
			$postArray = json_decode($postData, true);
			// die();
			if ($postArray['id'] == '') {
				$data = array();
				$data['ktvid'] = $postArray['ktvid'];
				$data['name'] = $postArray['name'];
				$data['des'] = $postArray['des'];
				$data['shouye'] = $postArray['shouye'];
				$data['count'] = $postArray['count'];
				$data['status'] = $postArray['status'];
				$tid = M('taocan_roomtype', 'ac_')->add($data);
				if ($tid > 0) {
					die(json_encode(array('result' => '0', 'msg' => '添加成功', 'tid' => $tid)));
				} else {
					die(json_encode(array('result' => '1', 'msg' => '添加失败')));
				}
			} else {
				$data = array();
				$data['name'] = $postArray['name'];
				$data['des'] = $postArray['des'];
				$data['shouye'] = $postArray['shouye'];
				$data['count'] = $postArray['count'];
				$data['status'] = $postArray['status'];
				if (M('taocan_roomtype', 'ac_')->where(array('id' => $postArray['id']))->save($data)) {
					die(json_encode(array('result' => '0', 'msg' => '更新成功')));
				}
			}
		}
	}

	public function updateTiaokuaninfo() {
		if (IS_POST) {
			$postData = file_get_contents('php://input', true);
			$postArray = json_decode($postData, true);
			// die();
			if ($postArray['id'] == '') {
				$data = array();
				$data['ktvid'] = $postArray['ktvid'];
				$data['name'] = $postArray['name'];
				$tid = M('taocan_tiaokuan', 'ac_')->add($data);
				if ($tid > 0) {
					die(json_encode(array('result' => '0', 'msg' => '添加成功', 'tid' => $tid)));
				} else {
					die(json_encode(array('result' => '1', 'msg' => '添加失败')));
				}
			} else {
				$data = array();
				$data['name'] = $postArray['name'];
				if (M('taocan_tiaokuan', 'ac_')->where(array('id' => $postArray['id']))->save($data)) {
					die(json_encode(array('result' => '0', 'msg' => '更新成功')));
				}
			}
		}
	}
	public function updatetaocaninfo() {
		if (IS_POST) {
			$postData = file_get_contents('php://input', true);
			$postArray = json_decode($postData, true);
			if ($postArray['id'] == '') {
				$data = array();
				$data['ktvid'] = $postArray['ktvid'];
				$data['name'] = $postArray['name'];
				$data['desc'] = $postArray['desc'];
				$data['shouye'] = $postArray['shouye'];
				$data['member_price'] = $postArray['member_price'];
				$data['price'] = $postArray['price'];
				$data['yd_price'] = $postArray['yd_price'];

				$data['shichang'] = $postArray['shichang'];
				$data['roomtype'] = $postArray['roomtypeid'];
				$data['shijianduan'] = $postArray['shijianduanid'];
				$data['mon'] = $postArray['mon'];
				$data['tue'] = $postArray['tue'];
				$data['wen'] = $postArray['wen'];
				$data['thu'] = $postArray['thu'];
				$data['fri'] = $postArray['fri'];
				$data['sat'] = $postArray['sat'];
				$data['sun'] = $postArray['sun'];
				$tid = M('taocan_content', 'ac_')->add($data);
				if ($tid > 0) {
					die(json_encode(array('result' => '0', 'msg' => '添加成功', 'tid' => $tid)));
				} else {
					die(json_encode(array('result' => '1', 'msg' => '添加失败')));
				}
			} else {
				$data = array();
				$data['name'] = $postArray['name'];
				$data['desc'] = $postArray['desc'];
				$data['shouye'] = $postArray['shouye'];
				$data['member_price'] = $postArray['member_price'];
				$data['price'] = $postArray['price'];
				$data['roomtype'] = $postArray['roomtypeid'];
				$data['shijianduan'] = $postArray['shijianduanid'];
				$data['yd_price'] = $postArray['yd_price'];

				$data['shichang'] = $postArray['shichang'];
				$data['mon'] = $postArray['mon'];
				$data['tue'] = $postArray['tue'];
				$data['wen'] = $postArray['wen'];
				$data['thu'] = $postArray['thu'];
				$data['fri'] = $postArray['fri'];
				$data['sat'] = $postArray['sat'];
				$data['sun'] = $postArray['sun'];
				if (M('taocan_content', 'ac_')->where(array('id' => $postArray['id']))->save($data)) {
					die(json_encode(array('result' => '0', 'msg' => '更新成功')));
				}
			}
		}
	}

	public function deltaocan() {
		if (IS_GET) {
			$id = I('get.id');
			if (M('taocan_content', 'ac_')->where(array('id' => $id))->delete()) {
				die(json_encode(array('result' => 0)));
			}
		}
	}

	public function deltiaokuan_s() {
		if (IS_GET) {
			$id = I('get.id');
			if (M('taocan_tiaokuan', 'ac_')->where(array('id' => $id))->delete()) {
				die(json_encode(array('result' => 0)));
			}
		}
	}
	protected function getKtvManger($row, $name) {
		$manger = M('ktvmanager', 'ydsjb_')->where(array('ktvid' => $row, 'status' => '1'))->find();
		return $manger[$name];
	}

	protected function getdistrictbycode($area_id) {
		$rs = M('area', 'ac_')->where(array('code' => $area_id))->find();
		return $rs['name'];
	}
	protected function getAdminName($uid = '') {
		if ($uid != '') {
			$uinfo = M('sjb_user')->where(array('id' => $uid))->find();
			return $uinfo['username'];
		} else {
			return '未修改过';
		}
	}

	public function addyzm() {
		if (IS_POST && IS_AJAX) {
			// echo I('post.ktvid');
			$ktvid = I('post.ktvid');
			$yzm = $this->getYZM();
			$result = M('yzm', 'ydsjb_')->add(array('yanzhengma' => $yzm, 'ktvid' => $ktvid, 'status' => 1));
			if ($result > 0) {
				die(json_encode(array('result' => 0, 'yzm' => $yzm)));
			} else {
				die(json_encode(array('result' => 1)));
			}

		}
	}
	protected function getYZM() {
		$yzm = rand(100000, 999999);
		return $yzm;
	}
}