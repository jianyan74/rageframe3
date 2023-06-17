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
    echartsList[boxId] = echarts.init(document.getElementById(boxId + '-echarts'), "$themeJs");
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
            'echarts_type': 'line-bar',
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
        
        getAreaStackData(boxId, config, data);
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
        data['echarts_type'] = 'line-bar';
        data['echarts_start'] = $(config).attr('data-start');
        data['echarts_end'] = $(config).attr('data-end');
        
        getAreaStackData(boxId, config, data);
    });

    // 首个触发点击
    $('#'+ boxId +' div span:first').trigger('click');
    
    function getAreaStackData(boxId, config, data) {
        $.ajax({
            type:"get",
            url: config.server,
            dataType: "json",
            data: data,
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     var seriesData = data.seriesData;
                     for(let j = 0; j < seriesData.length; j++) {
                         seriesData[j]['stack'] = '总数';
                         seriesData[j]['areaStyle'] = [];
                         seriesData[j]['areaStyle']['normal'] = {};
                     }
                    
                    echartsList[boxId].setOption({
                            title: {
                                text: ''
                            },
                            tooltip : {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: data.fieldsName
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis : [
                                {
                                    type : 'category',
                                    boundaryGap : false,
                                    data : data.xAxisData
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value'
                                }
                            ],
                            series : data.seriesData,
                        }, true);
                } else {
                    rfWarning(result.message);
                }
            }
        });
    }
JS
) ?>
