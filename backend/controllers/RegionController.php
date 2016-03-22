<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Region;
use yii\helpers\Html;
/**
 * ArticleController implements the CRUD actions for Article model.
 */
class RegionController extends Controller
{

    /**
     * Function output the region that you selected.
     * @param int $pid
     * @param int $typeid
     */
    public function actionList($pid, $typeid = 0)
    {
        $model = new Region();
        $model = $model->getRegionList($pid);

        if($typeid == 1){$aa="--请选择市--";}else if($typeid == 2 && $model){$aa="--请选择区--";}

        echo Html::tag('option',$aa, ['value'=>'empty']) ;

        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
}
