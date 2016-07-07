<?php
namespace Home\Controller;

use Think\Controller;

class CouponController extends CommonController {
	public function getCouponByConfirmOrder($userid = '', $type = 1) {
		if ($userid != '') {
			$coupon = array(
				'type' => $type,
				'userid' => $userid,
				'expire_time' => time() + 60 * 60 * 24 * 14,
				'status' => 0,
				'create_time' => date('Y-m-d H:i:s', time()),
				'available' => 1,
				'is_available' => 1);
			if (M('Coupon', 'ac_')->add($coupon) > 0) {
				return true;
			}
		}
	}
}