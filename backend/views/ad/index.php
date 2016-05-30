<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 16:49
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '轮播图管理';
$this->params['breadcrumbs'][] = $this->title;
backend\assets\AppAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/hplus');
?>




    <div class="modal-body">
        <div class="navbar-btn">
            <button id="btnAdd" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-plus"></span>添加
            </button>

            <button id="btnEdit" type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-edit"></span>修改
            </button>
            <button id="btnDelete" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash">删除</span></button>
        </div>
        <div class="row">
            <?= $strHtml ?>
        </div>


        <!-- 弹出曾部分 -->
        <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="overflow:auto">
            <div class="modal-dialog">
                <div class="modal-content animated bounceInRight">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        <h4 class="modal-title"><label id="l_title">添加</label></h4>
                    </div>
                    <?= $this->render('create', [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>
        </div>
<?php
$js = <<<JS

            var aid;
            var imgUrl;
            var sort;
            var linkUrl;
            var status;
            var attr_id;
            var attr_from;
            var titles;
        $('div.row img').click(function () {
            $("div.row img").css("border","1px solid #ddd");
            $(this).css("border","1px solid #3B9A2E");
            aid = $(this).attr('aid');
            imgUrl = $(this).attr('src');
            sort = $(this).attr('sort');
            linkUrl = $(this).attr('links');
            status = $(this).attr('status');
            attr_id = $(this).attr('attr_id');
            attr_from = $(this).attr('attr_from');
            titles = $(this).attr('atitle');

        });

        $('#btnAdd').click(function () {
            var title = '添加';
            $("#l_title").html(title);
            $("#mode").val('add');

            $("#aid").val('');
            $("#ad-sort").val('');
            $("#txt_show").val('');
            $("#txt_value").val('');
            $("#ad-status").val('');
            $("#ad-title").val('');
            $("#attr_id").val('');
            $("#attr_from").val('');
        });

        $('#btnEdit').click(function () {
            var title = '编辑';
            $("#l_title").html(title);
            $("#mode").val('edit');

            $("#aid").val(aid);
            $("#ad-sort").val(sort);
            $("#txt_show").val(imgUrl);
            $("#txt_value").val(imgUrl);
            $("#ad-status").val(status);
            $("#ad-title").val(titles);
            $("#attr_id").val(attr_id);
            $("#attr_from").val(attr_from);
        });

        $('#btnDelete').click(function () {
            $("#mode").val('delete');
            $("#tableForm").submit();

            $("#aid").val(aid);
        });

        $('#select').click(function(){
            layer.open({
              type: 2,
              title: '选择资源',
              shadeClose: false,
              shade: 0.3,
              closeBtn:1,
              area: ['600px', '90%'],
              content: '/ad/resource', //iframe的url

            });
        });


JS;
$this->registerJs($js);
?>