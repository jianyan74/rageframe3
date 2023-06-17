<?php

echo $this->render("_nav", [
    'boxId' => $boxId,
    'config' => $config,
    'themeJs' => $themeJs,
    'themeConfig' => $themeConfig,
    'columns' => $columns,
]);

$jsonConfig = \yii\helpers\Json::encode($config);

Yii::$app->view->registerJs(<<<JS
    var boxId = "$boxId";
    var geoCoordMap;
    echartsList[boxId] = echarts.init(document.getElementById(boxId + '-echarts'), '$themeJs');
    echartsListConfig[boxId] = jQuery.parseJSON('$jsonConfig');
    
    // 动态加载数据
    $('#'+ boxId +' div span').click(function () {
        if (!$(this).data('type')) {
            return;
        }
        
        $(this).parent().find('span').removeClass('orange');
        $(this).addClass('orange');
        var data = {
            'type': $(this).data('type'),
            'echarts_type': 'wordcloud',
            'echarts_start': $(this).data('data-start'),
            'echarts_end': $(this).data('data-end')
        }
        
        $(this).parent().parent().find('.echarts-input').each(function(index, item) {
            if ($(item).data('type') === 'dropDownList') {
                data[$(item).attr('name')] = $(item).val();
            }
            
            if ($(item).data('type') === 'radioList') {
                data[$(item).find('input:checked').attr('name')] = $(item).find('input:checked').val();
            }
        })

        var boxId = $(this).parent().parent().attr('id');
        var config = echartsListConfig[boxId];
        
        getWordcloudData(boxId, config, data);
    });
    
    $('#'+ boxId +' .echarts-input').change(function() {
        var data = {};
        $(this).parent().parent().find('.echarts-input').each(function(index, item) {
            if ($(item).data('type') === 'dropDownList') {
                data[$(item).attr('name')] = $(item).val();
            }
            
            if ($(item).data('type') === 'radioList') {
                data[$(item).find('input:checked').attr('name')] = $(item).find('input:checked').val();
            }
        })

        var config = $(this).parent().parent().find('.orange:first');
        data['type'] = $(config).data('type');
        data['echarts_type'] = 'wordcloud';
        data['echarts_start'] = $(config).attr('data-start');
        data['echarts_end'] = $(config).attr('data-end');
        
        getWordcloudData(boxId, config, data);
    });

    // 首个触发点击
    $('#'+ boxId +' div span:first').trigger('click');
    
    function getWordcloudData(boxId, config, data) {
       $.ajax({
            type:"get",
            url: config.server,
            dataType: "json",
            data: data,
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     geoCoordMap = data.geoCoordMapData;
                    echartsList[boxId].setOption({
                        tooltip: {},
                        series: [ {
                            type: 'wordCloud',
                            gridSize: 2,
                            sizeRange: [12, 50],
                            rotationRange: [-90, 90],
                            shape: 'pentagon',
                            width: '100%',
                            height: '100%', 
                            drawOutOfBound: true,
                            textStyle: {
                                normal: {
                                    color: function () {
                                        return 'rgb(' + [
                                            Math.round(Math.random() * 255),
                                            Math.round(Math.random() * 255),
                                            Math.round(Math.random() * 255)
                                        ].join(',') + ')';
                                    },
                                },
                                emphasis: {
                                    shadowBlur: 10,
                                    shadowColor: '#333'
                                }
                            },
                            data: data.seriesData
                        } ]
                    }, true);
                } else {
                    rfWarning(result.message);
                }
            }
        });
    }
JS
) ?>
