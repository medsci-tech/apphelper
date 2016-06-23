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
backend\assets\AppAsset::register($this);
?>
<?php echo $this->render('svg'); ?>

    <div class="modal-body">
        <div class="">
            <div class="player img-thumbnail">
                <video poster="" controls crossorigin>
                    <!-- Video files -->
                    <source src="http://o7f6z4jud.bkt.clouddn.com/video/20160623o_1alu4lfluv11ip019cfjon5ttp.flv" type="video/mp4;video/webm">
                    <!-- Text track file -->
                    <!-- Fallback for browsers that don't support the <video> element -->
                    <a href="https://cdn.selz.com/plyr/1.0/movie.mp4">Download</a>
                </video>
            </div>
        </div>
    </div>

<?php

$js = <<<JS
plyr.setup();
JS;

$this->registerJs($js);
?>