<style type="text/css">
    .info {
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border-radius: .25rem;
        background-color: white;
        width: auto;
        border-width: 0;
        position: relative;
        top: 0;
        right: 0;
        min-width: 0;
        box-shadow: 0 2px 6px 0 rgba(114, 124, 245, .5);
    }

    html, body, #container {
        height: 100%;
        width: 100%;
    }

    .amap-icon img {
        width: 25px;
        height: 34px;
    }

    .amap-marker-label{
        border: 0;
        background-color: transparent;
    }
</style>

<div class="row">
    <div id="container"></div>
</div>

<script type="text/javascript">
    $(function () {
        var label = "<?= $label; ?>";
        var as, x, y, address, map, lat, lng, geocoder;
        var init = function () {
            AMapUI.loadUI(['misc/PositionPicker', 'misc/PoiPicker'], function (PositionPicker, PoiPicker) {
                //加载PositionPicker，loadUI的路径参数为模块名中 'ui/' 之后的部分
                map = new AMap.Map('container', {
                    zoom: parseInt('<?= $zoom ?>'),
                    center: [<?= $lng ?>, <?= $lat ?>], //初始地图中心点
                });
                geocoder = new AMap.Geocoder({
                    radius: 1000 //范围，默认：500
                });

                var marker = new AMap.Marker({
                    position: map.getCenter(),
                    icon: 'https://a.amap.com/jsapi_demos/static/demo-center/icons/poi-marker-default.png',
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
</script>