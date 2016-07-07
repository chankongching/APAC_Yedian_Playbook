<?php
namespace Admin\Controller;
use Think\Controller;
class GiftController extends CommonController {
	public function lists()
	{
//		$model = M('Gifts');
//		$this->_list($model);
		$this->display();
	}
	public function lists_ajax(){
		$table = 'ac_gifts';
		$primaryKey = 'id';
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$columns = array(
			array('db' => 'product_id', 'dt' => 0),
			array('db' => 'productsale_name', 'dt' => 1),
			array('db' => 'productsale_points', 'dt' => 2),
			array('db' => 'productsale_issell', 'dt' => 3,
				'formatter'=>function($d,$row){
					if($d == '0'){
						return '在售';
					}elseif($d == '1'){
						return '下架';
					}
				}),
//			array('db' => 'openhours', 'dt' =>4),
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
			array('db' => 'id', 'dt' =>4,'formatter'=>function($d,$row){
				return '<a href="'.U('update',array('id'=>$d)).'">查看</a>';
			}),

		);

		$this->ssp_lists_ajax($_POST,$table,$columns);

	}
}