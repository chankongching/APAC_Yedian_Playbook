<?php
namespace SAdmin\Controller;
use Think\Controller;

class IndexController extends CommonController {
	public function index() {
		// $this->display();
		$this->redirect('Xktv/lists');
	}
}