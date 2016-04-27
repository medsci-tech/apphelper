<?php
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>


<!--树形视图--start-->
<div id="treeview10" class="col-lg-2 modal-body treeview">
    <ul class="list-group">
        <li class="list-group-item node-treeview10" data-nodeid="0" style="">
            <span class="icon"><i class="click-collapse glyphicon glyphicon-minus"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#parent1" style="color:inherit;">父节点 1</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="1" style="">
            <span class="indent"></span>
            <span class="icon"><i class="click-expand glyphicon glyphicon-plus"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#child1" style="color:inherit;">子节点 1</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="2" style="">
            <span class="indent"></span>
            <span class="icon"><i class="glyphicon"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#child2" style="color:inherit;">子节点 2</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="3" style="">
            <span class="icon"><i class="glyphicon"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#parent2" style="color:inherit;">父节点 2</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="4" style="">
            <span class="icon"><i class="glyphicon"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#parent3" style="color:inherit;">父节点 3</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="5" style="">
            <span class="icon"><i class="glyphicon"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#parent4" style="color:inherit;">父节点 4</a>
        </li>
        <li class="list-group-item node-treeview10" data-nodeid="6" style="">
            <span class="icon"><i class="glyphicon"></i></span>
            <span class="icon"><i class="glyphicon glyphicon-stop"></i></span>
            <a href="#parent5" style="color:inherit;">父节点 5</a>
        </li>
    </ul>
</div>
<!--树形视图--end-->