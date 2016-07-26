<?php
/**
 * Created by PhpStorm.
 * User: lincoln
 * Date: 15/11/28
 * Time: 下午1:02
 */

namespace SAdmin\Controller;

use Think\Controller;

class PublicController extends CommonController {
	public function index() {
		$this->login();
	}
	public function login() {
		if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display('login');
		} else {
			$this->success('登录成功', U('SAdmin/Index/index'));
		}
	}

	// 登录检测
	public function checkLogin() {
		if (empty(I('username', '', 'string'))) {
			$this->error('帐号错误！');
		} elseif (empty(I('password', '', 'string'))) {
			$this->error('密码必须！');
		}
//        elseif (empty($_POST['verify'])){
		//            $this->error('验证码必须！');
		//        }
		//生成认证条件
		$map = array();
		// 支持使用绑定帐号登录
		$map['username'] = I('username', '', 'string');
		$map["status"] = array('gt', 0);
//        if(session('verify') != md5($_POST['verify'])) {
		//            $this->error('验证码错误！');
		//        }
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
			$User = M('sjbUser');
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
			$this->success('登录成功！', U('SAdmin/Index/index'));

		}
	}

	public function logout() {
		session(null);
		$this->success('退出成功', U('SAdmin/Public/index'));

	}

	public function register() {
		$this->display();
	}

	public function Login_error() {
		echo 'error';
	}

}