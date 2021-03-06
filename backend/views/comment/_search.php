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
$titleSearch = $get['title'] ?? '';
$typeSearch = $get['type'] ?? '';
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
    <div class="form-group field-resource-title required">
        <label class="control-label">资源名</label>
        <input type="text" class="form-control" name="title" value="<?php echo $titleSearch?>">
    </div>
    <div class="form-group field-resource-rid required">
        <label class="control-label">所属目录</label>
        <select class="form-control" name="type">
            <?php
                $optionHtml = '';
                foreach ($cateList as $key => $val){
                    $optionHtml .= '<option value="' . $key . '" ';
                    if($typeSearch){
                        if($typeSearch == $key){
                            $optionHtml .= 'selected="selected" ';
                        }
                    }else{
                        if(1 == $key){
                            $optionHtml .= 'selected="selected" ';
                        }
                    }
                    $optionHtml .= '>' . $val . '</option>';
                }
            echo $optionHtml;
            ?>
        </select>
    </div>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::button('启用', ['class' => 'btn btn-info','data-toggle'=> 'enable']) ?>
    <?= Html::button('禁用', ['class' => 'btn btn-warning','data-toggle'=> 'disable']) ?>
    <?php ActiveForm::end(); ?>
<?php
$js = <<<JS
   /*删除*/
    $('[data-toggle="del"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要删除选中的信息吗",
            text: "删除后将无法恢复，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','del');
            swal("已删除！", "您已经永久删除了信息。", "success");
        });
    });
    /*启用*/
    $('[data-toggle="enable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要启用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#23c6c8",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','enable');
            swal("已启用！", "", "success");
        });
    });
    /*禁用*/
    $('[data-toggle="disable"]').click(function() {
        /*判断是否有选中*/
        var check = $('#changeStatus').find('input[name="selection[]"]');
        var verifyChecked = verifyCheckedForMime(check);
        if(false == verifyChecked){
            return false;
        }
        swal({
            title: "您确定要禁用选中的信息吗",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: "#f8ac59",
            confirmButtonText: "确定",
            closeOnConfirm: false
        }, function () {
            subActionForMime('#typeForm','disable');
            swal("已禁用！", "", "success");
        });
    });
JS;
$this->registerJs($js);
?>