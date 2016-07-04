<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/13
 * Time: 10:45
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

backend\assets\AppAsset::register($this);
?>

<?php $form = ActiveForm::begin(['action' => ['ad/find'], 'method' => 'get', 'id' => 'resourceForm']); ?>
    <div class="modal-body" style="background-color: white">
    <label class="control-label">资源类型：
        <label class="checkbox-inline">
            <input type="radio" name="optionsResource" id="rdo1" class="radioItem"
                   value="1"> 培训
        </label>
        <label class="checkbox-inline">
            <input type="radio" name="optionsResource" id="rdo2" class="radioItem"
                   value="2" checked> 试卷
        </label>
    </label>
    <label class="control-label">资源名称：
        <div class="input-group">
            <input name="resource" type="text" class="form-control">
            <span class="input-group-btn">
                <button id="btnSearch" type="button" class="btn btn-primary">搜索
                </button>
            </span>
        </div>
    </label>
    <table id="table3"
        data-toggle="table"
        data-single-select="true"
        data-height="350">
        <thead>
        <tr>
            <th data-width="10%">ID</th>
            <th>资源名</th>
        </tr>
        </thead>
        </tbody>
        <?= $strHtml ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <input type="hidden" name="res_type" id="res_type" value="2">
    <input type="hidden" name="attr_id" id="attr_id">
    <input type="hidden" name="attr_name" id="attr_name">
    <button id="btnClose" type="button" class="btn btn-white">关闭</button>
    <button id="btnConfirm" type="button" class="btn btn-primary">确定</button>
</div>


<?php ActiveForm::end(); ?>

<?php
$js = <<<JS

    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引

    $("#btnClose").click(function() {
        parent.layer.close(index);
    });

    $("#btnConfirm").click(function() {
        console.log("xxx:"+$("#res_type").val());
        parent.$('#attr_name').val($("#attr_name").val());
        parent.$('#attr_id').val($("#attr_id").val());
        parent.$('#attr_from').val($("#res_type").val());
        parent.layer.close(index);
    });

    $("#btnSearch").click(function() {
        $("#resourceForm").submit();
    });

    $("#table3 td").click(function () {
        var tdSeq = $(this).parent().find("td").index($(this));
        var trSeq = $(this).parent().parent().find("tr").index($(this).parent());
        var td1 = "#table3 tr:gt(0):eq("+trSeq+") td:eq(0)";
        var td2 = "#table3 tr:gt(0):eq("+trSeq+") td:eq(1)";
        $("#attr_id").val($(td1).text());
        $("#attr_name").val($(td2).text());
        //var v1 = $(td1).text();
        //var v2 = $(td2).text();
        //alert("第" + (trSeq) + "行，第" + (tdSeq+1) + "列, "+v1+","+v2);
    });

    $("#table3 tr").click(function () {
        $("#table3 tr").css('background-color','white');
        $(this).css('background-color','#B7B7AD');
    });

    $('.radioItem').change(function(){
        var valOption = $('input[name="optionsResource"]:checked').val();
        $("#res_type").val(valOption);
        console.log(valOption);
        $("#table3  tr:not(:first)").html("");
    });

var getParam = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();

    (function ($) {
        function init() {
            var type = getParam["optionsResource"];
            if(type != null) {
            console.log(type);
                if(type=='1') {
                    $("#rdo1").attr("checked","checked");
                    $("#res_type").val('1');
                } else{
                    $("#rdo2").attr("checked","checked");
                    $("#res_type").val('2');
                }
            }
        }
        window.onload = init();
    })(jQuery)
JS;
$this->registerJs($js);
?>