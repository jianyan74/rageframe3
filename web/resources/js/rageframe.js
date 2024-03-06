$(document).ready(function () {
    if ($(this).width() < 769) {
        config.isMobile = true;
    }

    // 动态改变菜单
    autoChangeMenu(true);
    // 修改颜色
    autoFontColor();
    // 初始化表格
    initTable();

    // 判断默认显示
    var isDefaultShow = false;
    $('.rfLeftMenu').each(function () {
        if ($(this).hasClass('is_default_show')) {
            isDefaultShow = true;
        }
    });

    if (isDefaultShow === false) {
        var id = $('.rfTopMenu').eq(0).data('id');
        if (id) {
            $('.rfLeftMenu-' + id).removeClass('hide');
            $('.rfTopMenu').eq(0).addClass('rfTopMenuHover');
        }
    }
});

$(window).resize(function () {
    var leftAuto = true;
    if (config.isMobile == false) {
        leftAuto = false;
    }

    if ($(this).width() < 769) {
        config.isMobile = true;
    } else {
        config.isMobile = false;
    }

    if (config.isMobile == false && leftAuto == false) {
        autoChangeMenu();
    } else {
        autoChangeMenu(true);
    }

    // 初始化table
    // initTable();
});

function autoFontColor() {
    $("body").find("label").each(function (i, data) {
        if ($(data).find('input').length > 0) {
            $(data).attr('style', 'color:#636f7a');
        }
    })
}

function autoChangeMenu(leftAuto = false) {
    // 改变框架高度
    var mainContent = window.innerHeight - 99;
    if (config.tag != true || config.isMobile == true) {
        mainContent = mainContent + 40;
    }
    $(".J_mainContent").height(mainContent);

    if (config.isMobile == true) {
        // 显示左边菜单
        $('.rfLeftMenu').removeClass('hide');
        // 隐藏tag
        $(".content-tabs").addClass('hide');
        // 显示退出
        $("#logout").removeClass('hide');
        // 隐藏头部菜单栏
        $('.rfTopMenu').each(function (i, data) {
            var type = $(this).data('type');
            if (type) {
                $(this).addClass('hide');
            }
        });

        // 增加样式
        $(".J_mainContent").addClass('rfMainContent');
        // 底部隐藏
        $(".main-footer").addClass('hide');
    } else {
        if (leftAuto == true) {
            // 隐藏左边菜单
            $('.rfLeftMenu').addClass('hide');
            // 默认菜单显示
            $('.is_default_show').removeClass('hide');
        }

        // 头部菜单栏
        $('.rfTopMenu').removeClass('hide');
        // 显示标签
        $('.content-tabs').removeClass('hide');
        // 隐藏退出
        $("#logout").addClass('hide');
        // 移除样式
        $(".J_mainContent").removeClass('rfMainContent');
        // 底部显示
        $(".main-footer").removeClass('hide');
    }

    if (config.tag != true) {
        // 隐藏tag
        $(".content-tabs").addClass('hide');
        // 显示退出
        $("#logout").removeClass('hide');
    }

    // 判断顶部菜单显示状态
    if (config.isMobile == false) {
        var allNavWidth = $('.rf-navbar-nav').width();
        if (allNavWidth === undefined) {
            return false;
        }

        $('.rf-navbar-nav .rf-navbar-nav-left li').removeClass('hide');
        $('.hide-menu div').html('');

        var leftWidth = allNavWidth - $('.rf-navbar-nav .ml-auto').width() - 70;

        if (leftWidth < $('.rf-navbar-nav-left').width()) {
            var tmpWith = 0;
            // 移动菜单显示
            $('.rf-navbar-nav .rf-navbar-nav-left li').each(function (i, item) {
                tmpWith += parseInt($(item).width());
                if (tmpWith > leftWidth) {
                    $(item).addClass('hide');
                    $('.hide-menu').removeClass('hide');
                    // 增加一次的菜单
                    $('.hide-menu > div').append("<li class='rfTopMenu' data-type=" + $(item).data('type') + " data-id=" + $(item).data('id') + ">" + $(item).html() + "</li>")
                    $('.hide-menu div li').find('a').css({"height": "35px", "line-height": "1.5"});
                }
            })
        } else {
            $('.hide-menu').addClass('hide');
        }
    } else {
        $('.hide-menu').addClass('hide');
    }
}

