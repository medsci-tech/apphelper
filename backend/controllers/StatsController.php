<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use common\models\ResourceStudy;
use Yii;

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
        $queryParams = Yii::$app->request->queryParams;
        $search = new ResourceStudy();
        $dataProvider = $search->search($queryParams);
        return $this->render('resource', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
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