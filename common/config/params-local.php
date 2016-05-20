<?php
return [
    /*通用状态选项*/
    'statusOption' => [
        '1' => '启用',
        '0' => '禁用',
    ],
    /*通用发布状态选项*/
    'pubStatusOption' => [
        '1' => '已发布',
        '0' => '未发布',
    ],
    /*通用推荐状态选项*/
    'recStatusOption' => [
        '1' => '已推荐',
        '0' => '未推荐',
    ],
    /*分页每页显示条数*/
    'pageSize' => 20,

    'member' => [
        /*医生职称*/
        'rank' => [
            '0' => '营养师',
            '1' => '职业药师',
            '2' => '药师',
            '3' => '店员',
            '4' => '店长',
            '5' => '店长助理',
        ],
        /*默认密码*/
        'defaultPwd' => '123456',
    ],
    /*题库配置项*/
    'exercise' => [
        /*试题类型*/
        'type' => [
            '1' => '单选',
            '2' => '多选',
        ],
    ],
    /*题库配置项*/
    'exam' => [
        /*试题类型*/
        'type' => [
            '0' => '自定义',
            '1' => '随机',
        ],
    ],
    /*七牛配置项*/
    'qiniu' => [
        'bucket' => 'apphelper-images',
        'domain' => 'o7f6z4jud.bkt.clouddn.com',
        'accessKey' => 'OL3qoivVQhxkRWAL_W3CRs435m1Y5CeJVfkKIDg-',
        'secretKey' => 'mPEylNDXx64U84HjkEcUwJyXg1B40-GUUfC_TR8T',
    ],
];
