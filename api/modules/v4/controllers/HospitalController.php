<?php
/**
 * Created by PhpStorm.
 * User: lxhui
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
            'index'=>['POST'],
        ];
    }
    /**
     * 单位列表
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $province = $this->params['province'] ?? '';
        if(!$province)
        {
            $result = ['code' => -1,'message'=>'缺少省份!','data'=>null];
            return $result;
        }
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset=$pagesize*($page - 1); //计算记录偏移量

        $province = str_replace("区","",$this->params['province']);
        $city = str_replace("市","",$this->params['city']);
        $area = str_replace("市","",$this->params['area']);
        $data = Hospital::find()
            ->select(['id as hospital_id','name'])
            ->andWhere(['status' => 1])
            ->andWhere(['>', "LOCATE('".$province."',province)", 0])
            ->andFilterWhere(['>', "LOCATE('".$this->params['city']."',city)", 0])
            ->andFilterWhere(['>', "LOCATE('".$this->params['area']."',area)", 0]);
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count()/$pagesize);

        $result = ['code' => 200,'message'=>'药店列表!','data'=>['isLastPage'=>$page>=$total_page ? true : false,'list'=>$model]];
        return $result;

    }

}


