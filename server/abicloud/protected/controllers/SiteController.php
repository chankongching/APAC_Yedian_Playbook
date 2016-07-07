<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
        $dataProvider = new CActiveDataProvider('Room');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            Yii::log($error['message'], CLogger::LEVEL_TRACE);
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else {
                $_current_api_action = Yii::app()->session['current_api'];
                if (isset($_current_api_action)) {

                    $api_array = ApiController::$api_array;
                    $api_action = $_current_api_action;

                    if (in_array($api_action, $api_array)) {
                        $result_array = array(
                            'result' => 500,
                            'msg' => $error['message'],
                        );

                        $this->sendResults($result_array, 500);
                    }
                    else {
                        $this->render('error', $error);
                    }
                } else {
                    $this->render('error', $error);
                }
            }
        }
    }

    /**
     * @param mixed  $result
     * @param int    $code
     * @param string $format
     * @param string $as_file
     * @param bool   $exitAfterSend
     *
     * @throws \DreamFactory\Platform\Exceptions\BadRequestException
     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sendResults($result, $code = 200, $format = 'json', $as_file = null, $exitAfterSend = true) {
        //	Some REST services may handle the response, they just return null
        if (is_null($result)) {
            Yii::app()->end(0, TRUE);

            return;
        }
        // Set all response as 200
        $code = 200;

        switch ($format) {
            case 'json':
                $_contentType = 'application/json; charset=utf-8';

                if (!is_string($result)) {
                    $result = json_encode($result);
                }

                // JSON if no callback
                if (isset($_GET['callback'])) {
                    $result = "{$_GET['callback']}($result);";
                }
                break;

            case 'xml':
                $_contentType = 'application/xml';
                $result = '<?xml version="1.0" ?>' . "<dfapi>$result</dfapi>";
                break;

            case 'csv':
                $_contentType = 'text/csv';
                break;

            default:
                $_contentType = 'application/octet-stream';
                break;
        }

        /* gzip handling output if necessary */
        ob_start();
        ob_implicit_flush(0);

        if (!headers_sent()) {
            // headers
            //$code = static::getHttpStatusCode($code);
            //$_title = static::getHttpStatusCodeTitle($code);
            $_title = $code;
            header("HTTP/1.1 $code $_title");
            header("Content-Type: $_contentType", true);
            //	IE 9 requires hoop for session cookies in iframes
            header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"', true);

            if (!empty($as_file)) {
                header("Content-Disposition: attachment; filename=\"$as_file\";", true);
            }
        }

        // send it out
        echo $result;

        // flush output and destroy buffer
        ob_end_flush();

        if ($exitAfterSend) {
            Yii::app()->end(0, TRUE);
        }

        return $result;
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('/'));
        }
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                ApiController::log('Admin ID: ' . Yii::app()->user->getId() . ', Name: ' . Yii::app()->user->getName() . ' logged in.', 'info', 'admin');
                //ApiController::log('Admin ' . $model->username . ' logged in.', 'info', 'admin');
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        ApiController::log('Admin ID: ' . Yii::app()->user->getId() . ', Name: ' . Yii::app()->user->getName() . ' logged out.', 'info', 'admin');
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionAllFunctions() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
        $controllers = array();
        $exclude_array = array('app', 'site', 'player', 'stb', 'version', 'user', 'test', 'setting', 'adminuser', 'shopcart', 'order', 'product');
        $basePath = Yii::app()->basePath;
        $path = $basePath . DIRECTORY_SEPARATOR . 'controllers';

        if (file_exists($path) === true) {
            $controllerDirectory = scandir($path);
            foreach ($controllerDirectory as $entry) {
                if ($entry{0} !== '.') {
                    $entryPath = $path . DIRECTORY_SEPARATOR . $entry;
                    if (strpos(strtolower($entry), 'controller') !== false) {
                        $name = substr($entry, 0, -14);
                        if (!in_array(strtolower($name), $exclude_array)) {
                            $controllers[strtolower($name)] = array(
                                'name' => $name,
                                'file' => $entry,
                                'path' => $entryPath,
                            );
                        }
                    }
                }
            }
        }
        //echo PinYin::getAllPY('你好吗？');
        //echo PinYin::getFirstPY('今夜你会不会来');
        //echo PinYin::getFirstPY('This is a test');

        $this->render('allfunc', array('allcontrollers' => $controllers));
    }

    public function actionShopAdmin() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
        $controllers = array();
        $exclude_array = array('default', 'install', 'shop', 'tax');
        $basePath = Yii::app()->basePath;
        $path = $basePath . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'shop' . DIRECTORY_SEPARATOR . 'controllers';

        if (file_exists($path) === true) {
            $controllerDirectory = scandir($path);
            foreach ($controllerDirectory as $entry) {
                if ($entry{0} !== '.') {
                    $entryPath = $path . DIRECTORY_SEPARATOR . $entry;
                    if (strpos(strtolower($entry), 'controller') !== false) {
                        $name = substr($entry, 0, -14);
                        if (!in_array(strtolower($name), $exclude_array)) {
                            $controllers[strtolower($name)] = array(
                                'name' => $name,
                                'file' => $entry,
                                'path' => $entryPath,
                            );
                        }
                    }
                }
            }
        }
        $this->render('shopadmin', array('allcontrollers' => $controllers));
    }

    /**
     * APP download
     * 将最终生成的 ipa 以及 plist 文件复制到内部的 Web 服务器， 在网页上添加类似这样的链接：
     * <a href="itms-services://?action=download-manifest&url=http://url-to-your-app.plist">安装移动办公iOS版</a>
     * 
     * Web 服务器上可能需要添加 .plist 和 .ipa 的 Mime 类型， 它们的 Mime 类型分别为：
     * .plist : text/xml;
     * .ipa : application/octet-stream
     * 
     */
    public function actionDownload() {
        $is_ios = false;

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        if ($is_iphone || $is_ipad) {
            $is_ios = true;
        }
        $this->render('download', array('is_ios' => $is_ios));
        //$this->redirect(Yii::app()->createAbsoluteUrl('//') . '/download.html');
        // TODO check android or iphone version
        //$this->redirect(Yii::app()->createAbsoluteUrl('//') . '/uploads/AbiKtv.apk');
    }

    public function actionAppfile($id = 0) {
        $dir = Yii::getPathOfAlias('webroot.uploads');
        if (empty($id)) {
            $app_file_name = 'BudKTV.apk';
            $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_file_name;
        } else {
            $model = Version::model()->findByPk($id);
            if ($model === null) {
                $app_file_name = 'BudKTV.apk';
                $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_file_name;
            } else {
                $app_file_name = $model->file_name;
                $app_real_name = $model->download_url;
                $app_full_path = $dir . DIRECTORY_SEPARATOR . $app_real_name;
            }
        }

        if (file_exists($app_full_path)) {
            // TODO record to download file log
            AbiStat::apiDayCountStat('site/appfile', $app_file_name);

            //打开文件
            $file = fopen($app_full_path, "rb");
            //返回的文件类型
            Header("Content-type: application/octet-stream");
            //按照字节大小返回
            Header("Accept-Ranges: bytes");
            //返回文件的大小
            Header("Accept-Length: " . filesize($app_full_path));
            //这里对客户端的弹出对话框，对应的文件名
            Header("Content-Disposition: attachment; filename=" . $app_file_name);
            //修改之前，一次性将数据传输给客户端
            //echo fread($file, filesize($app_full_path));
            //修改之后，一次只传输1024个字节的数据给客户端
            //向客户端回送数据
            $buffer = 8192; //
            //判断文件是否读完
            while (!feof($file)) {
                //将文件读入内存
                $file_data = fread($file, $buffer);
                //每次向客户端回送8192个字节的数据
                echo $file_data;
            }
            fclose($file);
        } else {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('对不起,您下载的文件不存在，请重试。');</script>";
        }
    }

}
