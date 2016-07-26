<?php
namespace SAdmin\Controller;
use Think\Controller;
class KtvOrderController extends CommonController {
	public function lists()
	{
//		$model = M('Order');
//		parent::lists('Order',1,1000);
		$this->display();
	}

	public function lists_ajax(){
		$table = 'ac_order';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'status', 'dt' => 1),
			array('db' => 'time', 'dt' => 2),
			array('db' => 'ktvid', 'dt' => 3),
			array('db' => 'userid', 'dt' =>4),
//			array('db' => 'address', 'dt' => 5),
//			array('db' => 'type', 'dt' =>6),
//			array('db' => 'type', 'dt' =>7,
//				'formatter' => function ($d,$row){
////					var_dump($row);die();
//					if($d == '2'){
//						return '商家版';
//					}elseif($d == '0'){
//						return "CallCenter";
//					}
//
//				}),
			array('db' => 'id', 'dt' =>5,'formatter'=>function($d,$row){
				return '<a href="'.U('update',array('id'=>$d)).'">查看</a>';
			}),

		);

		$this->ssp_lists_ajax($_POST,$table,$columns);

	}

	public function detail()
	{
		$koid = I('get.koid');
		if($koid == null){
			$this->redirect('KtvOrder/lists');
		}
		else{
			$orderinfo = M('Order')->where(array('id'=>$koid))->find();
			if($orderinfo!=NULL){
				$this->assign('orderinfo',$orderinfo);
				$this->display();
			}else{
				$this->redirect('KtvOrder/lists');
			}
		}
	}
}