<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Member;
use common\models\ExamLevel;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '试卷统计';
$this->params['breadcrumbs'][] = $this->title;

$get = $yiiApp->request->get();
$usernameSearch = $get['username'] ?? '';
$exa_id = $get['exa_id'];

$this->params['stats']['examInfo'] = $examInfo;
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body">
        <div class="hospital-search">
            <?php
            $form = ActiveForm::begin([
                'action' => ['exam-info', 'exa_id' => $exa_id],
                'method' => 'get',
                'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
            ]); ?>
            <?= Html::a('返回', $referrerUrl, ['class' => 'btn btn-white']) ?>
            <div class="form-group">
                <label class="control-label">手机号</label>
                <input type="text" class="form-control" name="username" value="<?php echo $usernameSearch?>">
            </div>

            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('导出', [
                'exam-info-export',
                'exa_id' => $exa_id,
                'username' => $usernameSearch,
            ], ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '序号'
                    ],
                    [
                        'attribute' => 'name',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['examInfo']['name'];
                            },
                    ],
                    [
                        'attribute' => 'real_name',
                        'value'=>
                            function($model){
                                $result = Member::findOne($model->uid);
                                $this->params['stats']['username'] = $result->username ?? '';
                                $this->params['stats']['nickname'] = $result->nickname ?? '';
                                return  $result->real_name ?? '';
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['username'];
                            },
                    ],
                    [
                        'attribute' => 'times',
                        'value'=>
                            function($model){
                                return  date('Y-m-d H:i:s',$model->start_time) . '～' . date('Y-m-d H:i:s',$model->end_time);
                            },
                    ],
                    [
                        'attribute' => 'rate',
                        'value'=>
                            function($model){
                                $examLogModel = $model::find()->where(['uid' => $model->uid, 'exa_id' => $model->exa_id])->max('answers');
                                $result = 0;
                                if($this->params['stats']['examInfo']['examLength'] > 0){
                                    $result = round($examLogModel * 100 / $this->params['stats']['examInfo']['examLength']);
                                }
                                /*等级*/
                                $rateExam = $this->params['stats']['examInfo']['rateExam'];
                                ksort($rateExam);
                                $level = '未知';
                                foreach ($rateExam as &$val){
                                    switch ($val['condition']){
                                        case 0:
                                            if($result == $val['rate']){
                                                $level = $val['level'];
                                            };
                                            break;
                                        case 1:
                                            if($result >= $val['rate']){
                                                $level = $val['level'];
                                            };
                                            break;
                                        case -1:
                                            if($result < $val['rate']){
                                                $level = $val['level'];
                                            };
                                            break;
                                    }
                                }
                                $this->params['stats']['level'] = $level;
                                return  $result . '%';
                            },
                    ],
                    [
                        'attribute' => 'level',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['level'];
                            },
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
