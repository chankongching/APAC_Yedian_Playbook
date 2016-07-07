<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StoreController
 *
 * @author wingsun
 */
class OrderController extends ApiController {

    const ORDER_API_URL = 'http://127.0.0.1/abiktv/store';
    const ORDER_API_ALL = 'index.php?route=feed/order_api/all';
    const ORDER_API_DETAIL = 'index.php?route=feed/order_api/detail';
    const ORDER_API_CREATE = 'index.php?route=feed/order_api/create';
    const ORDER_API_CREATE_ALL = 'index.php?route=feed/order_api/createall';
    const ORDER_API_SAVE = 'index.php?route=feed/order_api/save';
    const ORDER_API_SAVE_ALL = 'index.php?route=feed/order_api/saveall';
    const ORDER_API_UPDATE = 'index.php?route=feed/order_api/update';
    const ORDER_API_HISTORY = 'index.php?route=feed/order_api/history';

    protected $rest = null;
    protected $user_id = 0;

    // WEB Service URI
    const ERP_WEBSERVICE_URI = 'http://192.168.2.9/V8WebService.dll/wsdl/IMain';
    const ERP_USERID = 'dba';
    const ERP_SMALLPIC_PREFIX = 'small_';

    protected $soapClient = null;

    const USE_ABIKTV_STORE = false;

    protected $erp_userid = 'dba';

    function __destruct() {
        unset($this->soapClient);
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {

            if (self::USE_ABIKTV_STORE) {
                $this->rest = new RESTClient();
                $this->rest->initialize(array('server' => self::ORDER_API_URL));
            } else {
                $this->erp_userid = (empty(Yii::app()->params['erp_api_userid']) ? self::ERP_USERID : Yii::app()->params['erp_api_userid']);
                $this->soapClient = new SoapClient(empty(Yii::app()->params['erp_api_url']) ? self::ERP_WEBSERVICE_URI : Yii::app()->params['erp_api_url'], array('cache_wsdl' => WSDL_CACHE_BOTH, 'keep_alive' => true));
            }

            // Get user id
            if (isset(Yii::app()->user) && null !== Yii::app()->user) {
                $_user_id = Yii::app()->user->getId();
            } else {
                $_user_id = 0;
            }
            $_user_id = empty($_user_id) ? 0 : $_user_id;
            // TODO: test data
            //$_user_id = 3;

            $_user = PlatformUser::model()->findByPk($_user_id);
            if (!is_null($_user)) {
                $this->user_id = $_user->id;

                $_openid = $_user->username;
                $_userid = $_user->id;
                $_roomid = $this->_roomID;
                $_roomkey = $this->_roomKey;
                $_email = $_openid . '@abiktv.com';
                $_password = $_openid;

                if (self::USE_ABIKTV_STORE) {
                    $this->rest->set_header('X-KTV-SHOP-Email', $_email);
                    $this->rest->set_header('X-KTV-SHOP-Password', $_password);
                    $this->rest->set_header('X-KTV-SHOP-OpenID', $_openid);
                    $this->rest->set_header('X-KTV-SHOP-UserID', $_userid);
                    $this->rest->set_header('X-KTV-SHOP-RoomID', $_roomid);
                    $this->rest->set_header('X-KTV-SHOP-RoomKey', $_roomkey);
                    $this->rest->set_header('X-KTV-SHOP-checkinCode', $this->_checkinCode);
                }
            }
            // register store user: 
            // openid@abiktv.com as email
            // openid as firstname
            // userid as lastname
            // empty as company
            // roomid as fax
            // 8888 as telephone
            // abiktv as address_1
            // abiktv as city
            // 44 as country_id
            // 685 as zone_id
            // openid as password
            // 1 as agree
            // 0 as newsletter

            return true;
        } else {
            return false;
        }
    }

