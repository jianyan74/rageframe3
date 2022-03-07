<?php

use common\enums\AppEnum;

Yii::setAlias('@root', dirname(dirname(__DIR__)) . '/');
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@merchant', dirname(dirname(__DIR__)) . '/merchant');
Yii::setAlias('@html5', dirname(dirname(__DIR__)) . '/html5');
Yii::setAlias('@oauth2', dirname(dirname(__DIR__)) . '/oauth2');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@services', dirname(dirname(__DIR__)) . '/services');
Yii::setAlias('@addons', dirname(dirname(__DIR__)) . '/addons');
// 资源
Yii::setAlias('@baseResources', '/resources');
Yii::setAlias('@attachment', dirname(dirname(__DIR__)) . '/web/attachment'); // 本地资源目录绝对路径
Yii::setAlias('@attachurl', '/attachment'); // 资源目前相对路径，可以带独立域名，例如 https://attachment.rageframe.com
Yii::setAlias('@backendUrl', '');
Yii::setAlias('@frontendUrl', '');
Yii::setAlias('@html5Url', '');
Yii::setAlias('@apiUrl', '');
Yii::setAlias('@oauth2Url', '');
Yii::setAlias('@merchantUrl', '');
