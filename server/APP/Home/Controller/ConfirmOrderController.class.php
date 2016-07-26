<?php
namespace Home\Controller;

use Think\Controller;

class ConfirmOrderController extends CommonController {
	public function __construct() {
		parent::__construct();
		$session_get_action = array('index', 'index_history', 'QRyzm');
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
	public function testopenid() {
		session(null);
		if (IS_GET && I('get.openid') != '') {
			$openid = I('get.openid');
			// $session_get_action = array('bind', 'bindf', 'bindm', 'ktvmanage');
			// if (in_array(ACTION_NAME, $session_get_action)) {
			// 	// echo 'session destroy';
			// 	// session('[destroy]');
			// 	session(null);
			// }
			// echo 'sdfasdf'.$_SESSION['openid'].'121212121';
			if (!session('?openid')) {
				// $wc = new WeChatController();
				// $token = $wc->getOauthAccessToken();
				// $openid = $token['openid'];
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
				// echo $openid;
				session('openid', $openid);
			}
		}
		var_dump($_SESSION);
	}

	public function index() {
		$this->redirect('scanOrderCode');
	}

	public function scanOrderCode() {
		if (!session('?openid') || !session('?role')) {

			$this->error('非法请求');
		}
		$this->display();
	}
	public function getResultBykey() {
		if (IS_AJAX && IS_POST) {
			$key = I('post.key');
			$order_qrcode_info = M('qr_service', 'ydsjb_')->where(array('key' => $key))->find();
			if ($order_qrcode_info != null) {
				$order_id_info = json_decode($order_qrcode_info['content'], true);
				if ($order_id_info['type'] == 'order') {
					$order_info = M('order', 'ac_')->where(array('id' => $order_id_info['order_id']))->field('id,time,roomtype,ktvid,userid,starttime,endtime,members,couponid,roomtypeid,taocantype,taocanid,price')->find();
					if ($order_info != null) {
						if ($this->getKtvName($order_info['ktvid'], 'id') == session('ktvid')) {
							if ($order_info['taocantype'] == 0) {
								$order_info['roomtype'] = $this->getTaocanInfo($order_info['taocanid']);
								$order_info['taocan_desc'] = $this->getTaocanInfo($order_info['taocanid'], 'description');
							} else {
								$order_info['roomtype'] = $this->getRoomType($order_info['roomtypeid']);
							}
							$order_info['time'] = date("Y-m-d H:i:s", $order_info['time']);
							$order_info['starttime'] = date("Y-m-d H:i:s", $order_info['starttime']);
							$order_info['endtime'] = date("Y-m-d H:i:s", $order_info['endtime']);
							$order_info['ktvid'] = $this->getKtvName($order_info['ktvid']);
							$order_info['userinfo'] = $this->getUserInfo($order_info['userid']);
							$result_array['msg'] = 'scan success';
							$result_array['result'] = '0';
							$result_array['order_data'] = $order_info;
							if ($order_info['couponid'] > 0) {
								$coupon_info = M('coupon', 'ac_')->where(array('id' => $order_info['couponid']))->find();
								if ($coupon_info != NULL) {
									$coupon_info_type = M('coupon_type', 'ac_')->where(array('id' => $coupon_info['type']))->find();
									$result_array['coupon_info_data'] = array('name' => $coupon_info_type['name'], 'count' => $coupon_info_type['count']);
								}
							} else {
								$result_array['coupon_info_data'] = array('name' => '无', 'count' => 0);
							}
						} else {
							$result_array['msg'] = 'KTV信息错误';
							$result_array['result'] = 1;
						}
						die(json_encode($result_array));
					} else {
						$result_array['msg'] = '订单不存在';
						$result_array['result'] = 1;
						die(json_encode($result_array));
					}

                } elseif ($order_id_info['type'] == 'jaycn' && session('ktvid') == 229) {
                    $order_info = M('jaycn_event_order', 'ac_')->where(array('id' => $order_id_info['orderid']))->find();
                    $roominfo = M('jaycn_event','ac_')->where(array('id'=>$order_info['roomid']))->find();
                    $roominfo['dates'] = $roominfo['date'] == 23 ? '2016-07-23' : '2016-07-24';
                    if ($order_info != null) {
                        $result_array['msg'] = 'scan success';
                        $result_array['result'] = '0';
                        $result_array['order_info']=$order_info;
                        $result_array['room_info']=$roominfo;
                        $result_array['order_type']='jaycn';
                        die(json_encode($result_array));
                    }
                }

			}
		}
	}

	// public function getResultByQrUrl() {
	// 	if (IS_POST) {
	// 		// echo 'sdf';
	// 		$url = I('post.url');
	// 		// echo $url;
	// 		$ss = explode('/wechatshangjia/', $url);
	// 		// var_dump($ss);
	// 		$order_qrcode_info = M('qr_service', 'ydsjb_')->where(array('file' => $ss[1]))->find();
	// 		if ($order_qrcode_info != null) {
	// 			$order_id_info = json_decode($order_qrcode_info['content'], true);
	// 			if ($order_id_info['type'] == 'order') {
	// 				$order_info = M('order', 'ac_')->where(array('id' => $order_id_info['order_id']))->field('id,time,roomtype,ktvid,userid,starttime,endtime,members,couponid,roomtypeid,taocantype,taocanid,price')->find();
	// 				if ($order_info != null) {
	// 					if ($this->getKtvName($order_info['ktvid'], 'id') == session('ktvid')) {
	// 						if ($order_info['taocantype'] == 0) {
	// 							$order_info['roomtype'] = $this->getTaocanInfo($order_info['taocanid']);
	// 							$order_info['taocan_desc'] = $this->getTaocanInfo($order_info['taocanid'], 'description');
	// 						} else {
	// 							$order_info['roomtype'] = $this->getRoomType($order_info['roomtypeid']);
	// 						}
	// 						$order_info['time'] = date("Y-m-d H:i:s", $order_info['time']);
	// 						$order_info['starttime'] = date("Y-m-d H:i:s", $order_info['starttime']);
	// 						$order_info['endtime'] = date("Y-m-d H:i:s", $order_info['endtime']);
	// 						$order_info['ktvid'] = $this->getKtvName($order_info['ktvid']);
	// 						$order_info['userinfo'] = $this->getUserInfo($order_info['userid']);
	// 						$result_array['msg'] = 'scan success';
	// 						$result_array['result'] = '0';
	// 						$result_array['order_data'] = $order_info;
	// 						if ($order_info['couponid'] > 0) {
	// 							$coupon_info = M('coupon', 'ac_')->where(array('id' => $order_info['couponid']))->find();
	// 							if ($coupon_info != NULL) {
	// 								$coupon_info_type = M('coupon_type', 'ac_')->where(array('id' => $coupon_info['type']))->find();
	// 								$result_array['coupon_info_data'] = array('name' => $coupon_info_type['name'], 'count' => $coupon_info_type['count']);
	// 							}
	// 						} else {
	// 							$result_array['coupon_info_data'] = array('name' => '无', 'count' => 0);
	// 						}
	// 					} else {
	// 						$result_array['msg'] = 'KTV信息错误';
	// 						$result_array['result'] = 1;
	// 					}
	// 					die(json_encode($result_array));
	// 				} else {
	// 					$result_array['msg'] = '订单不存在';
	// 					$result_array['result'] = 1;
	// 					die(json_encode($result_array));
	// 				}

	// 			}

	// 		}
	// 	}
	// }

	public function order_Confirm() {
		if (IS_POST && IS_AJAX) {
			$orderid = I('post.orderid');
			$result_array = array();
			$order_info = M('order', 'ac_')->where(array('id' => $orderid))->find();
			if ($order_info['starttime'] - time() < 3600 && $order_info['starttime'] - time() > -3600) {
				if ($order_info['status'] == 3) {
					if (M('order', 'ac_')->where(array('id' => $orderid))->save(array('status' => 5, 'update_time' => date('Y-m-d H:i:s'))) != false) {
						if ($order_info['couponid'] > 0) {
							$coupon_info = M('coupon', 'ac_')->where(array('id' => $order_info['couponid']))->find();
							if ($coupon_info['status'] == 0 && $coupon_info['is_available'] == 0) {
								if (M('coupon', 'ac_')->where(array('id' => $order_info['couponid']))->save(array('status' => 1, 'update_time' => date('Y-m-d H:i:s'))) != false) {
									$result_array['coupon_msg'] = 'coupon Confirm Success';
									$sj_record_info = array();
									$sj_record_info['couponid'] = $order_info['couponid'];
									$sj_record_info['emp_openid'] = session('openid');
									$sj_record_info['userid'] = $coupon_info['userid'];
									$sj_record_info['status'] = 1;
									$sj_record_info['count'] = $this->getCouponCount($coupon_info['type'], 'count');
									$sj_record_info['ktvid'] = $this->getKtvName($order_info['ktvid'], 'id');
									$sj_record_info['coupon_type'] = $coupon_info['type'];
									$sj_record_info['create_time'] = date('Y-m-d H:i:s');
									if (M('sj_record')->add($sj_record_info) > 0) {
										$result_array['sj_record_msg'] = 'add record success';
										$this->sendmsg($coupon_info['userid'],
											'您好，您已经成功兑换' . $this->getCouponCount($coupon_info['type'], 'name') . '，
兑换时间：' . date('Y-m-d H:i') . '，
如有任何疑问，请联系客服电话400-650-7351');
									} else {
										$result_array['sj_record_msg'] = 'add record failed';
									}

								}
							} else {
								$result_array['coupon_msg'] = 'coupon Info Wrong';
							}
						} else {
							$result_array['coupon_msg'] = 'No coupon Info';
						}
						$result_array['result'] = 0;
						$result_array['msg'] = 'Order Confirm Success';
						$CouponContr = new CouponController();
						//送新的酒券 由6瓶改成3瓶
						if ($CouponContr->getCouponByConfirmOrder($order_info['userid'], 31)) {
							$result_array['new_coupon'] = array('msg' => 'Get Success New Coupon', 'result' => 0);
						}
						if ($order_info['roomtypeid'] == 0) {
							$roomtype_name = $this->getTaocanInfo($order_info['taocanid']);
						} else {
							$roomtype_name = $this->getRoomType($order_info['roomtypeid']);

						}
						$this->sendmsg($order_info['userid'], '您好，您预订的' . $this->getKtvName($order_info['ktvid']) . '开始时间：' . date("Y-m-d H:i:s", $order_info['starttime']) . $roomtype_name . '确认成功。为了感谢您的支持，夜点赠送您一张新的兑酒券，次日可使用。祝您欢唱愉快！');
						$spr_msg = $this->sendSprOpenid($order_info['userid'], $order_info['time'], time());
						$result_array['spr_msg'] = array('info' => $spr_msg, 'userid' => $order_info['userid'], 'ordertime' => $order_info['time'], 'confirm' => time());
					} else {
						$result_array['result'] = 1;
						$result_array['msg'] = 'Order Update Error';
					}

				} else {
					$result_array['result'] = 1;
					$result_array['msg'] = 'Order Status Error';
				}
			} else {
				$result_array['msg'] = 'Time error';
				$result_array['result'] = 1;
			}

			die(json_encode($result_array, true));
		}
	}
	protected function getCouponCount($typeid = '', $key = 'count') {
		if ($typeid != '') {
			$couponType = M('coupon_type', 'ac_')->where(array('id' => $typeid))->find();
			if ($couponType != null) {
				return $couponType[$key];
			}
		}
	}

	protected function getTaocanInfo($id = '', $key = 'roomtype') {
		if ($id != '') {
			$taocaninfo = M('taocan_content', 'ac_')
				->join('left join __TAOCAN_ROOMTYPE__ on __TAOCAN_ROOMTYPE__.id=__TAOCAN_CONTENT__.roomtype')
				->where(array('ac_taocan_content.id' => $id))->field('ac_taocan_roomtype.name as roomtype,ac_taocan_content.desc as description')->find();
			if ($taocaninfo != null) {
				return $taocaninfo[$key];
			}
		}
	}

	protected function getRoomType($typeid = '') {
		if ($typeid != '') {
			// switch ($typeid) {
			// case '1':
			// 	return '大包';
			// 	break;
			// case '2':
			// 	return '中包';
			// 	break;
			// case '3':
			// 	return '小包';
			// 	break;
			// default:
			// 	return 0;
			// 	break;
			// }
			$roomtypeinfo = M('taocan_roomtype', 'ac_')->where(array('id' => $typeid))->field('name,id')->find();
			if ($roomtypeinfo != null) {
				return $roomtypeinfo['name'];
			}
		}
	}

	protected function getKtvName($ktvid = '', $name = 'name') {
		if ($ktvid != '') {
			$ktvinfo = M('xktv', 'ac_')->where(array('xktvid' => $ktvid))->find();
			if ($ktvinfo != null) {
				return $ktvinfo[$name];
			}
		}
	}

	protected function getUserInfo($userid = '') {
		if ($userid != '') {
			$userinfo = M('platform_user', 'ac_')->where(array('id' => $userid))->find();
			return array('display_name' => $userinfo['display_name'], 'mobile' => $userinfo['mobile']);
		}
	}
//
	//	public function sendp(){
	//		$orders = M('order','ac_')->where(array('status'=>5,'time'=>array('gt',1464796800)))->select();
	//		foreach($orders as $k => $v){
	//			echo $orders[$k]['userid'].'----'.$orders[$k]['time'].'-----'.strtotime($orders[$k]['update_time']);
	//			echo '<br >';
	//			$result = $this->sendSprOpenid($orders[$k]['userid'],$orders[$k]['time'],strtotime($orders[$k]['update_time']));
	//			var_dump($result);
	//		}
	//	}

	protected function sendSprOpenid($userid = '', $ordertime = '', $confirm = '') {
//		return array('info'=>'test');
		if ($userid != '' && $ordertime != '' && $confirm != '') {
			$ordercount = M('order', 'ac_')->where(array('userid' => $userid, 'status' => 5))->Count('id');
			if ($ordercount == 1) {
				$userinfo = M('platform_user', 'ac_')->where(array('id' => $userid))->find();

				if ($userinfo != null) {
					$openid = $userinfo['openid'];
					return array('info' => $this->http_get_spr($openid, $ordertime, $confirm), 'openid' => $openid);
				} else {
					return array('info' => 'no openid info');
				}
			} else {
				return array('count' => $ordercount);
			}

		}
	}

	protected function http_get_spr($openid, $ordertime, $confirm) {
		$url = C('server_host') . '/letsktv_biz/promo_girls/_api.php?openid=' . $openid . '&order=' . $ordertime . '&confirm=' . $confirm;
//		echo $url;
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 1);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		// print_r($data);
		return $data;
	}

	protected function sendmsg($uid, $content) {
		$url = C('server_host') . '/wechat_ktv/Home/WeChatApi/sendMsgToUid';
		return $this->http_post($url, array('uid' => $uid, 'msg' => $content));
	}

    private function http_post($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    public function jaycn_Confirm(){
        if(IS_POST){
            $orderid = I('post.jaycnid');
            $status = M('jaycn_event_order','ac_')->where(array('id'=>$orderid))->save(array('status'=>1));
            $order_info = M('jaycn_event_order','ac_')->where(array('id'=>$orderid))->find();
            $userinfo = M('platform_user','ac_')->where(array('openid'=>$order_info['openid']))->find();
            if($status>0){
                M('coupon','ac_')->add(array('type'=>31,'userid'=>$userinfo['id'],'status'=>0,'create_time'=>date('Y-m-d H:i:s'),'expire_time'=>(time()+60*24*60*14)));
                $this->sendmsg($userinfo['id'],
                    '看不见你的笑我怎么睡的着～

K歌达人小夜欢迎您来到周董演唱会前的杰迷K房派对！

感谢您对夜点的支持！一张免费夜点兑酒券已经放入您的账户，请到【个人中心】－【我的兑酒券】中查看！

祝您玩的开心！');
                die(json_encode(array('result'=>0,'msg'=>'queren OK'),true));
            }else{
                die(json_encode(array('result'=>1,'msg'=>'queren OK'),true));
            }

        }
    }
}