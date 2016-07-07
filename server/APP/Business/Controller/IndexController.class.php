<?php
namespace Business\Controller;
use Think\Controller;

class IndexController extends CommonController {
	public function index() {
//        $xktvList = M('xktv')->where()->select();
		//        var_dump($xktvList);
		//        $this->display();
	}

	public function _before_xktvlist() {

	}

	public function xktvlist() {
		if (IS_AJAX && IS_POST) {
			$table = 'ac_xktv';
			$primaryKey = 'id';
			$manger_bind_ktvs = M('ktvmanager', 'ydsjb_')->where(array('openid' => array('neq', ''), 'status' => '1'))->field('ktvid')->select();
			$manger_bind_ktvs_f = array();
			foreach ($manger_bind_ktvs as $key => $value) {
				$manger_bind_ktvs_f[] = $value['ktvid'];
			}
			$this->manger_bind_ktvs = $manger_bind_ktvs_f;
			$columns = array(
				array('db' => 'id', 'dt' => 'id'),
				array('db' => 'xktvid', 'dt' => 'xktvid'),
				array('db' => 'name', 'dt' => 'name'),
				array('db' => 'telephone', 'dt' => 'telephone',
					'formatter' => function ($d, $row) {
						return $row['pretelephone'] . $d;
					}),
				array('db' => 'area_id', 'dt' => 'area_id',
					'formatter' => function ($d, $row) {
						return "<span title='" . $d . "'>" . $row['district'] . "</span>";
					}),
				array('db' => 'district', 'dt' => 'district'),
				array('db' => 'pretelephone', 'dt' => 'pretelephone'),
				array('db' => 'roomtotal', 'dt' => 'roomtotal'),
				array('db' => 'type', 'dt' => 'type',
					'formatter' => function ($d, $row) {
						if ($d == '2') {
							return '商家版';
						} elseif ($d == '0') {
							return "CallCenter";
						}

					}),
				array('db' => 'openhours', 'dt' => 'openhours'),
				array('db' => 'address', 'dt' => 'address'),
				array('db' => 'type', 'dt' => 'managername',
					'formatter' => function ($d, $row) {
						return $this->getKtvManger($row['id'], 'name');
					}),
				array('db' => 'type', 'dt' => 'managertel',
					'formatter' => function ($d, $row) {
						return $this->getKtvManger($row['id'], 'phone');
					}),
				array('db' => 'id', 'dt' => 'do', 'formatter' => function ($d, $row) {
					return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
				}),
				array('db' => 'id', 'dt' => 'bindstatus',
					"formatter" => function ($d, $row) {
//                        var_dump($this->manger_bind_ktvs);
						if (in_array($d, $this->manger_bind_ktvs)) {
							return '经理已经绑定';
						} else {
							return '';
						}
					}),
			);

			$this->ssp_lists_ajax($_POST, $table, $columns);
		} else {
			$this->display('XktvList');
		}

	}

