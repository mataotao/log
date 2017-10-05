<?php

namespace App\Http\Controllers;

class ToolController extends Controller
{
    const EXACT_MATCH      = 1;//精确匹配
    const LEFT_FUZZY_MATCH = 2;//从左边开始模糊匹配
    const FUZZY_MATCH      = 3;//模糊匹配
    const DICHOTOMY        = 4;//二分法查找
    const ON               = 1;
    const OFF              = 0;
    
    public static function combine_push_url($action, $agency, $responseArr = [])
    {
        if (empty($agency) || empty($action)) {
            return '';
        }
        switch ($agency) {
            case 'jjjty':
                $agencyCode = "Jty";
                break;
            default:
                $agencyCode = ucfirst($agency);
        }
        $h5url = "https://m.guxiansheng.cn/#!/third";
        $url   = $h5url . $agencyCode . ucfirst($action) . "?" . http_build_query($responseArr);
        
        return $url;
    }
    
    /**
     * 把二维数组的key换成field
     * @param $array
     * @param $field
     * @return array
     */
    public static function arrayToArrayKey($array, $field, $group = 0)
    {
        $arr = [];
        if (empty($array)) {
            return $arr;
        }
        if ($group == 0) {
            foreach ($array as $v) {
                if (array_key_exists($field, $v)) {
                    $arr[$v[$field]] = $v;
                }
            }
        } else {
            foreach ($array as $v) {
                if (array_key_exists($field, $v)) {
                    $arr[$v[$field]][] = $v;
                }
            }
        }
        
        return $arr;
    }
    
    public static function url($url, $parameter)
    {
        return $url . http_build_query($parameter);
    }
    
    public static function curlPost($url, $data)
    {
        $ch = curl_init();
        if (empty(config('system.proxy'))) {
            curl_setopt($ch, CURLOPT_PROXY, config('system.proxy'));
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设置为POST
        curl_setopt($ch, CURLOPT_POST, 1);
        //把POST的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }
    
    public static function aesEncrypt($arr)
    {
        $str = base64_encode(serialize($arr));
        
        return openssl_encrypt($str, "AES-256-CBC", config('system.third_token'), 0,
                               substr(md5(config('system.third_token')), 0, 16));
    }
    
    public static function log($param)
    {
        $global = [
            '操作时间'   => date("Y-m-d H:i:s"),
            'ip'     => self::getClientIp(),
            '操作人'    => @\Auth::user()->name,
            '操作人的id' => @\Auth::user()->id,
            '操作人的手机' => @\Auth::user()->id,
            '操作人的id' => @\Auth::user()->id,
        
        
        ];
        $str    = print_r(array_merge($global, $param), true);
        @\Log::alert($str);
        
        return array_merge($global, $param);
    }
    
    public static function getClientIp($type = 0)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? [
            $ip,
            $long,
        ] : [
            '0.0.0.0',
            0,
        ];
        
        return $ip[$type];
    }
    
    public static function curlGet($url)
    {
        $ch = curl_init();
        //设置选项，包括URL
        if (empty(config('system.proxy'))) {
            curl_setopt($ch, CURLOPT_PROXY, config('system.proxy'));
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        
        return $output;
    }
    
    public static function curlHttpsGet($upUrl)
    {
        $ch = curl_init();
        
        if (empty(config('system.proxy'))) {
            curl_setopt($ch, CURLOPT_PROXY, config('system.proxy'));
        }
        
        curl_setopt($ch, CURLOPT_URL, $upUrl);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $res = curl_exec($ch);
        
        return $res;
    }
    
    /**
     * 批量curl
     * @param $arrUrls ['var'=>'url']
     * @return array
     */
    public static function curlMultiGet($arrUrls)
    {
        $mh = curl_multi_init();
        
        $responsesKeyMap = [];
        
        $arrResponses = [];
        
        // 添加 Curl 批处理会话
        foreach ($arrUrls as $urlsKey => $strUrlVal) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $strUrlVal);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            if (empty(config('system.proxy'))) {
                curl_setopt($ch, CURLOPT_PROXY, config('system.proxy'));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh, $ch);
            $strCh                   = (string)$ch;
            $responsesKeyMap[$strCh] = $urlsKey;
        }
        
        // 批处理执行
        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
            
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);
        
