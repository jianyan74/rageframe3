<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseStringHelper;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * Class StringHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class StringHelper extends BaseStringHelper
{
    /**
     * 生成Uuid
     *
     * @param string $type 类型 默认时间 time/md5/random/sha1/uniqid 其中uniqid不需要特别开启php函数
     * @param string $name 加密名
     * @return string
     * @throws Exception
     */
    public static function uuid($type = 'time', $name = 'php.net')
    {
        switch ($type) {
            // 生成版本1（基于时间的）UUID对象
            case 'time' :
                $uuid = Uuid::uuid1();

                break;
            // 生成第三个版本（基于名称的和散列的MD5）UUID对象
            case 'md5' :
                $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $name);

                break;
            // 生成版本4（随机）UUID对象
            case 'random' :
                $uuid = Uuid::uuid4();

                break;
            // 产生一个版本5（基于名称和散列的SHA1）UUID对象
            case 'sha1' :
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $name);

                break;
            // php自带的唯一id
            case 'uniqid' :
                return md5(uniqid(md5(microtime(true).self::random(8)), true));

                break;
        }

        return $uuid->toString();
    }

    /**
     * 日期转时间戳
     *
     * @param $value
     * @return false|int
     */
    public static function dateToInt($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (!is_numeric($value)) {
            return strtotime($value);
        }

        return $value;
    }

    /**
     * 时间戳转日期
     *
     * @param $value
     * @return false|int
     */
    public static function intToDate($value, $format = 'Y-m-d H:i:s')
    {
        if (empty($value)) {
            return date($format);
        }

        if (is_numeric($value)) {
            return date($format, $value);
        }

        return $value;
    }

    /**
     * 获取缩略图地址
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public static function getThumbUrl($url, $width, $height)
    {
        $url = str_replace('attachment/images', 'attachment/thumb', $url);

        return self::createThumbUrl($url, $width, $height);
    }

    /**
     * 创建缩略图地址
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public static function createThumbUrl($url, $width, $height)
    {
        $url = explode('/', $url);
        $nameArr = explode('.', end($url));
        $url[count($url) - 1] = $nameArr[0]."@{$width}x{$height}.".$nameArr[1];

        return implode('/', $url);
    }

    /**
     * 获取压缩图片地址
     *
     * @param $url
     * @param $quality
     * @return string
     */
    public static function getAliasUrl($url, $alias = 'compress')
    {
        $url = explode('/', $url);
        $nameArr = explode('.', end($url));
        $url[count($url) - 1] = $nameArr[0]."@{$alias}.".$nameArr[1];

        return implode('/', $url);
    }

    /**
     * 根据Url获取本地绝对路径
     *
     * @param $url
     * @param string $type
     * @return string
     */
    public static function getLocalFilePath($url, $type = 'images')
    {
        if (RegularHelper::verify('url', $url)) {
            if (!RegularHelper::verify('url', Yii::getAlias("@attachurl"))) {
                $hostInfo = Yii::$app->request->hostInfo.Yii::getAlias("@attachurl");
                $url = str_replace($hostInfo, '', $url);
            } else {
                $url = str_replace(Yii::getAlias("@attachurl"), '', $url);
            }
        } else {
            $url = str_replace(Yii::getAlias("@attachurl"), '', $url);
        }

        return Yii::getAlias("@attachment").$url;
    }

    /**
     * 分析枚举类型配置值
     *
     * 格式 a:名称1,b:名称2
     *
     * @param $string
     * @return array
     */
    public static function parseAttr($string)
    {
        if (empty($string)) {
            return [];
        }

        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if (strpos($string, ':')) {
            $value = [];
            foreach ($array as $val) {
                list($k, $v) = explode(':', $val);
                $value[$k] = $v;
            }
        } else {
            $value = $array;
        }

        return $value;
    }

    /**
     * 返回字符串在另一个字符串中第一次出现的位置
     *
     * @param $string
     * @param $find
     * @return bool
     * true | false
     */
    public static function strExists($string, $find)
    {
        return !(strpos($string, $find) === false);
    }

    /**
     * XML 字符串载入对象中
     *
     * @param string $string 必需。规定要使用的 XML 字符串
     * @param string $class_name 可选。规定新对象的 class
     * @param int $options 可选。规定附加的 Libxml 参数
     * @param string $ns
     * @param bool $is_prefix
     * @return bool|SimpleXMLElement
     */
    public static function simplexmlLoadString(
        $string,
        $class_name = 'SimpleXMLElement',
        $options = 0,
        $ns = '',
        $is_prefix = false
    ) {
        libxml_disable_entity_loader(true);
        if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
            return false;
        }

        return simplexml_load_string($string, $class_name, $options, $ns, $is_prefix);
    }

    /**
     * 字符串提取汉字
     *
     * @param $string
     * @return mixed
     */
    public static function strToChineseCharacters($string)
    {
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $string, $chinese);

        return $chinese;
    }

    /**
     * 字符首字母转大小写
     *
     * @param $str
     * @return mixed
     */
    public static function strUcwords($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * 驼峰命名法转下划线风格
     *
     * @param $str
     * @return string
     */
    public static function toUnderScore($str)
    {
        $array = [];
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == strtolower($str[$i])) {
                $array[] = $str[$i];
            } else {
                if ($i > 0) {
                    $array[] = '-';
                }

                $array[] = strtolower($str[$i]);
            }
        }

        return implode('', $array);
    }

    /**
     * 获取字符串后面的字符串
     *
     * @param string $fileName 文件名
     * @param string $type 字符类型
     * @param int $length 长度
     * @return bool|string
     */
    public static function clipping($fileName, $type = '.', $length = 0)
    {
        return substr(strtolower(strrchr($fileName, $type)), $length);
    }

    /**
     * 获取随机字符串
     *
     * @param $length
     * @param bool $numeric
     * @return string
     */
    public static function random($length, $numeric = false)
    {
        $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));

        $hash = '';
        if (!$numeric) {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        $seed = str_split($seed);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }

        return $hash;
    }

    /**
     * 获取批量唯一码
     *
     * @param int $number
     * @return array
     */
    public static function randomList(int $number, $middleStr = '')
    {
        $arr = [];
        for ($i = 0; $i < $number; $i++) {
            $rand = time().$middleStr.self::random(10, true);
            $arr[$rand] = $rand;
        }

        if (count($arr) != $number) {
            $arr = ArrayHelper::merge($arr, self::randomList($number - count($arr), $middleStr));
        }

        return $arr;
    }

    /**
     * 清除Html & Script
     *
     * @param $document
     * @return array|string|string[]|null
     */
    public static function replaceHtmlAndJs($document)
    {
        $document = trim($document);
        if (strlen($document) <= 0) {
            return $document;
        }
        $search = [
            "'<script[^>]*?>.*?</script>'si", // 去掉 javascript
            "'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
            "'([\r\n])[\s]+'", // 去掉空白字符
            "'&(quot|#34);'i", // 替换 HTML 实体
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
        ];

        $replace = [
            '',
            '',
            "\\1",
            "\"",
            "&",
            "<",
            ">",
            " ",
        ];

        return preg_replace($search, $replace, $document);
    }

    /**
     * 匹配出内容的所有图片
     *
     * @param $content
     * @return array
     */
    public static function getImages($content): array
    {
        preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $content, $matches);
        if (empty($matches[0])) {
            return [];
        }
        $imgs = [];
        foreach ($matches[0] as $key => $match) {
            $imgs[] = ['html' => $matches[0][$key], 'src' => $matches[2][$key]];
        }

        return $imgs;
    }

    /**
     * 去除内容的注释
     *
     * @param $content
     * @return string|string[]|null
     */
    public static function removeAnnotation($content)
    {
        return preg_replace("/(\/\*(\s|.)*?\*\/)|(\/\/.(\s|.*))|(#(\s*)?(.*))/", '',
            str_replace(["\r\n", "\r"], "\n", $content));
    }

    /**
     * 字符串匹配替换
     *
     * @param string $search 查找的字符串
     * @param string $replace 替换的字符串
     * @param string $subject 字符串
     * @param null $count
     * @return mixed
     */
    public static function replace($search, $replace, $subject, &$count = null)
    {
        return str_replace($search, $replace, $subject, $count);
    }

    /**
     * 验证是否Windows
     *
     * @return bool
     */
    public static function isWindowsOS()
    {
        return strncmp(PHP_OS, 'WIN', 3) === 0;
    }

    /**
     * 换行显示内容
     *
     * @param string $string 字符串
     * @param int $num 每行长度
     * @param int $cycle_index 行数
     * @return array
     */
    public static function textNewLine($string, $num = 26, $cycle_index = 2)
    {
        // 中文字符串长度
        $num = $num * 3;
        $data = [];
        $letterLength = 0;
        $j = 0;
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $name = mb_substr($string, $i, 1, 'UTF-8');
            $length = strlen($name);
            if ($letterLength >= $num) {
                $letterLength = 0;
                $j++;
            }

            !isset($data[$j]) && $data[$j] = '';
            $data[$j] .= $name;
            $letterLength += $length;
        }

        if (count($data) > $cycle_index) {
            $data[$cycle_index - 1] .= '...';
        }

        foreach ($data as $key => $datum) {
            if (empty($datum) || $key > $cycle_index - 1) {
                unset($data[$key]);
            }
        }

        if (empty($data)) {
            $data[] = $string;
        }

        return $data;
    }

    /**
     * @param $string
     * @return string
     */
    public static function strToInt($string)
    {
        $versionArr = explode('.', $string);
        if (count($versionArr) > 3) {
            return false;
        }

        $version_id = 0;
        isset($versionArr[0]) && $version_id += substr(BcHelper::mul((int)$versionArr[0], 100000000000), 0, 12);
        isset($versionArr[1]) && $version_id += substr(BcHelper::mul((int)$versionArr[1], 10000000), 0, 8);
        isset($versionArr[2]) && $version_id += substr(BcHelper::mul((int)$versionArr[2], 1000), 0, 4);

        return $version_id;
    }

    /**
     * 将一个字符串部分字符用*替代隐藏
     *
     * @param string $string 待转换的字符串
     * @param int $bengin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string $glue 分割符
     * @return bool|string
     */
    public static function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@")
    {
        if (empty($string)) {
            return false;
        }

        $array = [];
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);

            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }

        switch ($type) {
            case 0 :
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", $array);
                break;
            case 1 :
                $array = array_reverse($array);
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", array_reverse($array));
                break;
            case 2 :
                $array = explode($glue, $string);
                $array[0] = self::hideStr($array[0], $bengin, $len, 1);
                $string = implode($glue, $array);
                break;
            case 3 :
                $array = explode($glue, $string);
                $array[1] = self::hideStr($array[1], $bengin, $len, 0);
                $string = implode($glue, $array);
                break;
            case 4 :
                $left = $bengin;
                $right = $len;
                $tem = array();
                for ($i = 0; $i < ($length - $right); $i++) {
                    if (isset($array[$i])) {
                        $tem[] = $i >= $left ? "*" : $array[$i];
                    }
                }

                $array = array_chunk(array_reverse($array), $right);
                $array = array_reverse($array[0]);
                for ($i = 0; $i < $right; $i++) {
                    $tem[] = $array[$i];
                }
                $string = implode("", $tem);
                break;
        }

        return $string;
    }

    /**
     * 匹配2个之间字符的字符串
     *
     * @param $str
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function matchStr($str, $start = '{', $end = '}')
    {
        $strPattern = "/(?<=".$start.")[^".$end."]+/";
        $arrMatches = [];
        preg_match_all($strPattern, $str, $arrMatches);

        return $arrMatches[0] ?? [];
    }
}
