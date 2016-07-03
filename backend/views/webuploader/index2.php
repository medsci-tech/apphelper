<?php
/**
 * 上传pdf和ppt转图片
 */
$modelName = $name ?? '';
$actionCtrl = $actionCtrl ?? 'pdf';//上传function
$uploadPath = $uploadPath ?? 'pdf';//上传到七牛的目录
$proBarMaxWidth = $proBarWidth ?? 200;
$saveInput = $saveInput ?? 'one';//为了区分上传一
?>
<!--引入CSS-->

<div class="form-inline">
    <div class="form-group">
        <input readonly type="text" class="form-control" data-toggle="upload-progressInput-<?php echo $saveInput?>">
        <div id="saveInput-<?php echo $saveInput?>"></div>
    </div>
    <div id="filePicker-<?php echo $saveInput?>" class="form-group">上传</div>
    <div class="form-group progress"  >
        <div id="fileList-<?php echo $saveInput?>" aria-valuemax="100" role="progressbar" class="progress-bar progress-bar-info">
            <span></span>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
.webuploader-container {
	position: relative;
}
.webuploader-element-invisible {
	position: absolute !important;
	clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick {
	position: relative;
	cursor: pointer;
	padding: 6px 12px;
	text-align: center;
	border-radius: 3px;
	overflow: hidden;
	background-color: #f8ac59;
    color: #FFF;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    margin-bottom: 0;
    border: 1px solid #f8ac59;
}
.webuploader-pick-hover {
    background-color: #f7a54a;
    border-color: #f7a54a;
    color: #FFF
}

.webuploader-pick-disable {
	opacity: 0.6;
	pointer-events:none;
}

CSS;


$js = <<<JS
// 初始化Web Uploader
var probar = $('#fileList-$saveInput');
var progressbarMaxWidth = $proBarMaxWidth;
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    // 文件接收服务端。
    server: '/upload/$actionCtrl?path=$uploadPath',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker-$saveInput',

});
// 当有文件添加进来的时候
uploader.on( 'fileQueued', function( file ) {


    // 创建缩略图
    // 如果为非图片文件，可以不用调用此方法。
    // thumbnailWidth x thumbnailHeight 为 100 x 100

});
// 文件上传过程中创建进度条实时显示。
uploader.on( 'uploadProgress', function( file , percentage ) {
    probar.removeClass('progress-bar-danger progress-bar-success');
    probar.addClass('progress-bar-info');
    probar.css('width', percentage * progressbarMaxWidth);
    probar.find('span').text(Math.round(percentage * 100) + '%');
});

// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file, ret, hds  ) {
    if(200 == ret.code){
        var data = ret.data;
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-success');
        $('[data-toggle="upload-progressInput-$saveInput"]').val(data.tName);
        saveInput(data.saveName);
    }else {
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-danger');
        $('[data-toggle="upload-progressInput-$saveInput"]').val('');
    }
    probar.find('span').text(ret.msg);
});

// 文件上传失败，显示上传出错。
uploader.on( 'uploadError', function( file ,reason) {
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-danger');
        probar.find('span').text('上传失败');
        probar.css('width', progressbarMaxWidth);
        $('[data-toggle="upload-progressInput-$saveInput"]').val('');
});
var saveInput =	function (list) {
    var html ='';
    for(var i = 0; i < list.length; i++){
        html += '<input type="hidden" data-toggle="upload-saveInput-$saveInput" name="$modelName" value="';
        html += list[i];
        html += '">';
    }
    $('#saveInput-$saveInput').html(html);
}
JS;
$this->registerJs($js);
$this->registerCss($css);
?>




