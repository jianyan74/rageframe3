<?php

namespace services\extend;

use Yii;
use Detection\MobileDetect;

/**
 * Class DetectionService
 * @package services\extend
 * @author jianyan74 <751393839@qq.com>
 */
class DetectionService
{
    /**
     * @return bool
     */
    public function isMobile()
    {
        return (new MobileDetect())->isiMobile();
    }

    /**
     * 获取设备客户端信息
     *
     * @return mixed|string
     */
    public function detectVersion()
    {
        /** @var MobileDetect $detect */
        $detect = new MobileDetect();
        if ($detect->isMobile()) {
            $devices = $detect->getOperatingSystems();
            $device = '';

            foreach ($devices as $key => $valaue) {
                if ($detect->is($key)) {
                    $device = $key . $detect->version($key);
                    break;
                }
            }

            return $device;
        }

        return $detect->getUserAgent();
    }
}
