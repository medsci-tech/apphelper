<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\base\InvalidConfigException;
class CommentController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Comment';

    protected function verbs(){
        return [
            'save'=>['POST'],
            'index'=>['POST'],
        ];
    }

    /**
     * 资源详情/题目详情的评论加载通用接口
     * @author by lxhui
     * @version [2010-05-31]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $model = new $this->modelClass();
        $type =$this->params['type']; // type 区分是试题还是资源
        $id = $this->params['id']; // 资源或试题id
        if(!$type || !$id)
        {
            $result = ['code' => -1,'message'=>'type or id 不能为空!','data'=>null];
            return $result;
        }
        /* 查找以及评论列表 */
        
        $reply1 =  [
            ['id'=>'201','nickname'=> '哇哈哈','to_nickname'=> '李易峰1号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'156','isPraise'=>false,],       
            ['id'=>'202','nickname'=> '哇哈哈2','to_nickname'=> '李易峰2号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'56','isPraise'=>false,],  
            ['id'=>'203','nickname'=> '哇哈哈3','to_nickname'=> '李易峰3号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'526','isPraise'=>false,], 
            ['id'=>'204','nickname'=> '哇哈哈4','to_nickname'=> '李易峰4号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'536','isPraise'=>false,], 
            ['id'=>'205','nickname'=> '哇哈哈5','to_nickname'=> '李易峰5号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'546','isPraise'=>false,], 

        ];
        $reply2 =  [
            ['id'=>'301','nickname'=> '哇哈哈3','to_nickname'=> '李易峰11号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'536','isPraise'=>false,],       
            ['id'=>'302','nickname'=> '哇哈哈6','to_nickname'=> '李易峰222号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'456','isPraise'=>false,],  
            ['id'=>'303','nickname'=> '哇哈哈7','to_nickname'=> '李易峰33号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'556','isPraise'=>false,], 
            ['id'=>'304','nickname'=> '哇哈哈8','to_nickname'=> '李易峰44号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'566','isPraise'=>false,], 
            ['id'=>'305','nickname'=> '哇哈哈9','to_nickname'=> '李易峰55号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'576','isPraise'=>false,], 

        ];
        $data=[
            ['id'=>'101','nickname'=> '哇哈哈','content'=> '普安药店员工收银服务指导说明','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'111','praise'=>'110','created_at'=>'2012-12-12','type'=> 'exam','isPraise'=>false,
                'replys'=>$reply1
                ],
            ['id'=>'102','nickname'=> '哇哈哈2','content'=> '缺铁性贫血及推荐用药2','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'112','praise'=>'56','created_at'=>'2012-12-12','type'=> 'exam','isPraise'=>false,'replys'=>null],
            ['id'=>'103','nickname'=> '哇哈哈3','content'=> '缺铁性贫血及推荐用药3','avatar'=>'http://qiuniu.up.com/13.jpg','comments'=>'124','praise'=>'99','created_at'=>'2012-12-12','type'=> 'resource', 'replys'=>$reply2],
            ['id'=>'104','nickname'=> '哇哈哈4','content'=> '缺铁性贫血及推荐用药4','avatar'=>'http://qiuniu.up.com/22.jpg','comments'=>'33','praise'=>'89','created_at'=>'2012-12-12','type'=> 'resource','isPraise'=>false,'replys'=>null],
            ['id'=>'211','nickname'=> '哇哈哈5','content'=> '缺铁性贫血及推荐用药dd','avatar'=>'http://qiuniu.up.com/34.jpg','comments'=>'56','praise'=>'67','created_at'=>'2012-12-12','type'=> 'resource','isPraise'=>false, 'replys'=>null],

        ];
        $result = ['code' => 200,'message'=>'评论列表','data'=>['isLastPage'=>true,'list'=>$data]];

        return $result;
    }

    /**
     * 查看某个评论下的所有回复通用接口
     * @author by lxhui
     * @version [2010-05-31]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionList()
    {
        $model = new $this->modelClass();
        $type =$this->params['type']; // type 区分是试题还是资源
        $id = $this->params['id']; // 评论id
        if(!$type || !$id)
        {
            $result = ['code' => -1,'message'=>'type or id 不能为空!','data'=>null];
            return $result;
        }
        $reply1 =  [
            ['id'=>'201','nickname'=> '哇哈哈','to_nickname'=> '李易峰1号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'156','isPraise'=>false,],       
            ['id'=>'202','nickname'=> '哇哈哈2','to_nickname'=> '李易峰2号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'56','isPraise'=>false],  
            ['id'=>'203','nickname'=> '哇哈哈3','to_nickname'=> '李易峰3号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'526','isPraise'=>false,], 
            ['id'=>'204','nickname'=> '哇哈哈4','to_nickname'=> '李易峰4号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'536','isPraise'=>false,], 
            ['id'=>'205','nickname'=> '哇哈哈5','to_nickname'=> '李易峰5号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'546','isPraise'=>false,], 

        ];
        $reply2 =  [
            ['id'=>'301','nickname'=> '哇哈哈3','to_nickname'=> '李易峰11号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'536','isPraise'=>false,],       
            ['id'=>'302','nickname'=> '哇哈哈6','to_nickname'=> '李易峰222号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'456','isPraise'=>false,],  
            ['id'=>'303','nickname'=> '哇哈哈7','to_nickname'=> '李易峰33号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'556','isPraise'=>false,], 
            ['id'=>'304','nickname'=> '哇哈哈8','to_nickname'=> '李易峰44号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'566','isPraise'=>false,], 
            ['id'=>'305','nickname'=> '哇哈哈9','to_nickname'=> '李易峰55号','content'=> '普安药店员工收银服务指导说明','type'=> 'exam','praise'=>'576','isPraise'=>false,], 

        ];
        /* 查找$id下的评论列表（这里二级会有嵌套回复的形式，请考虑接口组装） */
        $data=[
            ['id'=>'101','nickname'=> '哇哈哈','content'=> '普安药店员工收银服务指导说明','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'111','praise'=>'110','created_at'=>'2012-12-12','type'=> 'exam','praise'=>'156','isPraise'=>false, 'replys'=>$reply1],
            ['id'=>'102','nickname'=> '哇哈哈2','content'=> '缺铁性贫血及推荐用药2','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'112','praise'=>'56','created_at'=>'2012-12-12','type'=> 'exam','praise'=>'156','isPraise'=>false, 'replys'=>$reply2],
            ['id'=>'103','nickname'=> '哇哈哈3','content'=> '缺铁性贫血及推荐用药3','avatar'=>'http://qiuniu.up.com/13.jpg','comments'=>'124','praise'=>'99','created_at'=>'2012-12-12','type'=> 'resource', 'replys'=>null],
            ['id'=>'104','nickname'=> '哇哈哈4','content'=> '缺铁性贫血及推荐用药4','avatar'=>'http://qiuniu.up.com/22.jpg','comments'=>'33','praise'=>'89','created_at'=>'2012-12-12','type'=> 'resource', 'replys'=>null],
            ['id'=>'211','nickname'=> '哇哈哈5','content'=> '缺铁性贫血及推荐用药dd','avatar'=>'http://qiuniu.up.com/34.jpg','comments'=>'56','praise'=>'67','created_at'=>'2012-12-12','type'=> 'resource', 'replys'=>null],

        ];
        $result = ['code' => 200,'message'=>'推荐列表','data'=>['isLastPage'=>true,'list'=>$data]];

        return $result;
    }
    /**
     * 提交评论
     * @author by lxhui
     * @version [2010-05-31]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionSave()
    {
        $model = new $this->modelClass();
        $model->load($this->params, '');
        if(!$response = $model->saves())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'评论成功!','data'=>null];
        return $result;
    }

}