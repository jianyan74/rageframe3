<?php

namespace common\helpers;

/**
 * 日期数据格式返回
 *
 * Class DateHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class DateHelper
{
    /**
     * 获取今日开始时间戳和结束时间戳
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     */
    public static function today()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
        ];
    }

    /**
     * 昨日
     *
     * @return array
     */
    public static function yesterday()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        ];
    }

    /**
     * 这周
     *
     * @return array
     */
    public static function thisWeek()
    {
        $length = 0;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 7;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 上周
     *
     * @return array
     */
    public static function lastWeek()
    {
        $length = 7;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 14;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 本月
     *
     * @return array
     */
    public static function thisMonth()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        ];
    }

    /**
     * 上个月
     *
     * @return array
     */
    public static function lastMonth()
    {
        $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));

        if (date('m', $start) != date('m', $end)) {
            $end -= 60 * 60 * 24;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 几个月前
     *
     * @param integer $month 月份
     * @return array
     */
    public static function monthsAgo($month)
    {
        return [
            'start' => mktime(0, 0, 0, date('m') - $month, 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m') - $month, date('t'), date('Y')),
        ];
    }

    /**
     * 某年
     *
     * @param $year
     * @return array
     */
    public static function aYear($year)
    {
        $start_month = 1;
        $end_month = 12;

        $start_time = $year . '-' . $start_month . '-1 00:00:00';
        $end_month = $year . '-' . $end_month . '-1 23:59:59';
        $end_time = date('Y-m-t H:i:s', strtotime($end_month));

        return [
            'start' => strtotime($start_time),
            'end' => strtotime($end_time)
        ];
    }

    /**
     * 某月
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function aMonth($year = 0, $month = 0)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');
        $day = date('t', strtotime($year . '-' . $month));

        return [
            "start" => strtotime($year . '-' . $month),
            "end" => mktime(23, 59, 59, $month, $day, $year)
        ];
    }

    /**
     * @param int $time
     * @param string $format
     * @return mixed
     */
    public static function getWeekName(int $time, $format = "周")
    {
        $week = date('w', $time);
        $weekname = ['日', '一', '二', '三', '四', '五', '六'];
        foreach ($weekname as &$item) {
            $item = $format . $item;
        }

        return $weekname[$week];
    }

    /**
     * 格式化小时
     *
     * @param array $hours
     * @return array
     */
    public static function formatHours(array $hours)
    {
        $time = 3600 * 24;
        foreach ($hours as &$hour) {
            if ($hour == $time) {
                $hour = '24:00';
            } else {
                $hour = date('H:i', $hour + strtotime(date('Y-m-d')));
            }
        }

        return $hours;
    }

    /**
     * @param $hour
     * @return false|string
     */
    public static function formatHoursByInt($hour)
    {
        $time = 3600 * 24;
        if ($hour == $time) {
            $hour = '24:00';
        } else {
            $hour = date('H:i', $hour + strtotime(date('Y-m-d')));
        }

        return $hour;
    }

    /**
     * @param $seconds
     * @return string
     */
    public static function secondToTime($seconds)
    {
        if (is_string($seconds)) {
            return $seconds;
        }

        $result = '00:00:00';
        if ($seconds > 0) {
            $hour = floor($seconds / 3600);
            $minute = floor(($seconds - 3600 * $hour) / 60);
            $second = floor((($seconds - 3600 * $hour) - 60 * $minute) % 60);

            $hour < 10 && $hour = '0' . $hour;
            $minute < 10 && $minute = '0' . $minute;
            $second < 10 && $second = '0' . $second;

            $result = $hour . ':' . $minute . ':' . $second;
        }

        return $result;
    }

    /**
     * @param $seconds
     * @return string
     */
    public static function timeToSecond($timeStr)
    {
        if (!is_string($timeStr)) {
            return $timeStr;
        }

        $time = 0;
        $timeArr = explode(':', $timeStr);

        isset($timeArr[0]) && $time += $timeArr[0] * 3600;
        isset($timeArr[1]) && $time += $timeArr[1] * 60;
        isset($timeArr[2]) && $time += $timeArr[2];

        return $time;
    }

    /**
     * 格式化时间戳
     *
     * @param $time
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min = $time / 60;
        $hours = $time / 3600;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));

        return $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
    }

    /**
     * 获取时间区间天数
     *
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public static function getIntervalDay($startTime, $endTime)
    {
        $startDate = date('Y-m-d', $startTime);
        $endDate = date('Y-m-d', $endTime);
        $startDateTime = strtotime($startDate);

        if ($startDate == $endDate) {
            return [
                'date' => $endDate,
                'time' => strtotime($endDate),
                'week' => date('w', $endDate),
            ];
        }

        $data = [];
        for ($i = $startDateTime; $i < $endTime; $i += 3600 * 24) {
            $data[] = [
                'date' => date('Y-m-d', $i),
                'time' => $i,
                'week' => date('w', $i),
            ];
        }

        return $data;
    }

    /**
     * 获取时间区间月份
     *
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public static function getIntervalMonth($startTime, $endTime)
    {
        $startDate = date('Y-m', $startTime);
        $endDate = date('Y-m', $endTime);
        $startDateTime = strtotime($startDate);
        $endDateTime = strtotime($endDate);

        if ($startDate == $endDate) {
            return [
                'mouth' => $startDate,
                'time' => $startDateTime,
            ];
        }

        $data = [];
        while ($startDateTime <= $endDateTime) {
            $data[] = [
                'mouth' => date('Y-m', $startDateTime),
                'time' => $startDateTime
            ];

            $startDateTime = strtotime("+1 month", $startDateTime);
        }

        return $data;
    }

    /**
     * 时间戳
     *
     * @param integer $accuracy 精度 默认微妙
     * @return int
     */
    public static function microtime($accuracy = 1000)
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * $accuracy);

        return $msectime;
    }
}