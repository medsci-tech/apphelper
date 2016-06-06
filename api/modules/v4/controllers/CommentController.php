<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\Comment;
use common\models\Member;
use api\common\models\Praise;
use backend\controllers\TreeController;
use api\common\models\Resource;
use api\common\models\Exam;
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
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function actionIndex()
    {
        $model = new Comment();
        //where条件
        $type =$this->params['type']; // type 区分是试题还是资源
        $rid = $this->params['id']; // 资源或试题id
        $uid = $this->uid; 
        if(!$type || !$rid){
            $return = ['code' => -1,'message'=>'type or id 不能为空!','data'=>null];
        }else{
            $where = [
                'type' => $type,
                'rid' => $rid,
                'cid' => 0,
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
            $dataCount = $model->getDataCountForWhere($where);//查询评论总条数
            $data = $this->CommentListInfo($uid, false, $where, $start, $size, $orderBy);//查询评论列表
            if($dataCount < $page * $size){
                $isLastPage = true;
            }else{
                $isLastPage = false;
            }
            if('resource' == $type){
                $res = Resource::findOne($rid);
            }elseif ('exam' == $type){
                $res = Exam::findOne($rid);
            }
            $comments = $res->comments ?? 0;
            $return = [
                'code' => 200,
                'message' => '评论一级列表',
                'data'=>[
                    'isLastPage' => $isLastPage,
                    'comments' => $comments,
                    'list' => $data,
                ],
            ];
        }
        return $return;
    }

    /**
     * 查看某个评论下的所有回复通用接口
     * @author by zhaiyu
     * @startDate 20160601
     * @upDate 20160601
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function actionList()
    {
        $model = new Comment();
        //where条件
        $cid = $this->params['cid']; // 资源或试题id
        $uid = $this->uid; //登陆用户id
        if(!$cid){
            $return = ['code' => -1,'message'=>'cid 不能为空!','data'=>null];
        }else{
            $where = [
                'cid' => $cid,
            ];
            //limit条件
            $page = intval($this->params['page']); // 资源或试题id
            $page = $page > 0 ? $page : 1; // 资源或试题id
            $size = 10; // 每页显示条数
            $start = ($page - 1) * $size; // 资源或试题id

            $dataCount = $model->getDataCountForWhere($where);//查询评论总条数
            $data = $this->CommentListInfo($uid, true, $where, $start, $size);
            if($dataCount < $page * $size){
                $isLastPage = true;
            }else{
                $isLastPage = false;
            }
            $return = [
                'code' => 200,
                'message' => '评论二级列表',
                'data'=>[
                    'isLastPage' => $isLastPage,
                    'list' => $data,
                ],
            ];
        }
        return $return;
    }

    /**
     * 提交评论
     * @author by lxhui
     * @version [2010-05-31]
     * @editor zhaiyu
     * @upDate 20160602
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function actionSave()
    {
        $model = new $this->modelClass();
        $model->load($this->params, '');
        $result = $model->saves();
        if($result){
            if($result->cid){
                $this->AddCommentCount($result->cid);
                //资源评论加一
                $type = $this->params['type'];
                if('resource' == $type){
                    $res = Resource::findOne($result->rid);
                }elseif ('exam' == $type){
                    $res = Exam::findOne($result->rid);
                }
                if($res){
                    $res->comments += 1;
                    $res->save(false);
                }
            }
            $result = ['code' => 200,'message'=>'评论成功!','data'=>null];
        }else{
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        return $result;
    }

    /**
     * 递归添加评论数
     * @author by zhaiyu
     * @startDate 20160602
     * @upDate 20160602
     * @param $cid
     */
    public function AddCommentCount($cid){
        $model = Comment::findOne($cid);
        if($model){
            $model->comments += 1;
            $model->save(false);
            if($model->cid){
                $this->AddCommentCount($model->cid);
            }
        }
    }

    /**
     * 递归查询评论数
     * @author by zhaiyu
     * @startDate 20160603
     * @upDate 20160603
     * @param $where
     * @param $start
     * @param $size
     * @param $uid
     * @return array
     */
    public function CommentListInfo($uid,$recursive, $where = [], $start = 0, $size = 0,$orderBy = [], $loop = 0){
        $model = new Comment();
        $dataList = $model->getDataForWhere($where, $start, $size, $orderBy);//查询评论列表
        $data = [];
        if($dataList){
            foreach($dataList as $key =>$val){
                if($val->uid){
                    //用户相关
                    $member = Member::findOne($val->uid);
                    $data[$loop]['nickname'] = $member->nickname;
                    $data[$loop]['avatar'] = $member->avatar;
                    if($val->reply_to_uid){
                        $toMember = Member::findOne($val->reply_to_uid);
                        $data[$loop]['toNickname'] = $toMember->nickname ?? '';
                    }else{
                        $data[$loop]['toNickname'] = '';
                    }
                    //点赞相关
                    $praiseCount = Praise::find()->select('id')->where(['id' => $val->id])->count();
                    $isPraise = Praise::find()->select('id')->where(['id' => $val->id, 'uid' => $uid])->one();
                    $data[$loop]['praise'] = $praiseCount;//点赞数
                    $data[$loop]['isPraise'] = $isPraise ? true : false;//点赞数
                    //评论相关
                    $data[$loop]['id'] = $val->id;//内容
                    $data[$loop]['content'] = $val->content;//内容
                    $data[$loop]['type'] = $val->type;//类型
                    $data[$loop]['created_at'] = date('Y-m-d H:i:s', $val->created_at);//类型
                    $data[$loop]['comments'] = $val->comments;//评论次数
                    $data[$loop]['toUid'] = $val->uid;
                    $loop++;
                    if($recursive){
                        $dataChild = $this->CommentListInfo($uid, $recursive,  ['cid' => $val->id], 0, 0, $orderBy, $loop);
                        if($dataChild){
                            foreach ($dataChild as $v){
                                $data[$loop] = $v;
                                $loop++;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
}