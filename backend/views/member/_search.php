<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'options' => ['class' => 'form-inline','enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'real_name') ?>
    <?= $form->field($model, 'hospital_id')->dropDownList(array_flip(array_merge(['全部' => ''], array_flip(\common\models\Hospital::find()->select('name')->indexBy('id')->column())))) ?>
    <?= Html::a('添加用户', ['create'], ['class' => 'btn btn-success animation_select','data-animation'=>'fadeInDown']) ?>
    <?= FileUpload::widget([
        'model' => $uploadModel,
        'attribute' => 'file',
        'url' => ['index'],
    ]);?>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::a('导出','export', ['class' => 'btn btn-info']) ?>
    <?= Html::a('启用', "javascript:void(0);", ['class' => 'btn btn-primary','id'=> 'enable']) ?>
    <?= Html::a('禁用', "javascript:void(0);", ['class' => 'btn btn-warning','id'=> 'disable']) ?>
    <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger','id'=> 'del']) ?>
    <?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $(function() {
      /*删除*/
      $('#del').click(function() {
        subActionForMamber('typeForm','del');
      });
      /*禁用*/
       $('#disable').click(function() {
        subActionForMamber('typeForm','disable');
      });
      /*启用*/
       $('#enable').click(function() {
        subActionForMamber('typeForm','enable');
      });
    });
    function subActionForMamber(formId,val) {
        $('#' + formId).val(val);
        $('#'+formId).submit();
    }
JS;
$this->registerJs($js);
?>


</div>