<?php
namespace Admin\Controller;
use Think\Controller;

class TaocanController extends CommonController {
    public function datelists() {
        if (IS_GET) {
            $this->datalists = M('taocan_shijianduan_type', 'ac_')->where(array('status' => 1))->select();
            $this->display();
        }

    }
    public function addshijianduan() {
        if (IS_GET) {
            $this->display();
        }
        if (IS_POST) {
            $name = I('post.name');
            if (M('taocan_shijianduan_type', 'ac_')->add(array('name' => $name, 'status' => 1)) > 0) {
                $this->success('添加成功', 'datelists');
            }
        }
    }
    public function updateshijianduan(){
        if(IS_GET){
            $this->info = M('taocan_shijianduan_type','ac_')->where(array('id'=>I('get.id')))->find();
            $this->display();
        }else{
            if(M('taocan_shijianduan_type','ac_')->where(array('id'=>I('post.id')))->save(array('name'=>I('post.name')))){
                $this->success('更新成功','datelists');
            }
        }
    }

	public function addsjd() {
		if (IS_POST) {

			$starttime = I('post.start_time');
			$endtime = I('post.end_time');
			$kid = I('post.ktvid');
			$ciri = I('post.ciri');
			$shijianduan_type = I('post.shijianduan_type');
			if (I('post.shijianduanid') == '') {
				if (M('taocan_shijianduan', 'ac_')->add(array('ktvid' => $kid, 'starttime' => $starttime, 'endtime' => $endtime, 'shijianduantype' => $shijianduan_type, 'ciri' => $ciri)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			} else {
				if (M('taocan_shijianduan', 'ac_')->where(array('id' => I('post.shijianduanid')))->save(array('ktvid' => $kid, 'starttime' => $starttime, 'endtime' => $endtime, 'shijianduantype' => $shijianduan_type, 'ciri' => $ciri))) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			}

		}
	}

	public function addroomtype() {
		if (IS_POST) {
			$name = I('post.name');
			$shouye = I('post.shouye');
			$count = I('post.count');
			$des = I('post.des');
			$ktvid = I('post.ktvid');
			if (I('post.roomtypeid') == '') {
				if (M('taocan_roomtype', 'ac_')->add(array('ktvid' => $ktvid, 'name' => $name, 'des' => $des, 'shouye' => $shouye, 'count' => $count)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			} else {
				if (M('taocan_roomtype', 'ac_')->where(array('id' => I("post.roomtypeid")))->save(array('ktvid' => $ktvid, 'name' => $name, 'des' => $des, 'shouye' => $shouye, 'count' => $count)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			}

		}
	}

	public function addtiaokuan() {
		if (IS_POST) {
			$name = I('post.name');
			$ktvid = I('post.ktvid');
			if (I('post.tiaokuanid') == '') {
				if (M('taocan_tiaokuan', 'ac_')->add(array('ktvid' => $ktvid, 'name' => $name)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			} else {
				if (M('taocan_tiaokuan', 'ac_')->where(array('id' => I("post.tiaokuanid")))->save(array('ktvid' => $ktvid, 'name' => $name)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			}

		}
	}

	public function addtaocan_adds() {
		if (IS_POST) {
			$name = I('post.name');
			$roomtype = I('post.roomtype');
			$shijianduan = I('post.shijianduan');
			$price = I('post.price');
			$member_price = I('post.member_price');
			$yd_price = I('post.yd_price');
			$is_yd_price = I('post.is_yd_price');
			$shichang = I('post.shichang');
			$desc = I('post.desc');
			$ktvid = I('post.ktvid');
			$shouye = I('post.shouye');
			$mon = I('post.mon');
			$tue = I('post.tue');
			$wen = I('post.wen');
			$thu = I('post.thu');
			$fri = I('post.fri');
			$sat = I('post.sat');
			$sun = I('post.sun');
			foreach ($shijianduan as $key => $value) {
				M('taocan_content', 'ac_')->add(array(
					'ktvid' => $ktvid,
					'shijianduan' => $value,
					'roomtype' => $roomtype,
					'name' => $name,
					'price' => $price,
					'member_price' => $member_price,
					'yd_price' => $yd_price,
					'is_yd_price' => $is_yd_price,
					'shichang' => $shichang,
					'desc' => $desc,
					'shouye' => $shouye,
					'mon' => $mon,
					'tue' => $tue,
					'wen' => $wen,
					'thu' => $thu,
					'fri' => $fri,
					'sat' => $sat,
					'sun' => $sun,
				));
			}
			die(json_encode(array('result' => '0', 'msg' => 'success'), true));
		}
	}

	public function addtaocan() {
		if (IS_POST) {
			$name = I('post.name');
			$roomtype = I('post.roomtype');
			$shijianduan = I('post.shijianduan');
			$price = I('post.price');
			$member_price = I('post.member_price');
			$is_yd_price = I('post.is_yd_price');
			$yd_price = I('post.yd_price');
			$shichang = I('post.shichang');
			$desc = I('post.desc');
			$ktvid = I('post.ktvid');
			$shouye = I('post.shouye');
			$mon = I('post.mon');
			$tue = I('post.tue');
			$wen = I('post.wen');
			$thu = I('post.thu');
			$fri = I('post.fri');
			$sat = I('post.sat');
			$sun = I('post.sun');
			if (I('post.taocancontentid') == '') {
				if (M('taocan_content', 'ac_')->add(array(
					'ktvid' => $ktvid,
					'shijianduan' => $shijianduan,
					'roomtype' => $roomtype,
					'name' => $name,
					'price' => $price,
					'member_price' => $member_price,
					'is_yd_price' => $is_yd_price,
					'yd_price' => $yd_price,
					'shichang' => $shichang,
					'desc' => $desc,
					'shouye' => $shouye,
					'mon' => $mon,
					'tue' => $tue,
					'wen' => $wen,
					'thu' => $thu,
					'fri' => $fri,
					'sat' => $sat,
					'sun' => $sun,
				)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			} else {
				if (M('taocan_content', 'ac_')->where(array('id' => I('post.taocancontentid')))->save(array(
					'ktvid' => $ktvid,
					'shijianduan' => $shijianduan,
					'roomtype' => $roomtype,
					'name' => $name,
					'price' => $price,
					'member_price' => $member_price,
					'is_yd_price' => $is_yd_price,
					'yd_price' => $yd_price,
					'shichang' => $shichang,
					'desc' => $desc,
					'shouye' => $shouye,
					'mon' => $mon,
					'tue' => $tue,
					'wen' => $wen,
					'thu' => $thu,
					'fri' => $fri,
					'sat' => $sat,
					'sun' => $sun,
				)) > 0) {
					die(json_encode(array('result' => '0', 'msg' => 'success'), true));
				}
			}

		}
	}

	public function rometypelists() {

	}

	public function taocanlists() {
		$this->display();
	}

	public function enterTimelists() {
		$this->display();
	}
	public function totaltaocanlists() {
		$this->display();
	}
	public function date_lists_ajax() {

	}
	public function roomtype_lists_ajax() {

	}
	public function taocan_lists_ajax() {

	}
	public function enterTime_lists_ajax() {

	}
	public function totaltaocan_lists_ajax() {

	}
}