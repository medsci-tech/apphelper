/**
 * 编辑数据提交前获取地区数据
 * author zhaiyu
 * startDate 20160510
 * updateDate 20160510
 * @param m
 * @param parentBom
 */
getRegionValue = function (m, parentBom) {
    if(parentBom){
        parentBom = '#' + parentBom + ' ';
    }else {
        parentBom = '';
    }
    var cityTitle = $(parentBom + '[data-toggle="city-picker"]').next().find('.title');
    var province = cityTitle.find('span[data-count="province"]');
    var city = cityTitle.find('span[data-count="city"]');
    var area = cityTitle.find('span[data-count="district"]');
    var province_id = province.attr('data-code') ? province.attr('data-code') : '';
    var city_id = city.attr('data-code') ? city.attr('data-code') : '';
    var area_id = area.attr('data-code') ? area.attr('data-code') : '';

    var regionHtml = '';
    regionHtml += '<input type="hidden" name="' + m + '[province_id]" value="' + province_id + '">';
    regionHtml += '<input type="hidden" name="' + m + '[city_id]" value="' + city_id + '">';
    regionHtml += '<input type="hidden" name="' + m + '[area_id]" value="' + area_id + '">';

    regionHtml += '<input type="hidden" name="' + m + '[province]" value="' + province.text() + '">';
    regionHtml += '<input type="hidden" name="' + m + '[city]" value="' + city.text() + '">';
    regionHtml += '<input type="hidden" name="' + m + '[area]" value="' + area.text() + '">';

    $(parentBom + '[data-toggle="city-picker-region"]').html(regionHtml);
    console.log(area);
};

/**
 *  修改数据前获取数据信息
 * author zhaiyu
 * startDate 20160510
 * updateDate 20160510
 * @param value
 * @param parentBom
 */
getRegionDefault = function (value,parentBom) {
    if(parentBom){
        parentBom = '#' + parentBom + ' ';
    }else {
        parentBom = '';
    }
    var pickerHtml = '';
    var pickerSpan = $(parentBom + '[data-toggle="city-picker"]').next();
    if (value.province) {
        pickerHtml += '<span class="select-item" data-count="province" data-code="' + value.province_id + '">' + value.province + '</span>';
        if (value.city) {
            pickerHtml += '/<span class="select-item" data-count="city" data-code="' + value.city_id + '">' + value.city + '</span>';
            if (value.area) {
                pickerHtml += '/<span class="select-item" data-count="district" data-code="' + value.area_id + '">' + value.area + '</span>';
            }
        }
        pickerSpan.find('.placeholder').css('display', 'none');
        pickerSpan.find('.title').css('display', 'inline').html(pickerHtml);
    }else {
        pickerSpan.find('.placeholder').css('display','');
        pickerSpan.find('.title').css('display','none').html(pickerHtml);
    }
};

/**
 *  编辑数据前初始化数据--一般为添加操作
 * author zhaiyu
 * startDate 20160510
 * updateDate 20160510
 * @param parentBom
 */
getRegionInit = function (parentBom) {
    if(parentBom){
        parentBom = '#' + parentBom + ' ';
    }else {
        parentBom = '';
    }
    var pickerHtml = '';
    var pickerSpan = $(parentBom + '[data-toggle="city-picker"]').next();
    pickerSpan.find('.placeholder').css('display','');
    pickerSpan.find('.title').css('display','none').html(pickerHtml);
};