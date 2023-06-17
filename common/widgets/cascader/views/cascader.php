<?php

use yii\helpers\Json;
use common\helpers\Html;
use common\helpers\ArrayHelper;

?>

<style>
    .cascaderbox .hid {
        visibility: hidden;
    }

    .cascaderbox {
        width: 420px;
        height: 35px;
        line-height: 33px;
        margin-bottom: 15px;
        position: relative;
    }

    .cascaderbox .inputbox {
        width: 100%;
        height: 35px;
        border: 1px solid #e4e7ed;
        border-radius: 4px;
        cursor: pointer;
        position: relative;
    }

    .cascaderbox .searchtxt {
        display: inline-block;
        box-sizing: border-box;
        width: 85%;
        height: 33px;
        line-height: 33px;
        margin-left: 3px;
        border: 0 none;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #606266;
    }

    .cascaderbox .arrdown {
        float: right;
        display: inline;
        width: 12px;
        height: 12px;
        background: url('/resources/img/ld_dot.png') left top no-repeat;
        margin-right: 10px;
        margin-top: 10px;
    }

    .cascaderbox.open .arrdown {
        transform: rotate(180deg);
    }

    .cascaderbox .dlist {
        position: absolute;
        left: 0;
        top: 48px;
        white-space: nowrap;
        background-color: #fff;
        border: 1px solid #e4e7ed;
        height: 300px;
        overflow: hidden;
        z-index: 999;
    }

    .cascaderbox .dlist_ul {
        min-width: 160px;
        display: inline-block;
        border-right: 1px solid #e4e7ed;
        padding: 0 10px;
        height: 300px;
        overflow: auto;
    }

    .cascaderbox .dlist_ul:last-child {
        border-right: 0 none;
    }

    .cascaderbox .item, .cascaderbox .item2 {
        padding: 5px 10px;
        background: url('/resources/img/ld_dr.png') right 18px no-repeat;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .cascaderbox .dlist_search .item2 {
        background: none;
        max-width: 350px;
    }

    .cascaderbox .item:hover,
    .cascaderbox .item.on {
        color: #409EFF;
    }

    .cascaderbox .item.lastchild {
        background: 0 none;
    }

    .cascaderbox .labelshow {
        position: absolute;
        left: 20px;
        top: 0;
        width: 90%;
        display: inline-block;
        height: 35px;
        color: #606266;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cascaderbox .searchdlist,
    .cascaderbox .searchdlist .dlist_ul {
        height: auto;
        max-height: 300px;
    }

    .cascaderbox .labelshow.hid {
        display: none;
    }

    .cascaderbox .nosearch {
        color: #c0c4cc;
        height: 30px;;
    }
</style>

<?php foreach ($selected as $key => $item) { ?>
    <div class="cascaderbox cascaderbox-<?= $boxId ?>" data-id="<?= $boxId ?>" style="<?= $options['style'] ?>">
        <div class="row">
            <div class="<?php if ($multiple == true) { ?>col-10<?php } else { ?>col-12<?php } ?>">
                <div class="inputbox">
                    <span class="arrdown"></span>
                    <input autocomplete="off" class="searchtxt form-control" type="text" placeholder="<?= empty($item['id']) ? $options['placeholder'] : ''; ?>">
                    <span class="labelshow"><?= implode(' / ', ArrayHelper::getColumn($item['parents'], 'title')); ?></span>
                </div>
                <div class="cascaderinput hide">
                    <?= Html::hiddenInput($name, $item['id']); ?>
                </div>
                <div class="dlist hid <?= !empty(Json::decode($items)) ? '' : 'hidden'; ?>"></div>
                <div class="dlist searchdlist hid"></div>
            </div>
            <?php if ($multiple == true) { ?>
                <div class="col-lg-2">
                    <?php if ($key == 0) { ?>
                        <span class="multiple-cascader-input cascader-input-plus btn btn-white">
                            <i class="fa fa-plus"></i>
                        </span>
                    <?php } else { ?>
                        <span class="multiple-cascader-input cascader-input-remove btn btn-danger">
                            <i class="fa fa-times"></i>
                        </span>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<script type="text/html" id="cascaderBoxHtml-<?= $boxId ?>">
    <div class="cascaderbox" id="cascaderbox-{{boxId}}">
        <div class="row">
            <div class="col-lg-10">
                <div class="inputbox">
                    <span class="arrdown"></span>
                    <input autocomplete="off" class="searchtxt form-control" type="text" placeholder="<?= $options['placeholder']; ?>">
                    <span class="labelshow"></span>
                </div>
                <div class="cascaderinput hide">
                    <?= Html::hiddenInput($name); ?>
                </div>
                <div class="dlist hid"></div>
                <div class="dlist searchdlist hid"></div>
            </div>
            <div class="col-lg-2">
                <span class="multiple-cascader-input cascader-input-remove btn btn-danger">
                    <i class="fa fa-times"></i>
                </span>
            </div>
        </div>

    </div>
</script>

<script>
    (function ($) {
        $.fn.cascader = function (options) {
            var defaults = {
                data: [],
                changeOnSelect: false,
                boxId: '',
                value: '',
                selectFn: function (selectjson, boxId) {
                }
            };
            var opts = $.extend(defaults, options);

            var _this = this, searchtxt = _this.find('.searchtxt'), dlist = _this.find('.dlist').eq(0),
                searchdlist = _this.find('.searchdlist'), curArr = [], labelshow = _this.find('.labelshow'), level = 0,
                initData = false, selJson = [], searchArr = [], searActiveArr = [], searchArr_value = [],
                focusState = false;

            labelshow.bind('click', function () {
                if (!focusState) {
                    searchtxt.trigger('focus');
                    focusState = true;
                }

            })

            searchtxt.bind('focus', function () {
                if (!initData) {
                    createUl(opts.data);
                    popOpen(dlist);
                    createSearchArr(opts.data);
                    initData = true;
                } else if (searchdlist.hasClass('hid')) {
                    popOpen(dlist);
                }

            }).bind('blur', function () {
                focusState = false;
            }).bind('keyup', function () {
                var serchstr = $(this).val();
                popClose();
                labelshow.addClass('hid');
                if (serchstr.length > 0) {
                    createSearchBox(serchstr);
                } else {
                    popClose();
                }
            })

            dlist.delegate('li.item', 'click', function () {
                var parent_index = $(this).parent().index();
                var value = $(this).attr('data-value');
                $(this).addClass('on').siblings().removeClass('on');
                level = parent_index;
                getArray(opts.data, value);
            });

            searchdlist.delegate('li.item2', 'click', function () {
                var searStr = ($(this).text()).split(' / '), litxt = dlist.find('.dlist_ul').eq(0), len = searStr.length;
                litxt.nextAll().remove();

                createSearchdlist(opts.data, searStr[0]);

                for (var i = 1; i < len - 1; i++) {
                    createSearchdlist(searActiveArr, searStr[i]);
                }
                dlist.find('.dlist_ul').each(function (i) {
                    var jqElArr = $(this).find('li');
                    highlighting(jqElArr, searStr[i])
                    if (i == len - 1) {
                        getValue();
                        labelshow.removeClass('hid');
                        searchtxt.val('');
                        popClose();
                    }
                })
            })

            if (opts.value.length) {
                var searStr = opts.value.split(' / '), len = searStr.length;

                createUl(opts.data);
                createSearchdlist(opts.data, searStr[0]);
                for (var i = 1; i < len - 1; i++) {
                    createSearchdlist(searActiveArr, searStr[i]);
                }

                dlist.find('.dlist_ul').each(function (i) {
                    var jqElArr = $(this).find('li');
                    highlighting(jqElArr, searStr[i])
                })

                initData = true;
            }

            function highlighting(jqElArr, highStr) {
                jqElArr.each(function () {
                    var curtxt = $(this).attr('data-label');
                    if (highStr == curtxt) {
                        var selectedItem = $(this);
                        $(this).addClass('on').siblings().removeClass('on');
                        scrollToOpened(selectedItem);
                    }
                })

            }

            function scrollToOpened(selectedItem) {
                var listUl = selectedItem.parents('ul');
                if (selectedItem.length > 0) {
                    var scrollTop = listUl.scrollTop(), top = selectedItem.position().top + scrollTop;
                    if (scrollTop < top) {
                        top = top - (listUl.height() - selectedItem.height()) / 2;
                        listUl.scrollTop(top);
                    }
                }
            }

            function createSearchdlist(data, label) {
                for (var i in data) {
                    if (data[i].label == label) {
                        searActiveArr = data[i].children;
                    }
                }
                createUl(searActiveArr);
            }

            var htmlClickHandler = function (e) {
                if (dlist.hasClass('hid') && searchdlist.hasClass('hid')) return;
                var cascader = $(e.target).parents('.cascaderbox');
                if (cascader.length == 0) {
                    popClose();
                    if (labelshow.hasClass('hid')) {
                        labelshow.removeClass('hid')
                        searchtxt.val('');
                    }
                }
            }

            function getArray(data, value) {
                for (var i in data) {
                    if (data[i].value == value) {
                        curArr = data[i].children;
                        createEl();
                        break;
                    } else {
                        getArray(data[i].children, value);
                    }
                }
            }

            function createEl() {
                if (curArr.length > 0) {
                    /*  点击非最后一个子级 */
                    _this.find('.dlist_ul').eq(level).nextAll().remove();
                    createUl(curArr);
                    popOpen(dlist);
                    /* 选择即改变,可选择任意级 */
                    if (opts.changeOnSelect) {
                        getValue();
                    }
                } else {
                    /* 点击最后一个子级 */
                    _this.find('.dlist_ul').eq(level).nextAll().remove();
                    getValue();
                    popClose();
                }
            }

            function createUl(data) {
                var arr = data, liArr = [];
                ul = $('<ul class="dlist_ul"></ul>');
                $.each(arr, function (i, data) {
                    var lastClass = '';
                    if (!data.children) {
                        lastClass = 'lastchild'
                    }
                    liArr.push('<li data-label="' + data.label + '" data-value="' + data.value + '" class="item ' + lastClass + '">' + data.label + '</li>')
                })

                if (liArr.length > 0) {
                    ul.append(liArr.join(''));
                    dlist.append(ul);
                }
            }

            function getValue() {
                selJson = []; /* 最终选项数组 */
                dlist.find('li.on').each(function (i, data) {
                    var label = $(this).attr('data-label'),
                        value = $(this).attr('data-value');
                    selJson.push({"value": value, "label": label});
                })

                var selectedStr = selJsonToStr(selJson);

                searchtxt.attr('placeholder', '');
                labelshow.html(selectedStr);
                opts.selectFn(selJson, defaults.boxId);

            }

            function selJsonToStr(arr) {
                var listArr = [];
                $.each(arr, function (i, data) {
                    var label = data.label;
                    listArr.push(label);
                })
                str = listArr.join(' / ');
                return str;
            }

            function createSearchArr(data, label) {
                for (var i in data) {
                    if (!label) {
                        label = ''
                    }
                    var str = label + '' + data[i].label + " / ";
                    if (data[i].children.length > 0) {
                        curArr = data[i].children;
                        createSearchArr(curArr, str)
                    } else {
                        str = str.substring(0, str.lastIndexOf(" / "));
                        searchArr.push(str);
                        searchArr_value.push(data[i].value);
                    }
                }
            }

            function createSearchBox(label) {
                var liArr = [];
                ul = $('<ul class="dlist_ul dlist_search"></ul>');
                $.each(searchArr, function (i, data) {
                    if (data.indexOf(label) != -1) {
                        var value = searchArr_value[i];
                        liArr.push('<li data-value="' + value + '" class="item2">' + data + '</li>')
                    }
                });

                if (liArr.length !== 0) {
                    searchdlist.empty();
                    ul.append(liArr.join(''));
                    searchdlist.append(ul);
                    popOpen(searchdlist);
                } else {
                    searchdlist.empty();
                    ul.append('<li class="nosearch">无匹配数据</li>')
                    searchdlist.append(ul);
                    popOpen(searchdlist);
                }
            }

            function popClose() {
                _this.removeClass('open');
                _this.find('.dlist').addClass('hid');
            }

            function popOpen(el) {
                _this.addClass('open');
                el.removeClass('hid');
            }

            $('html').bind('click', htmlClickHandler)
        }
    })(jQuery)

    var defaultBoxId = '<?= $boxId ?>';
    $('.cascaderbox-' + defaultBoxId).each(function (i, row) {
        var boxId = getRandomString(20);
        $(row).attr('id', 'cascaderbox-' + boxId);
        cascaderboxInit(boxId, $(row).find('.labelshow').text());
    })

    $(document).on("click", ".cascader-input-remove", function () {
        $(this).parent().parent().parent().remove();
    });

    // 初始化
    function cascaderboxInit(boxId, value = '') {
        $('#cascaderbox-' + boxId).cascader({
            data: <?= $items ?>,
            changeOnSelect: '<?= $changeOnSelect ?>', // 开启选择任意级
            boxId: boxId,
            value: value,
            selectFn: function (selected, boxId) { //selectFn回调函数
                for (let i = 0; i < selected.length; i++) {
                    $('#cascaderbox-' + boxId).find('.cascaderinput input').val(selected[i].value);
                }
            }
        });
    }

    // 获取长度为len的随机字符串
    function getRandomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
</script>
