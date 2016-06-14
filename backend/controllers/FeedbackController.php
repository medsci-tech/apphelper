<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use backend\models\search\Feedback as FeedbackSearch;
use common\models\Member;
use common\models\Resource;
use common\models\Exercise;
use common\models\ResourceClass;
use Yii;
use yii\web\Cookie;
class FeedbackController extends BackendController
{

    /**
     * 评论资源详情/题目详情列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $type = $appYii->request->queryParams['type'] ?? '';
//        var_dump($appYii->request->url);exit;
        /*条件查询*/
        if($type == 'exercise'){
            $search = new Exercise();
            $view = 'exercise';
        }else{
            $search = new Resource();
            $view = 'resource';
        }
        $dataProvider = (new CommentSearch())->search($appYii->request->queryParams);

        //资源种类
        $cateList = [];
        $resourceClassModel = new ResourceClass();
        $cateListArr = $resourceClassModel->getDataForWhere(['parent' => 0]);
        if($cateListArr){
            foreach ($cateListArr as $key => $val){
                $cateList[$val['id']] = $val['name'];
            }
        }
        $cateList['exercise'] = '试题';
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'comment-index-html',
            'value' => $appYii->request->url,
        ]));
        return $this->render($view . '-index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'cateList' => $cateList,
        ]);
    }

    /**
     * 资源一级评论列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @param $id
     * @return string
     */
    public function actionInfo($id)
    {

    }

}