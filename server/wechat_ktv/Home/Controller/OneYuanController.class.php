<?php
namespace Home\Controller;
use Think\Controller;

class OneYuanController extends CommonController {
	public function __construct() {
		parent::__construct();
		$_contentType = 'application/json; charset=utf-8';
		header("Content-Type: $_contentType", true);
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Accept, Content-Type, X-KTV-Application-Name, X-KTV-Vendor-Name, X-KTV-Application-Platform, X-KTV-User-Token");
		$this->actid = 4;
	}

	protected function getActiveStatus() {
		$result_array = array('now' => time());
		$result_array['is_over'] = $this->is_over();
		return $result_array;
	}

	protected function is_over() {
		$oneyuan = M('oneyuan', 'ac_')->where(array('id' => $this->actid))->find();
		if ($oneyuan['status'] == 1) {
			return 0;
		} else {
			return 1;
		}
	}

	public function getzige() {
		if (IS_POST) {
			$uid = I('post.uid');
			if (empty($uid)) {
				$post_data = file_get_contents("php://input");
				$post_array = json_decode($post_data, true);
				$uid = $post_array['uid'] == null ? '' : $post_array['uid'];
			}
			$result_array = array('msg' => 'get zige success', 'result' => 0);
			$result_array['active_status'] = $this->getActiveStatus();
			$result_array['zige_info'] = $this->is_zhongjiang($uid);
		}

		die(json_encode($result_array, true));
	}
	protected function is_zhongjiang($uid) {
		$this->record($uid);
		M('oneyuan_event', 'ac_')->where('`status`=1 and `mobile`="" and TIMESTAMPDIFF(SECOND,`create_time`,NOW())>300 and actid=' . $this->actid)->save(array('status' => 0, 'userid' => '', 'actid' => $this->actid));
		// echo M()->getLastSql();
		$hasown = M('oneyuan_event', 'ac_')->where(array('userid' => $uid, 'actid' => $this->actid))->find();
		if ($hasown != null) {
			return array('zhongjiang' => 0, 'uid' => $uid);
		} else {
			$info = M('oneyuan_event', 'ac_')->where(array('status' => 0, 'actid' => $this->actid))->find();
			if ($info != null) {
				$zhong = rand(0, 20);
				if ($zhong == 0) {
					$result = M('oneyuan_event', 'ac_')->where(array('id' => $info['id'], 'actid' => $this->actid))->save(array('status' => 1, 'userid' => $uid, 'create_time' => date("Y-m-d H:i:s")));
					if ($result > 0) {
						return array('zhongjiang' => 1, 'uid' => $uid);
					} else {
						return array('zhongjiang' => 0, 'uid' => $uid);
					}
				} else {
					return array('zhongjiang' => 0, 'uid' => $uid);
				}
			} else {
				return array('zhongjiang' => 0, 'uid' => $uid);
			}
		}

	}
	protected function record($openid = '') {
		if ($openid != '') {
			if (M('user_oneyuan_record', 'ac_')->add(array('openid' => $openid, 'create_time' => time(), 'actid' => $this->actid)) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function AddMobile() {
		if (IS_POST) {
			$mobile = I('post.mobile');
			$uid = I('post.uid');
			if (empty($mobile) && empty($uid)) {
				$post_data = file_get_contents("php://input");
				$post_array = json_decode($post_data, true);
				$mobile = $post_array['mobile'];
				$uid = $post_array['uid'];
			}
			if (M('oneyuan_event', 'ac_')->where(array('actid' => $this->actid, 'mobile' => $mobile))->find() != null) {
				die(json_encode(array('msg' => 'Mobile Has Been Add', 'result' => 2)));
			}
			$info = M('oneyuan_event', 'ac_')->where(array('userid' => $uid, 'status' => 1, 'mobile' => '', 'actid' => $this->actid))->find();

			if ($info != null) {
				$result = M('oneyuan_event', 'ac_')->where(array('userid' => $uid, 'status' => 1, 'mobile' => '', 'actid' => $this->actid))->save(array('mobile' => $mobile));
				// echo M()->getLastSql();
				if ($result != 0) {
					$result_array = array('msg' => 'add Mobile success', 'result' => 0);
					$result_array['duijiangma'] = $info['zhongjiangma'];
					$result_array['active_status'] = $this->getActiveStatus();
					$result_array['moible'] = $mobile;
					$result_array['uid'] = $uid;
				} else {
					$result_array = array('msg' => 'has add mobile', 'result' => 0);
					$result_array['duijiangma'] = $info['zhongjiangma'];
					$result_array['active_status'] = $this->getActiveStatus();
					$result_array['moible'] = $mobile;
					$result_array['uid'] = $uid;
				}
			} else {
				$result_array = array('msg' => 'Mobile Error', 'result' => 1);
			}

			die(json_encode($result_array, true));
		}

	}

	public function getTime() {
		$result_array = array('msg' => 'get TimeInfo success', 'result' => 0);
		if (IS_POST) {
			$uid = I('post.uid');
			if (empty($uid) || $uid != '') {
				$post_data = file_get_contents("php://input");
				$post_array = json_decode($post_data, true);
				// var_dump($post_array);die();
				$uid = $post_array['uid'];
			}
			$result_array['uid'] = $uid;
			if ($uid != null) {
				$result_array['zhongjiang_status'] = $this->getzhongjiang_status($uid);
			}
		}
		$result_array['active_status'] = $this->getActiveStatus();
		die(json_encode($result_array, true));
	}
	protected function getzhongjiang_status($uid = '') {
		if ($uid != '') {
			$info = M('oneyuan_event', 'ac_')->where(array('userid' => $uid, 'status' => 1, 'actid' => $this->actid))->find();
			if ($info != null) {
				if ($info['mobile'] == '') {
					return array('status' => 1);
				} else {
					return array('status' => 2, 'duijiangma' => $info['zhongjiangma']);
				}
			} else {
				return array('status' => 0);
			}
		}
	}
	public function getTime_1() {
		$result_array = array('msg' => 'get TimeInfo success', 'result' => 0);
		$result_array['active_status'] = $this->getActiveStatus_1();
		die(json_encode($result_array, true));
	}

}