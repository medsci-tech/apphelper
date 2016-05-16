<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\Region;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\components\Helper;
use yii\base\InvalidConfigException;
class IndexController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Member';

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
            'rank'=>['POST'],
            'reg'=>['POST'],
            'nickname'=>['POST'],
            'username'=>['POST'],
            'realname'=>['POST'],
        ];
    }
    public function actionIndex()
    {
        $data=[
            ['id'=>'101','classname'=> '疾病','title'=> '普安药店员工收银服务指导说明','imgurl'=>'http://qiuniu.up.com/12.jpg','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
            ['id'=>'102','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药2','imgurl'=>'http://qiuniu.up.com/12.jpg','labelName'=>'题目总数','labelValue'=>'56','type'=> 'exam'],
            ['id'=>'103','classname'=> '产品','title'=> '缺铁性贫血及推荐用药3','imgurl'=>'http://qiuniu.up.com/13.jpg','labelName'=>'参与人数','labelValue'=>'99','type'=> 'article'],
            ['id'=>'104','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药4','imgurl'=>'http://qiuniu.up.com/22.jpg','labelName'=>'参与人数','labelValue'=>'89','type'=> 'article'],
            ['id'=>'211','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药dd','imgurl'=>'http://qiuniu.up.com/34.jpg','labelName'=>'参与人数','labelValue'=>'67','type'=> 'article'],
            ['id'=>'222','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药rre','imgurl'=>'http://qiuniu.up.com/34.jpg','labelName'=>'参与人数','labelValue'=>'110','type'=> 'exam'],
            ['id'=>'223','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药78','imgurl'=>'http://qiuniu.up.com/3.jpg','labelName'=>'题目总数','labelValue'=>'22','type'=> 'article'],
            ['id'=>'345','classname'=> '药店','title'=> '缺铁性贫血及推荐用药55','imgurl'=>'http://qiuniu.up.com/12.jpg','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
            ['id'=>'345','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药66','imgurl'=>'http://qiuniu.up.com/12.jpg','labelName'=>'题目总数','labelValue'=>'34','type'=> 'exam'],
            ['id'=>'543','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药77','imgurl'=>'http://qiuniu.up.com/12.jpg','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
        ];
        $result = ['code' => 200,'message'=>'推荐列表','data'=>array_values($data),'isLastPage'=>true];
        return $result;
    }

}