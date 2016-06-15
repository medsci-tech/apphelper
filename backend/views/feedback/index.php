<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\Hospital;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $directoryStructureSearch */
/* @var $dataProvider */
$this->title = '反馈列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = Yii::$app->params;
backend\assets\AppAsset::register($this);
/*根据get参数判断是否是考卷添加试题*/
?>

<div class="modal-body">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', [
                'model' => $searchModel,
            ]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['delete'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'changeStatus'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }
                    ],
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'real_name',
                        'value' =>
                            function ($model) {
                                $result = Member::findOne($model->uid);
                                $this->params['comment']['nickname'] = $result->nickname ?? '';
                                $this->params['comment']['username'] = $result->username ?? '';
                                return $result->real_name ?? '';
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['username'];
                            },
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{view}',//只需要展示删除和更新
                        'buttons' => [
                            'view'=> function ($url, $model, $key) {
                                $imgurl = json_encode(unserialize($model->imgurl));
                                $aHtml = '<span class="glyphicon glyphicon-eye-open" data-toggle="modal" data-target="#formModal" data-content=' . $model->content . ' data-imgurl=' . $imgurl . ' ></span>';
                                return Html::a($aHtml);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- 弹出层 -->
<div class="modal inmodal" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">反馈详情</h4>
            </div>
            <?= $this->render('_form'); ?>
        </div>
    </div>
</div>

<?php
$js=<<<JS
    /*查看详情*/
    $('[data-toggle="modal"]').click(function(){
        var content = $(this).attr('data-content');
        var imgurl = JSON.parse($(this).attr('data-imgurl'));
        console.log(imgurl);
        console.log(content);
        var html = '';
        if(imgurl){
            var listLen = imgurl.length;
            for(var i = 0; i < listLen; i++){
                var fix = '';
                var pattern = /^http:\/\//i;
                var preg = pattern.test(imgurl[i]);
                if(false == preg){
                    fix = 'http://';
                }
                html += '<img class="img-thumbnail"  src="' + fix + imgurl[i] + '">';
            }
        }

        $('[data-toggle="form-content"]').text(content);
        $('[data-toggle="form-img"]').html(html);
    });
JS;
$this->registerJs($js);

?>