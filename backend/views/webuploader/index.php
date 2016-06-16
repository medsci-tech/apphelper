<?php
/**
 * Created by PhpStorm.
 * User: mime
 * Date: 2016/5/19
 * Time: 16:32
 */
//自定义参数
$modelName = $name ?? '';
$imgMaxSize = $imgMaxSize ?? 2097152;//最大限制，默认2M
$proBarMaxWidth = $proBarWidth ?? 200;
$actionCtrl = $actionCtrl ?? 'img';//上传function
$uploadPath = $uploadPath ?? 'images/exam';//上传到七牛的目录
$buttonName = $buttonName ?? '上传';

?>

<div class="form-inline">
    <div class="form-group">
        <input readonly id="txt_show" type="text" class="form-control" data-toggle="upload-progressInput">
        <input id="txt_value" type="hidden" data-toggle="upload-saveInput" name="<?php echo $modelName;?>">
    </div>
    <button id="upload-promptzone" type="button" class="btn btn-warning"><?php echo $buttonName;?></button>
    <div class="form-group progress">
        <div id="upload-progressbar" aria-valuemax="100" role="progressbar" class="progress-bar progress-bar-info">
            <span></span>
        </div>
    </div>
</div>
<?php
$formatterSize = Yii::$app->formatter->asShortSize($imgMaxSize);
$js = <<<JS
// Set fieldname
	$.ajaxUploadSettings.name = 'file';
	var probar = $('#upload-progressbar');
	var progressbarMaxWidth = $proBarMaxWidth;
	$('#upload-promptzone').ajaxUploadPrompt({
		url : '/upload/$actionCtrl?path=$uploadPath',
		beforeSend : function(e,f) {
		    if(f.files[0].size > $imgMaxSize){
                uploadResultError('文件不能超过' + '$formatterSize');
                return false;
		    }
		},
		onprogress : function (e) {
		    probar.removeClass('progress-bar-danger progress-bar-success');
		    probar.addClass('progress-bar-info');
			if (e.lengthComputable) {
				var percentComplete = e.loaded / e.total;
				probar.css('width', percentComplete * progressbarMaxWidth);
                probar.find('span').text(Math.round(percentComplete * 100) + '%');
			}
		},
		error : function () {
            uploadResultError();
		},
		success : function (result) {
			if (result.code == 200) {
                uploadResultSuccess(result.data);
			}else{
                uploadResultError(result.msg);
			}
		}
	});
	
	function uploadResultSuccess(data) {
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-success');
        probar.find('span').text('上传成功');
        $('[data-toggle="upload-progressInput"]').val(data.tName);
        $('[data-toggle="upload-saveInput"]').val(data.saveName);
	}
	
	uploadResultError = function (msg) {
	    if(undefined == msg){
	        msg = '上传失败';
	    }
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-danger');
        probar.find('span').text(msg);
        probar.css('width', progressbarMaxWidth);
        $('[data-toggle="upload-progressInput"]').val('');
        $('[data-toggle="upload-saveInput"]').val('');
	}
JS;
$this->registerJs($js);
?>




