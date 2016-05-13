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
 * 试题管理-题库添加试题-初始化选项-默认四个选项
 * @param element string | eg:'#div'
 */
exerciseInitForMime = function (element) {
    var html = ''
        + '<tr data-key="1">'
        + '    <td>A</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="A"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="2">'
        + '    <td>B</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="B"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="3">'
        + '    <td>C</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="C"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '    </td>'
        + '</tr>'
        + '<tr data-key="4">'
        + '    <td>D</td>'
        + '    <td><input type="text" class="form-control" name="Exercise[option][]" value=""></td>'
        + '    <td><input type="radio" class="checkValue" name="Exercise[answer][]" value="D"></td>'
        + '    <td>'
        + '        <a href="javascript:void(0);" class="delThisOption"><span class="glyphicon glyphicon-minus-sign"></span></a>'
        + '        <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>'
        + '    </td>'
        + '</tr>';
    $(element).html(html);
};

/**
 * 删除所在的行(tr)-仅适用于table
 * @param element string | eg:'#div'
 * @param asThis
 */
delThisRowOptionForMime = function (element, asThis) {
    if($(element).find('tr').length > 1){
        var parentTr = $(asThis).parent().parent();
        // var nextTr = parentTr.nextAll('tr');
        var addHtmlLength = $(asThis).next('a').length;
        if(addHtmlLength){
            var addHtml = ' <a href="javascript:void(0);" class="addNextOption"><span class="glyphicon glyphicon-plus-sign"></span></a>';
            parentTr.prev('tr').find('td').last().append(addHtml);
        }
        for (var i = 0; i < parentTr.nextAll('tr').length; i++){
            var thisTr = $(parentTr.nextAll('tr')[i]);
            var thisTd = thisTr.find('td');
            var dataKey = parseInt(thisTr.attr('data-key'));
            var thisLatter = String.fromCharCode(63 + dataKey);
            thisTr.attr('data-key',dataKey - 1);
            thisTd.eq(0).text(thisLatter);
            thisTd.find('.checkValue').val(thisLatter);
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
    console.log(checked);
    if(0 == checked){
        swal('未选择','请勾选需要操作的信息');
        return false;
    }else {
        return true;
    }
};
