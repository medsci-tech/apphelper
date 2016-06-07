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
use yii\swiftmailer\Message;
use yii\base\InvalidConfigException;
use yii\data\Pagination;

class MessageController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Message';

    protected function verbs()
    {
        return [
            'index' => ['POST'],
            'warn' => ['POST'],
        ];
    }
    /**
     * 消息列表
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量
        $model = new $this->modelClass();
        $data = $model::find()
            ->select('title,type,link_url');
            ->where(['uid'=>$this->uid]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);
        $result = ['code' => 200, 'message' => '消息列表!', 'data' => ['isLastPage' => $page >= $total_page ? true : false, 'list' => $model]];
        return $result;
    }
    
    /**
     * 消息提醒
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionWarn()
    {
        $model = new $this->modelClass();
        $count = $model::find()->where(['uid'=>$this->uid,'isread'=>0])->count();
        $result = ['code' => 200, 'message' => '消息提醒!', 'data' => ['count' => $count]];
        return $result;
    }

}


