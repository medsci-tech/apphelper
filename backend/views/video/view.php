<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/28
 * Time: 17:34
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
$referrer = Yii::$app->request->referrer ?? 'index';
backend\assets\AppAsset::register($this);
?>

<?php echo $this->render('svg'); ?>

<div style="margin: 0 auto;width: 75%; min-width: 900px">
    <div class="modal-header clearfix">
        <a class="btn btn-white pull-left" href="<?php echo $referrer;?>">返回</a>
        <div class="pull-left"><h4 class="modal-title" style="line-height: 34px;padding-left: 50px;font-size: 20px"><label id="l_title"><?php echo $modelData->name;?></label></h4></div>
    </div>

    <div class="player">
        <video controls crossorigin>
            <!-- Video files -->
            <source data-toggle="videoView" src="<?php echo $modelData->url;?>" type="<?php echo $modelData->type;?>">
            <!-- Text track file -->
            <!-- Fallback for browsers that don't support the <video> element -->
        </video>
    </div>
</div>

<?php

$js = <<<JS
plyr.setup();
JS;

$this->registerJs($js);
?>