/* data-table */
var $table = $('.rf-table');
function buildTable($el, fixedNumber, fixedRightNumber) {
    if (fixedNumber <= 0) {
        fixedNumber = 0;
    }

    if (fixedRightNumber <= 0) {
        fixedRightNumber = 0;
    }

    $el.bootstrapTable('destroy').bootstrapTable({
        classes: 'table table-hover',
        fixedColumns: true,
        fixedNumber: fixedNumber,
        fixedRightNumber: fixedRightNumber,
        showFooter: true
    })
}

function initTable() {
    var fixedNumber = $($table).attr('fixedNumber');
    var fixedRightNumber = $($table).attr('fixedRightNumber');
    buildTable($table, fixedNumber, fixedRightNumber);

    if ($('.fixed-table-container .fixed-table-body table thead').length > 1) {
        $('.fixed-table-container .fixed-table-body table thead').each(function (index, row) {
            var length = $(row).find('td').length;
            console.log(index)

            switch (index) {
                // 内容
                case 0 :
                    $(row).find('td').each(function (key, val) {
                        if (key < fixedNumber || key >= (length - fixedRightNumber)) {
                            // 去掉input
                            $(val).find('input').attr('name', '');
                            $(val).find('select').attr('name', '');
                            $(val).html('');
                        }
                    });
                    break;
                // 头部
                case 1 :
                    $(row).find('td').each(function (key, val) {
                        if (key >= fixedNumber) {
                            // 去掉input
                            $(val).find('input').attr('name', '');
                            $(val).find('select').attr('name', '');
                            $(val).html('');
                        }
                    });
                    break;
                // 尾部
                case 2 :
                    $(row).find('td').each(function (key, val) {
                        if (key < (length - fixedRightNumber - 1)) {
                            // 去掉input
                            $(val).find('input').attr('name', '');
                            $(val).find('select').attr('name', '');
                            $(val).html('');
                        }
                    });
                    break;
            }
        });
    }

    $('[data-toggle="tooltip"]').tooltip();
}

/* 导航标签切换 */
$(document).on("click", ".rfTopMenu", function () {
    var id = $(this).data('id');

    $('.rfTopMenu').removeClass('open');
    if (parseInt(id) > 0) {
        $('.rfTopMenu').removeClass('rfTopMenuHover');
        $('.rfLeftMenu').addClass('hide');
        $('.rfLeftMenu-' + id).removeClass('hide');
        $(this).addClass('rfTopMenuHover');
    }

    // 无标签页面
    if (config.tag != true) {
        // 自动打开菜单二级
        $('.rfLeftMenu-' + id).each(function (index, item) {
            if (index === 0 && !$(item).hasClass('menu-open')) {
                $(item).find('a').eq(0).trigger('click');
                // 二级菜单打开
                var itemUlTwo = $(item).find('a').eq(0).next('ul');
                if (itemUlTwo.length > 0) {
                    $(itemUlTwo).find('a').eq(0).trigger('click');
                }

                // 判断三级
                var itemThree = $(itemUlTwo).find('a').eq(0).next('ul');
                if (itemThree.length > 0) {
                    $(itemThree).find('a').eq(0).trigger('click');
                }
            }
        });
    }
});

/* 打一个新窗口 */
$(document).on("click", ".openUploadIframe", function (e) {
    var width = $(this).data('width');
    var boxId = $(this).data('boxId');
    var height = $(this).data('height');
    var offset = $(this).data('offset');
    var href = $(this).attr('href');

    if (width === undefined) {
        width = '92%';
    }

    if (height === undefined) {
        height = '85%';
    }

    if (offset === undefined) {
        offset = "7%";
    }

    openUploadIframe(width, height, href, offset);
    e.preventDefault();
    return false;
});

