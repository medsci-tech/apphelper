<?php
use yii\helpers\Html;
?>


<link rel="stylesheet" href="/css/easyTree.css">
    <script src="/js/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="/js/easyTree.js"></script>
    <style>

    </style>

<div class="col-md-3">
    <h3 class="text-success">目录分类管理</h3>
    <div class="easy-tree">
        <?= $strHtml ?>
    </div>


</div>
<script>
    (function ($) {
        function init() {
            $('.easy-tree').EasyTree({
                addable: true,
                editable: true,
                deletable: true
            });
        }
        window.onload = init();
    })(jQuery)
</script>
