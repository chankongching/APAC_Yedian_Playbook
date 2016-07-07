<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array();
        return array(
            array('allow', // allow all users to access site actions
                'controllers' => array('site'),
                //'actions' => array('contact', 'login', 'index', 'error'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to access index and view actions
                //'controllers' => array('site'),
                'actions' => array('index', 'view'),
                'users' => array('@'),
                'roles' => array('Member', 'member', 'reader'),
            ),
            array('allow', // allow authenticated user to perform user 'view' and 'update' actions
                'controllers' => array('adminuser'),
                'actions' => array('view', 'update'),
                'users' => array('@'),
                'roles' => array('Member', 'member', 'reader'),
            ),
            array('allow', // allow authenticated user to perform user 'view' and 'update' actions
                'actions' => array('view', 'update', 'create'),
                'users' => array('@'),
                'roles' => array('Member', 'member'),
            ),
            array('allow', // allow admin user to perform all actions
                'users' => array('@'),
                'roles' => array('Super', 'super'),
            ),
            array('deny', // otherwise deny all users to all actions
                'users' => array('*'),
            ),
        );
    }

    /**
     * auto rights controll
     * @return type
     */
    public function filters() {
        //parent::filters();
        return array(
            'rights',
        );
    }

    /**
     * allowed actions
     * Format： ControllerName.*, ControllerName.ActionName, ActionName
     * @return string
     */
    public function allowedActions() {
        //parent::allowedActions();
        return 'Site.*';
    }

}