        while ($active && CURLM_OK == $mrc) {
            
            if (-1 == curl_multi_select($mh)) {
                usleep(100);
            }
            
            do {
                
                $mrc = curl_multi_exec($mh, $active);
                
                if (CURLM_OK == $mrc) {
                    while ($multiInfo = curl_multi_info_read($mh)) {
                        $curl_info                              = curl_getinfo($multiInfo['handle']);
                        $curl_error                             = curl_error($multiInfo['handle']);
                        $curl_results                           = curl_multi_getcontent($multiInfo['handle']);
                        $strCh                                  = (string)$multiInfo['handle'];
                        $arrResponses[$responsesKeyMap[$strCh]] = compact('curl_info', 'curl_error', 'curl_results');
                        curl_multi_remove_handle($mh, $multiInfo['handle']);
                        curl_close($multiInfo['handle']);
                    }
                }
                
            } while (CURLM_CALL_MULTI_PERFORM == $mrc);
        }
        $map = [];
        foreach ($arrResponses as $key => $arrResponse) {
            $map[$key] = $arrResponse['curl_results'];
        }
        // 关闭资源
        curl_multi_close($mh);
        
        return $map;
        
    }
    
    /**
     * 转化/解码 html实体字符
     * @param $str
     * @param bool $type true转化 否则解码
     * @param int $style 默认 转化/解码双引号和单引号
     * @return string
     */
    public static function htmlentites($str, $type = true, $style = ENT_QUOTES)
    {
        if ($type == true) {
            $string = htmlentities($str, $style, 'UTF-8');
        } else {
            $string = html_entity_decode($str, $style, 'UTF-8');
        }
        
        return $string;
    }
    
    /**
     * 查找传入类的文件位置
     * @param $class
     * @return string
     */
    public static function searchClass($class)
    {
        $func = new \ReflectionClass($class);
        
        return $func->getFileName();
    }
    
    /**
     * 判断数组中key存不存在,不存在默认返回null,有则返回它本身的值
     * @param $key
     * @param $array
     * @param array $additional 附加参数
     * @param null $return 不存返回的参数
     * @return null|string
     */
    public static function arrayKeyExistsNull($key, $array, $additional = [], $return = null)
    {
        if (array_key_exists($key, $array)) {
            if (!empty($additional)) {
                $addArray = $array[$key];
                foreach ($additional as $add) {
                    $addArray = $addArray[$add];
                }
                
                return $addArray;
            }
            
            return $array[$key];
        } else {
            return $return;
        }
    }
    
    
    /**关键字查找数组或数组中的某一列
     * @param $arr
     * @param $value
     * @param int $method
     * @param bool $type $type为FALSE 查找数组  $type为别的 查找二维数组中的这一列
     * @param int $isCase 是否支持大小写 1支持 0不支持
     * @return array|int|null
     */
    public static function arraySearch($arr, $value, $type = false, $method = self::EXACT_MATCH, $isCase = self::OFF)
    {
        
        switch ($method) {
            case self::EXACT_MATCH:
                $res = self::exactMatch($arr, $value, $type, $isCase);
                break;
            case self::LEFT_FUZZY_MATCH:
                $res = self::leftSearch($arr, $value, $type, $isCase);
                break;
            case self::FUZZY_MATCH:
                $res = self::fuzzyMatch($arr, $value, $type, $isCase);
                break;
            case self::DICHOTOMY:
                $res = self::dichotomy($arr, $value);
                break;
            default:
                $res = self::exactMatch($arr, $value, $type, $isCase);
        }
        
        return $res;
    }
    
    /**
     * 从左边开始模糊匹配
     * @param $arr
     * @param $value
     * @param bool $type $type为FALSE 查找数组  $type为别的 查找二维数组中的这一列
     * @param int $isCase 是否支持大小写 1支持 0不支持
     * @return array
     */
    public static function leftSearch($arr, $value, $type = false, $isCase = self::OFF)
    {
        if ($type === false) {
            $res = array_filter($arr, function ($v) use ($value, $isCase) {
                $val = substr($v, 0, strlen($value));
                if ($isCase == self::ON) {
                    $val   = strtolower($val);
                    $value = strtolower($value);
                }
                if ($val == $value) {
                    return true;
                }
            });
        } else {
            $res = array_filter($arr, function ($v) use ($value, $type, $isCase) {
                $val = substr($v[$type], 0, strlen($value));
                if ($isCase == self::ON) {
                    $val   = strtolower($val);
                    $value = strtolower($value);
                }
                if ($val == $value) {
                    return true;
                }
            });
        }
        
        return $res;
    }
    
    /**
     * 精确匹配
     * @param $arr
     * @param $value
     * @param bool $type $type为FALSE 查找数组  $type为别的 查找二维数组中的这一列
     * @param int $isCase 是否支持大小写 1支持 0不支持
     * @return array
     */
    public static function exactMatch($arr, $value, $type = false, $isCase = self::OFF)
    {
        if ($type === false) {
            $res = array_filter($arr, function ($v) use ($value, $isCase) {
                if ($isCase == self::ON) {
                    $v     = strtolower($v);
                    $value = strtolower($value);
                }
                if ($v == $value) {
                    return true;
                }
                
            });
        } else {
            $res = array_filter($arr, function ($v) use ($value, $type, $isCase) {
                if ($isCase == self::ON) {
                    $v[$type] = strtolower($v[$type]);
                    $value    = strtolower($value);
                }
                if ($v[$type] == $value) {
                    return true;
                }
            });
        }
        
        return $res;
    }
    
    /**模糊匹配
     * @param $arr
     * @param $value
     * @param bool $type $type为FALSE 查找数组  $type为别的 查找二维数组中的这一列
     * @param int $isCase 是否支持大小写 1支持 0不支持
     * @return array
     */
    public static function fuzzyMatch($arr, $value, $type = false, $isCase = self::OFF)
    {
        if ($type === false) {
            $res = array_filter($arr, function ($v) use ($value, $isCase) {
                if ($isCase == self::ON) {
                    $v     = strtolower($v);
                    $value = strtolower($value);
                }
                if (strpos($v, $value) !== false) {
                    return true;
                }
            });
        } else {
            $res = array_filter($arr, function ($v) use ($value, $type, $isCase) {
                if ($isCase == self::ON) {
                    $v[$type] = strtolower($v[$type]);
                    $value    = strtolower($value);
                }
                if (strpos($v[$type], $value) !== false) {
                    return true;
                }
            });
        }
        
        return $res;
    }
    
    /**
     * 二分法查找值,只查找一个 不支持模糊匹配  只能查找数字 返回的是下标
     * @param $arr
     * @param $value
     * @return int|null
     */
    public static function dichotomy($arr, $value)
    {
        sort($arr);
        $len   = count($arr);
        $start = 0;
        $end   = $len - 1;
        while ($start <= $end) {
            $mid = (int)(($start + $end) / 2);
            if ($arr[$mid] == $value) {
                return $mid;
            } else if ($arr[$mid] < $value) {
                $start = $mid + 1;
            } else if ($arr[$mid] > $value) {
                $end = $mid - 1;
            }
        }
        
        return null;
    }
    
    /**写入文件
     * @param $fileName
     * @param $data
     * @param $path
     * @return array|int
     */
    public static function wFile($fileName, $data, $path = '')
    {
        if (empty($fileName) || empty($data)) {
            return [];
        }
        $fileName    = ucfirst($fileName);
        $fileNameOne = $path . "one$fileName";
        $fileNameTwo = $path . "two$fileName";
        if (file_exists($fileNameOne) && file_exists($fileNameTwo)) {
            $fileOneTime     = filemtime($fileNameOne);
            $fileTwoTime     = filemtime($fileNameTwo);
            $finallyFileName = $fileOneTime > $fileTwoTime ? $fileNameTwo : $fileNameOne;
        } elseif (file_exists($fileNameOne) && !file_exists($fileNameTwo)) {
            $finallyFileName = $fileNameTwo;
        } elseif (!file_exists($fileNameOne) && file_exists($fileNameTwo)) {
            $finallyFileName = $fileNameOne;
        } elseif (!file_exists($fileNameOne) && !file_exists($fileNameTwo)) {
            $finallyFileName = $fileNameOne;
        }
        $res = file_put_contents($finallyFileName, $data);
        
        return $res;
    }
    
    /**
     * 读取文件
     * @param $fileName
     * @param $path
     * @return array|string
     */
    public static function rFile($fileName, $path = '')
    {
        if (empty($fileName)) {
            return [];
        }
        $fileName    = ucfirst($fileName);
        $fileNameOne = $path . "one$fileName";
        $fileNameTwo = $path . "two$fileName";
        if (!file_exists($fileNameOne) && !file_exists($fileNameTwo)) {
            $finallyFileName = '';
        } elseif (file_exists($fileNameOne) && file_exists($fileNameTwo)) {
            $fileTimeOne     = filemtime($fileNameOne);
            $fileTimeTwo     = filemtime($fileNameTwo);
            $finallyFileName = $fileTimeOne > $fileTimeTwo ? $fileNameOne : $fileNameTwo;
        } elseif (file_exists($fileNameOne) && !file_exists($fileNameTwo)) {
            $finallyFileName = $fileNameOne;
        } elseif (!file_exists($fileNameOne) && file_exists($fileNameTwo)) {
            $finallyFileName = $fileNameTwo;
        }
        if (empty($finallyFileName)) {
            return [];
        } else {
            return file_get_contents($finallyFileName);
        }
    }
    
    
}
