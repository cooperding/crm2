
/*
 *  submitForm  提交表单时执行
 *  classId 为当前表单的id
 */
function submitForm(classId) {
    //var url = $('#form_'+classId).attr('action');
    var url = $('.form_dogocms').attr('action');
    //    alert(url);
    //        return false;
    $('.form_dogocms').form('submit', {
        url: url,
        onSubmit: function() {
            //$('#dialog').dialog('refresh', '__APP__/Setting/add');

            //$('#dialog').dialog('close');
        },
        success: function(msg) {
            var data = $.parseJSON(msg);
            //alert(data.msg+'=======dede====');
            //return false;
            formAjax(data, classId);
        }
    });

}
/*更新tab功能*/
function updateTab(classId, url, subtitle) {
    //alert(url);
    //return false;
    $('#tabs_' + classId).tabs('select', subtitle);
    var tab = $('#tabs_' + classId).tabs('getSelected');
    tab.panel('refresh', url);
}
/*
 *openDialog 弹出框
 *href 传递控制器的url地址
 *title 弹出窗口的标题
 */
function openDialog(classId, href, title) {
    $('#dialog_cms').dialog({
        href: href,
        width: 900,
        height: 550,
        resizable: true,
        title: title,
        modal: true,
        resizable:true,
                collapsible: true,
        maximizable: true,
        cache: false,
        onClose: function() {
            dialogOnClose();
        },
        buttons: [{
                text: '保存',
                iconCls: 'icon-ok',
                handler: function() {
                    submitForm(classId);
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function() {
                    dialogOnClose();
                }
            }
        ]
    });
//$('#dialog'+classId).dialog('refresh', href);
}
/*
 * 关闭dialog时，销毁dialog代码
 */
function dialogOnClose() {
    $('#dialog_cms').dialog('destroy');
    $('body.layout_index').append('<div id="dialog_cms"  data-options="iconCls:\'icon-save\'"></div>');
    var frame = $('iframe[src="about:blank"]');//destroy与iframe冲突问题，大概是内存释放的原因
    frame.remove();
}


/*
 *添加tab
 *暂未使用，有问题
 */
function addTab(subtitle, url) {
    //alert(555);
    if (!$('#tabs').tabs('exists', subtitle)) {
        $('#tabs').tabs('add', {
            title: subtitle,
            content: subtitle,
            closable: true,
            href: url,
            tools: [{
                    iconCls: 'icon-mini-refresh',
                    handler: function() {
                        updateTab(url);
                    }
                }]
        });
        return false;
    } else {
        $('#tabs').tabs('select', subtitle);
        var tab = $('#tabs').tabs('getSelected');  // get selected panel
        tab.panel('refresh', url);
        return false;
    }
}

/*
 * openDatagrid 执行数据结构的文档
 * classId id
 * urljson 读取数据的url地址
 * hrefadd 添加信息路径
 * hrefedit修改信息路径
 * hrefcancel 删除信息路径 暂未使用
 */
function openDatagrid(classId, urljson, hrefadd, hrefedit, hrefcancel) {
    var height = $('.indexcenter').height();
    $('#datagrid_' + classId).datagrid({
        url: urljson,
        idField: 'id',
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        checkbox: true,
        height: height - 50,
        frozenColumns: [[
                {
                    field: 'ck',
                    checkbox: true
                }
            ]],
        toolbar: [{
                id: 'btnadd_' + classId,
                text: '添加',
                iconCls: 'icon-add',
                handler: function() {
                    var title = '添加分类';
                    openDialog(classId, hrefadd, title);
                }
            }, '-', {
                id: 'btnedit_' + classId,
                text: '编辑',
                iconCls: 'icon-edit',
                handler: function() {
                    var ids = [];
                    var rows = $('#datagrid_' + classId).datagrid('getSelections');
                    for (var i = 0; i < rows.length; i++) {
                        ids.push(rows[i].id);
                    }
                    if (ids == '') {
                        $.messager.alert('信息提示', '请选择要操作的项', 'error');
                        return false;
                    } else if (rows.length > 1) {
                        $.messager.alert('信息提示', '请选择一个要操作的项', 'error');
                        return false;
                    }

                    var href = hrefedit + '?id=' + ids;
                    var title = '编辑信息';
                    openDialog(classId, href, title);
                }
            }, '-', {
                id: 'btncanel_' + classId,
                text: '删除',
                iconCls: 'icon-cancel',
                handler: function() {
                    var selected = $('#datagrid_' + classId).datagrid('getSelected');
                    if (!selected) {
                        $.messager.alert('信息提示', '请选择要操作的项', 'error');
                        return false;
                    }
                    var id = selected.id;
                    var href = hrefcancel;
                    var title = '删除信息';
                    dogoDelete(id,title,href,classId);
                }
            }//
        ]//toolbar
    });

}
/*
 * dogoDelete 执行删除功能
 * classId id
 * href 请求地址
 */
function dogoDelete(id,title,href,classId) {
    $.messager.confirm(title, '确定要删除信息吗?', function(r) {
        if (!r) {
            return false;
        }
        $.ajax({
            url: href,
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                formAjax(data, classId);
            }
        });
    });//$
}

