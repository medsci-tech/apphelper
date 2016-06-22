<?php

use yii\helpers\Html;
$referrer = Yii::$app->request->referrer ?? 'index';
?>
<link rel="stylesheet" href="/js/plugins/qiniu-upload/main.css">

<div class="modal-body">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12"  id="container">
            <?= Html::a('返回', $referrer, ['class' => 'btn btn-white']) ?>
            <a class="btn btn-warning " id="pickfiles" href="#" >
                <span>上传文件</span>
            </a>
            <?= Html::button('确定', ['class' => 'btn btn-default','id'=>'submitBtn']) ?>
        </div>
        <div style="display: none" id="success" class="col-md-12">
            <div class="alert alert-success alert-dismissable" style="padding-top: 5px;padding-bottom: 5px;">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button" style="top:0;">×</button>
                队列全部文件处理完毕
            </div>
        </div>
        <div class="col-md-12 ">
            <table class="table table-striped table-hover text-left"   style="margin-top:40px;display:none">
                <thead>
                <tr>
                    <th class="col-md-4">图片</th>
                    <th class="col-md-2">大小</th>
                    <th class="col-md-6">信息</th>
                </tr>
                </thead>
                <tbody id="fsUploadProgress">
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$accessKey = Yii::$app->params['qiniu']['accessKey'];
$secretKey = Yii::$app->params['qiniu']['secretKey'];
$bucket = Yii::$app->params['qiniu']['bucket']; // 要上传的空间
$domain = Yii::$app->params['qiniu']['domain']; // 七牛返回的域名
// 构建鉴权对象
$auth = new \Qiniu\Auth($accessKey, $secretKey);
// 生成上传 Token
$token = $auth->uploadToken($bucket);
$getDate = date('Ymd');
$js = <<<JS
var uploader = Qiniu.uploader({
    runtimes: 'html5,flash,html4',
    browse_button: 'pickfiles',
    container: 'container',
    drop_element: 'container',
    max_file_size: '1000mb',
    dragdrop: true,
    chunk_size: '4mb',
    multi_selection: !(mOxie.Env.OS.toLowerCase()==="ios"),
    uptoken:'$token',
    domain: '$domain/',
    uptoken_url: 'videos',
    auto_start: true,
    log_level: 5,
    init: {
        'FilesAdded': function(up, files) {
            $('table').show();
            $('#success').hide();
            $('#submitBtn').removeClass('btn-primary').addClass('btn-default');
            plupload.each(files, function(file) {
                var progress = new FileProgress(file, 'fsUploadProgress');
                progress.setStatus("等待...");
                progress.bindUploadCancel(up);
            });
        },
        'BeforeUpload': function(up, file) {
            var progress = new FileProgress(file, 'fsUploadProgress');
            var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
            if (up.runtime === 'html5' && chunk_size) {
                progress.setChunkProgess(chunk_size);
            }
        },
        'UploadProgress': function(up, file) {
            var progress = new FileProgress(file, 'fsUploadProgress');
            var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
            progress.setProgress(file.percent + "%", file.speed, chunk_size);
        },
        'UploadComplete': function() {
            $('#success').show();
            $('#submitBtn').removeClass('btn-default').addClass('btn-primary');
        },
        'FileUploaded': function(up, file, info) {
            var progress = new FileProgress(file, 'fsUploadProgress');
            progress.setComplete(up, info);
        },
        'Error': function(up, err, errTip) {
            $('table').show();
            var progress = new FileProgress(err.file, 'fsUploadProgress');
            progress.setError();
            progress.setStatus(errTip);
        },
        'Key': function(up, file) {
            // do something with key
            var suffix = file.name.split('.');
            var key = 'video/$getDate'+file.id + '.' + suffix[suffix.length-1];
            return key
        }
    }
});

uploader.bind('FileUploaded', function() {
});
$('#container').on(
    'dragenter',
    function(e) {
        e.preventDefault();
        $('#container').addClass('draging');
        e.stopPropagation();
    }
).on('drop', function(e) {
    e.preventDefault();
    $('#container').removeClass('draging');
    e.stopPropagation();
}).on('dragleave', function(e) {
    e.preventDefault();
    $('#container').removeClass('draging');
    e.stopPropagation();
}).on('dragover', function(e) {
    e.preventDefault();
    $('#container').addClass('draging');
    e.stopPropagation();
});



$('#show_code').on('click', function() {
    $('#myModal-code').modal();
    $('pre code').each(function(i, e) {
        hljs.highlightBlock(e);
    });
});


$('body').on('click', 'table button.btn', function() {
    $(this).parents('tr').next().toggle();
});


var getRotate = function(url) {
    if (!url) {
        return 0;
    }
    var arr = url.split('/');
    for (var i = 0, len = arr.length; i < len; i++) {
        if (arr[i] === 'rotate') {
            return parseInt(arr[i + 1], 10);
        }
    }
    return 0;
};

$('#submitBtn').on('click', function() {
    var success = $('#success').css('display');
    if('none' == success){
        //禁止提交
         return false;
    }else {
       //保存数据 
       // var 
    }
    
});

JS;
$this->registerJs($js);
?>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/moxie.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/plupload.dev.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/ui.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/qiniu.js"></script>

