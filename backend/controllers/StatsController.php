<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use common\models\ResourceStudy;
use common\models\ResourceView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Cookie;
class StatsController extends BackendController
{

    /**
     * 资源统计--按资源统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionResource()
    {
        $appYii = Yii::$app;
        $queryParams = $appYii->request->queryParams;
        $search = new ResourceStudy();
        $dataProvider = $search->search($queryParams);
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

    public function actionResourceYi()
    {
        $queryParams = Yii::$app->request->queryParams;
        $search = new ResourceStudy();
        $dataProvider = $search->searchResourceForResource($queryParams);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'stats-resource-yi-html',
            'value' => Yii::$app->request->url,
        ]));
        $referrerUrl = Yii::$app->request->cookies->getValue('stats-resource-html');
        return $this->render('resource-yi', [
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
        ]);
    }

    public function actionResourceEr()
    {

        $queryParams = Yii::$app->request->queryParams;
        $query = ResourceView::find();
        $uid = $queryParams['uid'] ?? '';
        $rid = $queryParams['rid'] ?? '';
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
        ]);
    }

    /**
     * 资源统计--按用户统计
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionReuser()
    {

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

}