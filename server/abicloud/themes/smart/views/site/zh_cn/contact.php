<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->layout = '/layouts/simple';

//$cs=Yii::app()->clientScript;
//$cs->registerCoreScript('yiiactiveform');

$this->pageTitle = Yii::t('site', Yii::app()->name) . ' - ' . Yii::t('site', 'Contact Us');
$this->breadcrumbs = array(
    'Contact',
);

$error_info = '';
?>
<style type="text/css">
    div.errorMessage {
        color: #BC628B;
        margin-bottom: 10px;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
    }
</style>

<?php if (isset($this->breadcrumbs)): ?>
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => $this->breadcrumbs,
        'htmlOptions' => array('class' => 'breadcrumb'),
    ));
    ?><!-- breadcrumbs -->
<?php endif ?>

<div class="row-fluid">
    <div class="span12 center login-header">
        <h2><?php echo CHtml::encode(Yii::t('site', Yii::app()->name)); ?></h2>
        <h3><?php echo Yii::t('site', 'Contact us'); ?></h3>
    </div><!--/span-->
</div><!--/row-->

<div class="row-fluid">
    <div class="well span5 center login-box">

        <?php if (Yii::app()->user->hasFlash('contact')): ?>

            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('contact'); ?>
            </div>

        <?php else: ?>

            <p>
                If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
            </p>

            <div class="form">

                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'contact-form',
                        //'enableClientValidation' => true,
                        //'clientOptions' => array(
                        //    'validateOnSubmit' => true,
                        //),
                ));
                ?>

                <p class="note">Fields with <span class="required">*</span> are required.</p>

                <?php echo $form->errorSummary($model); ?>

                <div class="row">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name'); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email'); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'subject'); ?>
                    <?php echo $form->textField($model, 'subject', array('size' => 60, 'maxlength' => 128)); ?>
                    <?php echo $form->error($model, 'subject'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'body'); ?>
                    <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
                    <?php echo $form->error($model, 'body'); ?>
                </div>

                <?php if (CCaptcha::checkRequirements()): ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'verifyCode'); ?>
                        <div>
                            <?php $this->widget('CCaptcha'); ?>
                            <?php echo $form->textField($model, 'verifyCode'); ?>
                        </div>
                        <div class="hint">Please enter the letters as they are shown in the image above.
                            <br/>Letters are not case-sensitive.</div>
                        <?php echo $form->error($model, 'verifyCode'); ?>
                    </div>
                <?php endif; ?>

                <div class="row buttons">
                    <?php echo CHtml::submitButton('Submit'); ?>
                </div>

                <?php $this->endWidget(); ?>

            </div><!-- form -->

        <?php endif; ?>

    </div><!--/span-->
</div><!--/row-->

