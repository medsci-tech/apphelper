<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/28
 * Time: 11:08
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */


?>

    <div class="user-search">

        <?php
        Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
        ]); ?>

        <?= $form->field($model, 'username') ?>

        <?= Html::button('查询', ['id'=>'btn_search','class' => 'btn btn-primary']) ?>
        <?= Html::button('添加', ['id'=>'btn_add','class' => 'btn btn-success','data-toggle'=>'modal','data-target'=>"#myModal"]) ?>

        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS

    $('#btn_search').click(function() {
        //getRegionValue('Hospital','searchForm');/*地区联动*/
        $(this).submit();
    });
JS;
$this->registerJs($js);
?>