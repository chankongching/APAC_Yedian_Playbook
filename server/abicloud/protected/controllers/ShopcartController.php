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
class ShopcartController extends ApiController {

    const CART_API_URL = 'http://127.0.0.1/abiktv/store';
    const CART_API_ADD = 'index.php?route=feed/cart_api/add';
    const CART_API_DELETE = 'index.php?route=feed/cart_api/delete';
    const CART_API_CART = 'index.php?route=feed/cart_api/cart';
    const CART_API_UPDATE = 'index.php?route=feed/cart_api/update';
    const CART_API_CLEAR = 'index.php?route=feed/cart_api/clear';

    protected $rest = null;
    protected $user_id = 0;

    const USE_ABIKTV_STORE = false;

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
                $this->rest->initialize(array('server' => self::CART_API_URL));
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

    public function actionAdd() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'cart_amount' => 0.00,
            'cart_number' => 0,
            'list' => array()
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        // Get post data
        $post_data = Yii::app()->request->getPost('ShopcartAddRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Shop cart add data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);
        $product_list = isset($post_array['list']) ? $post_array['list'] : array();

        $product_array = array();
        // check add list and update
        if (!empty($product_list) && is_array($product_list)) {
            foreach ($product_list as $key => $_list) {
                $_product_id = isset($_list['productid']) ? intval($_list['productid']) : 0;
                $_product_num = isset($_list['prod_num']) ? intval($_list['prod_num']) : 0;
                if (empty($_product_id) || empty($_product_num)) {
                    continue;
                }
                // Add item to array
                $product_array['list'][] = array('productid' => $_product_id, 'prod_num' => $_product_num);
            }
        }
        // TODO: test
        //$product_array['list'][] = array('productid' => 28, 'prod_num' => 1);
        //$product_array['list'][] = array('productid' => 49, 'prod_num' => 1);
        //$this->sendResults(print_r($_user, true));
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;

                $api_post_array = $product_array;
                $api_post_json = base64_encode(json_encode($api_post_array));

                $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::CART_API_ADD, array('product_json' => $api_post_json));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['cart_number'] = $json_array['total_float'];
                        $result_array['cart_amount'] = $json_array['amount_float'];
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
                            $result_array['msg'] = Yii::t('user', 'Add to Shop Cart list success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'Shop Cart empty!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Shop Cart list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }

        // TODO: add category id to return list
        $this->sendResults($result_array);
    }

    public function actionCart() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'cart_amount' => 0.00,
            'cart_number' => 0,
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
                $json = $rest->post(self::CART_API_CART);
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['cart_number'] = $json_array['total_float'];
                        $result_array['cart_amount'] = $json_array['amount_float'];
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
                            $result_array['msg'] = Yii::t('user', 'Shop Cart list got success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'Shop Cart empty!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Shop Cart list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }


        // Return response
        $this->sendResults($result_array);
    }

    public function actionDelete() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'cart_amount' => 0.00,
            'cart_number' => 0,
            'list' => array()
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('POST' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        // Get post data
        $post_data = Yii::app()->request->getPost('ShopcartAddRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Shop cart delete data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);
        $product_list = isset($post_array['list']) ? $post_array['list'] : array();

        $product_array = array();
        // check add list and update
        if (!empty($product_list) && is_array($product_list)) {
            foreach ($product_list as $key => $_list) {
                $_product_id = isset($_list['productid']) ? intval($_list['productid']) : 0;
                if (empty($_product_id)) {
                    continue;
                }
                // Add item to array
                $product_array['list'][] = array('productid' => $_product_id);
            }
        }
        // TODO: test
        //$product_array['list'][] = array('productid' => 28);
        //$product_array['list'][] = array('productid' => 49);
        //$this->sendResults(print_r($_user, true));
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;

                $api_post_array = $product_array;
                $api_post_json = base64_encode(json_encode($api_post_array));

                $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::CART_API_DELETE, array('product_json' => $api_post_json));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['cart_number'] = $json_array['total_float'];
                        $result_array['cart_amount'] = $json_array['amount_float'];
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
                            $result_array['msg'] = Yii::t('user', 'Delete from Shop Cart list success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'Shop Cart empty!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Shop Cart list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }

        // TODO: add category id to return list
        $this->sendResults($result_array);
    }

    public function actionUpdate() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'cart_amount' => 0.00,
            'cart_number' => 0,
            'list' => array()
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        //if ('POST' != $request_type) {
        //    $this->sendResults($result_array, self::BadRequest);
        //}
        // Get post data
        $post_data = Yii::app()->request->getPost('ShopcartAddRequest');
        if (empty($post_data)) {
            $post_data = file_get_contents("php://input");
        }
        // log
        self::log('Shop cart update data: ' . print_r($post_data, TRUE), 'trace', $this->id);
        // Decode post data
        $post_array = json_decode($post_data, true);
        $product_list = isset($post_array['list']) ? $post_array['list'] : array();

        $product_array = array();
        // check add list and update
        if (!empty($product_list) && is_array($product_list)) {
            foreach ($product_list as $key => $_list) {
                $_product_id = isset($_list['productid']) ? intval($_list['productid']) : 0;
                $_product_num = isset($_list['prod_num']) ? intval($_list['prod_num']) : 0;
                if (empty($_product_id) || empty($_product_num)) {
                    continue;
                }
                // Add item to array
                $product_array['list'][] = array('productid' => $_product_id, 'prod_num' => $_product_num);
            }
        }
        // TODO: test
        //$product_array['list'][] = array('productid' => 28, 'prod_num' => 3);
        //$product_array['list'][] = array('productid' => 49, 'prod_num' => 5);
        //$this->sendResults(print_r($_user, true));
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;

                $api_post_array = $product_array;
                $api_post_json = base64_encode(json_encode($api_post_array));

                $rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::CART_API_UPDATE, array('product_json' => $api_post_json));
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['cart_number'] = $json_array['total_float'];
                        $result_array['cart_amount'] = $json_array['amount_float'];
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
                            $result_array['msg'] = Yii::t('user', 'Update Shop Cart list success!');
                        } else {
                            $result_array['msg'] = Yii::t('user', 'Shop Cart empty!');
                        }
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Shop Cart list got failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }

        // TODO: add category id to return list
        $this->sendResults($result_array);
    }

    public function actionClear($roomid = 0) {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
        );

        if ($roomid > 0) {
            self::log('Clear user shop cart list of room ' . $roomid, 'trace', $this->id);
            if (self::USE_ABIKTV_STORE) {
                $this->rest->set_header('X-KTV-SHOP-RoomID', $roomid);
            }
        }
        if ($this->user_id > 0) {
            if (self::USE_ABIKTV_STORE) {
                $rest = $this->rest;
                //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                $json = $rest->post(self::CART_API_CLEAR);
                //$this->sendResults($json);
                // Process API response
                $json_array = json_decode($json, TRUE);
                if (!is_null($json_array)) {
                    if (isset($json_array['success']) && $json_array['success']) {
                        $result_array['result'] = self::Success;
                        $result_array['msg'] = Yii::t('user', 'Shop Cart clear success!');
                    } else {
                        $result_array['msg'] = $json_array['message'];
                    }
                } else {
                    $result_array['msg'] = Yii::t('user', 'Shop Cart clear failed!');
                }
            }
        } else {
            $result_array['msg'] = Yii::t('user', 'Invalid user session, please login again!');
        }

        // Return response
        self::log(print_r($result_array, TRUE), 'trace', $this->id);
        //$this->sendResults($result_array);
    }

}
