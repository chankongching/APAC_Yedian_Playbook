<?php
/**
 * Created by PhpStorm.
 * User: lincoln
 * Date: 15/11/28
 * Time: 下午1:02
 */

namespace Admin\Controller;
use Think\Controller;

class LoginController extends CommonController {
	public function index() {
		if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		} else {
			$this->redirect(U('/'));
		}
	}

	// 登录检测
	public function checkLogin() {
		if (empty(I('username', '', 'string'))) {
			$this->error('帐号错误！');
		} elseif (empty(I('password', '', 'string'))) {
			$this->error('密码必须！');
		}

		//生成认证条件
		$map = array();
		// 支持使用绑定帐号登录
		$map['username'] = I('username', '', 'string');
		$map["status"] = array('gt', 0);
		$rbac = new \Org\Util\Rbac();
		$authInfo = $rbac->authenticate($map);
		//使用用户名、密码和状态的方式进行认证
		if (false === $authInfo) {
			$this->error('帐号不存在或已禁用！');
		} else {
			if ($authInfo['password'] != I('password', '', 'md5')) {
				$this->error('密码错误！');
			}
			$_SESSION[C('USER_AUTH_KEY')] = $authInfo['id'];
			$_SESSION['email'] = $authInfo['email'];
			$_SESSION['logintime'] = $authInfo['logintime'];
			if ($authInfo['username'] == C('RBAC_SUPERADMIN')) {
				$_SESSION[C('ADMIN_AUTH_KEY')] = true;
			}
			//保存登录信息
			$User = M('user');
			$ip = get_client_ip();
			$time = time();
			$data = array();
			$data['id'] = $authInfo['id'];
			$data['logintime'] = $time;
			$data['loginip'] = $ip;
			$User->save($data);

			// 缓存访问权限
			$rbac->saveAccessList();
//            var_dump($_SESSION);die();
			$this->success('登录成功！', U('/'));

		}
	}

	public function logout() {
		session(null);
		$this->success('退出成功', U('/'));

	}

	public function register() {
		$this->display();
	}

	public function Login_error() {
		$this->error('登录错误');
	}
}