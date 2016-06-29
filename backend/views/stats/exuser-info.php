<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\Exam;
use common\models\ExamLevel;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '试卷统计';
$this->params['breadcrumbs'][] = $this->title;

$get = $yiiApp->request->get();
$nameSearch = $get['name'] ?? '';
$uid = $get['uid'];

$this->params['stats']['memberInfo'] = $memberInfo;
$referrerUrl = Yii::$app->request->referrer ?? 'exuser';
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body">
        <div class="hospital-search">
            <?php
            $form = ActiveForm::begin([
                'action' => ['exuser-info', 'uid' => $uid],
                'method' => 'get',
                'options' => ['class' => 'form-inline navbar-btn','id'=>'searchForm'],
            ]); ?>
            <?= Html::a('返回', $referrerUrl ?? 'exuser', ['class' => 'btn btn-white']) ?>
            <div class="form-group">
                <label class="control-label">试卷名</label>
                <input type="text" class="form-control" name="name" value="<?php echo $nameSearch?>">
            </div>

            <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('导出', [
                'exuser-info-export',
                'uid' => $uid,
                'name' => $nameSearch,
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
                        'attribute' => 'real_name',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['memberInfo']['real_name'];
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['memberInfo']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value'=>
                            function($model){
                                return  $this->params['stats']['memberInfo']['username'];
                            },
                    ],
                    [
                        'attribute' => 'name',
                        'value'=>
                            function($model){
                                $examModel = Exam::findOne($model->exa_id);
                                $examLevelModel = ExamLevel::find()->where(['exam_id' => $examModel->id])->all();
                                $rateExam = [];
                                if($examLevelModel){
                                    foreach ($examLevelModel as $val){
                                        $rateExam[$val['rate']] = [
                                            'rate' => $val['rate'],
                                            'level' => $val['level'],
                                            'condition' => $val['condition'],
                                        ];
                                    }
                                }
                                if(1 == $examModel->type){
                                    $examLength = $examModel->total;
                                }else{
                                    $exe_ids = explode(',', $examModel->exe_ids);
                                    $examLength = count($exe_ids);
                                }
                                $examInfo = [
                                    'examLength' => $examLength,
                                    'rateExam' => $rateExam,
                                ];
                                $this->params['stats']['examInfo'] = $examInfo;
                                return  $examModel->name ?? '';
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
                                $result = 0;
                                if($this->params['stats']['examInfo']['examLength'] > 0){
                                    $result = round($model->answers * 100 / $this->params['stats']['examInfo']['examLength']);
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
