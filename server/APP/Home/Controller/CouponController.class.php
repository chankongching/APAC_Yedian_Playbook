<?php
namespace Home\Controller;

use Think\Controller;

class CouponController extends CommonController
{
    public function getCouponByConfirmOrder($userid = '', $type = 1)
    {
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

    public function getShareCouponByConfirmOrder($userid = 0, $orderid = 0)
    {
        if ($userid != 0 && $orderid != 0) {
            $hash_key = $this->createKey(32);
            $result = M('CouponShare', 'ac_')->add(array('userid' => $userid, 'orderid' => $orderid, 'hash_url' => $hash_key, 'create_time' => date('Y-m-d H:i:s'), 'create_user_id' => session('openid')));
            if ($result > 0) {
                if ($this->getShareCouponHashKey($hash_key, $result, $orderid)) {
                    return array('result' => 0, 'share_coupon_id' => $result, 'msg' => 'get success!');
                } else {
                    return array('result' => 1, 'msg' => 'gett share coupon failed!');
                }

            } else {
                return array('result' => 1, 'msg' => 'get share coupon failed!');
            }
        } else {
            return array('result' => 1, 'msg' => 'err params');
        }
    }

    private function getShareCouponHashKey($hash_key, $sharecouponid, $orderid)
    {
        $result = M('url_hash', 'ac_')->add(array(
            'hash_key' => $hash_key,
            'content' => json_encode(array('orderid' => intval($orderid), 'sharecouponid' => intval($sharecouponid)), true),
            'type' => 1,
            'create_time' => date('Y-m-d H:i:s')
        ));
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function createKey($len = 32)
    {
        // 校验提交的长度是否合法
        if (!is_numeric($len) || ($len > 32) || ($len < 16)) {
            return;
        }
        // 获取当前时间的微秒
        list($u, $s) = explode(' ', microtime());
        $time = (float)$u + (float)$s;
        // 产生一个随机数
        $rand_num = rand(100000, 999999);
        $rand_num = rand($rand_num, $time);
        mt_srand($rand_num);
        $rand_num = mt_rand();
        // 产生SessionID
        $sess_id = md5(md5($time) . md5($rand_num));
        // 截取指定需要长度的SessionID
        $sess_id = substr($sess_id, 0, $len);
        return $sess_id;
    }
}