<?php
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>


<!--树形视图--start-->
<div id="treeView" class="col-lg-2 modal-body"></div>
<!--树形视图--end-->

<?php
$js = <<<JS
$(function() {
	var initSelectableTree = function() {
		return $('#treeView').treeview({
		    levels: 1,
			data: $examClass
		});
	};
	var selectableTree = initSelectableTree();
	var findSelectableNodes = function() {
		return selectableTree.treeview('search', [ '第', { ignoreCase: false, exactMatch: false } ]);
	};
	var selectableNodes = findSelectableNodes();
});
JS;
$this->registerJs($js);
?>