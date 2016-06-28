<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

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
            'referrerUrl' => $referrerUrl,
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
        $query = ResourceStudy::find();
        $uid = $queryParams['uid'] ?? '';
        $rid = $queryParams['rid'] ?? '';
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
        $query->andFilterWhere([
            'uid' => $uid,
            'rid' => $rid,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-resource-yi-html');
        return $this->render('resource-er', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
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
        $query = ResourceStudy::find();
        $uid = $queryParams['uid'];
        $title = $queryParams['title'] ?? '';
        $resourceModel = Resource::find()->where(['like', 'title', $title])->all();
        $resourceList = [];
        if($resourceModel){
            foreach ($resourceModel as $key => $val){
                $resourceList[] = $val->id;
            }
            $query->andFilterWhere(['rid' => $resourceList]);
        }else{
            $query->andFilterWhere(['id' => '']);
        }
        $query->andFilterWhere(['uid' => $uid]);
        $memberModel = Member::findOne($uid);
        $memberInfo = [
            'real_name' => $memberModel->real_name ?? '',
            'nickname' => $memberModel->nickname ?? '',
            'username' => $memberModel->username ?? '',
        ];
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-reuser-yi-html',
            'value' => Yii::$app->request->url,
        ]));
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-reuser-html');
        return $this->render('reuser-yi', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
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
        $query = ResourceStudy::find();
        $uid = $queryParams['uid'];
        $rid = $queryParams['rid'];
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
        $query->andFilterWhere(['uid' => $uid]);
        $query->andFilterWhere(['rid' => $rid]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-reuser-yi-html');
        return $this->render('reuser-er', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
            'memberInfo' => $memberInfo,
            'resourceInfo' => $resourceInfo,
        ]);
    }

    /**
     * 考卷统计--按考卷统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionExam()
    {

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

    }

    /**
     * 资源统计--按资源统计--列表导出
     */
    public function actionResourceExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchResource($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('rid'),
        ]);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $resource = Resource::findOne($val->rid);
            $resourceStudy = ResourceStudy::find()->where(['rid' => $val->rid]);
            $attr_type = $resource ? ResourceClass::findOne($resource->rid)->attr_type : 0;
            $dataArray[$key]['title'] = $resource->title ?? '';
            $dataArray[$key]['attr_type'] = $appYii->params['resourceClass']['attrType'][$attr_type];
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
     */
    public function actionResourceYiExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $rid = $queryParams['rid'];
        $search = new ResourceStudy();
        $query = $search->searchResourceYi($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
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
     *
     * 资源统计--按用户统计--列表导出
     */
    public function actionReuserExport(){
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $query = $search->searchReuser($queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('uid'),
        ]);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $member = Member::findOne($val->uid);
            $resourceStudy = ResourceStudy::find()->where(['rid' => $val->rid]);
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

}