<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/16
 * Time: 16:00
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;


class Resource extends ActiveRecord {

    public static function tableName()
    {
        return '{{%resource}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'title',
                'rid',
                'rids',
                'author',
                'keyword',
            ], 'required'],
            [[
                'status',
                'hour',
                'uid',
                'comments',
                'views',
            ], 'integer'],
            [[
                'title',
                'author',
                'keyword',
                'imgurl',
                'ppt_imgurl',
                'videourl',
                'content',
                'author',
            ], 'string'],
              // 若 "imgurl" 为空，则设其为 null
            ['imgurl', 'default', 'value' => null],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => '资源名',
            'author' => '作者',
            'rid' => '所属目录',
            'rids' => '关联目录',
            'keyword' => '关键词',
            'hour' => '课时(单位:分钟)',
            'imgurl' => '缩略图',
            'videourl' => '视频地址',
            'content' => '内容',
            'views' => '浏览次数',
            'comments' => '评论次数',
            'uid' => '创建者id',
            'publish_status' => '发布状态',
            'recommend_status' => '推荐状态',
            'status' => '状态',
            'created_at' => '创建时间',
            'publish_time' => '发布时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * 批量修改
     * author zhaiyu
     * startDate 20160511
     * updateDate 20160511
     * @param array $where
     * @param array $data
     */
    public function saveData($where = [], $data = []){
        $exam = $this::find()->where($where)->all();
        foreach ($exam as $val){
            foreach ($data as $k => $v){
                $val->$k = $v;
            }
            $val->save(false);
        }
    }

}