<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/hplus');
$this->registerCssFile($directoryAsset.'/css/bootstrap.min14ed.css?v=3.3.6');
$this->registerCssFile($directoryAsset.'/css/font-awesome.min93e3.css?v=4.4.0');
$this->registerCssFile($directoryAsset.'/css/animate.min.css');
$this->registerCssFile($directoryAsset.'/css/style.min862f.css?v=4.1.0');

$this->registerJsFile($directoryAsset.'/js/bootstrap.min.js?v=3.3.6');
?>
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">H+</h1>
        </div>
        <h3>欢迎使用 普安医师助手</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'm-t'],
            'enableClientValidation' => false
        ]); ?>
        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')])?>
        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])?>
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="input-group  form-group required"><span class="input-group-addon"><i class="glyphicon glyphicon-eye-open red"></i></span>{input}<div class="input-group-addon" style="padding:0px; cursor: pointer" title
="点击更换验证码">{image}</div></div>',
            'options' => ['class' => 'form-control','placeholder'=>"验证码"]
        ])->label(false)?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
        </div>
        <?= Html::submitButton('登录', ['class' => 'btn btn-primary block full-width m-b', 'name' => 'login-button']) ?>
        <?php ActiveForm::end();?>

        </form>
    </div>
</div>