layer.config({
    extend: 'style.css', //加载您的扩展样式
});

// 启用状态 status 1:启用;0禁用;
function rfStatus(obj) {
    let id = $(obj).attr('data-id');
    let status = 0;
    self = $(obj);
    if (self.hasClass("btn-success")) {
        status = 1;
    }

    if (!id) {
        id = $(obj).parent().parent().attr('id');
    }

    if (!id) {
        id = $(obj).parent().parent().attr('data-key');
    }

    $.ajax({
        type: "get",
        url: 'ajax-update',
        dataType: "json",
        data: {
            id: id,
            status: status
        },
        success: function (data) {
            if (parseInt(data.code) === 200) {
                if (self.hasClass("btn-success")) {
                    self.removeClass("btn-success").addClass("btn-default");
                    self.attr("data-toggle", 'tooltip');
                    self.attr("data-original-title", '点击禁用');
                    self.text('禁用');
                } else {
                    self.removeClass("btn-default").addClass("btn-success");
                    self.attr("data-toggle", 'tooltip');
                    self.attr("data-original-title", '点击启用');
                    self.text('启用');
                }
            } else {
                rfAffirm(data.message);
            }
        }
    });
}

// 排序
function rfSort(obj) {
    let id = $(obj).attr('data-id');

    if (!id) {
        id = $(obj).parent().parent().attr('id');
    }

    if (!id) {
        id = $(obj).parent().parent().attr('data-key');
    }

    var sort = $(obj).val();
    if (isNaN(sort)) {
        rfAffirm('排序只能为数字');
        return false;
    } else {
        $.ajax({
            type: "get",
            url: 'ajax-update',
            dataType: "json",
            data: {
                id: id,
                sort: sort
            },
            success: function (data) {
                if (parseInt(data.code) !== 200) {
                    rfAffirm(data.message);
                }
            }
        });
    }
}

// 打一个新窗口
function openUploadIframe(width, height, content, offset) {
    layer.open({
        type: 2,
        title: '素材选择',
        shade: 0.3,
        offset: offset,
        shadeClose: true,
        btn: ['确认', '关闭'],
        yes: function (index, layero) {
            let allData = [];
            var body = layer.getChildFrame('body', index);
            $(body).find('#rfAttachmentList .active').each(function(i, data){
                var tmpData = [];
                tmpData['id'] = $(data).data('id');
                tmpData['url'] = $(data).data('url');
                tmpData['name'] = $(data).data('name');
                tmpData['upload_type'] = $(data).data('upload_type');
                allData.push(tmpData)

                console.log(allData);
            });

            var boxId = $(body).find('.box-id').data('id');
            $(document).trigger('select-file-' + boxId, [boxId, allData]);

            layer.closeAll();
        },
        btn2: function () {
            layer.closeAll();
        },
        area: [width, height],
        content: content
    });

    return false;
}

/* 提示报错弹出框配置 */
toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

/* 打一个新窗口 */
$(document).on("click", ".openIframe", function (e) {
    var title = $(this).data('title');
    var width = $(this).data('width');
    var height = $(this).data('height');
    var offset = $(this).data('offset');
    var href = $(this).attr('href');

    if (title == undefined) {
        title = '基本信息';
    }

    if (width == undefined) {
        width = '80%';
    }

    if (height == undefined) {
        height = '80%';
    }

    if (offset == undefined) {
        offset = "10%";
    }

    openIframe(title, width, height, href, offset);
    e.preventDefault();
    return false;
});

layer.config({
    extend: 'style.css', //加载您的扩展样式
});

