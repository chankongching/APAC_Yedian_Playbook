<?php
namespace SAdmin\Controller;
use Think\Controller;

class CommonController extends Controller {
	public function _initialize() {
		// 用户权限检查
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
			$rbac = new \Org\Util\Rbac();
			if (!$rbac->AccessDecision()) {
				//检查认证识别号
				if (!$_SESSION[C('USER_AUTH_KEY')]) {
					//跳转到认证网关
					// $this->redirect(C('USER_AUTH_GATEWAY'));
					$this->error('用户未登录，请先登录', U('Public/login'));
				}
				// 没有权限 抛出错误
				if (C('RBAC_ERROR_PAGE')) {
					// 定义权限错误页面
					// echo '2';die();
					redirect(C('RBAC_ERROR_PAGE'));
				} else {
					if (C('GUEST_AUTH_ON')) {
						$this->assign('jumpUrl', U('Public/login'));
					}
					// 提示错误信息
					// die();
					$this->error(L('_VALID_ACCESS_'), U('Public/login'));
				}
			}
		}
	}

	public function ssp_lists_ajax($type, $table, $columns, $primaryKey = 'id') {
		$sql_details = array(
			'user' => C('DB_USER'),
			'pass' => C('DB_PWD'),
			'db' => C('DB_NAME'),
			'host' => C('DB_HOST'),
		);
		vendor('DataTables/ssp');
		echo json_encode(\SSP::simple($type, $sql_details, $table, $primaryKey, $columns));
	}
	/**
	 * 显示指定模型列表数据
	 * @param  String $model 模型标识
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function lists($model = null, $p = 0, $r = 0) {
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$row = intval($r);
		$row = $row ? $row : 10; //默认显示第一页数据
		$map = array();
		// $key = $model['search_key'] ? $model['search_key'] : 'title';
		// if (isset($_REQUEST[$key])) {
		// 	$map[$key] = array('like', '%' . $_GET[$key] . '%');
		// 	unset($_REQUEST[$key]);
		// }
		// 条件搜索
		foreach ($_REQUEST as $name => $val) {
			if (in_array($name, $fields)) {
				$map[$name] = $val;
			}
		}
		$data = M($model)
		/* 查询指定字段，不指定则查询所有字段 */
		// ->field(empty($fields) ? true : $fields)
		// 查询条件
		->where($map)
		/* 默认通过id逆序排列 */
			->order('id DESC')
		/* 数据分页 */
			->page($page, $row)
		/* 执行查询 */
			->select();

		/* 查询记录总数 */
		$count = M($model)->where($map)->count();
		//分页
		if ($count > $row) {
			$page = new \Think\Page($count, $row);
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$this->assign('_page', $page->show());
		}
		// $this->assign('model', $model);
		// $this->assign('list_grids', $grids);
		$this->assign('list_data', $data);
		// $this->meta_title = $model['title'] . '列表';
		// $this->display($model['template_list']);

	}
	public function lists1($model = null, $p = 0) {
		$model || $this->error('模型名标识必须！');
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据

		//获取模型信息
		$model = M('Model')->getByName($model);
		$model || $this->error('模型不存在！');

		//解析列表规则
		$fields = array();
		$grids = preg_split('/[;\r\n]+/s', $model['list_grid']);
		foreach ($grids as &$value) {
			// 字段:标题:链接
			$val = explode(':', $value);
			// 支持多个字段显示
			$field = explode(',', $val[0]);
			$value = array('field' => $field, 'title' => $val[1]);
			if (isset($val[2])) {
				// 链接信息
				$value['href'] = $val[2];
				// 搜索链接信息中的字段信息
				preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use (&$fields) {$fields[] = $match[1];}, $value['href']);
			}
			if (strpos($val[1], '|')) {
				// 显示格式定义
				list($value['title'], $value['format']) = explode('|', $val[1]);
			}
			foreach ($field as $val) {
				$array = explode('|', $val);
				$fields[] = $array[0];
			}
		}
		// 过滤重复字段信息
		$fields = array_unique($fields);
		// 关键字搜索
		$map = array();
		$key = $model['search_key'] ? $model['search_key'] : 'title';
		if (isset($_REQUEST[$key])) {
			$map[$key] = array('like', '%' . $_GET[$key] . '%');
			unset($_REQUEST[$key]);
		}
		// 条件搜索
		foreach ($_REQUEST as $name => $val) {
			if (in_array($name, $fields)) {
				$map[$name] = $val;
			}
		}
		$row = empty($model['list_row']) ? 10 : $model['list_row'];

		//读取模型数据列表
		if ($model['extend']) {
			$name = get_table_name($model['id']);
			$parent = get_table_name($model['extend']);
			$fix = C("DB_PREFIX");

			$key = array_search('id', $fields);
			if (false === $key) {
				array_push($fields, "{$fix}{$parent}.id as id");
			} else {
				$fields[$key] = "{$fix}{$parent}.id as id";
			}

			/* 查询记录数 */
			$count = M($parent)->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")->where($map)->count();

			// 查询数据
			$data = M($parent)
				->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")
				/* 查询指定字段，不指定则查询所有字段 */
				->field(empty($fields) ? true : $fields)
				// 查询条件
				->where($map)
				/* 默认通过id逆序排列 */
				->order("{$fix}{$parent}.id DESC")
				/* 数据分页 */
				->page($page, $row)
				/* 执行查询 */
				->select();

		} else {
			in_array('id', $fields) || array_push($fields, 'id');
			$name = parse_name(get_table_name($model['id']), true);
			$data = M($name)
			/* 查询指定字段，不指定则查询所有字段 */
				->field(empty($fields) ? true : $fields)
				// 查询条件
				->where($map)
				/* 默认通过id逆序排列 */
				->order('id DESC')
				/* 数据分页 */
				->page($page, $row)
				/* 执行查询 */
				->select();

			/* 查询记录总数 */
			$count = M($name)->where($map)->count();
		}

		//分页
		if ($count > $row) {
			$page = new \Think\Page($count, $row);
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$this->assign('_page', $page->show());
		}

		$this->assign('model', $model);
		$this->assign('list_grids', $grids);
		$this->assign('list_data', $data);
		$this->meta_title = $model['title'] . '列表';
		$this->display($model['template_list']);
	}

	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search();
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		$this->display();
		return;
	}

	/**
	+----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
	 * 可以在action控制器中重载
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	function getReturnUrl() {
		return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
	}

	/**
	+----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	+----------------------------------------------------------
	 * @access protected
	+----------------------------------------------------------
	 * @param string $name 数据对象名称
	+----------------------------------------------------------
	 * @return HashMap
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty($name)) {
			$name = $this->getActionName();
		}
		$name = $this->getActionName();
		$model = D($name);
		$map = array();
		foreach ($model->getDbFields() as $key => $val) {
			if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
				$map[$val] = $_REQUEST[$val];
			}
		}
		return $map;
	}

	/**
	+----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	+----------------------------------------------------------
	 * @access protected
	+----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	+----------------------------------------------------------
	 * @return void
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset($_REQUEST['_order'])) {
			$order = $_REQUEST['_order'];
		} else {
			$order = !empty($sortBy) ? $sortBy : $model->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST['_sort'])) {
			$sort = $_REQUEST['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where($map)->count('id');
		if ($count > 0) {
			// import("@.ORG.Util.Page");
			//创建分页对象
			if (!empty($_REQUEST['listRows'])) {
				$listRows = $_REQUEST['listRows'];
			} else {
				$listRows = '';
			}
			// $p = new \OPage($count, $listRows);
			// $p = new Page($count, $listRows);
			//分页查询数据

			// $voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
			$voList = $model->where($map)->order("`" . $order . "` " . $sort)->select();
			echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ($map as $key => $val) {
				if (!is_array($val)) {
					$p->parameter .= "$key=" . urlencode($val) . "&";
				}
			}
			//分页显示
			// $page = $p->show();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign('sort', $sort);
			$this->assign('order', $order);
			$this->assign('sortImg', $sortImg);
			$this->assign('sortType', $sortAlt);
			// $this->assign("page", $page);
		}
		cookie('_currentUrl_', __SELF__);
		return;
	}

	function insert() {
		$name = $this->getActionName();
		$model = D($name);
		if (false === $model->create()) {
			$this->error($model->getError());
		}
		//保存当前数据对象
		$list = $model->add();
		if ($list !== false) {
			//保存成功
			$this->success('新增成功!', cookie('_currentUrl_'));
		} else {
			//失败提示
			$this->error('新增失败!');
		}
	}

	function read() {
		$this->edit();
	}

	function edit() {
		$name = $this->getActionName();
		$model = M($name);
		$id = $_REQUEST[$model->getPk()];
		$vo = $model->getById($id);
		$this->assign('vo', $vo);
		$this->display();
	}

	function update() {
		$name = $this->getActionName();
		$model = D($name);
		if (false === $model->create()) {
			$this->error($model->getError());
		}
		// 更新数据
		$list = $model->save();
		if (false !== $list) {
			//成功提示
			$this->success('编辑成功!', cookie('_currentUrl_'));
		} else {
			//错误提示
			$this->error('编辑失败!');
		}
	}

	/**
	+----------------------------------------------------------
	 * 默认删除操作
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	public function delete() {
		//删除指定记录
		$name = $this->getActionName();
		$model = M($name);
		if (!empty($model)) {
			$pk = $model->getPk();
			$id = $_REQUEST[$pk];
			if (isset($id)) {
				$condition = array($pk => array('in', explode(',', $id)));
				$list = $model->where($condition)->setField('status', -1);
				if ($list !== false) {
					$this->success('删除成功！');
				} else {
					$this->error('删除失败！');
				}
			} else {
				$this->error('非法操作');
			}
		}
	}

	public function foreverdelete() {
		//删除指定记录
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$pk = $model->getPk();
			$id = $_REQUEST[$pk];
			if (isset($id)) {
				$condition = array($pk => array('in', explode(',', $id)));
				if (false !== $model->where($condition)->delete()) {
					$this->success('删除成功！');
				} else {
					$this->error('删除失败！');
				}
			} else {
				$this->error('非法操作');
			}
		}
		$this->forward();
	}

	public function clear() {
		//删除指定记录
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			if (false !== $model->where('status=1')->delete()) {
				$this->success(L('_DELETE_SUCCESS_'), $this->getReturnUrl());
			} else {
				$this->error(L('_DELETE_FAIL_'));
			}
		}
		$this->forward();
	}

	/**
	+----------------------------------------------------------
	 * 默认禁用操作
	 *
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 * @throws FcsException
	+----------------------------------------------------------
	 */
	public function forbid() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_REQUEST[$pk];
		$condition = array($pk => array('in', $id));
		$list = $model->forbid($condition);
		if ($list !== false) {
			$this->success('状态禁用成功', $this->getReturnUrl());
		} else {
			$this->error('状态禁用失败！');
		}
	}

	public function checkPass() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$condition = array($pk => array('in', $id));
		if (false !== $model->checkPass($condition)) {
			$this->success('状态批准成功！', $this->getReturnUrl());
		} else {
			$this->error('状态批准失败！');
		}
	}

	public function recycle() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$condition = array($pk => array('in', $id));
		if (false !== $model->recycle($condition)) {
			$this->success('状态还原成功！', $this->getReturnUrl());
		} else {
			$this->error('状态还原失败！');
		}
	}

	public function recycleBin() {
		$map = $this->_search();
		$map['status'] = -1;
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		$this->display();
	}

	/**
	+----------------------------------------------------------
	 * 默认恢复操作
	 *
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 * @throws FcsException
	+----------------------------------------------------------
	 */
	function resume() {
		//恢复指定记录
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$condition = array($pk => array('in', $id));
		if (false !== $model->resume($condition)) {
			$this->success('状态恢复成功！', $this->getReturnUrl());
		} else {
			$this->error('状态恢复失败！');
		}
	}

	function saveSort() {
		$seqNoList = $_POST['seqNoList'];
		if (!empty($seqNoList)) {
			//更新数据对象
			$name = $this->getActionName();
			$model = D($name);
			$col = explode(',', $seqNoList);
			//启动事务
			$model->startTrans();
			foreach ($col as $val) {
				$val = explode(':', $val);
				$model->id = $val[0];
				$model->sort = $val[1];
				$result = $model->save();
				if (!$result) {
					break;
				}
			}
			//提交事务
			$model->commit();
			if ($result !== false) {
				//采用普通方式跳转刷新页面
				$this->success('更新成功');
			} else {
				$this->error($model->getError());
			}
		}
	}
}