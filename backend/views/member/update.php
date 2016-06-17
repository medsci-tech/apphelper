<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */

$this->title = '编辑用户数据';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];

?>
<div class="article-update">

    <?= $this->render('_form') ?>

</div>
<?php
$js = <<<JS

//提交操作
$('#updateModal #memberFormSubmit').click(function() {
    getRegionValue('Member','updateModal');/*地区联动*/
    $('#updateModal #tableForm').submit();
});


JS;
$this->registerJs($js);
?>