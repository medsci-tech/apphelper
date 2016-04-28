<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="hospital-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'question') ?>
    <?= Html::button('添加', ['class' => 'btn btn-success animation_select','id'=>'createBtn','data-toggle'=>'modal','data-target'=>'#formModal']) ?>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::a('启用', 'javascript:void(0);', ['class' => 'btn btn-primary','id'=> 'enable']) ?>
    <?= Html::a('禁用', 'javascript:void(0);', ['class' => 'btn btn-warning','id'=> 'disable']) ?>
    <?= Html::a('批量删除', 'javascript:void(0);', ['class' => 'btn btn-danger', 'id'=> 'del']) ?>
    <?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $(function() {
      /*删除*/
      $('#del').click(function() {
        var cf = confirm("Press a button");
        if(cf){
            subActionForMamber('typeForm','del');
        }else {
            return false;
        }
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