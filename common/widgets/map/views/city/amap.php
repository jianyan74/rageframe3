
<style>
    #city-container{
        margin:0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0) !important;
    }

    .map-container {
        height: 500px;
        width:500px;
        position: absolute;
        z-index: 9999
    }

    .tipMarker {
        padding-left:20px;
        width:200px
    }
    .amap-logo {
        display: none;
        opacity: 0 !important;
    }
    .amap-copyright {
        opacity: 0;
    }
</style>

<script>
    window._AMapSecurityConfig = {
        //密钥
        securityJsCode:'<?= $secret_code; ?>',
    }
</script>
<script language="javascript" src="https://webapi.amap.com/maps?v=1.4.15&key=<?= $secret_key; ?>&plugin=Map3D,AMap.DistrictSearch"></script>
<script src="https://webapi.amap.com/ui/1.1/main.js"></script>

<div class="map-container">
    <div id="city-container"></div>
</div>

<script language="javascript">
    var opts = {
        subdistrict: 0,
        extensions: 'all',
        level: 'city'
    };
    //利用行政区查询获取边界构建mask路径
    //也可以直接通过经纬度构建mask路径
    var district = new AMap.DistrictSearch(opts);
    var map;
    // console.log(district);

    district.search('<?= $value ?>', function(status, result) {
        var bounds = result.districtList[0].boundaries;
        var mask = []
        for(var i =0;i<bounds.length;i+=1){
            mask.push([bounds[i]])
        }
        map = new AMap.Map('city-container', {
            mask:mask,
            disableSocket:true,
            viewMode:'3D',
            showLabel:false,
            labelzIndex:130,
            pitch:20,
            zoom:9,
            resizeEnable: true,
            showIndoorMap: false,
            //mapStyle: "", 地图样式
            features: ["point", "road", "bg"],
            zoomEnable: true
        });

        initOtherMap(result.districtList[0].adcode);
    });

    function initOtherMap(adcode) {
        AMapUI.load(["ui/geo/DistrictExplorer", "lib/$"], (DistrictExplorer, $) => {
            let districtExplorer = new DistrictExplorer({
                map: map, //关联的地图实例
                eventSupport: true,
            });

            districtExplorer.loadAreaNode(adcode, (error, areaNode) => {
                if (error) {
                    console.error(error);
                    return;
                }
                //绘制载入的区划节点
                renderAreaNode(districtExplorer, areaNode);
            });

            var $tipMarkerContent = $('<div class="tipMarker top"></div>');
            var tipMarker = new AMap.Marker({
                content: $tipMarkerContent.get(0),
                offset: new AMap.Pixel(0, 0),
                bubble: true,
            });
            districtExplorer.on("featureMousemove", function (e, feature) {
                // 更新提示位置
                tipMarker.setPosition(e.originalEvent.lnglat);
            });
            districtExplorer.on("featureMouseout featureMouseover", (e, feature) => {
                toggleHoverFeature(
                    districtExplorer,
                    $tipMarkerContent,
                    tipMarker,
                    feature,
                    e.type === "featureMouseover",
                    e.originalEvent ? e.originalEvent.lnglat : null,
                );
            });
        });
    }

    function renderAreaNode(districtExplorer, areaNode) {
        //清除已有的绘制内容
        districtExplorer.clearFeaturePolygons();
        //绘制子级区划
        districtExplorer.renderSubFeatures(areaNode, function (feature, i) {
            return {
                cursor: "default",
                bubble: true,
                strokeColor: '#0691FF', //线颜色
                strokeOpacity: 1, //线透明度
                strokeWeight: 0.5, //线宽
                fillColor: '#ffffff', //填充色
                fillOpacity: 0.5, //填充透明度
            };
        });

        //绘制父级区划，仅用黑色描边
        districtExplorer.renderParentFeature(areaNode, {
            cursor: "default",
            bubble: true,
            strokeColor: "#0691FF", //线颜色
            fillColor: null,
            strokeWeight: 2, //线宽
        });
        //更新地图视野以适合区划面
        map.setFitView(districtExplorer.getAllFeaturePolygons());
    }

    // 提示框
    function toggleHoverFeature(
        districtExplorer,
        $tipMarkerContent,
        tipMarker,
        feature,
        isHover,
        position,
    ) {
        tipMarker.setMap(isHover ? map : null);
        if (!feature) {
            return;
        }
        let props = feature.properties;
        if (isHover) {
            // 更新提示内容
            $tipMarkerContent.html(props.adcode + ": " + props.name);
            // 更新位置
            tipMarker.setPosition(position || props.center);
        }

        // 更新相关多边形的样式
        let polys = districtExplorer.findFeaturePolygonsByAdcode(props.adcode);
        for (let i = 0, len = polys.length; i < len; i++) {
            polys[i].setOptions({
                fillOpacity: isHover ? 0.5 : 0.5,
                fillColor: isHover ? '#0691FF' : '#fff',
            });
        }
    }
</script>
