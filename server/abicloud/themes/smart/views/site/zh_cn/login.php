<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->layout = '/layouts/simple';

//$cs=Yii::app()->clientScript;
//$cs->registerCoreScript('yiiactiveform');
$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Login');
$this->breadcrumbs = array(
    'Login',
);

$error_info = '';
if (isset($model->errors['password']) && !empty($model->errors['password'])) {
    $error_info = implode("<br>", $model->errors['password']);
}
//print_r($model);
?>
<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-bottom: 10px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.yiiactiveform.js"></script>

<div class="row-fluid">
    <div class="span12 center login-header">
        <h2><?php echo CHtml::encode(Yii::t('site', Yii::app()->name)); ?></h2>
    </div><!--/span-->
</div><!--/row-->

<div class="row-fluid">
    <div class="well span5 center login-box">
        <div class="alert alert-info">
            <?php
            if (empty($error_info)) {
                echo Yii::t('site', 'Please login with your Username and Password.');
            } else {
                echo '<span style="color:#BC628B">' . Yii::t('site', $error_info) . '</span>';
            }
            ?>
        </div>
        <form class="form-horizontal" id="login-form" action="<?php echo $this->createUrl('site/login'); ?>" method="post">
            <fieldset>
                <div class="input-prepend" title="用户名" data-rel="tooltip">
                    <span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="LoginForm[username]" id="LoginForm_username" type="text" value="<?php echo CHtml::encode($model->username); ?>" />
                </div>
                <div class="errorMessage" id="LoginForm_username_em_" style="display:none"></div>
                <div class="clearfix"></div>

                <div class="input-prepend" title="密码" data-rel="tooltip">
                    <span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="LoginForm[password]" id="LoginForm_password" type="password" value="<?php echo CHtml::encode($model->password); ?>" />
                </div>
                <div class="errorMessage" id="LoginForm_password_em_" style="display:none"></div>
                <div class="clearfix"></div>

                <div class="input-prepend">
                    <label class="remember" for="remember"><input type="checkbox" name="LoginForm[rememberMe]" id="LoginForm_rememberMe" value="1" />十天内免登录</label>
                </div>
                <div class="clearfix"></div>

                <p class="center span5">
                    <button type="submit" name="yt0" class="btn btn-primary">登录</button>
                </p>
            </fieldset>
        </form>
    </div><!--/span-->
</div><!--/row-->


<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery('#login-form').yiiactiveform({'validateOnSubmit': true, 'attributes': [{'id': 'LoginForm_username', 'inputID': 'LoginForm_username', 'errorID': 'LoginForm_username_em_', 'model': 'LoginForm', 'name': 'username', 'enableAjaxValidation': false, 'clientValidation': function(value, messages, attribute) {

                        if (jQuery.trim(value) == '') {
                            messages.push("<?php echo Yii::t('site', 'Username cannot be blank.') ?>");
                        }

                    }}, {'id': 'LoginForm_password', 'inputID': 'LoginForm_password', 'errorID': 'LoginForm_password_em_', 'model': 'LoginForm', 'name': 'password', 'enableAjaxValidation': false, 'clientValidation': function(value, messages, attribute) {

                        if (jQuery.trim(value) == '') {
                            messages.push("<?php echo Yii::t('site', 'Password cannot be blank.') ?>");
                        }

                    }}, {'id': 'LoginForm_rememberMe', 'inputID': 'LoginForm_rememberMe', 'errorID': 'LoginForm_rememberMe_em_', 'model': 'LoginForm', 'name': 'rememberMe', 'enableAjaxValidation': false, 'clientValidation': function(value, messages, attribute) {

                        if (jQuery.trim(value) != '' && value != "1" && value != "0") {
                            messages.push("<?php echo Yii::t('site', 'Remember me next time must be either 1 or 0.') ?>");
                        }

                    }}], 'errorCss': 'error'});
    });
    /*]]>*/
</script>
