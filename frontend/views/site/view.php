<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>文章详情</title>
    <link href="http://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    body{
        background-color: #fff;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .article{
        margin-left: auto;
        margin-right: auto;
        padding-bottom: 100px;
    }
    .content{
        position: relative;
        padding: 20px 15px 15px;
        background-color: #fff;
    }
    .article_title{
        margin-bottom: 10px;
        line-height: 1.4;
        font-weight: 400;
        font-size: 24px;
    }
    .article_time{
        font-size: 16px;
        margin-bottom: 18px;
        color: #8c8c8c;
        line-height: 20px;
    }
    .article_details{
        color: #3e3e3e;
    }
    img{
        width: 100%;
    }
    .ppt img{
        display: none;
    }
    .ppt img:first-child{
        display: block;
    }
</style>
<body>
<div class="article">
    <div class="content">
        <div class="article_time">发布时间：<?= date('Y-m-d h:m:s', $data['publish_time']) ?></div>
        <div class="ppt">
            <img src="http://7xshr6.com1.z0.glb.clouddn.com/1%20(2).png">
            <img src="http://7xshr6.com1.z0.glb.clouddn.com/1%20(2).png">
        </div>
        <video id="" class="video-js vjs-default-skin vjs-big-play-centered"
               controls preload="auto" width="100%"
               poster=""
               data-setup='{"example_option":true}'>
            <source src="https://cdn.selz.com/plyr/1.5/View_From_A_Blue_Moon_Trailer-HD.mp4" type='video/mp4' />
        </video>
        <div class="article_details"><?= $data['content'] ?></div>
    </div>
</div>
<input type="hidden" id="txt_value">
</body>
</html>
<script src="http://vjs.zencdn.net/4.12/video.js"></script>
<script src="/js/jquery.min.js"></script>
<script type="text/javascript">
    var temp = null;
    $(function(){
        var imgList = $('img');
        var imgArray = [];
        for(var i = 0; i < imgList.length; i++){
            imgList.eq(i).attr('data-position',i);
            imgArray[i] = imgList.eq(i).attr('src');
        }
        $('img').click(function(){
            var position = $(this).attr('data-position');
            var jsonString = {
                "imageList": imgArray,
                "position": position
            };
            temp = jsonString;
            console.log(jsonString);
            if (browser.versions.ios || browser.versions.iPhone || browser.versions.iPad) {
//                window.location="https://itunes.apple.com/xxx";
                clickImg();
            }
            else if (browser.versions.android) {
//                window.location="http://xxx/xxx.apk";
                appCallJs();
            }
        });
    });
    function clickImg(){
//        console.log(temp.imageList[temp.position]);
        var params="ClickImage:"+JSON.stringify(temp);
        console.log(params);
//        console.log(url);
//        var url = location.href;
//        location.href = url+"&"+params;
        document.location = params;
    }
    function appCallJs(){
        var jsonString = JSON.stringify(temp);
        console.log(111);
        console.log(jsonString);
        Client.showImage(jsonString);
    }
    var browser = {
        versions: function() {
            var u = navigator.userAgent, app = navigator.appVersion;
            return {//移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
        }(),
        language: (navigator.browserLanguage || navigator.language).toLowerCase()
    }
</script>