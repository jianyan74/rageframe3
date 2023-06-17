<?php

use yii\helpers\Json;

?>

<style type="text/css">
    html, body {
        height: 100%;
        width: 100%;
    }

    #container {
        height: 100%;
        width: 80%;
    }

    .map-box {
        width: 20%;
        height: 100%;
        position: fixed;
        right: 0;
        background-color: #ffffff;
    }

    .amap-marker-label {
        border: 0;
        background-color: transparent;
    }
</style>

<div class="row">
    <div id="container"></div>
    <div class="map-box text-center" id="mapVue">
        <div class="row p-4">
            <div :style="{'height': inventoryBodyHeight, 'overflow-y': 'auto', 'width': '100%'}">
                <div class="col-12 mt-2" v-for="(area, index) in areaAll" style="border: 1px solid #ededed">
                    <div class="row">
                        <div class="col-12 pb-2 pt-2">
                            <span class="float-left"># {{index + 1}}</span>
                            <span class="float-right blue pointer" @click="deleteArea(index)">删除</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            <label class="control-label" for="cate-title">区域名称</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" v-model="area.name">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            <label class="control-label" for="cate-title">起送价</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text"  class="form-control" v-model="area.shipping_fee">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            <label class="control-label" for="cate-title">划分方式</label>
                        </div>
                        <div class="col-sm-8 text-left">
                            <label style="color:#636f7a"><input type="radio" value="circle" v-model="area.type" @click="changeType(index, 'circle')"> 半径</label>
                            <label style="color:#636f7a"><input type="radio" value="polygon" v-model="area.type" @click="changeType(index, 'polygon')"> 自定义</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="btn btn-white" @click="addArea()">增加区域</span>
        <span class="btn btn-white hide" @click="getData()">查看数据</span>
        <input type="hidden" id="boxId" value="<?= $boxId; ?>">
    </div>
</div>

