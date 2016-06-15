<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/22
 * Time: 17:41
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$get = Yii::$app->request->get();
$startTimeSearch = $get['startTime'] ?? '';
$endTimeSearch = $get['endTime'] ?? '';
/* @var $this yii\web\View */
/* @var $model backend\models\search\Article */
/* @var $form yii\widgets\ActiveForm */
/* @var $cateList  */
?>

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline navbar-btn', 'id' => 'search-form'],
    ]); ?>
    <div class="form-group field-resource-title">
        <label class="control-label">资源名</label>
        <input id="startTime" type="text" class="form-control layer-date" name="startTime" value="<?php echo $startTimeSearch?>">
    </div>
    <div class="form-group field-resource-title">
        <label class="control-label">资源名</label>
        <input id="endTime" type="text" class="form-control layer-date" name="endTime" value="<?php echo $endTimeSearch?>">
    </div>

    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?php ActiveForm::end(); ?>
<?php
$js = <<<JS
var start = {
    elem: '#startTime',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: '2000-00-00 00:00:00', //设定最小日期为当前日期
    max: '2099-06-16 23:59:59', //最大日期
    istime: true,
    istoday: false,
    choose: function(datas){
         end.min = datas; //开始日选好后，重置结束日的最小日期
         end.start = datas //将结束日的初始值设定为开始日
    }
};
var end = {
    elem: '#endTime',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: '2000-00-00 00:00:00',
    max: '2099-06-16 23:59:59',
    istime: true,
    istoday: false,
    choose: function(datas){
        start.max = datas; //结束日选好后，重置开始日的最大日期
    }
};
laydate(start);
laydate(end);
JS;
$this->registerJs($js);
?>