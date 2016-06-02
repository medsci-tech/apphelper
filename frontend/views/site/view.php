<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>文章详情</title>
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

</style>
<body>
<div class="article">
    <div class="content">
        <div class="article_time">发布时间：<?= $data['publish_time'] ?></div>
        <div class="article_details"><?= $data['content'] ?></div>
    </div>
</div>
<input type="hidden" id="txt_value">
</body>
</html>
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
//            $("#txt_value").val(jsonString);
//            window.Client.showImage(jsonString);
            appCallJs();
        });
    });

    function appCallJs(){
        var jsonString = JSON.stringify(temp);
        console.log(111);
        console.log(jsonString);
        Client.showImage(jsonString);
    }
</script>
