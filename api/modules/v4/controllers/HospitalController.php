<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use api\common\models\Hospital;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
class HospitalController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Hospital';

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
        ];
    }
    public function actionIndex()
    {
        $pagesize = 2; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset=$pagesize*($page - 1); //计算记录偏移量
        $data = Hospital::find()->select(['id as hospital_id','name'])->andWhere(['status' => 1]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $result = ['code' => '200','message'=>'药店列表!','data'=>$model];
        return $result;

    }

}