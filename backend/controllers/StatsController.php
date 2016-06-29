<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use common\models\Exam;
use common\models\ExamLevel;
use common\models\ExamLog;
use common\models\Member;
use common\models\Resource;
use common\models\ResourceClass;
use common\models\ResourceStudy;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Cookie;
class StatsController extends BackendController
{

    /**
     * 资源统计--按资源统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160627
     * @return string
     */
    public function actionResource()
    {
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchResource($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-resource-html',
            'value' => $appYii->request->url,
        ]));
        return $this->render('resource', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 资源统计--按资源统计--一级详情页
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160627
     * @return string
     */
    public function actionResourceYi()
    {
        $queryParams = Yii::$app->request->queryParams;
        $rid = $queryParams['rid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceYi($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $resourceModel = Resource::findOne($rid);
        $attr_type = ResourceClass::findOne($resourceModel->rid)->attr_type;
        $resourceInfo = [
            'title' => $resourceModel->title ?? '',
            'attr_type' => Yii::$app->params['resourceClass']['attrType'][$attr_type],
        ];
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-resource-yi-html',
            'value' => Yii::$app->request->url,
        ]));
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-resource-html');
        return $this->render('resource-yi', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl ?? 'resource',
            'resourceInfo' => $resourceInfo,
        ]);
    }

    /**
     * 资源统计--按资源统计--二级详情页
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160627
     * @return string
     */
    public function actionResourceEr()
    {
        $queryParams = Yii::$app->request->queryParams;
        $rid = $queryParams['rid'];
        $uid = $queryParams['uid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceEr($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize'],
            ],
        ]);
        $resourceModel = Resource::findOne($rid);
        $attr_type = ResourceClass::findOne($resourceModel->rid)->attr_type;
        $resourceInfo = [
            'title' => $resourceModel->title ?? '',
            'attr_type' => Yii::$app->params['resourceClass']['attrType'][$attr_type],
        ];
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'nickname' => $memberModel->nickname ?? '',
            'username' => $memberModel->username ?? '',
        ];
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-resource-yi-html');
        return $this->render('resource-er', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl ?? 'resource',
            'memberInfo' => $memberInfo,
            'resourceInfo' => $resourceInfo,
        ]);
    }

    /**
     * 资源统计--按用户统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160628
     * @return string
     */
    public function actionReuser()
    {
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchReuser($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-reuser-html',
            'value' => $appYii->request->url,
        ]));
        return $this->render('reuser', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 资源统计--按资源统计--一级详情页
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160628
     * @return string
     */
    public function actionReuserYi()
    {
        $queryParams = Yii::$app->request->queryParams;
        $uid = $queryParams['uid'];
        $search = new ResourceStudy();
        $query = $search->searchReuserYi($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'nickname' => $memberModel->nickname ?? '',
            'username' => $memberModel->username ?? '',
        ];
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-reuser-yi-html',
            'value' => Yii::$app->request->url,
        ]));
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-reuser-html');
        return $this->render('reuser-yi', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl ?? 'reuser',
            'memberInfo' => $memberInfo,
        ]);
    }

    /**
     * 资源统计--按资源统计--二级详情页
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160628
     * @return string
     */
    public function actionReuserEr()
    {
        $queryParams = Yii::$app->request->queryParams;
        $rid = $queryParams['rid'];
        $uid = $queryParams['uid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceEr($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $resourceModel = Resource::findOne($rid);
        $attr_type = ResourceClass::findOne($resourceModel->rid)->attr_type;
        $resourceInfo = [
            'title' => $resourceModel->title ?? '',
            'attr_type' => Yii::$app->params['resourceClass']['attrType'][$attr_type],
        ];
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'nickname' => $memberModel->nickname ?? '',
            'username' => $memberModel->username ?? '',
        ];
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-reuser-yi-html');
        return $this->render('reuser-er', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl ?? 'reuser',
            'memberInfo' => $memberInfo,
            'resourceInfo' => $resourceInfo,
        ]);
    }

    /**
     * 资源统计--按资源统计--列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionResourceExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchResource($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $attrType = $appYii->params['resourceClass']['attrType'];
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $resource = Resource::findOne($val->rid);
            $resourceStudy = ResourceStudy::find()->where(['rid' => $val->rid]);
            $attr_type = $resource ? ResourceClass::findOne($resource->rid)->attr_type : 0;
            $dataArray[$key]['title'] = $resource->title ?? '';
            $dataArray[$key]['attr_type'] = $attrType[$attr_type];
            $dataArray[$key]['view'] = $resourceStudy->count('id');
            $dataArray[$key]['times'] = $resourceStudy->sum('times');
        }
        $column = [
            'title'=>['column'=>'A','name'=>'资源名','width'=>30],
            'attr_type'=>['column'=>'B','name'=>'资源类别','width'=>20],
            'view'=>['column'=>'C','name'=>'浏览数','width'=>10],
            'times'=>['column'=>'D','name'=>'时长(秒)','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 资源统计--按资源统计--一级列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionResourceYiExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $rid = $queryParams['rid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceYi($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $resourceModel = Resource::findOne($rid);
        $attr_type = ResourceClass::findOne($resourceModel->rid)->attr_type;
        $attr_type_name = Yii::$app->params['resourceClass']['attrType'][$attr_type];
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['title'] = $resourceModel->title ?? '';
            $dataArray[$key]['attr_type'] = $attr_type_name;
            $member = Member::findOne($val->uid);
            $dataArray[$key]['real_name'] = $member->real_name ?? '';
            $dataArray[$key]['username'] = $member->username ?? '';
            $dataArray[$key]['view'] = ResourceStudy::find()->where(['uid' => $val->uid, 'rid' => $val->rid])->count('id');
            $dataArray[$key]['times'] = ResourceStudy::find()->where(['uid' => $val->uid, 'rid' => $val->rid])->sum('times');
        }
        $column = [
            'title'=>['column'=>'A','name'=>'资源名','width'=>30],
            'attr_type'=>['column'=>'B','name'=>'资源类别','width'=>20],
            'real_name'=>['column'=>'C','name'=>'姓名','width'=>25],
            'username'=>['column'=>'D','name'=>'手机号','width'=>25],
            'view'=>['column'=>'E','name'=>'浏览数','width'=>20],
            'times'=>['column'=>'F','name'=>'时长(秒)','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 资源统计--按资源统计--二级列表导出公共部分
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     * @param $column
     */
    protected function actionResourceErExportCommon($column){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $rid = $queryParams['rid'];
        $uid = $queryParams['uid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceEr($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $resourceModel = Resource::findOne($rid);
        $attr_type = ResourceClass::findOne($resourceModel->rid)->attr_type;
        $resourceInfo = [
            'title' => $resourceModel->title ?? '',
            'attr_type' => Yii::$app->params['resourceClass']['attrType'][$attr_type],
        ];
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'username' => $memberModel->username ?? '',
        ];
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
            $dataArray[$key]['title'] = $resourceInfo['title'];
            $dataArray[$key]['attr_type'] = $resourceInfo['attr_type'];
            $dataArray[$key]['real_name'] = $memberInfo['real_name'];
            $dataArray[$key]['username'] = $memberInfo['username'];
            $dataArray[$key]['times'] = $val->times;
        }
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 资源统计--按资源统计--二级列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionResourceErExport(){
        $column = [
            'created_at'=>['column'=>'A','name'=>'浏览时间','width'=>25],
            'title'=>['column'=>'B','name'=>'资源名','width'=>25],
            'attr_type'=>['column'=>'C','name'=>'资源类别','width'=>20],
            'real_name'=>['column'=>'D','name'=>'姓名','width'=>20],
            'username'=>['column'=>'E','name'=>'手机号','width'=>25],
            'times'=>['column'=>'F','name'=>'时长(秒)','width'=>20],
        ];
        $this->actionResourceErExportCommon($column);
    }

    /**
     * 资源统计--按用户统计--列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionReuserExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchReuser($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $member = Member::findOne($val->uid);
            $resourceStudy = ResourceStudy::find()->where(['uid' => $val->uid]);
            $dataArray[$key]['real_name'] = $member->real_name ?? '';
            $dataArray[$key]['username'] = $member->username ?? '';
            $dataArray[$key]['view'] = $resourceStudy->count('id');
            $dataArray[$key]['times'] = $resourceStudy->sum('times');

        }
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>30],
            'username'=>['column'=>'B','name'=>'手机号','width'=>20],
            'view'=>['column'=>'C','name'=>'浏览数','width'=>10],
            'times'=>['column'=>'D','name'=>'时长(秒)','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 资源统计--按用户统计--一级列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionReuserYiExport(){
        $queryParams = Yii::$app->request->queryParams;
        $uid = $queryParams['uid'];
        $search = new ResourceStudy();
        $query = $search->searchReuserYi($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'username' => $memberModel->username ?? '',
        ];
        $attrType = Yii::$app->params['resourceClass']['attrType'];
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $resourceModel = Resource::findOne($val->rid);
            $attr_type = $resourceModel ? ResourceClass::findOne($resourceModel->rid)->attr_type : 0;
            $resourceStudy = ResourceStudy::find()->where(['rid' => $val->rid, 'uid' => $val->uid]);
            $dataArray[$key]['real_name'] = $memberInfo['real_name'];
            $dataArray[$key]['username'] = $memberInfo['username'];
            $dataArray[$key]['title'] = $resourceModel->title ?? '';
            $dataArray[$key]['attr_type'] = $attrType[$attr_type] ?? '';
            $dataArray[$key]['view'] = $resourceStudy->count('id');
            $dataArray[$key]['times'] = $resourceStudy->sum('times');
        }
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>30],
            'username'=>['column'=>'B','name'=>'手机号','width'=>20],
            'title'=>['column'=>'C','name'=>'资源名','width'=>25],
            'attr_type'=>['column'=>'D','name'=>'资源类别','width'=>25],
            'view'=>['column'=>'E','name'=>'浏览数','width'=>20],
            'times'=>['column'=>'F','name'=>'时长(秒)','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 资源统计--按用户统计--二级列表导出
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     */
    public function actionReuserErExport(){
        $column = [
            'created_at'=>['column'=>'A','name'=>'浏览时间','width'=>25],
            'real_name'=>['column'=>'B','name'=>'姓名','width'=>25],
            'username'=>['column'=>'C','name'=>'手机号','width'=>20],
            'title'=>['column'=>'D','name'=>'资源名','width'=>20],
            'attr_type'=>['column'=>'E','name'=>'资源类别','width'=>25],
            'times'=>['column'=>'F','name'=>'时长(秒)','width'=>20],
        ];
        $this->actionResourceErExportCommon($column);
    }


    /**
     * 考卷统计--按考卷统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160628
     * @return string
     */
    public function actionExam()
    {
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ExamLog();
        $query = $search->searchExam($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('exa_id'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-exam-html',
            'value' => Yii::$app->request->url,
        ]));
        return $this->render('exam', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 考卷统计--按考卷统计--详情
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     * @return string
     */
    public function actionExamInfo()
    {
        $queryParams = Yii::$app->request->queryParams;
        $exa_id = $queryParams['exa_id'];
        $search = new ExamLog();
        $query = $search->searchExamInfo($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $examModel = Exam::findOne($exa_id);
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
            'name' => $examModel->name ?? '',
            'examLength' => $examLength,
            'rateExam' => $rateExam,
        ];
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-exam-html');
        return $this->render('exam-info', [
            'dataProvider' => $dataProvider,
            'examInfo' => $examInfo,
            'referrerUrl' => $referrerUrl ?? 'exam',
        ]);
    }

    /**
     * 考卷统计--按用户统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionExuser()
    {
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ExamLog();
        $query = $search->searchExam($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-exuser-html',
            'value' => Yii::$app->request->url,
        ]));
        return $this->render('exuser', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 考卷统计--按用户统计--详情
     * @author zhaiyu
     * @startDate 20160628
     * @upDate 20160628
     * @return string
     */
    public function actionExuserInfo()
    {
        $queryParams = Yii::$app->request->queryParams;
        $uid = $queryParams['uid'];
        $search = new ExamLog();
        $query = $search->searchExuserInfo($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('exa_id'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'username' => $memberModel->username ?? '',
            'real_name' => $memberModel->real_name ?? '',
            'nickname' => $memberModel->nickname ?? '',
        ];
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-exuser-html');
        return $this->render('exuser-info', [
            'dataProvider' => $dataProvider,
            'memberInfo' => $memberInfo,
            'referrerUrl' => $referrerUrl ?? 'exuser',
        ]);
    }

    /**
     * 试卷统计--按资源统计--列表导出
     * @author zhaiyu
     * @startDate 20160629
     * @upDate 20160629
     */
    public function actionExamExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ExamLog();
        $query = $search->searchExam($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('exa_id'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['name'] = Exam::findOne($val->exa_id)->name;
            $dataArray[$key]['num'] = $search::find()->where(['exa_id' => $val->exa_id])->count('id');
        }
        $column = [
            'name'=>['column'=>'A','name'=>'试卷名','width'=>30],
            'num'=>['column'=>'B','name'=>'答题人数','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 试卷统计--按资源统计--详细列表导出
     * @author zhaiyu
     * @startDate 20160629
     * @upDate 20160629
     */
    public function actionExamInfoExport(){
        $queryParams = Yii::$app->request->queryParams;
        $exa_id = $queryParams['exa_id'];
        $search = new ExamLog();
        $query = $search->searchExamInfo($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $examModel = Exam::findOne($exa_id);
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
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $member = Member::findOne($val->uid);
            $dataArray[$key]['real_name'] = $member->real_name ?? '';
            $dataArray[$key]['username'] = $member->username ?? '';
            $dataArray[$key]['name'] = $examModel->name ?? '';
            $getMaxAnswer = ExamLog::find()->where(['uid' => $val->uid, 'exa_id' => $val->exa_id])->max('answers');
            if($examInfo['examLength'] > 0){
                $rate = round($getMaxAnswer * 100 / $examInfo['examLength']);
            }else{
                $rate = 0;
            }
            $dataArray[$key]['rate'] = $rate . '%';
            $dataArray[$key]['times'] = date('Y-m-d H:i:s',$val->start_time) . '～' . date('Y-m-d H:i:s',$val->end_time);
            $dataArray[$key]['level'] = $this->getLevel($rateExam, $rate);
        }
        $column = [
            'name'=>['column'=>'A','name'=>'试卷名','width'=>30],
            'real_name'=>['column'=>'B','name'=>'姓名','width'=>20],
            'username'=>['column'=>'C','name'=>'手机号','width'=>25],
            'times'=>['column'=>'D','name'=>'答题时间','width'=>40],
            'rate'=>['column'=>'E','name'=>'正确率','width'=>20],
            'level'=>['column'=>'F','name'=>'	答题成绩','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 试卷统计--按用户统计--列表导出
     * @author zhaiyu
     * @startDate 20160629
     * @upDate 20160629
     */
    public function actionExuserExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ExamLog();
        $query = $search->searchExam($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $member = Member::findOne($val->uid);
            $dataArray[$key]['real_name'] = $member->real_name ?? '';
            $dataArray[$key]['username'] = $member->username ?? '';
            $dataArray[$key]['num'] = $search::find()->where(['uid' => $val->uid])->count('id');
        }
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>25],
            'username'=>['column'=>'B','name'=>'手机号','width'=>25],
            'num'=>['column'=>'C','name'=>'所答试卷数','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 试卷统计--按用户统计--详细列表导出
     * @author zhaiyu
     * @startDate 20160629
     * @upDate 20160629
     */
    public function actionExuserInfoExport(){
        $queryParams = Yii::$app->request->queryParams;
        $uid = $queryParams['uid'];
        $search = new ExamLog();
        $query = $search->searchExuserInfo($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('exa_id'),
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $memberModel = Member::findOne($uid);

        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['real_name'] = $memberModel->real_name ?? '';
            $dataArray[$key]['username'] = $memberModel->username ?? '';
            $examModel = Exam::findOne($val->exa_id);
            $examLevelModel = ExamLevel::find()->where(['exam_id' => $examModel->id])->all();
            $rateExam = [];
            if($examLevelModel){
                foreach ($examLevelModel as $v){
                    $rateExam[$v['rate']] = [
                        'rate' => $v['rate'],
                        'level' => $v['level'],
                        'condition' => $v['condition'],
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
            $dataArray[$key]['name'] = $examModel->name ?? '';
            $getMaxAnswer = ExamLog::find()->where(['uid' => $val->uid, 'exa_id' => $val->exa_id])->max('answers');
            if($examInfo['examLength'] > 0){
                $rate = round($getMaxAnswer * 100 / $examInfo['examLength']);
            }else{
                $rate = 0;
            }
            $dataArray[$key]['rate'] = $rate . '%';
            $dataArray[$key]['times'] = date('Y-m-d H:i:s',$val->start_time) . '～' . date('Y-m-d H:i:s',$val->end_time);
            $dataArray[$key]['level'] = $this->getLevel($rateExam, $rate);
        }
        $column = [
            'real_name'=>['column'=>'A','name'=>'试卷名','width'=>30],
            'username'=>['column'=>'B','name'=>'姓名','width'=>20],
            'name'=>['column'=>'C','name'=>'手机号','width'=>25],
            'times'=>['column'=>'D','name'=>'答题时间','width'=>40],
            'rate'=>['column'=>'E','name'=>'正确率','width'=>20],
            'level'=>['column'=>'F','name'=>'	答题成绩','width'=>20],
        ];
        $config = [
            'fileName' => '资源统计列表导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $excel = new ExcelController();
        $excel->Export($config, $column, $dataArray);
    }

    /**
     * 获取等级
     * @author zhaiyu
     * @startDate 20160629
     * @upDate 20160629
     * @param $rateExam
     * @param $rate
     * @return string
     */
    protected function getLevel($rateExam, $rate){
        /*等级*/
        ksort($rateExam);
        $level = '未知';
        foreach ($rateExam as &$val){
            switch ($val['condition']){
                case 0:
                    if($rate == $val['rate']){
                        $level = $val['level'];
                    };
                    break;
                case 1:
                    if($rate >= $val['rate']){
                        $level = $val['level'];
                    };
                    break;
                case -1:
                    if($rate < $val['rate']){
                        $level = $val['level'];
                    };
                    break;
            }
        }
        return $level;
    }

}