<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 14:48
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(['action' => ['message/create'],'method'=>'post','id'=>'tableForm']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <label class="control-label">推送范围：
                <label class="checkbox-inline">
                    <input type="radio" name="push_type" id="rdo1" class="radioItem"
                           value="1" checked> 全部用户
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="push_type" id="rdo2" class="radioItem"
                           value="0"> 指定用户
                </label>
            </label>
        </div>

        <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'id')->input('hidden')->label(false) ?>
    </div>
    <div class="modal-footer">
        <button id="btnSave" type="button" class="btn btn-white" data-dismiss="modal">保存，稍后发送</button>
        <button id="btnSend" type="button" class="btn btn-primary" data-dismiss="modal">发送</button>
        <input id="type" name="type" type="hidden">
        <input id="post_type" name="post_type" type="hidden">
    </div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $('.radioItem').change(function(){
        var valOption = $('input[name="type"]:checked').val();
        if(valOption =='0') {
            layer.open({
              type: 2,
              title: '填写人员手机',
              shadeClose: false,
              shade: 0.3,
              closeBtn:1,
              area: ['400px', '50%'],
              content: '/message/member', //iframe的url

            });
        }
    });

    $('#btnSave').click(function(){
        $('#type').val('save');
        $("#tableForm").submit();
    });

    $('#btnSend').click(function(){
        $('#type').val('send');
        $("#tableForm").submit();
    });
JS;
$this->registerJs($js);
?>