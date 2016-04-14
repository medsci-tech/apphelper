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
     *  通用省市区联动菜单
     * @param int
     * @param int
     */
    public function actionIndex()
    {
        return $this->render('index', [
           // 'model' => new Region(),
        ]);
    }

    /**
     * Function output the region that you selected.
     * @param int $pid
     * @param int $grade
     */
    public function actionList($pid,$grade=1)
    {
        $model = new Region();
        $model = $model->getRegionList($pid, $grade);

        if($grade == 2){$aa="--请选择市--";}else if($grade == 3 && $model){$aa="--请选择区--";}

        echo Html::tag('option',$aa, ['value'=>'empty']) ;

        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
}
