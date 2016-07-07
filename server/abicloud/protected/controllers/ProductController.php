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
class ProductController extends ApiController {

    const SHOP_API_URL = 'http://127.0.0.1/abiktv/store/index.php';
    const SHOP_API_CATEGORIES = 'feed/web_api/categories';
    const SHOP_API_PRODUCTS = 'feed/web_api/products';
    const SHOP_API_PRODUCT = 'feed/web_api/product';

    protected $rest = null;

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

    public function init() {
        parent::init();

        if (self::USE_ABIKTV_STORE) {
            $this->rest = new RESTClient();
            $this->rest->initialize(array('server' => self::SHOP_API_URL));
        } else {
            $this->erp_userid = (empty(Yii::app()->params['erp_api_userid']) ? self::ERP_USERID : Yii::app()->params['erp_api_userid']);
            $this->soapClient = new SoapClient(empty(Yii::app()->params['erp_api_url']) ? self::ERP_WEBSERVICE_URI : Yii::app()->params['erp_api_url'], array('cache_wsdl' => WSDL_CACHE_BOTH, 'keep_alive' => true));
        }
    }

    public function actionCategory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        if (self::USE_ABIKTV_STORE) {
            $rest = $this->rest;
            //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->get('', array('route' => self::SHOP_API_CATEGORIES, 'parent' => 0, 'level' => 1));

            // Process API response
            $json_array = json_decode($json, TRUE);
            if (!is_null($json_array)) {
                if (isset($json_array['success']) && $json_array['success']) {
                    $result_array['result'] = self::Success;
                    if (isset($json_array['categories']) && !empty($json_array['categories']) && is_array($json_array['categories'])) {
                        foreach ($json_array['categories'] as $key => $_category) {
                            if (isset($_category) && !empty($_category) && is_array($_category)) {
                                $result_array['list'][] = array(
                                    'id' => ($_category['category_id']),
                                    'name' => $_category['name'],
                                    'smallpicurl' => $_category['image'],
                                    'bigpicurl' => $_category['image_popup'],
                                    'productscount' => intval($_category['total']),
                                    'parentid' => '',
                                );
                            }
                        }
                        $result_array['msg'] = Yii::t('user', 'Category list got success!');
                    } else {
                        $result_array['msg'] = Yii::t('user', 'No categories!');
                    }
                } else {
                    $result_array['msg'] = $json_array['message'];
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Category list got failed!');
            }
        } else {
            $respXML = $this->soapClient->WS_GetMenuClass($this->_roomKey, $this->erp_userid, '0');
            $xml = simplexml_load_string($respXML);
            if ($xml !== FALSE) {
                $parent_array = array();
                $_result_array = array();
                foreach ($xml->MenuClass as $_class) {
                    $parent_id = ((string) $_class->attributes()->F_PARENTID);
                    $_result_array[] = array(
                        'id' => ((string) $_class->attributes()->F_CLASSID),
                        'name' => (string) $_class->attributes()->F_CLASS,
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 0, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 0, 1),
                        'productscount' => 0,
                        'parentid' => $parent_id,
                    );
                    if (!empty($parent_id)) {
                        $parent_array[] = $parent_id;
                    }
                }
            }
            if (empty($_result_array)) {
                $result_array['msg'] = Yii::t('user', 'Category list got failed!');
            } else {
                // filter parent id
                foreach ($_result_array as $_index => $_array) {
                    foreach ($parent_array as $key => $_parent_id) {
                        if ($_array['id'] == $_parent_id) {
                            unset($_result_array[$_index]);
                            break;
                        }
                    }
                }
                foreach ($_result_array as $key => $_array) {
                    $result_array['list'][] = $_array;
                }
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Category list got success!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionSetcategory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        if (self::USE_ABIKTV_STORE) {
            $rest = $this->rest;
            //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->get('', array('route' => self::SHOP_API_CATEGORIES, 'parent' => 0, 'level' => 1));

            // Process API response
            $json_array = json_decode($json, TRUE);
            if (!is_null($json_array)) {
                if (isset($json_array['success']) && $json_array['success']) {
                    $result_array['result'] = self::Success;
                    if (isset($json_array['categories']) && !empty($json_array['categories']) && is_array($json_array['categories'])) {
                        foreach ($json_array['categories'] as $key => $_category) {
                            if (isset($_category) && !empty($_category) && is_array($_category)) {
                                $result_array['list'][] = array(
                                    'id' => ($_category['category_id']),
                                    'name' => $_category['name'],
                                    'smallpicurl' => $_category['image'],
                                    'bigpicurl' => $_category['image_popup'],
                                    'productscount' => intval($_category['total']),
                                    'parentid' => '',
                                );
                            }
                        }
                        $result_array['msg'] = Yii::t('user', 'Category list got success!');
                    } else {
                        $result_array['msg'] = Yii::t('user', 'No categories!');
                    }
                } else {
                    $result_array['msg'] = $json_array['message'];
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Category list got failed!');
            }
        } else {
            // TODO check cached set category
            if (file_exists(Yii::app()->getRuntimePath() . "/ERP_SET_CATEGORY.php")) {
                $_category_array = require ( Yii::app()->getRuntimePath() . "/ERP_SET_CATEGORY.php" );
                if (is_array($_category_array)) {
                    $current_time = time();
                    $last_time = isset($_category_array['current_time']) ? $_category_array['current_time'] : 0;
                    $time_diff = $current_time - $last_time;
                    if ($time_diff < (3600 * 24) && isset($_category_array['list']) && is_array($_category_array['list'])) {
                        // TODO 1 day valid record
                        $result_array['result'] = self::Success;
                        $result_array['msg'] = Yii::t('user', 'Category list got success!');
                        $result_array['list'] = $_category_array['list'];
                        // Return response
                        $this->sendResults($result_array);
                    }
                }
            }

            // otherwise, query from ERP server
            $respXML = $this->soapClient->WS_GetMenuClass($this->_roomKey, $this->erp_userid, '0');
            $xml = simplexml_load_string($respXML);
            if ($xml !== FALSE) {
                $parent_array = array();
                $_result_array = array();
                foreach ($xml->MenuClass as $_class) {
                    $parent_id = ((string) $_class->attributes()->F_PARENTID);
                    $_result_array[] = array(
                        'id' => ((string) $_class->attributes()->F_CLASSID),
                        'name' => (string) $_class->attributes()->F_CLASS,
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 0, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 0, 1),
                        'productscount' => 0,
                        'parentid' => $parent_id,
                    );
                    if (!empty($parent_id)) {
                        $parent_array[] = $parent_id;
                    }
                }
            }
            if (empty($_result_array)) {
                $result_array['msg'] = Yii::t('user', 'Category list got failed!');
            } else {
                // filter parent id
                foreach ($_result_array as $_index => $_array) {
                    foreach ($parent_array as $key => $_parent_id) {
                        if ($_array['id'] == $_parent_id) {
                            unset($_result_array[$_index]);
                            break;
                        }
                    }
                }
                foreach ($_result_array as $key => $_array) {
                    $result_array['list'][] = $_array;
                }
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Category list got success!');
            }
        }

        // save to category file
        $_category_saved['current_time'] = time();
        $_category_saved['list'] = $result_array['list'];
        $file_content = "<?php\n\n";
        $file_content .= "return " . var_export($_category_saved, TRUE) . ";\n";
        file_put_contents(Yii::app()->getRuntimePath() . "/ERP_SET_CATEGORY.php", $file_content);

        // Return response
        $this->sendResults($result_array);
    }

    public function actionListbycategory() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_category_id = Yii::app()->request->getQuery('categoryid');
        $_category_id = empty($_category_id) ? '' : ($_category_id);
        $_keyword = Yii::app()->request->getQuery('keyword');
        $_keyword = empty($_keyword) ? '' : $_keyword;

        if (self::USE_ABIKTV_STORE) {
            $rest = $this->rest;
            //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->get('', array('route' => self::SHOP_API_PRODUCTS, 'category' => $_category_id, 'name' => ''));
            //$json = file_get_contents(self::SHOP_API_URL . '?route=' . self::SHOP_API_PRODUCTS . '&category=' . $_category_id);
            //$this->sendResults($json);
            // Process API response
            $json_array = json_decode($json, TRUE);
            if (!is_null($json_array)) {
                if (isset($json_array['success']) && $json_array['success']) {
                    $result_array['result'] = self::Success;
                    if (isset($json_array['products']) && !empty($json_array['products']) && is_array($json_array['products'])) {
                        foreach ($json_array['products'] as $key => $_product) {
                            if (isset($_product) && !empty($_product) && is_array($_product)) {
                                $result_array['list'][] = array(
                                    'id' => ($_product['id']),
                                    'name' => $_product['name'],
                                    'description' => $_product['description'],
                                    'smallpicurl' => $_product['thumb'],
                                    'bigpicurl' => $_product['image_popup'],
                                    'price' => floatval($_product['price_float']) + 0.00,
                                    'unit' => '',
                                    'is_setmenu' => 0,
                                    'is_stopsell' => 0,
                                );
                            }
                        }
                        $result_array['msg'] = Yii::t('user', 'Products by category list got success!');
                    } else {
                        $result_array['msg'] = Yii::t('user', 'No products!');
                    }
                } else {
                    $result_array['msg'] = $json_array['message'];
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Products by category list got failed!');
            }
        } else {
            $respXML = $this->soapClient->WS_GetMenuItem($this->_roomKey, $_category_id, $this->erp_userid, '', '0');
            //$respXML = $result->WS_GetMenuItemResult;
            $xml = simplexml_load_string($respXML);
            if ($xml !== FALSE) {
                $bool_array = array('T' => 1, 't' => 1, 'F' => 0, 'f' => 0, '' => 0);
                foreach ($xml->MenuItem as $_class) {
                    $result_array['list'][] = array(
                        'id' => ((string) $_class->attributes()->F_POSITEMID),
                        'name' => (string) $_class->attributes()->F_POSITEM,
                        'description' => (string) $_class->attributes()->F_POSITEM,
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 1),
                        'price' => floatval((string) $_class->attributes()->F_PRICE) + 0.00,
                        'unit' => (string) $_class->attributes()->F_POSUNIT,
                        'is_setmenu' => $bool_array[(string) $_class->attributes()->F_ISSETMENU],
                        'is_stopsell' => $bool_array[(string) $_class->attributes()->F_ISSTOPSELL],
                    );
                }
            }
            if (empty($result_array['list'])) {
                $result_array['msg'] = Yii::t('user', 'Products by category list got failed!');
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'Products by category list got success!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionSetmenuclass() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }
        $_item_id = Yii::app()->request->getQuery('setid');
        $_item_id = empty($_item_id) ? '' : ($_item_id);

        if (self::USE_ABIKTV_STORE) {
            // do nothing
        } else {
            $respXML = $this->soapClient->WS_GetSetMenuClass($this->_roomKey, $_item_id);
            $xml = simplexml_load_string($respXML);
            if ($xml !== FALSE) {
                $parent_array = array();
                foreach ($xml->SetClass as $_class) {
                    $result_array['list'][] = array(
                        'id' => ((string) $_class->attributes()->F_SETCLASSID),
                        'name' => (string) $_class->attributes()->F_SETCLASS,
                        'limitedqty' => intval((string) $_class->attributes()->F_LIMITEDQTY),
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 1),
                    );
                }
            }
            if (empty($result_array['list'])) {
                $result_array['msg'] = Yii::t('user', 'SetMenu Category list got failed!');
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'SetMenu Category list got success!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionSetmenuitem() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_item_id = Yii::app()->request->getQuery('setid');
        $_item_id = empty($_item_id) ? '' : ($_item_id);
        $_item_class_id = Yii::app()->request->getQuery('setclassid');
        $_item_class_id = empty($_item_class_id) ? '' : ($_item_class_id);

        if (self::USE_ABIKTV_STORE) {
            // do nothing
        } else {
            $respXML = $this->soapClient->WS_GetSetMenuItem($this->_roomKey, $_item_id, $_item_class_id);
            //$respXML = $result->WS_GetMenuItemResult;
            $xml = simplexml_load_string($respXML);
            if ($xml !== FALSE) {
                $bool_array = array('T' => 1, 't' => 1, 'F' => 0, 'f' => 0, '' => 0);
                foreach ($xml->SetItem as $_class) {
                    $result_array['list'][] = array(
                        'id' => ((string) $_class->attributes()->F_SUBITEMID),
                        'name' => (string) $_class->attributes()->F_POSITEM,
                        'price' => floatval((string) $_class->attributes()->F_PRICE) + 0.00,
                        'unit' => (string) $_class->attributes()->F_POSUNIT,
                        'quantity' => intval((string) $_class->attributes()->F_QUANTITY),
                        'maximalqty' => intval((string) $_class->attributes()->F_MAXIMALQTY),
                        'smallpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 0),
                        'bigpicurl' => $this->getPicAttachUrl((string) $_class->attributes()->F_PICTFILE, 1, 1),
                    );
                }
            }
            if (empty($result_array['list'])) {
                $result_array['msg'] = Yii::t('user', 'SetMenu Products list got failed!');
            } else {
                $result_array['result'] = self::Success;
                $result_array['msg'] = Yii::t('user', 'SetMenu Products list got success!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionListbyname() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'list' => array(),
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_name = Yii::app()->request->getQuery('keyword');
        $_name = empty($_name) ? '' : $_name;

        if (self::USE_ABIKTV_STORE) {
            $rest = $this->rest;
            //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->get('', array('route' => self::SHOP_API_PRODUCTS, 'category' => 0, 'name' => $_name));

            // Process API response
            $json_array = json_decode($json, TRUE);
            if (!is_null($json_array)) {
                if (isset($json_array['success']) && $json_array['success']) {
                    $result_array['result'] = self::Success;
                    if (isset($json_array['products']) && !empty($json_array['products']) && is_array($json_array['products'])) {
                        foreach ($json_array['products'] as $key => $_product) {
                            if (isset($_product) && !empty($_product) && is_array($_product)) {
                                $result_array['list'][] = array(
                                    'id' => intval($_product['id']),
                                    'category_id' => intval($_product['category_id']),
                                    'name' => $_product['name'],
                                    'description' => $_product['description'],
                                    'smallpicurl' => $_product['thumb'],
                                    'bigpicurl' => $_product['image_popup'],
                                    'price' => floatval($_product['price_float']) + 0.00,
                                    'unit' => '',
                                );
                            }
                        }
                        $result_array['msg'] = Yii::t('user', 'Products search list got success!');
                    } else {
                        $result_array['msg'] = Yii::t('user', 'No products!');
                    }
                } else {
                    $result_array['msg'] = $json_array['message'];
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Products search list got failed!');
            }
        }

        // Return response
        $this->sendResults($result_array);
    }

    public function actionDetail() {
        // Response format data
        $result_array = array(
            'result' => self::BadRequest,
            'msg' => Yii::t('user', 'Request method illegal!'),
            'id' => 0,
            'category_id' => 0,
            'category_name' => "",
            'name' => "",
            'description' => "",
            'smallpicurl' => "",
            'bigpicurl' => "",
            'price' => 0.00,
            'unit' => "",
        );
        // Check request type
        $request_type = Yii::app()->request->getRequestType();
        if ('GET' != $request_type) {
            $this->sendResults($result_array, self::BadRequest);
        }

        $_productid = Yii::app()->request->getQuery('productid');
        $_productid = empty($_productid) ? 0 : intval($_productid);

        if (self::USE_ABIKTV_STORE) {
            $rest = $this->rest;
            //$rest->set_header('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            $json = $rest->get('', array('route' => self::SHOP_API_PRODUCT, 'id' => $_productid));

            // Process API response
            $json_array = json_decode($json, TRUE);
            if (!is_null($json_array)) {
                if (isset($json_array['success']) && $json_array['success']) {
                    $result_array['result'] = self::Success;
                    if (isset($json_array['product']) && !empty($json_array['product']) && is_array($json_array['product'])) {
                        $_product = $json_array['product'];
                        $result_array = array(
                            'result' => self::Success,
                            'msg' => Yii::t('user', 'Product detail information got success!'),
                            'id' => intval($_product['id']),
                            'category_id' => intval($_product['category_id']),
                            'category_name' => $_product['category_name'],
                            'name' => $_product['name'],
                            'description' => $_product['description'],
                            'smallpicurl' => $_product['thumb'],
                            'bigpicurl' => $_product['image_popup'],
                            'price' => floatval($_product['price_float']) + 0.00,
                            'unit' => '',
                        );
                    } else {
                        $result_array['msg'] = Yii::t('user', 'No product detail information!');
                    }
                } else {
                    $result_array['msg'] = $json_array['message'];
                }
            } else {
                $result_array['msg'] = Yii::t('user', 'Product detail information got failed!');
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