/*
 * openTreeGrid 执行树结构的文档
 *classId id
 * urljson 读取数据的url地址
 * hrefadd 添加信息路径
 * hrefedit修改信息路径
 * hrefcancel 删除信息路径 暂未使用
 */

function openTreeGrid(classId, urljson, hrefadd, hrefedit, hrefcancel) {
    var height = $('.indexcenter').height();
    $('#treegrid_' + classId).treegrid({
        url: urljson,
        idField: 'id',
        treeField: 'text',
        pagination: true,
        rownumbers: true,
        fitColumns: true,
        autoRowHeight: false,
        showFooter: true,
        height: height - 50,
        animate: true,
        toolbar: [{
                id: 'btnadd' + classId,
                text: '添加',
                iconCls: 'icon-add',
                handler: function() {
                    var title = '添加分类';
                    openDialog(classId, hrefadd, title);
                }
            }, '-', {
                id: 'btnedit' + classId,
                text: '编辑',
                iconCls: 'icon-edit',
                handler: function() {
                    var selected = $('#treegrid_' + classId).datagrid('getSelected');
                    if (!selected) {
                        $.messager.alert('信息提示', '请选择要操作的项', 'error');
                        return false;
                    }
                    var id = selected.id;
                    var href = hrefedit + '?id=' + id;
                    var title = '编辑信息';
                    openDialog(classId, href, title);
                }
            }, '-', {
                id: 'btncancel' + classId,
                text: '删除',
                iconCls: 'icon-cancel',
                handler: function() {
                    var selected = $('#treegrid_' + classId).datagrid('getSelected');
                    if (!selected) {
                        $.messager.alert('信息提示', '请选择要操作的项', 'error');
                        return false;
                    }
                    var id = selected.id;
                    var href = hrefcancel;
                    var title = '删除信息';
                    dogoDelete(id,title,href,classId);
                }
            }
        ]
    });
}

function formAjax(data, classId) {

    if (data.status == 1) {
        $.messager.alert(data.info, data.info, 'error');
    } else if (data.status == 2) {
        $.messager.show({
            title: data.info,
            msg: data.info,
            timeout: 5000,
            showType: 'slide'
        });
        $('#treegrid_' + classId).treegrid('reload');
        $('#datagrid_' + classId).datagrid('reload');
        if (data.isclose == 'ok') {
            dialogOnClose();
        }


    }
}
function changeTheme(themeName) {
    var $easyuiTheme = $('#easyuiTheme');
    var url = $easyuiTheme.attr('href');
    var href = url.substring(0, url.indexOf('themes')) + 'themes/' + themeName + '/easyui.css';
    $easyuiTheme.attr('href', href);

    var $iframe = $('iframe');
    if ($iframe.length > 0) {
        for (var i = 0; i < $iframe.length; i++) {
            var ifr = $iframe[i];
            $(ifr).contents().find('#easyuiTheme').attr('href', href);
        }
    }

    $.cookie('easyuiThemeName', themeName, {
        expires: 7
    });
}

function ding_views(href, classId) {
    //var title = '编辑信息';
    //openDialog(classId,href,title);
}
function ding_edit(href, classId) {
    var title = '编辑信息';
    openDialog(classId, href, title);
}
function ding_cancel(id, href, classId) {
    var title = '删除信息';
    $.messager.confirm(title, '确定要删除信息吗?', function(r) {
        if (!r) {
            return false;
        }
        $.ajax({
            url: href,
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                formAjax(data, classId);
            }
        });
    });//$
}














/*
 *  失去作用
 *
 */
function closeCombo() {
    $('body.layoutindex>.combo-p').remove();
    $('body.layoutindex>.window').remove();
    $('body.layoutindex>.window-shadow').remove();
    $('body.layoutindex>.window-mask').remove();
}