<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {
	public function _initialize() {
		// 用户权限检查
		// if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
		// 	$rbac = new \Org\Util\Rbac();
		// 	if (!$rbac->AccessDecision()) {
		// 		//检查认证识别号
		// 		if (!$_SESSION[C('USER_AUTH_KEY')]) {
		// 			//跳转到认证网关
		// 			$this->redirect(C('USER_AUTH_GATEWAY'));
		// 		}
		// 		// 没有权限 抛出错误
		// 		if (C('RBAC_ERROR_PAGE')) {
		// 			// 定义权限错误页面
		// 			redirect(C('RBAC_ERROR_PAGE'));
		// 		} else {
		// 			if (C('GUEST_AUTH_ON')) {
		// 				$this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
		// 			}
		// 			// 提示错误信息
		// 			$this->error(L('_VALID_ACCESS_'));
		// 		}
		// 	}
		// }
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
			$rbac = new \Org\Util\Rbac();
			if (!$rbac->AccessDecision()) {
				//检查认证识别号
				if (!$_SESSION[C('USER_AUTH_KEY')]) {
					//跳转到认证网关
					// $this->redirect(C('USER_AUTH_GATEWAY'));
					$this->error('用户未登录，请先登录', U('Login/index'));
				}
				// 没有权限 抛出错误
				if (C('RBAC_ERROR_PAGE')) {
					// 定义权限错误页面
					// echo '2';die();
					redirect(C('RBAC_ERROR_PAGE'));
				} else {
					if (C('GUEST_AUTH_ON')) {
						$this->assign('jumpUrl', U('Login/index'));
					}
					// 提示错误信息
					// die();
					$this->error(L('_VALID_ACCESS_'), U('Login/index'));
				}
			}
		}
	}

	public function ssp_lists_ajax($type, $table, $columns, $whereResult = null, $whereAll = null, $primaryKey = 'id') {
		$sql_details = array(
			'user' => C('DB_USER'),
			'pass' => C('DB_PWD'),
			'db' => C('DB_NAME'),
			'host' => C('DB_HOST'),
		);
		vendor('DataTables/ssp');
		// echo json_encode(\SSP::simple($type, $sql_details, $table, $primaryKey, $columns));
		echo json_encode(\SSP::complex($type, $sql_details, $table, $primaryKey, $columns, $whereResult, $whereAll));
	}

}