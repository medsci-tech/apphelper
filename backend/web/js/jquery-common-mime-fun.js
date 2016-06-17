/**
 * 基于jQuery的方法
 * author zhaiyu
 * startDate 20160511
 * updateDate 20160511
 */
console.log('jq-common-mime');
/**
 * 试题管理-题库编辑试题-初始化选项
 * @param element string | eq:'#div'
 * @param option json | eq:{A: "1", B: "2", C: "4"}
 * @param answer string | eq:'A,B,C'
 * @param checkType string | eq:'radio'
 */
exerciseEditForMime = function (element, option, answer, checkType) {
    var html = '';
    var i = 0;
    var optionLength = Object.keys(option).length;
    if(optionLength > 0){
        for(var key in option){
            html += '<tr data-key="' + ( i + 1 ) + '">';
            html += '    <td>' +key+ '</td>';
            html += '    <td><input type="text" class="form-control" name="Exercise[option][]" value="' + option[key] + '"></td>';
            html += '    <td><input type="' + checkType + '" ';
            if(answer.match(key)){
                html += 'checked="checked" ';
            }
            html += ' class="checkValue" name="Exercise[answer][]" value="' +key+ '"></td>';
            html += '    <td>';
            html += '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>';
            if(i == optionLength - 1){
                html += '        <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
            }
            html += '    </td>';
            html += '</tr>';
            i++;
        }
        $(element).html(html);
    }else {
        exerciseInitForMime(element);
    }
};

/**
 * 试卷管理-编辑试题-初始化选项
 * @param asThis string | eq:[tbody#optionListBody, context: document, selector: "#optionListBody"]
 * @param list array | eq:{A: "1", B: "2", C: "4"}
 */
examEditForMime = function (asThis, list) {
    var html = '';
    var listLen = list.length;
    for(var i = 0; i < listLen; i++){
        html += '<tr data-key="' + ( i + 1 ) + '">';
        html += '    <td>' + (i + 1) + '</td>';
        html += '    <td>' + list[i]['type'] + '<input type="hidden" name="Exam[exe_ids][]" value="' + list[i]['id'] + '"></td>';
        html += '    <td><a href="/exercise/view?id=' + list[i]['id'] + '">' + list[i]['question'] + '</a></td>';
        html += '    <td>' + list[i]['option'] + '</td>';
        html += '    <td>' + list[i]['answer'] + '</td>';
        html += '    <td>';
        html += '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>';
        html += '    </td>';
        html += '</tr>';
    }
    asThis.html(html);
};

/**
 * 删除所在的行(tr)-仅适用于table
 * @param element string | eg:'#div'
 * @param asThis
 * @param retainNum int 保留项数
 * @param type int 1,字母序号 2，数字序号 3，无序号
 */
delThisRowOptionForMime = function (element, asThis, retainNum, type) {
    if(undefined == retainNum){
        retainNum = 0;
    }
    if(undefined == type){
        type = 1;
    }
    if($(element).find('tr').length > retainNum){
        var parentTr = $(asThis).parent().parent();
        var addHtmlLength = $(asThis).next('a').length;
        if(addHtmlLength){
            var addHtml = ' <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
            parentTr.prev('tr').find('td').last().append(addHtml);
        }
        for (var i = 0; i < parentTr.nextAll('tr').length; i++){
            var thisTr = $(parentTr.nextAll('tr')[i]);
            var thisTd = thisTr.find('td');
            var dataKey = parseInt(thisTr.attr('data-key'));
            var order;
            if(1 == type){
                order = String.fromCharCode(63 + dataKey);
                thisTd.find('.checkValue').val(order);
                thisTd.eq(0).text(order);
            }else if(2 == type){
                thisTd.eq(0).text(dataKey - 1);
            }
            thisTr.attr('data-key',dataKey - 1);
        }
        parentTr.remove();
    }
};

/**
 * 禁用启用等按钮的提交操作
 * @param element string | eg:'#div'
 * @param val
 */
subActionForMime = function (element,val) {
    $(element).val(val);
    $(element).submit();
};

/**
 * 判断多选框是否有勾选，有勾选返回true，没有则弹窗提示并返回false
 * @param check array
 * @returns {boolean}
 */
verifyCheckedForMime = function (check) {
    var checked = 0;
    for(var i =0; i < check.length; i++){
        if(check[i].checked == true){
            checked++;
        }
    }
    if(0 == checked){
        swal('未选择','请勾选需要操作的信息');
        return false;
    }else {
        return true;
    }
};

/**
 *  初始化图片上传数据
 */
uploadResultInit = function () {
    $('#upload-progressbar').css('width', 0);
    $('#upload-progressbar').find('span').text('');
    $('[data-toggle="upload-progressInput"]').val('');
    $('[data-toggle="upload-saveInput"]').val('');
};

/**
 * ajax提交请求
 * @param type
 * @param url
 * @param data
 * @param location
 */
subActionAjaxForMime = function (type, url, data, location) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        success: function(res){
            if(res.code == 200){
                swal({
                    title: "成功",
                    type: "success",
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "确定",
                    closeOnConfirm: false
                }, function () {
                    window.location.href = location;
                });
            }else {
                swal({
                    title: "失败",
                    text: res.msg,
                    type: "warning",
                    confirmButtonColor: "#1ab394",
                    confirmButtonText: "确定",
                    closeOnConfirm: false
                });
            }
        }
    });
};