	public function xktvlistdetail() {
		if (IS_AJAX && IS_POST) {
			$table = 'ac_xktv';
			$primaryKey = 'id';
			$manger_bind_ktvs = M('ktvmanager', 'ydsjb_')->where(array('openid' => array('neq', ''), 'status' => '1'))->field('ktvid')->select();
			$manger_bind_ktvs_f = array();
			foreach ($manger_bind_ktvs as $key => $value) {
				$manger_bind_ktvs_f[] = $value['ktvid'];
			}
			$this->manger_bind_ktvs = $manger_bind_ktvs_f;
			$columns = array(
				array('db' => 'id', 'dt' => 'id'),
				array('db' => 'xktvid', 'dt' => 'xktvid'),
				array('db' => 'name', 'dt' => 'name'),
				array('db' => 'telephone', 'dt' => 'telephone',
					'formatter' => function ($d, $row) {
						return $row['pretelephone'] . $d;
					}),
				array('db' => 'area_id', 'dt' => 'area_id',
					'formatter' => function ($d, $row) {
						return "<span title='" . $d . "'>" . $row['district'] . "</span>";
					}),
				array('db' => 'district', 'dt' => 'district'),
				array('db' => 'pretelephone', 'dt' => 'pretelephone'),
				array('db' => 'roomtotal', 'dt' => 'roomtotal'),
				// { "data": "desc",title:"desc"},
				// { "data": "spic1",title:"spic1"},
				// { "data": "bpic1",title:"bpic1"},
				// { "data": "spic2",title:"spic2"},
				// { "data": "bpic2",title:"bpic2"},
				// { "data": "spic3",title:"spic3"},
				// { "data": "bpic3",title:"bpic3"},
				// { "data": "spic4",title:"spic4"},
				// { "data": "bpic4",title:"bpic4"},
				// { "data": "spic5",title:"spic5"},
				// { "data": "bpic5",title:"bpic5"},
				// { "data": "bathroom",title:"bathroom"},
				// { "data": "parking",title:"parking"},
				// { "data": "WIFI",title:"WIFI"},
				// { "data": "Themerooms",title:"Themerooms"},
				// { "data": "XKTV",title:"XKTV"},
				// { "data": "WirelessMicrophones",title:"WirelessMicrophones"},

				array('db' => 'description', 'dt' => 'desc'),
				array('db' => 'spic1', 'dt' => 'spic1',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bpic1', 'dt' => 'bpic1',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'spic2', 'dt' => 'spic2',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bpic2', 'dt' => 'bpic2',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'spic3', 'dt' => 'spic3',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bpic3', 'dt' => 'bpic3',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'spic4', 'dt' => 'spic4',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bpic4', 'dt' => 'bpic4',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'spic5', 'dt' => 'spic5',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bpic5', 'dt' => 'bpic5',
					'formatter' => function ($d, $row) {
						if ($d != '') {
							return 'http://letsktv.chinacloudapp.cn/uploads/room/' . $d;
						}
					}),
				array('db' => 'bathroom', 'dt' => 'bathroom',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'parking', 'dt' => 'parking',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'WIFI', 'dt' => 'WIFI',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'Themerooms', 'dt' => 'Themerooms',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'XKTV', 'dt' => 'XKTV',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'WirelessMicrophones', 'dt' => 'WirelessMicrophones',
					'formatter' => function ($d, $row) {
						if ($d == 0) {
							return '无';
						} elseif ($d == 1) {
							return "有";
						}

					}),
				array('db' => 'type', 'dt' => 'type',
					'formatter' => function ($d, $row) {
						if ($d == '2') {
							return '商家版';
						} elseif ($d == '0') {
							return "CallCenter";
						}

					}),
				array('db' => 'openhours', 'dt' => 'openhours'),
				array('db' => 'address', 'dt' => 'address'),
				array('db' => 'type', 'dt' => 'managername',
					'formatter' => function ($d, $row) {
						return $this->getKtvManger($row['id'], 'name');
					}),
				array('db' => 'type', 'dt' => 'managertel',
					'formatter' => function ($d, $row) {
						return $this->getKtvManger($row['id'], 'phone');
					}),
				array('db' => 'id', 'dt' => 'do', 'formatter' => function ($d, $row) {
					return '<a href="' . U('update', array('id' => $d)) . '">查看</a>';
				}),
				array('db' => 'id', 'dt' => 'bindstatus',
					"formatter" => function ($d, $row) {
//                        var_dump($this->manger_bind_ktvs);
						if (in_array($d, $this->manger_bind_ktvs)) {
							return '经理已经绑定';
						} else {
							return '';
						}
					}),
			);

			$this->ssp_lists_ajax($_POST, $table, $columns);
		} else {
			$this->display('xktvlistdetail');
		}

	}

	public function BookingStatus() {
		$this->display();
	}

	private function getKtvManger($row, $name) {
		$manger = M('ktvmanager', 'ydsjb_')->where(array('ktvid' => $row, 'status' => '1'))->find();
		return $manger[$name];
	}

//    private function getKtvManger($row,$name){
	//        $manger = M('ktvmanager','ydsjb_')->where(array('ktvid'=>$row,'status'=>'1'))->find();
	//        return $manger[$name];
	//    }
}