<?php

namespace backend\controllers;

use backend\models\search\Resource as ResourceSearch;
use common\models\Resource;
use common\models\ResourceClass;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ResourceController extends BackendController
{

    /**
     *
     */
    public function actionIndex()
    {

    }

    public function actionForm()
    {

    }


    public function actionDelete()
    {

    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Resource::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function actionView($id)
    {

    }

}