    public function actionList() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );


        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::ORDER_API_ALL);
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['result'] = self::Success;
                        if (isset($json_array['orders']) && !empty($json_array['orders']) && is_array($json_array['orders'])) {
                            $index_num = 0;
                            foreach ($json_array['orders'] as $key => $_order) {
                                if (isset($_order) && !empty($_order) && is_array($_order)) {
                                    $result_array['list'][] = array(
                                        'order_invoice' => ($_order['invoice_no']),
                                        'order_amount' => floatval($_order['total_float']) + 0.00,
                                        'order_number' => intval($_order['products']),
                                        'order_status' => $_order['status'],
                                        'order_time' => $_order['added_time'],
                                    );
                                    $index_num ++;
                                }
                            }
                            $result_array['msg'] = Yii::t('user', 'Room order list got success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'No room order!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room order list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionHistory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );

        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::ORDER_API_HISTORY);
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['result'] = self::Success;
                        if (isset($json_array['orders']) && !empty($json_array['orders']) && is_array($json_array['orders'])) {
                            $index_num = 0;
                            foreach ($json_array['orders'] as $key => $_order) {
                                if (isset($_order) && !empty($_order) && is_array($_order)) {
                                    $result_array['list'][] = array(
                                        'order_invoice' => ($_order['invoice_no']),
                                        'order_amount' => floatval($_order['total_float']) + 0.00,
                                        'order_number' => intval($_order['products']),
                                        'order_status' => $_order['status'],
                                        'order_time' => $_order['added_time'],
                                            //'room_id' => $_order['room_id'],
                                    );
                                    $index_num ++;
                                }
                            }
                            $result_array['msg'] = Yii::t('user', 'Customer order list got success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'No customer order!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Customer order list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionDetail() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'order_amount' => 0.00,
            'order_number' => 0,
            'order_invoice' => '',
            'order_status' => '',
            'order_time' => 0,
            'list' => array(),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        $_invoice_no = Yii::app()->request->getQuery('order_invoice');
        $_invoice_no = empty($_invoice_no) ? '' : $_invoice_no;
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::ORDER_API_DETAIL, array('invoice_no' => $_invoice_no));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['result'] = self::Success;
                        // order summary
                        if (isset($json_array['order']) && !empty($json_array['order']) && is_array($json_array['order'])) {
                            $_order = $json_array['order'];
                            $result_array['order_amount'] = floatval($_order['total_float']) + 0.00;
                            $result_array['order_number'] = intval($_order['products']);
                            $result_array['order_invoice'] = $_order['invoice_no'];
                            $result_array['order_status'] = $_order['status'];
                            $result_array['order_time'] = $_order['added_time'];
                            $result_array['msg'] = Yii::t('user', 'Room order detail got success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'No room order!');
                        }

                        // order product list
                        if (isset($json_array['products']) && !empty($json_array['products']) && is_array($json_array['products'])) {
                            $index_num = 0;
                            foreach ($json_array['products'] as $key => $_product) {
                                if (isset($_product) && !empty($_product) && is_array($_product)) {
                                    $result_array['list'][] = array(
                                        'id' => intval($_product['id']),
                                        'category_id' => intval($_product['category_id']),
                                        'category_name' => $_product['category_name'],
                                        'name' => $_product['name'],
                                        'description' => $_product['description'],
                                        'smallpicurl' => $_product['thumb'],
                                        'bigpicurl' => $_product['image_popup'],
                                        'price' => floatval($_product['price_float']) + 0.00,
                                        'unit' => '',
                                        'prod_num' => intval($_product['quantity']),
                                        'prod_amount' => floatval($_product['total_float']) + 0.00,
                                        'index_num' => $index_num,
                                    );
                                    $index_num ++;
                                }
                            }
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room order list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionCreate() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'order_amount' => 0.00,
            'order_number' => 0,
            'order_id' => '',
            'list' => array(),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $api_url = self::ORDER_API_CREATE;
                if (isset(Yii::app()->params['merge_room_order']) && Yii::app()->params['merge_room_order']) {
                    $api_url = self::ORDER_API_CREATE_ALL;
                }
                $json = $rest->post($api_url);
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['order_number'] = $json_array['total_float'];
                        $result_array['order_amount'] = $json_array['amount_float'];
                        $result_array['order_id'] = $json_array['order_id'];

                        $result_array['result'] = self::Success;
                        if (isset($json_array['products']) && !empty($json_array['products']) && is_array($json_array['products'])) {
                            $index_num = 0;
                            foreach ($json_array['products'] as $key => $_product) {
                                if (isset($_product) && !empty($_product) && is_array($_product)) {
                                    $result_array['list'][] = array(
                                        'id' => intval($_product['id']),
                                        'category_id' => intval($_product['category_id']),
                                        'category_name' => $_product['category_name'],
                                        'name' => $_product['name'],
                                        'description' => $_product['description'],
                                        'smallpicurl' => $_product['thumb'],
                                        'bigpicurl' => $_product['image_popup'],
                                        'price' => floatval($_product['price_float']) + 0.00,
                                        'unit' => '',
                                        'prod_num' => intval($_product['quantity']),
                                        'prod_amount' => floatval($_product['total_float']) + 0.00,
                                        'index_num' => $index_num,
                                    );
                                    $index_num ++;
                                }
                            }
                            $result_array['msg'] = Yii::t('user', 'Room temporary order created success, please confirm and submit!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'Room temporary order created failure, no product!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room temporary order information got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionSave() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'order_amount' => 0.00,
            'order_number' => 0,
            'order_invoice' => '',
            'order_status' => '',
            'order_time' => 0,
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        $_invoice_no = Yii::app()->request->getQuery('order_id');
        $_invoice_no = empty($_invoice_no) ? '' : $_invoice_no;
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $api_url = self::ORDER_API_SAVE;
                if (isset(Yii::app()->params['merge_room_order']) && Yii::app()->params['merge_room_order']) {
                    $api_url = self::ORDER_API_SAVE_ALL;
                }
                $json = $rest->post($api_url, array('order_no' => $_invoice_no));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                //$this->sendResults($json_array);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        self::log('Order: ' . print_r($json_array, true), 'trace', $this->id);
                        $result_array['result'] = self::Success;
                        // order summary
                        $_order = $json_array;
                        $result_array['order_amount'] = floatval($_order['total_float']) + 0.00;
                        $result_array['order_number'] = intval($_order['products']);
                        $result_array['order_invoice'] = isset($_order['invoice_no']) ? $_order['invoice_no'] : '';
                        $result_array['order_status'] = isset($_order['status']) ? $_order['status'] : 'Pending';
                        $result_array['order_time'] = isset($_order['added_time']) ? $_order['added_time'] : time();
                        $result_array['msg'] = Yii::t('user', 'Room order confirmed success!');
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room order submitted failure!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionUpdate() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'order_amount' => 0.00,
            'order_number' => 0,
            'order_invoice' => '',
            'order_status' => '',
            'order_time' => 0,
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        // Get post data
        $post_data = Yii::app()->request->getPost('OrderUpdateRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Shop cart update data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);
        $_invoice_no = isset($post_array['order_invoice']) ? $post_array['order_invoice'] : '';
        $_invoice_status = isset($post_array['order_status_id']) ? intval($post_array['order_status_id']) : 0;

        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $api_url = self::ORDER_API_UPDATE;
                $json = $rest->post($api_url, array('invoice_no' => $_invoice_no, 'invoice_status_id' => $_invoice_status));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['result'] = self::Success;
                        // order summary
                        $_order = $json_array;
                        $result_array['order_amount'] = floatval($_order['total_float']) + 0.00;
                        $result_array['order_number'] = intval($_order['products']);
                        $result_array['order_invoice'] = $_order['invoice_no'];
                        $result_array['order_status'] = $_order['status'];
                        $result_array['order_time'] = $_order['added_time'];
                        $result_array['msg'] = Yii::t('user', 'Room order status updated success!');
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Room order status updated failure!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionSubmit() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
        );

        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        if (self::USE_ABIKTV_STORE) {
            // do nothing
        } else {
            $context_template = '<?xml version="1.0"?>' . "\n" . '<LsService>' . "\n" . '%CONSUMES%' . "" . '</LsService>';
            $consume_template = '<Consume F_POSITEMID="%P1%" F_SETITEMID="%P2%" F_POSUNIT="%P3%" F_QUANTITY="%P4%" F_ISGIFT="%P5%" F_REQUEST="%P6%" />' . "\n";
            // Get post data
            $post_data = Yii::app()->request->getPost('SubmitOrderRequest');
            if (empty($post_data)) {
                $post_data = file_get_contents("php://input");
            }
            // log
            self::log('SubmitOrderRequest data: ' . print_r($post_data, TRUE), 'trace', $this->id);

            // Decode post data
            $post_array = json_decode($post_data, true);
            $product_list = isset($post_array['list']) ? $post_array['list'] : array();
            if (!empty($product_list) && is_array($product_list)) {
                $bool_array = array('1' => 'T', 1 => 'T', '0' => 'F', 0 => 'F', '' => 'F');
                $_consumes = '';
                foreach ($product_list as $key => $_product) {
                    $_productid = empty($_product['productid']) ? 0 : $_product['productid'];
                    $_setproductid = empty($_product['setproductid']) ? '' : $_product['setproductid'];
                    $_prod_num = empty($_product['prod_num']) ? 0 : $_product['prod_num'];
                    $_prod_unit = empty($_product['prod_unit']) ? '' : $_product['prod_unit'];
                    $_isgift = empty($_product['isgift']) ? 'F' : $bool_array[$_product['isgift']];
                    $_request = empty($_product['request']) ? '' : $_product['request'];

                    $_consumes .= str_replace(array('%P1%', '%P2%', '%P3%', '%P4%', '%P5%', '%P6%'), array($_productid, $_setproductid, $_prod_unit, $_prod_num, $_isgift, $_request), $consume_template);
                }
                $_context = str_replace('%CONSUMES%', $_consumes, $context_template);
                self::log('XML data: ' . print_r($_context, TRUE), 'trace', $this->id);

                $result = $this->soapClient->WS_SubmitOrder($this->_roomKey, $this->erp_userid, $_context);
                self::log('Result data: ' . print_r($result, TRUE), 'trace', $this->id);
                if (empty($result) && isset($result) && intval($result) === 0) {
                    $result_array['result'] = self::Success;
                    $result_array['msg'] = Yii::t('user', 'Order submitted success!');
                } else {
                    $result_array['msg'] = $result;
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Order list is empty or incorrect format!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionBillinfo() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'order_amount' => 0.00,
            'order_number' => 0,
            'order_invoice' => '',
            'order_status' => '',
            'order_time' => 0,
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        if (self::USE_ABIKTV_STORE) {
            // do nothing
        } else {
            $respXML = $this->soapClient->WS_GetBillInfo($this->_roomKey);
            $xml = simplexml_load_string($respXML);
            //$this->log(print_r($xml, true));
            if ($xml !== FALSE) {
                $order_amount = floatval((string) $xml->Bill->attributes()->F_BILLAMOUNT) + 0.00;
                $order_status = intval((string) $xml->Bill->attributes()->F_ROOMSTATE);
                $order_number = 0;
                $order_invoice = ((string) $xml->Bill->attributes()->F_TABLE);
                $order_time = time();
                $_index = 0;
                $_totalnum = 0;
                $_totalamount = 0;
                $bool_array = array('T' => 1, 't' => 1, 'F' => 0, 'f' => 0, '' => 0);

                foreach ($xml->Consume as $_class) {
                    $_index ++;
                    $_totalnum += intval((string) $_class->attributes()->F_QUANTITY);
                    if ($bool_array[(string) $_class->attributes()->F_ISGIFT] < 1) {
                        $_totalamount += floatval((string) $_class->attributes()->F_SUBTOTAL);
                    }
                    $result_array['list'][] = array(
                        'id' => (string) $_class->attributes()->F_POSITEM,
                        'name' => (string) $_class->attributes()->F_POSITEM,
                        'description' => (string) $_class->attributes()->F_POSITEM,
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 1),
                        'price' => floatval((string) $_class->attributes()->F_PRICE) + 0.00,
                        'unit' => (string) $_class->attributes()->F_POSUNIT,
                        'prod_num' => intval((string) $_class->attributes()->F_QUANTITY),
                        'prod_amount' => floatval((string) $_class->attributes()->F_SUBTOTAL) + 0.00,
                        'index_num' => $_index,
                        'isgift' => $bool_array[(string) $_class->attributes()->F_ISGIFT],
                        'iscancel' => $bool_array[(string) $_class->attributes()->F_ISCANCEL],
                    );
                }
                $order_number = $_totalnum;
                $result_array['order_amount'] = empty($order_amount) ? $_totalamount : $order_amount;
                $result_array['order_number'] = $order_number;
                $result_array['order_invoice'] = $order_invoice;
                $result_array['order_status'] = $order_status;
                $result_array['order_time'] = $order_time;

                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Room bill info got success!');
            } else {
                $result_array['msg'] = Yii::t('user', 'Room bill info got failed!');
            }
        }
        // Return response
        $this->sendResults($result_array);
    }

    /**
     * 
     * @param type $filename
     * @param type $pictype 0 - category, 1 - product
     * @param type $picsize 0 - small size, 1 - big size
     * @return type
     */
    public function getPicAttachUrl($filename = '', $pictype = 0, $picsize = 1) {
        $_baseurl = Yii::app()->createAbsoluteUrl('/');
        $_mediabaseurl = (empty(Yii::app()->params['upload_url']) ? ($_baseurl . '/uploads') : Yii::app()->params['upload_url']) . '/attach/erp';
        $_mediaurl = $_mediabaseurl . '/erp/';

        $default_picture_setting = empty(Yii::app()->params['erp_default_setting']) ? array() : Yii::app()->params['erp_default_setting'];
        if (1 == $picsize) {
            if (0 == $pictype) {
                $default_pic = (isset($default_picture_setting['category_bigpic']) && !empty($default_picture_setting['category_bigpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['category_bigpic']) : '';
            } else {
                $default_pic = (isset($default_picture_setting['product_bigpic']) && !empty($default_picture_setting['product_bigpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['product_bigpic']) : '';
            }
        } else {
            if (0 == $pictype) {
                $default_pic = (isset($default_picture_setting['category_smallpic']) && !empty($default_picture_setting['category_smallpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['category_smallpic']) : '';
            } else {
                $default_pic = (isset($default_picture_setting['product_smallpic']) && !empty($default_picture_setting['product_smallpic'])) ? ($_mediabaseurl . '/' . $default_picture_setting['product_smallpic']) : '';
            }
        }
        $file_base_url = isset($default_picture_setting['erp_picbaseurl']) ? $default_picture_setting['erp_picbaseurl'] : '';
        if (empty($file_base_url)) {
            $file_url = '';
        } else {
            if (0 == $picsize && !empty($filename)) {
                $filename = self::ERP_SMALLPIC_PREFIX . $filename;
            }
            $file_url = empty($filename) ? '' : $file_base_url . '/' . str_replace('\\', '/', $filename);
        }
        $_media_full_url = empty($file_url) ? $default_pic : ($file_url);
        return $_media_full_url;
    }

}
