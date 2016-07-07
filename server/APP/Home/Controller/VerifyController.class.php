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
			if ((date('d', time()) > 24 && date('d', time()) <= 31) || (date('d', time()) > 9 && date('d', time()) <= 15)) {
				// $startday = date('Y-m-24');
				// $endday = date('Y-m-d', strtotime($startday) + 3600 * 24 * 6);

				$startday = date('Y-m-25');
				$endday = date('Y-m-d', strtotime($startday) + 3600 * 24 * 7);
				$startday_1 = date('Y-m-25');
				$endday_1 = date('Y-m-d', strtotime($startday_1) + 3600 * 24 * 7);
				// echo M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'create_time' => array('BETWEEN', array($startday, $endday))))->count();
				// echo M()->getlastsql();die();
				if (M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'type' => 0, 'create_time' => array('BETWEEN', array($startday, $endday))))->count() < 1) {
					// if (M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'type' => 0, 'create_time' => array('BETWEEN', array($startday_1, $endday_1))))->count() < 1) {
					// echo M()->getlastsql();die();
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
		// $this->max_count = $this->max_count == null ? 0 : $this->max_count;
		$this->display();
	}

	public function lishi() {
		$history = M('songjiushenqing')->where(array('ktvid' => session('ktvid')))->select();
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
				// die();
				// $n_count = intval(I('post.need_count'));
				// if ($n_count == null || $n_count == '' || $n_count == 0) {
				// 	$this->error('请正确填写数量');
				// }
				// if ($this->checkMaxCount($n_count)) {
				// 	if ($SongJiuShenQing->add(array('ktvid' => session('ktvid'), 'count' => $n_count * 24, 'create_time' => date("Y-m-d H:i:s", time()), 'update_time' => date("Y-m-d H:i:s", time()))) > 0) {
				// 		$this->success('申请成功', 'MakeVerify');
				// 	}
				// } else {
				// 	$this->error('请求数量错误');
				// }
			} else {
				$this->error('申请不成功,请勿重复提交');
			}
		}
	}

	protected function getMaxCount() {
		$coupon_type_ids = M('coupon_type', 'ac_')->field('id')->select();
		$MaxCounts = array();
		foreach ($coupon_type_ids as $key => $value) {
			if ($value['id'] == 1) {
				$total_count = M('sj_record')->where(array('ktvid' => session('ktvid'), 'coupon_type' => $value['id']))->sum('count');
				$has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'sj_type' => $value['id']))->sum('count');
				if ($total_count - $has_hexiao_count > 24) {
					$MaxCounts[$value['id']] = array('name' => $this->getTypeName($value['id']), 'type' => $value['id'], 'count' => floor(($total_count - $has_hexiao_count) / 24));
				}
			} else {
				$total_count = M('sj_record')->where(array('ktvid' => session('ktvid'), 'coupon_type' => $value['id']))->sum('count');
				$has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'sj_type' => $value['id']))->sum('count');
//				if ($total_count - $has_hexiao_count > 24) {
				if ($MaxCounts[2] != null) {
					$MaxCounts[2]['count'] += $total_count - $has_hexiao_count;
				} else {
					$MaxCounts[2] = array('name' => $this->getTypeName($value['id']), 'type' => 2, 'count' => $total_count - $has_hexiao_count);
				}

//				}
			}

		}
		$MaxCounts[2]['count'] = floor($MaxCounts[2]['count'] / 24);
		return $MaxCounts;
		// $total_count = M('sj_record')->where(array('ktvid' => session('ktvid')))->sum('count');
		// $has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid')))->sum('count');
		// echo $total_count - $has_hexiao_count;

	}

	protected function getTypeName($id) {
		if ($id == 1) {
			return '铝瓶';
		} elseif ($id == 2) {
			return '罐装';
		} elseif ($id == 5) {
			return '罐装';
		} elseif ($id == 7) {
			return '罐装';
		} elseif ($id == 8) {
			return '罐装';
		} elseif ($id == 9) {
			return '罐装';
		}
	}

	protected function checkMaxCount($subcount, $type) {
		if ($type == 1) {
			$total_count = M('sj_record')->where(array('ktvid' => session('ktvid'), 'coupon_type' => $type))->sum('count');
			$has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'sj_type' => $type))->sum('count');
			if ($total_count - $has_hexiao_count >= 24 * $subcount) {
				return true;
			} else {
				return false;
			}
		} else {
			$total_count = M('sj_record')->where(array('ktvid' => session('ktvid'), 'coupon_type' => array('neq', 1)))->sum('count');
			$has_hexiao_count = M('songjiushenqing')->where(array('ktvid' => session('ktvid'), 'sj_type' => 2))->sum('count');
			if ($total_count - $has_hexiao_count >= 24 * $subcount) {
				return true;
			} else {
				return false;
			}
		}

	}
}