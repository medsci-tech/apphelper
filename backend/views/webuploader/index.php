<?php
/**
 * Created by PhpStorm.
 * User: mime
 * Date: 2016/5/19
 * Time: 16:32
 */
$modelName = $name ?? '';
$proBarMaxWidth = $proBarWidth ?? 200;
?>

<div class="form-inline">
    <div class="form-group">
        <input readonly type="text" class="form-control" data-toggle="progressInput">
        <input type="hidden" data-toggle="saveInput" name="<?php echo $modelName;?>">
    </div>
    <button id="promptzone" type="button" class="btn btn-warning">上传</button>
    <div class="form-group progress">
        <div id="progressbar" aria-valuemax="100" role="progressbar" class="progress-bar progress-bar-info">
            <span></span>
        </div>
    </div>
</div>
<?php
$js = <<<JS
// Set fieldname
	$.ajaxUploadSettings.name = 'file';
	var probar = $('#progressbar');
	var progressbarMaxWidth = $proBarMaxWidth;
	$('#promptzone').ajaxUploadPrompt({
		url : '/upload/img',
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
                uploadResultError();
			}
		}
	});
	
	function uploadResultSuccess(data) {
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-success');
        probar.find('span').text('上传成功');
        $('[data-toggle="progressInput"]').val(data.tName);
        $('[data-toggle="saveInput"]').val(data.saveName);
	}
	
	function uploadResultError() {
        probar.removeClass('progress-bar-info');
        probar.addClass('progress-bar-danger');
        probar.find('span').text('上传失败');
        $('[data-toggle="progressInput"]').val('');
        $('[data-toggle="saveInput"]').val('');
	}
JS;
$this->registerJs($js);
?>