// 打一个新窗口
function openIframe(title, width, height, content, offset) {
    layer.open({
        type: 2,
        title: title,
        shade: 0.3,
        offset: offset,
        shadeClose: true,
        btn: ['保存', '关闭'],
        yes: function (index, layero) {
            var body = layer.getChildFrame('body', index);
            var form = body.find('#w0');
            var postUrl = form.attr('action');
            $.ajax({
                type: "post",
                url: postUrl,
                dataType: "json",
                data: form.serialize(),
                success: function (data) {
                    if (parseInt(data.code) !== 200) {
                        rfMsg(data.message);
                    } else {
                        layer.close(index);
                        location.reload();
                    }
                }
            });
        },
        btn2: function () {
            layer.closeAll();
        },
        area: [width, height],
        content: content
    });

    return false;
}

/* 打一个新窗口 */
$(document).on("click", ".openIframeView", function (e) {
    var title = $(this).data('title');
    var width = $(this).data('width');
    var height = $(this).data('height');
    var offset = $(this).data('offset');
    var href = $(this).attr('href');
    var btnHide = $(this).data('btn_hide');

    if (title == undefined) {
        title = '基本信息';
    }

    if (width == undefined) {
        width = '80%';
    }

    if (height == undefined) {
        height = '80%';
    }

    if (offset == undefined) {
        offset = "10%";
    }

    openIframeView(title, width, height, href, offset, btnHide);
    e.preventDefault();
    return false;
});

// 打一个新窗口
function openIframeView(title, width, height, content, offset, btnHide) {
    var btn = ['关闭'];
    if (btnHide) {
        btn = '';
    }

    console.log(btnHide)

    layer.open({
        type: 2,
        title: title,
        shade: 0.3,
        offset: offset,
        shadeClose: true,
        btn: btn,
        yes: function (index, layero) {
            layer.closeAll();
        },
        area: [width, height],
        content: content
    });

    return false;
}

/* 在顶部导航栏打开tab */
$(document).on("click", ".openContab", function (e) {
    parent.openConTab($(this));
    return false;
});

// 关闭当前的标签
$(document).on("click", ".closeCurrentConTab", function (e) {
    parent.closeCurrentConTab();
    return false;
});


// 另外一种风格提示
function rfMsg(title) {
    layer.msg(title);
}

// 错误提示
function rfError(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "error",
        button: "确定",
    });
}

// 警告提示
function rfWarning(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "warning",
        button: "确定",
    });
}

// 普通提示
function rfAffirm(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        button: "确定",
    });
}

// 信息提示
function rfInfo(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "info",
        button: "确定",
    });
}

// 成功提示
function rfSuccess(title, text) {
    let dialogText = rfText(text);
    swal({
        title: title,
        text: dialogText,
        icon: "success",
        button: "确定",
    });
}

// 删除提示
function rfDelete(obj, text) {
    title = "确定要删除这条记录吗?";
    if (!text) {
        text = '删除有可能导致数据不可恢复，请谨慎操作。';
    }

    rfTwiceAffirm(obj, title, text, "")
}

// 二次确认提示
function rfTwiceAffirm(obj, title, text, icon = '') {
    var dialogText = rfText(text);

    swal(title, {
        buttons: {
            cancel: "取消",
            defeat: '确定',
        },
        title: title,
        text: dialogText,
        icon: icon,
    }).then(function (value) {
        switch (value) {
            case "defeat":
                window.location = $(obj).attr('href');
                break;
            default:
        }
    });
}

// 二次确认返回提示
function rfTwiceAffirmBack(obj, title, text) {
    var dialogText = rfText(text);

    swal(title, {
        buttons: {
            cancel: "取消",
            defeat: '确定'
        },
        title: title,
        text: dialogText,
        // icon: "warning",
    }).then(function (value) {
        switch (value) {
            case "defeat":
                history.go(-1);
                break;
            default:
        }
    });
}

function rfText(text) {
    if (text) {
        return text;
    }

    return '小手一抖就打开了一个框';
}
