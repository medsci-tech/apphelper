/**
 * An easy tree view plugin for jQuery and Bootstrap
 * @Copyright yuez.me 2014
 * @Author yuez
 * @Version 0.1
 */
(function ($) {
    $.fn.EasyTree = function (options) {
        var defaults = {
            selectable: true,
            deletable: false,
            editable: false,
            addable: false,
            enable: false,
            disable: false,
            i18n: {
                deleteNull: '请选择一个节点删除',
                deleteConfirmation: '删除这个节点？',
                confirmButtonLabel: '确定',
                editNull: '请选择一个节点进行编辑',
                editMultiple: '一次只能选择一个节点进行编辑',
                enableNull: '请选择一个节点进行启用',
                enableMultiple: '一次只能选择一个节点进行启用',
                enableConfirmation: '启用这个节点？',
                disableNull: '请选择一个节点进行禁用',
                disableMultiple: '一次只能选择一个节点进行禁用',
                disableConfirmation: '禁用这个节点？',
                addMultiple: 'Select a node to add a new node',
                collapseTip: '收起分支',
                expandTip: '展开分支',
                selectTip: '选择',
                unselectTip: '取消选择',
                editTip: '编辑',
                addTip: '添加',
                deleteTip: '删除',
                enableTip: '启用',
                disableTip: '禁用',
                cancelButtonLabel: '取消'
            },
            disableUid:{}
        };

        var warningAlert = $('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong><span class="alert-content"></span> </div> ');
        var dangerAlert = $('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong><span class="alert-content"></span> </div> ');

        var createInput = $('<div class="input-group"><input type="text" class="form-control" style="color:black"><span class="input-group-btn"><button type="button" class="btn btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');

        options = $.extend(defaults, options);

        this.each(function () {
            var easyTree = $(this);
            $.each($(easyTree).find('ul > li'), function() {
                var text;
                if($(this).is('li:has(ul)')) {
                    var children = $(this).find(' > ul');
                    $(children).remove();
                    text = $(this).text();
                    $(this).html('<span><span class="glyphicon"></span><a href="javascript: void(0);"></a> </span>');
                    $(this).find(' > span > span').addClass('glyphicon-folder-open');
                    $(this).find(' > span > a').text(text);
                    $(this).append(children);
                }
                else {
                    text = $(this).text();
                    $(this).html('<span><span class="glyphicon"></span><a href="javascript: void(0);"></a> </span>');
                    $(this).find(' > span > span').addClass('glyphicon-file');
                    $(this).find(' > span > a').text(text);
                }
            });

            $(easyTree).find('li:has(ul)').addClass('parent_li').find(' > span').attr('title', options.i18n.collapseTip);

            // add easy tree toolbar dom
            if (options.deletable || options.editable || options.addable) {
                $(easyTree).prepend('<div class="easy-tree-toolbar"></div> ');
            }

            // addable
            if (options.addable) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create"><button class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create > button').attr('title', options.i18n.addTip).click(function () {
                    var createBlock = $(easyTree).find('.easy-tree-toolbar .create');
                    $(createBlock).append(createInput);
                    $(createInput).find('input').focus();
                    $(createInput).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createInput).find('.confirm').click(function () {
                        if ($(createInput).find('input').val() === '')
                            return;
                        var flag = false;
                        var selected = getSelectedItems();
                        console.log(selected);
                        var item = $('<li><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);">' + $(createInput).find('input').val() + '</a> </span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                            var input = $(createInput).find('input').val();
                            $("#resource_name").val(input);
                            $("#type").val('addable');
                            $("#grade").val(1);
                            $("#uid").val(0);
                            flag = true;
                        } else if (selected.length > 1) {
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else {
                            var grade = $("#grade").val();
                            if( grade != '3') {
                                if ($(selected).hasClass('parent_li')) {
                                    $(selected).find(' > ul').append(item);
                                } else {
                                    $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                    $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                                }
                                var input = $(createInput).find('input').val();
                                $("#resource_name").val(input);
                                $("#type").val('addable');
                                flag = true;
                            }
                        }

                        $(createInput).find('input').val('');
                        if (options.selectable) {
                            console.log('selectable');
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                                if (li.hasClass('li_selected')) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                }
                                else {
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createInput).remove();
                        if(flag) {
                            console.log("addable submit");
                            $("#option").submit();
                        }
                    });
                    $(createInput).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createInput).find('.cancel').click(function () {
                        $(createInput).remove();
                    });
                });
            }

            // editable
            if (options.editable) {
                console.log('editable');
                $(easyTree).find('.easy-tree-toolbar').append('<div class="edit"><button class="btn btn-sm btn-primary disabled"><span class="glyphicon glyphicon-edit"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .edit > button').attr('title', options.i18n.editTip).click(function () {

                    /****disable状态下不可点-start-zhaiyu***********/
                    var zyeasyTreeClass = $(this).attr('class');
                    var zycheckClass = zyCheckDisable(zyeasyTreeClass);
                    if(zycheckClass){
                        console.log('biu');
                        return false;
                    }
                    /****disable状态下不可点-end-zhaiyu***********/
                    $(easyTree).find('input.easy-tree-editor').remove();
                    $(easyTree).find('input.easy-tree-editor-sort').remove();
                    $(easyTree).find('button.edit-confirm').remove();
                    $(easyTree).find('button.edit-cancel').remove();
                    $(easyTree).find('li > span > a:hidden').show();
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.editNull);
                    }
                    else if (selected.length > 1) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.editMultiple);
                    }
                    else {
                        var value = $(selected).find(' > span > a').text();
                        $(selected).find(' > span > a').hide();
                        $(selected).find(' > span').append('<input type="text" class="easy-tree-editor ">' +
                            '<input type="number" class="easy-tree-editor-sort" placeholder="排序">' +
                            '<button type="button" class="btn btn-info btn-xs btn-right edit-confirm">确定</button>' +
                            '<button type="button" class="btn btn-default btn-xs edit-cancel">取消</button>');
                        var editor = $(selected).find(' > span > input.easy-tree-editor');
                        var sort_input = $(selected).find(' > span > input.easy-tree-editor-sort');
                        var confirm = $(selected).find(' > span > button.edit-confirm');
                        var cancel = $(selected).find(' > span > button.edit-cancel');
                        $(editor).val(value);
                        $(sort_input).val($("#sort").val());
                        $(editor).focus();
                        $(editor).keydown(function (e) {
                            if (e.which == 13) {
                                if ($(editor).val() !== '') {
                                    $(selected).find(' > span > a').text($(editor).val());
                                    $(editor).remove();
                                    $(selected).find(' > span > a').show();
                                    $("#resource_name").val($(editor).val());
                                    $("#type").val('editable');
                                    $("#option").submit();
                                }
                            }
                        });
                        $(cancel).click(function () {
                            console.log($(easyTree));
                            $(easyTree).find('input.easy-tree-editor').remove();
                            $(easyTree).find('input.easy-tree-editor-sort').remove();
                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                            $(easyTree).find('button.edit-confirm').remove();
                            $(easyTree).find('button.edit-cancel').remove();
                            $(easyTree).find('li > span > a:hidden').show();
                            $(easyTree).find('li.li_selected').removeClass('li_selected');
                            $(this).attr('title', options.i18n.unselectTip);
                        });

                        $(confirm).click(function () {
                            console.log('confirm click');
                            $(easyTree).find('input.easy-tree-editor').remove();
                            $(easyTree).find('input.easy-tree-editor-sort').remove();
                            $(easyTree).find('button.edit-confirm').remove();
                            $(easyTree).find('button.edit-cancel').remove();
                            $(easyTree).find('li > span > a:hidden').show();
                            $(easyTree).find('li.li_selected').removeClass('li_selected');
                            $(this).attr('title', options.i18n.unselectTip);

                            $("#resource_name").val($(editor).val());
                            $("#sort").val($(sort_input).val())
                            $("#type").val('editable');
                            $("#option").submit();
                        });
                        //$(editor).blur(function(){
                        //    console.log('editor blur');
                        //    $(easyTree).find('input.easy-tree-editor').remove();
                        //    $(easyTree).find('input.easy-tree-editor-sort').remove();
                        //    $(easyTree).find('button.edit-confirm').remove();
                        //    $(easyTree).find('button.edit-cancel').remove();
                        //    $(easyTree).find('li > span > a:hidden').show();
                        //    $(easyTree).find('li.li_selected').removeClass('li_selected');
                        //    $(this).attr('title', options.i18n.unselectTip);
                        //});
                    }
                });
            }

            // deletable
            if (options.deletable) {
                console.log('deletable');
                $(easyTree).find('.easy-tree-toolbar').append('<div class="remove"><button class="btn btn-default btn-sm btn-danger disabled"><span class="glyphicon glyphicon-trash"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .remove > button').attr('title', options.i18n.deleteTip).click(function () {
                    /****disable状态下不可点-start-zhaiyu***********/
                    var zyeasyTreeClass = $(this).attr('class');
                    var zycheckClass = zyCheckDisable(zyeasyTreeClass);
                    if(zycheckClass){
                        console.log('biu');
                        return false;
                    }
                    /****disable状态下不可点-end-zhaiyu***********/
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.deleteNull);
                    } else {
                        $(easyTree).prepend(dangerAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.deleteConfirmation)
                            .append('<a style="margin-left: 10px;" class="btn btn-default btn-danger confirm"></a>')
                            .find('.confirm').html(options.i18n.confirmButtonLabel);
                        $(easyTree).find('.alert .alert-content .confirm').on('click', function () {
                            $(selected).find(' ul ').remove();
                            if($(selected).parent('ul').find(' > li').length <= 1) {
                                $(selected).parents('li').removeClass('parent_li').find(' > span > span').removeClass('glyphicon-folder-open').addClass('glyphicon-file');
                                $(selected).parent('ul').remove();
                            }
                            $(selected).remove();
                            $(dangerAlert).remove();
                            $("#type").val('delete');
                            $("#option").submit();
                        });
                    }
                });
            }

            // enable
            if (options.enable) {
                console.log('enable');
                $(easyTree).find('.easy-tree-toolbar').append('<div class="enable"><button class="btn btn-sm btn-primary disabled"><span class="glyphicon glyphicon-ok-circle"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .enable > button').attr('title', options.i18n.enableTip).click(function () {
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.enableNull);
                    } else if (selected.length > 1) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.enableMultiple);
                    } else {
                        $(easyTree).prepend(dangerAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.enableConfirmation)
                            .append('<a style="margin-left: 10px;" class="btn btn-default btn-danger confirm"></a>')
                            .find('.confirm').html(options.i18n.confirmButtonLabel);
                        $(easyTree).find('.alert .alert-content .confirm').on('click', function () {
                            //$(selected).find(' ul ').remove();
                            //if($(selected).parent('ul').find(' > li').length <= 1) {
                            //    $(selected).parents('li').removeClass('parent_li').find(' > span > span').removeClass('glyphicon-folder-open').addClass('glyphicon-file');
                            //    $(selected).parent('ul').remove();
                            //}
                            //$(selected).remove();
                            $(dangerAlert).remove();
                            $("#type").val('enable');
                            $("#option").submit();
                        });
                    }
                });
            }

            // disable
            if (options.disable) {
                console.log('disable');
                $(easyTree).find('.easy-tree-toolbar').append('<div class="disable"><button class="btn btn-default btn-sm btn-danger disabled"><span class="glyphicon glyphicon-ban-circle"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .disable > button').attr('title', options.i18n.disableTip).click(function () {
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.disableNull);
                    } else if (selected.length > 1) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.disableMultiple);
                    } else {
                        $(easyTree).prepend(dangerAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.disableConfirmation)
                            .append('<a style="margin-left: 10px;" class="btn btn-default btn-danger confirm"></a>')
                            .find('.confirm').html(options.i18n.confirmButtonLabel);
                        $(easyTree).find('.alert .alert-content .confirm').on('click', function () {
                            //$(selected).find(' ul ').remove();
                            //if($(selected).parent('ul').find(' > li').length <= 1) {
                            //    $(selected).parents('li').removeClass('parent_li').find(' > span > span').removeClass('glyphicon-folder-open').addClass('glyphicon-file');
                            //    $(selected).parent('ul').remove();
                            //}
                            //$(selected).remove();
                            $(dangerAlert).remove();
                            $("#type").val('disable');
                            $("#option").submit();
                        });
                    }
                });
            }

            // selectable, only single select
            if (options.selectable) {
                console.log('selectable');
                $(easyTree).find('li > span > a').attr('title', options.i18n.selectTip);
                $(easyTree).find('li > span > a').click(function (e) {
                    var li = $(this).parent().parent();
                    //console.log(li[0]);
                    //console.log($(li[0]).attr('grade'));
                    $("#grade").val($(li[0]).attr('grade'));
                    var zyUid = $(li[0]).attr('uid');
                    $("#uid").val(zyUid);
                    $("#sort").val($(li[0]).attr('sort'));
                    $("#type").val('selectable');
                    var dd = $(li[0]).find(' span > a')[0];
                    console.log($(dd).text());
                    if (li.hasClass('li_selected')) {
                        $(this).attr('title', options.i18n.selectTip);
                        $(li).removeClass('li_selected');
                    }
                    else {
                        $(easyTree).find('li.li_selected').removeClass('li_selected');
                        $(this).attr('title', options.i18n.unselectTip);
                        $(li).addClass('li_selected');
                    }

                    if (options.deletable || options.editable || options.enable || options.disable || options.addable) {
                        var selected = getSelectedItems();
                        var zyIsDisableUid = zyCheckDisableUid(zyUid);
                        if (options.editable) {
                            $(easyTree).find('input.easy-tree-editor').remove();
                            $(easyTree).find('input.easy-tree-editor-sort').remove();
                            $(easyTree).find('button.edit-confirm').remove();
                            $(easyTree).find('button.edit-cancel').remove();
                            $(easyTree).find('li > span > a:hidden').show();
                            if (selected.length == 0){
                                $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                            }else{
                                if(zyIsDisableUid == -1){
                                    $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                }else {
                                    $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                }
                            }
                        }

                        if (options.deletable) {
                            if (selected.length == 0){
                                $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                            }else{
                                if(zyIsDisableUid == -1){
                                    $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                }else {
                                    $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                }
                            }
                        }

                        if (options.enable) {
                            if (selected.length <= 0 || selected.length > 1)
                                $(easyTree).find('.easy-tree-toolbar .enable > button').addClass('disabled');
                            else
                                $(easyTree).find('.easy-tree-toolbar .enable > button').removeClass('disabled');
                        }

                        if (options.disable) {
                            if (selected.length <= 0 || selected.length > 1)
                                $(easyTree).find('.easy-tree-toolbar .disable > button').addClass('disabled');
                            else
                                $(easyTree).find('.easy-tree-toolbar .disable > button').removeClass('disabled');
                        }

                    }

                    e.stopPropagation();

                });
            };

            // collapse or expand
            $(easyTree).delegate('li.parent_li > span', 'click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(':visible')) {
                    children.hide('fast');
                    $(this).attr('title', options.i18n.expandTip)
                        .find(' > span.glyphicon')
                        .addClass('glyphicon-folder-close')
                        .removeClass('glyphicon-folder-open');
                } else {
                    children.show('fast');
                    $(this).attr('title', options.i18n.collapseTip)
                        .find(' > span.glyphicon')
                        .addClass('glyphicon-folder-open')
                        .removeClass('glyphicon-folder-close');
                }
                e.stopPropagation();
            });

            // Get selected items
            var getSelectedItems = function () {
                return $(easyTree).find('li.li_selected');
            };
        });

        /**
         * 检测是否可操作，不可操作返回true，可操作返回false
         * @author zhaiyu
         * @param zyeasyTreeClass
         * @returns {boolean}
         */
        var zyCheckDisable = function (zyeasyTreeClass) {
            return /disable/.test(zyeasyTreeClass);
        };

        /**
         * 验证元素uid是否存在于不可操作数据列表，存在返回所在位置，不存在返回-1
         * @author zhaiyu
         * @param uid
         * @returns {*}
         */
        var zyCheckDisableUid = function (uid) {
            return $.inArray(uid, defaults.disableUid);
        };
    };
})(jQuery);
