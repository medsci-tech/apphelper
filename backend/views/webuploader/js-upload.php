<?php


?>

<div class="container">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="demo" aria-labelledby="demo-tab">

            <div class="row" style="margin-top: 20px;">
                <ul class="tip col-md-12 text-mute">
                    <li>
                        <small>
                            JavaScript SDK 基于 Plupload 开发，可以通过 Html5 或 Flash 等模式上传文件至七牛云存储。
                        </small>
                    </li>
                    <li>
                        <small>临时上传的空间不定时清空，请勿保存重要文件。</small>
                    </li>
                    <li>
                        <small>Html5模式大于4M文件采用分块上传。</small>
                    </li>
                    <li>
                        <small>上传图片可查看处理效果。</small>
                    </li>
                    <li>
                        <small>本示例限制最大上传文件100M。</small>
                    </li>
                </ul>
                <div class="col-md-12">
                    <div id="container">
                        <a class="btn btn-default btn-lg " id="pickfiles" href="#" >
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>选择文件</span>
                        </a>
                    </div>
                </div>
                <div style="display:none" id="success" class="col-md-12">
                    <div class="alert-success">
                        队列全部文件处理完毕
                    </div>
                </div>
                <div class="col-md-12 ">
                    <table class="table table-striped table-hover text-left"   style="margin-top:40px;display:none">
                        <thead>
                        <tr>
                            <th class="col-md-4">Filename</th>
                            <th class="col-md-2">Size</th>
                            <th class="col-md-6">Detail</th>
                        </tr>
                        </thead>
                        <tbody id="fsUploadProgress">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="container" style="display: none;">

    <div class="text-left col-md-12 wrapper">
        <h1 class="text-left col-md-12 ">
            七牛云存储 - JavaScript SDK
            <a class="btn btn-default view_code" id="show_code">
                查看初始化代码
            </a>
            <a class="btn btn-default view_github" href="https://github.com/qiniupd/qiniu-js-sdk" target="_blank">
                <img src="http://qtestbucket.qiniudn.com/GitHub-Mark-32px.png">
                View Source on Github
            </a>
        </h1>
    </div>
    <div class="body">
        <!-- <div class="col-md-12" id="qiniu-js-sdk-log"></div> -->
    </div>
    <div class="modal fade body" id="myModal-code" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">查看初始化代码</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade body" id="myModal-img" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">图片效果查看</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body-wrapper text-center">
                        <a href="" target="_blank" >
                            <img src="" alt="" data-key="" data-h="">
                        </a>
                    </div>
                    <div class="modal-body-footer">
                        <div class="watermark">
                            <span>水印控制：</span>
                            <a href="#" data-watermark="NorthWest" class="btn btn-default">
                                左上角
                            </a>
                            <a href="#" data-watermark="SouthWest" class="btn btn-default">
                                左下角
                            </a>
                            <a href="#" data-watermark="NorthEast" class="btn btn-default">
                                右上角
                            </a>
                            <a href="#" data-watermark="SouthEast" class="btn btn-default disabled">
                                右下角
                            </a>
                            <a href="#" data-watermark="false" class="btn btn-default">
                                无水印
                            </a>
                        </div>
                        <div class="imageView2">
                            <span>缩略控制：</span>
                            <a href="#" data-imageview="large" class="btn btn-default disabled">
                                大缩略图
                            </a>
                            <a href="#" data-imageview="middle" class="btn btn-default">
                                中缩略图
                            </a>
                            <a href="#" data-imageview="small" class="btn btn-default">
                                小缩略图
                            </a>
                        </div>
                        <div class="imageMogr2">
                            <span>高级控制：</span>
                            <a href="#" data-imagemogr="left" class="btn btn-default no-disable-click" >
                                逆时针
                            </a>
                            <a href="#" data-imagemogr="right" class="btn btn-default no-disable-click">
                                顺时针
                            </a>
                            <a href="#" data-imagemogr="no-rotate" class="btn btn-default">
                                无旋转
                            </a>
                        </div>
                        <div class="text-warning">
                            备注：小图片水印效果不明显，建议使用大图片预览水印效果
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="pull-left">本示例仅演示了简单的图片处理效果，了解更多请点击</span>

                    <a href="https://github.com/SunLn/qiniu-js-sdk" target="_blank" class="pull-left">本SDK文档</a>
                    <span class="pull-left">或</span>

                    <a href="http://developer.qiniu.com/docs/v6/api/reference/fop/image/" target="_blank" class="pull-left">七牛官方文档</a>

                    <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                </div>
            </div>
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

