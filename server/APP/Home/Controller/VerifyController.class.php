<?php
namespace Home\Controller;

use Think\Controller;

class VerifyController extends CommonController {
	public function __construct() {
		parent::__construct();
		$session_get_action = array('index');
		if (in_array(ACTION_NAME, $session_get_action)) {
			session(null);
		}
		if (!session('?openid')) {
			$wc = new WeChatController();
			$token = $wc->getOauthAccessToken();
			$openid = $token['openid'];
			$tmpmanager = M('ktvmanager')->where(array('openid' => $openid, 'status' => '1'))->find();
			if ($tmpmanager != NULL) {
				session('role', 'manager');
				session('ktvid', $tmpmanager['ktvid']);
			}
			$tmpempl = M('ktvemp')->where(array('openid' => $openid, 'status' => '1'))->find();
			if ($tmpempl != NULL) {
				session('role', 'emplyee');
				session('ktvid', $tmpempl['ktvid']);
			}
			session('openid', $openid);
		}
	}

	public function index() {
		if (session('role') == 'manager') {
			$this->redirect('MakeVerify');
		} elseif (session('role') == 'emplyee') {
			$this->error('请使用管理员微信登录');
		}{
			$this->error('非法请求');
		}
	}

	public function MakeVerify() {
		$this->total_song_count = M('sj_record')->where(array('ktvid' => session('ktvid')))->sum('count');
		$this->total_song_count = $this->total_song_count == null ? 0 : $this->total_song_count;
		$this->has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'status' => 2))->sum('count');
		$this->has_hexiao_count = $this->has_hexiao_count == null ? 0 : $this->has_hexiao_count;
		$this->has_hexiao_xiang = floor($this->has_hexiao_count / 24);
		$this->hexiaozhong_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'status' => array('in', array(0, 1))))->sum('count');
		$this->hexiaozhong_count = $this->hexiaozhong_count == null ? 0 : $this->hexiaozhong_count;
		$this->hexiaozhong_xiang = floor($this->hexiaozhong_count / 24);
		$this->weihexiao_count = $this->total_song_count - $this->has_hexiao_count - $this->hexiaozhong_count;
		$this->can_submit = $this->checkSubMit($this->weihexiao_count);
		$this->display();
	}

	protected function checkSubMit($weihexiao_count) {

		if ($weihexiao_count > 24) {
			if (date('d', time()) > 19 && date('d', time()) <= 25) {
				$startday = date('Y-m-20');
				$endday = date('Y-m-d', strtotime($startday) + 3600 * 24 * 7);
				if (M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'type' => 0, 'create_time' => array('BETWEEN', array($startday, $endday))))->count() < 1) {
					return true;
				} else {
					return false;
				}
			} elseif (date('d', time()) > 4 && date('d', time()) <= 10) {
				$startday = date('Y-m-5');
				$endday = date('Y-m-d', strtotime($startday) + 3600 * 24 * 7);
				if (M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'type' => 0, 'create_time' => array('BETWEEN', array($startday, $endday))))->count() < 1) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	public function shenqing() {
		$this->max_counts = $this->getMaxCount();
		$this->display();
	}

	public function lishi() {
		$history = M('songjiushenqing')->where(array('ktvid' => session('ktvid')))->field('sj_type,count,create_time,status')->select();
		if ($history != null) {
			foreach ($history as $key => $value) {
				$history[$key]['type'] = $this->getTypeName($value['sj_type']);
			}
			$this->lists = $history;
		}
		$this->display();
	}

	public function hexiaosub() {
		if (IS_POST) {
			$SongJiuShenQing = M('songjiushenqing');
			if ($SongJiuShenQing->autoCheckToken($_POST)) {
				foreach ($_POST as $key => $value) {
					if (preg_match('/^need_count_/', $key)) {
						$ss = explode('need_count_', $key);
						$sj_type = $ss[1];
						$n_count = $_POST[$key];
						if ($n_count == null || $n_count == '' || $n_count == 0) {
							$this->error('请正确填写数量');
						}
						if ($this->checkMaxCount($n_count, $sj_type)) {

						} else {
							$this->error('请求数量错误');
						}
					}
				}
				foreach ($_POST as $key => $value) {
					if (preg_match('/^need_count_/', $key)) {
						$ss = explode('need_count_', $key);
						$sj_type = $ss[1];
						$n_count = $_POST[$key];
						if ($SongJiuShenQing->add(array('ktvid' => session('ktvid'), 'count' => $n_count * 24, 'create_time' => date("Y-m-d H:i:s", time()), 'update_time' => date("Y-m-d H:i:s", time()), 'sj_type' => $sj_type)) > 0) {
							// $this->success('申请成功', 'MakeVerify');
						} else {
							$this->error('申请' . $sj_type . '失败', 'MakeVerify');
						}
					}
				}
				$this->success('申请成功', 'MakeVerify');
			} else {
				$this->error('申请不成功,请勿重复提交');
			}
		}
	}

	protected function getMaxCount() {
		$ktvid = session('ktvid');
		$total_MaxCount = M()->query("select
			sum(ydsjb_sj_record.`count`) as count,
			ac_beer_type.id as beer_type_id,
			ac_beer_type.name as beer_name
			from `ydsjb_sj_record`
			left join `ac_coupon_type` on `ac_coupon_type`.id=`ydsjb_sj_record`.`coupon_type`
			left join `ac_beer_type` on `ac_beer_type`.id=ac_coupon_type.`beer_type`
			where ktvid=" . $ktvid . "  group by ac_beer_type.id");

		$total_has_hexiao_count = M()->query("select sj_type as type
											,sum(count) as total_count
											from `ydsjb_songjiushenqing` where ktvid=" . $ktvid . " group by sj_type");
		$has_hexiao_count_arr = array();
		foreach ($total_has_hexiao_count as $key => $value) {
			$has_hexiao_count_arr[$value['type']] = $value['total_count'];
		}
		// var_dump($total_MaxCount);
		// var_dump($has_hexiao_count_arr);
		$MaxCounts = array();
		foreach ($total_MaxCount as $key => $value) {
			// echo $value['beer_type_id'] . '--';
			if ($has_hexiao_count_arr[$value['beer_type_id']] != null) {
				$now_count = $value['count'] - $has_hexiao_count_arr[$value['beer_type_id']];
			} else {
				$now_count = $value['count'];
			}
			if ($now_count > 24) {
				$MaxCounts[$value['beer_type_id']] = array('count' => floor($now_count / 24), 'name' => $value['beer_name'], 'type' => $value['beer_type_id']);
			}

		}
		// var_dump($MaxCounts);die();
		return $MaxCounts;

	}

	protected function getTypeName($id) {
		// $coupontype = M('coupon_type', 'ac_')->where(array('id' => $id))->find();
		$beerType = M('beer_type', 'ac_')->where(array('id' => $id))->find();
		return $beerType;
	}

	protected function checkMaxCount($subcount, $type) {
		$ktvid = session('ktvid');
		$total_MaxCount = M()->query("select
			sum(ydsjb_sj_record.`count`) as count,
			ac_beer_type.id as beer_type_id,
			ac_beer_type.name as beer_name
			from `ydsjb_sj_record`
			left join `ac_coupon_type` on `ac_coupon_type`.id=`ydsjb_sj_record`.`coupon_type`
			left join `ac_beer_type` on `ac_beer_type`.id=ac_coupon_type.`beer_type`
			where ktvid=" . $ktvid . " and `ac_beer_type`.id=" . $type . "  group by ac_beer_type.id");

		$total_has_hexiao_count = M()->query("select sj_type as type
											,sum(count) as total_count
											from `ydsjb_songjiushenqing` where ktvid=" . $ktvid . " and sj_type=" . $type . " group by sj_type");
		if (count($total_has_hexiao_count) > 0) {
			if ($total_MaxCount[0]['count'] - $total_has_hexiao_count[0]['total_count'] >= 24 * $subcount) {
				return true;
			} else {
				return false;
			}
		} else {
			if ($total_MaxCount[0]['count'] >= 24 * $subcount) {
				return true;
			} else {
				return false;
			}
		}
	}
}