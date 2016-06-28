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
        $query = ResourceStudy::find();
        $username = $queryParams['username'] ?? '';
        $rid = $queryParams['rid'];
        $where = [];
        if($username){
            $memberModel = Member::find()->where(['like', 'username', $username])->all();
            if($memberModel){
                foreach ($memberModel as $key => $val){
                    $where['uid'][] = $val->id;
                }
            }else{
                $where['id'][] = '';
            }
        }
        $where['rid'] = $rid;
        $query->andFilterWhere($where);
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
        $dataProvider = $search->searchReuser($queryParams);
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

}