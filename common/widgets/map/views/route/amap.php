<style type="text/css">
    html,
    body,
    #container {
        width: 100%;
        height: 100%;
    }
    #panel {
        position: fixed;
        background-color: white;
        max-height: 90%;
        overflow-y: auto;
        top: 10px;
        right: 10px;
        width: 280px;
    }
    #panel .amap-lib-driving {
        border-radius: 4px;
        overflow: hidden;
    }
</style>

<div class="row">
    <div id="container"></div>
    <div id="panel"></div>
</div>

<script type="text/javascript">
    var map = new AMap.Map("container", {
        resizeEnable: true,
        zoom: parseInt('<?= $zoom ?>'),
        center: [<?= $lng ?>, <?= $lat ?>], //初始地图中心点
    });
    //骑行导航
    var riding = new AMap.Riding({
        map: map,
        panel: "panel"
    });
    //根据起终点坐标规划骑行路线
    riding.search([<?= $lng ?>, <?= $lat ?>],[<?= $lng2 ?>, <?= $lat2 ?>], function(status, result) {
        // result即是对应的骑行路线数据信息，相关数据结构文档请参考  https://lbs.amap.com/api/javascript-api/reference/route-search#m_RidingResult
        if (status === 'complete') {
           // rfMsg('绘制骑行路线完成')
        } else {
            if (result) {
                rfMsg('骑行路线数据查询失败' + result)
            } else {
                rfMsg('骑行路线数据查询失败')
            }
        }
    });
</script>