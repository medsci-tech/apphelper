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
?>

<div class="ad-index">
<button type="button" class="btn btn-success" data-toggle="modal" data-target ="#myModal"><span class="glyphicon-class"></span>添加</button>

<button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span>修改</button>
<button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash">删除</span></button>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <img src="../img/a1.jpg"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="../img/a2.jpg"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="../img/a3.jpg"
             alt="" class="thumbnail">
    </div>
    <div class="col-sm-6 col-md-4">
        <img src="../img/a4.jpg"
             alt="" class="thumbnail">
    </div>


</div>


<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><label id="l_title">添加轮播图</label></h4>
            </div>
            <?=$this->render('create', [
                'model' => $model,
            ]);?>
        </div>
    </div>
</div>
</div>