<script type="text/javascript">
    var mapVue = new Vue({
        el: '#mapVue',
        data: {
            inventoryBodyHeight: '',
            path: [
                [<?= $longitude ?> + 0.03, <?= $latitude ?> + 0.03],
                [<?= $longitude ?> + 0.03, <?= $latitude ?> - 0.03],
                [<?= $longitude ?> - 0.03, <?= $latitude ?> - 0.03],
                [<?= $longitude ?> - 0.03, <?= $latitude ?> + 0.03],
            ],
            areaAll: [],
            defaultArea: JSON.parse('<?= Json::encode($overlay) ?>'),
        },
        methods: {
            // 删除优惠券
            addArea: function (type = 'polygon') {
                var overlay = this.addOverlay(type, 5000, this.path);
                var overlayEditor = this.openOverlay(type, overlay);
                var area = {
                    'name': '',
                    'type': type,
                    'shipping_fee': '',
                    'overlay': overlay,
                    'overlayEditor': overlayEditor,
                    'path': [],
                    'radius': 0,
                };

                this.areaAll.push(area)
            },
            deleteArea: function (index) {
                this.areaAll[index].overlayEditor.close();
                map.remove(this.areaAll[index].overlay)
                this.areaAll.splice(index, 1);
            },
            changeType: function (index, type) {
                // 移除原先的地图
                this.areaAll[index].overlayEditor.close();
                map.remove(this.areaAll[index].overlay)
                this.areaAll[index].overlay = '';
                this.areaAll[index].overlayEditor = '';
                // 添加新的进地图
                var overlay = this.addOverlay(type, 5000, this.path);
                // 开启编辑
                var overlayEditor = this.openOverlay(type, overlay);
                // 写入
                this.areaAll[index].overlay = overlay;
                this.areaAll[index].overlayEditor = overlayEditor;
            },
            addOverlay: function (type, radius = 5000, path = []) {
                var overlay;
                switch (type) {
                    // 圆形
                    case 'circle' :
                        overlay = new AMap.Circle({
                            center: new AMap.LngLat(<?= $longitude ?>, <?= $latitude ?>),
                            radius: radius,
                            strokeColor: getRandomColor(),
                            strokeWeight: 2,
                            // strokeOpacity: 0.2,
                            fillOpacity: 0.4,
                            fillColor: '#1791fc',
                            zIndex: 50,
                            extData: '', // 自定义参数
                        })
                        break;
                    // 多边形
                    case 'polygon' :
                        overlay = new AMap.Polygon({
                            path: path,
                            strokeColor: getRandomColor(),
                            strokeWeight: 2,
                            // strokeOpacity: 0.2,
                            fillOpacity: 0.4,
                            fillColor: '#1791fc',
                            zIndex: 50,
                            extData: '', // 自定义参数
                        })
                        break;
                }

                // 添加地图并 缩放地图到合适的视野级别
                map.add(overlay)
                map.setFitView([overlay])

                return overlay;
            },
            openOverlay: function (type, overlay) {
                var overlayEditor;
                switch (type) {
                    // 圆形
                    case 'circle' :
                        overlayEditor = new AMap.CircleEditor(map, overlay);
                        break;
                    // 多边形
                    case 'polygon' :
                        overlayEditor = new AMap.PolyEditor(map, overlay);
                        break;
                }

                overlayEditor.open();

                return overlayEditor;
            },
            getData: function () {
                var data = [];
                if (this.areaAll.length === 0) {
                    rfMsg("请添加区域范围");
                    return;
                }

                for (let i = 0; i < this.areaAll.length; i++) {
                    if (this.areaAll[i].name.length === 0) {
                        rfMsg("请填写 # " + (i + 1) + "区域名称");
                        return;
                    }

                    if (this.areaAll[i].shipping_fee === '') {
                        rfMsg("请填写 # " + (i + 1) + "配送费");
                        return;
                    }

                    if (isNaN(this.areaAll[i].shipping_fee)) {
                        rfMsg("# " + (i + 1) + "配送费只能为数字");
                        return;
                    }

                    if (parseFloat(this.areaAll[i].shipping_fee) < 0) {
                        rfMsg("# " + (i + 1) + "配送费不能小于 0");
                        return;
                    }

                    var path = [];
                    var overlayPath = this.areaAll[i].overlay.getPath();
                    for (let j = 0; j < overlayPath.length; j++) {
                        path.push([overlayPath[j].lng, overlayPath[j].lat])
                    }

                    data.push({
                        'name': this.areaAll[i].name,
                        'type': this.areaAll[i].type,
                        'shipping_fee': this.areaAll[i].shipping_fee,
                        'path': path,
                        'radius': this.areaAll[i].type === 'polygon' ? 0 : this.areaAll[i].overlay.getRadius(),
                    })
                }

                console.log(data);

                return data;
            },
            mapInit: function () {
                if (this.defaultArea.length === 0) {
                    this.addArea();
                    return;
                }

                console.log(this.defaultArea)

                for (let i = 0; i < this.defaultArea.length; i++) {
                    var path = JSON.parse(this.defaultArea[i].path);
                    var overlay = this.addOverlay(this.defaultArea[i].type, this.defaultArea[i].radius, path);
                    var overlayEditor = this.openOverlay(this.defaultArea[i].type, overlay);
                    var area = {
                        'name': this.defaultArea[i].name,
                        'type': this.defaultArea[i].type,
                        'shipping_fee': this.defaultArea[i].shipping_fee,
                        'overlay': overlay,
                        'overlayEditor': overlayEditor,
                        'path': [],
                        'radius': 0,
                    };

                    // console.log(area)

                    this.areaAll.push(area)
                }
            },
        },
        // 初始化
        mounted() {
            let mapVueScrollHeight = $('#mapVue').prop('scrollHeight'); // 可视角高度
            this.inventoryBodyHeight = (mapVueScrollHeight - 100) + 'px';
            console.log(mapVueScrollHeight)
        },
    })

    var map;
    $(function () {
        var label = "<?= $label; ?>";
        var geocoder;
        var init = function () {
            AMapUI.loadUI(['misc/PositionPicker', 'misc/PoiPicker'], function (PositionPicker, PoiPicker) {
                //加载PositionPicker，loadUI的路径参数为模块名中 'ui/' 之后的部分
                map = new AMap.Map('container', {
                    zoom: parseInt('<?= $zoom ?>'),
                    center: [<?= $longitude ?>, <?= $latitude ?>], //初始地图中心点
                });

                // 初始化完成
                map.on('complete', function() {
                    mapVue.mapInit();
                });

                geocoder = new AMap.Geocoder({
                    radius: 1000 //范围，默认：500
                });

                var marker = new AMap.Marker({
                    position: map.getCenter(),
                    icon: new AMap.Icon({
                        image: 'https://a.amap.com/jsapi_demos/static/demo-center/icons/poi-marker-default.png',
                        imageSize: new AMap.Size(25, 34),
                    }),
                    offset: new AMap.Pixel(-13, -30)
                });

                marker.setMap(map);

                // 设置label标签
                // label默认蓝框白底左上角显示，样式className为：amap-marker-label
                if (label) {
                    marker.setLabel({
                        offset: new AMap.Pixel(5, 5),  //设置文本标注偏移量
                        content: "<div class='info'>" + label + "</div>", //设置文本标注内容
                        direction: 'right' //设置文本标注方位
                    });
                }

                //加载工具条
                var tool = new AMap.ToolBar();
                map.addControl(tool);
            });
        };

        init();
    });

    /**
     * 随机生成颜色
     * @returns {string}
     */
    function getRandomColor() {
        return  '#' + (function(color){
            return (color +=  '0123456789abcdef'[Math.floor(Math.random()*16)])
            && (color.length === 6) ?  color : arguments.callee(color);
        })('');
    }
</script>
