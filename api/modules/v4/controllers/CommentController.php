<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\Comment;
use api\common\models\Member;
use api\common\models\Praise;
use api\common\models\Resource;
use api\common\models\Exercise;
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
     * @author by zhaiyu
     * @startDate 20160601
     * @upDate 20160601
     * @param
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function actionIndex()
    {
        $model = new Comment();
        //where条件
        $type =$this->params['type']; // type 区分是试题还是资源
        $rid = $this->params['id']; // 资源或试题id
        $uid = $this->params['uid']; //登陆用户id
        if(!$type || !$rid){
            $result = ['code' => -1,'message'=>'type or id 不能为空!','data'=>null];
            return $result;
        }
        $where = [
            'type' => $type,
            'rid' => $rid,
        ];
        //limit条件
        $page = intval($this->params['page']); // 资源或试题id
        $page = $page > 0 ? $page : 1; // 资源或试题id
        $size = 10; // 每页显示条数
        $start = ($page - 1) * $size; // 资源或试题id
        //orderBy排序
        $orderByParams = $this->params['orderBy'];
        $orderBy = [];
        if('hot' == $orderByParams){
            $orderBy['comments'] = SORT_DESC;
        }
        $dataList = $model->getDataForWhere($where, $start, $size, $orderBy);//查询评论列表
        $dataCount = $model->getDataCountForWhere($where);//查询评论总条数
        $data = [];
        foreach($dataList as $key =>$val){
            if($val->uid){
                //用户相关
                $member = Member::findOne($val->uid);
                $data[$key]['nickname'] = $member->oldAttributes['nickname'];
                $data[$key]['avatar'] = $member->avatar;
                //点赞相关
                $praiseCount = Praise::find($val->id)->count();
                $isPraise = Praise::find()->select('id')->where(['id' => $val->id, 'uid' => $uid])->one();
                $data[$key]['praise'] = $praiseCount;//点赞数
                $data[$key]['isPraise'] = $isPraise ? true : false;//点赞数
                //评论相关
                $data[$key]['id'] = $val->id;//内容
                $data[$key]['content'] = $val->content;//内容
                $data[$key]['type'] = $val->type;//类型
                $data[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);//类型
                $data[$key]['comments'] = $val->comments;//评论次数
            }
        }
        if($dataCount < $page * $size){
            $isLastPage = true;
        }else{
            $isLastPage = false;
        }
        $result = [
            'code' => 200,
            'message' => '评论列表',
            'data'=>[
                'isLastPage' => $isLastPage,
                'list' => $data,
            ]
        ];
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
        /* 查找$id下的评论列表（这里二级会有嵌套回复的形式，请考虑接口组装） */
        $data=[
            ['id'=>'101','nickname'=> '哇哈哈','content'=> '普安药店员工收银服务指导说明','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'111','praise'=>'110','created_at'=>'2012-12-12','type'=> 'exam'],
            ['id'=>'102','nickname'=> '哇哈哈2','content'=> '缺铁性贫血及推荐用药2','avatar'=>'http://qiuniu.up.com/12.jpg','comments'=>'112','praise'=>'56','created_at'=>'2012-12-12','type'=> 'exam'],
            ['id'=>'103','nickname'=> '哇哈哈3','content'=> '缺铁性贫血及推荐用药3','avatar'=>'http://qiuniu.up.com/13.jpg','comments'=>'124','praise'=>'99','created_at'=>'2012-12-12','type'=> 'resource'],
            ['id'=>'104','nickname'=> '哇哈哈4','content'=> '缺铁性贫血及推荐用药4','avatar'=>'http://qiuniu.up.com/22.jpg','comments'=>'33','praise'=>'89','created_at'=>'2012-12-12','type'=> 'resource'],
            ['id'=>'211','nickname'=> '哇哈哈5','content'=> '缺铁性贫血及推荐用药dd','avatar'=>'http://qiuniu.up.com/34.jpg','comments'=>'56','praise'=>'67','created_at'=>'2012-12-12','type'=> 'resource'],

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
        $result = $model->saves();
        if($result){
            if($result->cid){
                $comment = Comment::findOne($result->cid);
                if($comment){
                    $comment->comments += 1;
                    $comment->save(false);
                }
            }
            $result = ['code' => 200,'message'=>'评论成功!','data'=>null];
        }else{
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        return $result;
    }

}