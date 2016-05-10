<?php
return [
    'httpCode' => [
        '200' => '一切正常',
        '201' => '响应 POST 请求时成功创建一个资源。Location header 包含的URL指向新创建的资源。',
        '204' => '该请求被成功处理，响应不包含正文内容 (类似 DELETE 请求)',
        '304' => '资源没有被修改。可以使用缓存的版本',
        '400' => '错误的请求。可能通过用户方面的多种原因引起的，例如在请求体内有无效的JSON 数据，无效的操作参数，等等',
        '401' => '验证失败',
        '403' => '已经经过身份验证的用户不允许访问指定的 API 末端',
        '404' => '所请求的资源不存在',
        '405' => '不被允许的方法。 请检查 Allow header 允许的HTTP方法',
        '415' => '不支持的媒体类型。 所请求的内容类型或版本号是无效的。',
        '422' => '数据验证失败 (例如，响应一个 POST 请求)。 请检查响应体内详细的错误消息。',
        '429' => '请求过多。 由于限速请求被拒绝。',
        '500' => '内部服务器错误。 这可能是由于内部程序错误引起的',
    ],
    'adminEmail' => 'admin@example.com',
    'seoTitle' => '微信热文精选,微信文章,微信分享,微信美文收录,微信公众号和热门文章,微信大全,微信朋友圈好文章',
    'seoSeparator' => '_',
    'icp' => '京ICP备15065774号',
    'wxAppId' => 'wx2d5c95252ba671cf',
    'wxAppSecret' => 'af6059c4d91063fb73a0f9b51f0a34d4',
    'wxNonceStr' => 'hehe',
    'redisKey' => [
        '0' => 'tocken_uid_', // 授权验证
    ],

];
