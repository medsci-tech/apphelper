<?php

return [
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'redisKey' => [
        '0' => 'tocken_uid_', // 授权验证
        '1' => 'ad_list_', // 广告轮播图缓存键名
        '2' => 'resource_view_', // 资源详情
        '3' => 'app_index_', // 首页

    ],
];