$js = <<<JS
$(function() {
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
        auto_start: true,
        log_level: 5,
        init: {
            'FilesAdded': function(up, files) {
                $('table').show();
                $('#success').hide();
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
            }
        }
    });
    // console.log(uptoken_func());
    uploader.bind('FileUploaded', function() {
        console.log('hello man,a file is uploaded');
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

    $('#myModal-img .modal-body-footer').find('a').on('click', function() {
        var img = $('#myModal-img').find('.modal-body img');
        var key = img.data('key');
        var oldUrl = img.attr('src');
        var originHeight = parseInt(img.data('h'), 10);
        var fopArr = [];
        var rotate = getRotate(oldUrl);
        if (!$(this).hasClass('no-disable-click')) {
            $(this).addClass('disabled').siblings().removeClass('disabled');
            if ($(this).data('imagemogr') !== 'no-rotate') {
                fopArr.push({
                    'fop': 'imageMogr2',
                    'auto-orient': true,
                    'strip': true,
                    'rotate': rotate,
                    'format': 'png'
                });
            }
        } else {
            $(this).siblings().removeClass('disabled');
            var imageMogr = $(this).data('imagemogr');
            if (imageMogr === 'left') {
                rotate = rotate - 90 < 0 ? rotate + 270 : rotate - 90;
            } else if (imageMogr === 'right') {
                rotate = rotate + 90 > 360 ? rotate - 270 : rotate + 90;
            }
            fopArr.push({
                'fop': 'imageMogr2',
                'auto-orient': true,
                'strip': true,
                'rotate': rotate,
                'format': 'png'
            });
        }

        $('#myModal-img .modal-body-footer').find('a.disabled').each(function() {

            var watermark = $(this).data('watermark');
            var imageView = $(this).data('imageview');
            var imageMogr = $(this).data('imagemogr');

            if (watermark) {
                fopArr.push({
                    fop: 'watermark',
                    mode: 1,
                    image: 'http://www.b1.qiniudn.com/images/logo-2.png',
                    dissolve: 100,
                    gravity: watermark,
                    dx: 100,
                    dy: 100
                });
            }

            if (imageView) {
                var height;
                switch (imageView) {
                    case 'large':
                        height = originHeight;
                        break;
                    case 'middle':
                        height = originHeight * 0.5;
                        break;
                    case 'small':
                        height = originHeight * 0.1;
                        break;
                    default:
                        height = originHeight;
                        break;
                }
                fopArr.push({
                    fop: 'imageView2',
                    mode: 3,
                    h: parseInt(height, 10),
                    q: 100,
                    format: 'png'
                });
            }

            if (imageMogr === 'no-rotate') {
                fopArr.push({
                    'fop': 'imageMogr2',
                    'auto-orient': true,
                    'strip': true,
                    'rotate': 0,
                    'format': 'png'
                });
            }
        });

        var newUrl = Qiniu.pipeline(fopArr, key);

        var newImg = new Image();
        img.attr('src', 'images/loading.gif');
        newImg.onload = function() {
            img.attr('src', newUrl);
            img.parent('a').attr('href', newUrl);
        };
        newImg.src = newUrl;
        return false;
    });

});

JS;
$this->registerJs($js);
?>

<script type="text/javascript" src="http://jssdk.demo.qiniu.io/bower_components/jquery/jquery.min.js"></script>
<script type="text/javascript" src="http://jssdk.demo.qiniu.io/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/moxie.js"></script>
<script type="text/javascript" src="/js/plugins/qiniu-upload/plupload.dev.js"></script>

<script type="text/javascript" src="/js/plugins/qiniu-upload/qiniu.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>

