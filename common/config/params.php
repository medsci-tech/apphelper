<?php

return [
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'redisKey' => [
        '0' => 'tocken_uid_', // 授权验证
        '1' => 'ad_list_', // 广告轮播图缓存键名
        '2' => 'resource_view_', // 资源详情
        '3' => 'app_index_', // 首页
        '4' => 'exam_list_', // 试卷试题
        '5' => 'exam_analyze_', // 试卷解析
        '6' => 'exam_rand_', // 随机试卷
        '7' => 'exam_info_', // 试卷基础信息
    ],
];
