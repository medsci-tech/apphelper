<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */

$this->title = '添加用户数据 ';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];

?>
<div class="article-create">

    <?= $this->render('_form') ?>

</div>
<?php
$js = <<<JS

//提交操作
$('#myModal #memberFormSubmit').click(function() {
    getRegionValue('Member','myModal');/*地区联动*/
    $('#myModal #tableForm').submit();
});

JS;
$this->registerJs($js);
?>