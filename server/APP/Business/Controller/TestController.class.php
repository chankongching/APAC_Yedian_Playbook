<?php
/**
 * Created by PhpStorm.
 * User: lincoln
 * Date: 16/7/7
 * Time: 下午4:57
 */
namespace Business\Controller;
use Think\Controller;
class TestController extends CommonController{
    public  function index(){
        $passhash = new \Org\Util\PasswordStorage();
        $hash= $passhash->create_hash('123456');
        echo $hash;
        echo $passhash->verify_password('123456',$hash);
//        var_dump($passhash);
    }
}