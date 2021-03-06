<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/20
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '考题库';
$this->params['breadcrumbs'][] = $this->title;
?>
<p></p>
<div class="classquestion-index">
    <div>
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'Ctype',
                    'Csame',
                    'Enable',
//                [
//                    'attribute' => 'province_id',
//                    'value' =>
//                        function ($model) {
//                            $result = Region::findOne($model->province_id);
//                            return $result->name;
//                        },
//                ],
//                [
//                    'attribute' => 'city_id',
//                    'value' =>
//                        function ($model) {
//                            $result = Region::findOne($model->city_id);
//                            return $result->name;
//                        },
//                ],
//                [
//                    'attribute' => 'area_id',
//                    'value' =>
//                        function ($model) {
//                            $result = Region::findOne($model->area_id);
//                            return $result->name;
//                        },
//                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}  {update} {delete}',
                    'header' => '操作',
                    'buttons' => [
                        'update' =>
                            function ($url, $model, $key) {
                                return Html::a('<span name="del" class="glyphicon glyphicon-pencil" id="' . $model->id . '"></span>');
                            },
                    ]
                ]
                ],
             ]); ?>
        </div>
    </div>
</div>

<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">单位发布</h4>
            </div>
            <?=$this->render('create', [
                'model' => $model,
            ]);?>
        </div>
    </div>
</div>
</div>
<?php
$js=<<<JS
 $(document).ready(function(){
    $('div').removeClass('container-fluid'); // 去除多余样式

    $("span[name='del']").click(function(){
        alert($(this).attr('id'));
    });

 });
JS;
$this->registerJs($js);
?>