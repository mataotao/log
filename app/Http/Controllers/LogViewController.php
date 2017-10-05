<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class LogViewController extends Controller
{
    const SUCCESS  = "success";
    const ERROR    = "error";
    const ROOT_DIR = "public/log/";
    const LOG      = "storage/logs";
    
    const MERCHANTS_SITE = "merchants";
    const TPS_API_SITE   = "tpsApi";
    
    const SYMBOL              = "{@&@}";
    const ARRAY_SUCCESS_ERROR = [
        self::SUCCESS => "正常",
        self::ERROR   => "错误",
    ];
    const ARRAY_SITE          = [
        self::MERCHANTS_SITE => "机构后台",
        self::TPS_API_SITE   => "tps.api接口站点",
    ];
    
    public function index(Request $request)
    {
        $type  = empty($request->input('type')) ? self::SUCCESS : $request->input('type');
        $time  = empty($request->input('time')) ? '' : $request->input('time');
        $year  = empty($request->input('year')) ? '' : $request->input('year');
        $site  = empty($request->input('site')) ? self::MERCHANTS_SITE : $request->input('site');
        $param = [
            '操作' => "查看日志",
        ];
        self::log($param);
        $types = self::ROOT_DIR . $site . "/" . $type;
        $path  = base_path() . "/{$types}/";
        if ($type != self::LOG) {
            if (empty($time)) {
                $time = date("m");
            }
            if (empty($year)) {
                $year = date("Y");
            }
            $time = "{$year}/{$time}";
            $path = $path . "{$time}";
        }
        $arr = [];
        if (!is_dir($path)) {
            return view('log.log', compact('arr', 'type', 'site'));
        }
        $dir = scandir($path);
        unset($dir[0]);
        unset($dir[1]);
        if (empty($dir)) {
            return view('log.log', compact('arr', 'type', 'site'));
        }
        foreach ($dir as $k => $d) {
            if ($k != 0 && $k != 1) {
                $len         = strpos($d, '.');
                $day         = substr($d, 0, $len);
                $arr["{$d}"] = [
                    'name'  => $path . "/" . $d,
                    'mouth' => $time,
                    'day'   => $day,
                    'type'  => $type,
                ];
            }
        }
        
        return view('log.log', compact('arr', 'type', 'site'));
    }
    
    /**
     * 预览
     * @param Request $request
     */
    public function view(Request $request)
    {
        $fileName = empty($request->input('name')) ? self::SUCCESS : $request->input('name');
        $type     = empty($request->input('type')) ? '' : $request->input('type');
        $file     = file_get_contents($fileName);
        $data     = explode(self::SYMBOL, $file);
        unset($data[0]);
        if (!empty($type)) {
            foreach ($data as &$item) {
                $item = unserialize($item);
            }
            $name = '转码';
            $type = 0;
        } else {
            $name = '解码';
            $type = 1;
        }
        $url = route('log.view', [
            'name' => $fileName,
            'type' => $type,
        ]);
        echo "<pre>";
        echo "<a href='$url'><button>$name</button></a><br>";
        print_r($data);
    }
    
    /**
     * 下载
     * @param Request $request
     */
    public function load(Request $request)
    {
        $fileName = empty($request->input('name')) ? self::SUCCESS : $request->input('name');
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=" . basename($fileName));
        readfile($fileName);
        exit;
    }
    
    public function search(Request $request)
    {
        set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $content   = empty($request->input('content')) ? '' : $request->input('content');
        $dirType   = empty($request->input('dirType')) ? self::SUCCESS : $request->input('dirType');
        $startS    = empty($request->input('start')) ? date("Y-m-d") : $request->input('start');
        $endS      = empty($request->input('end')) ? date("Y-m-d") : $request->input('end');
        $site      = empty($request->input('site ')) ? self::MERCHANTS_SITE : $request->input('site ');
        $type      = empty($request->input('type')) ? false : true;
        $start     = strtotime("-1 day", strtotime($startS));
        $end       = strtotime("+1 day", strtotime($endS));
        $fileNames = [];
        while (($start = strtotime("+1 day", $start)) < $end) {
            $y       = date('Y', $start);
            $m       = date('m', $start);
            $d       = date('d', $start);
            $rootDir = self::ROOT_DIR;
            $fName   = base_path() . "/{$rootDir}{$site}/{$dirType}/{$y}/{$m}/$d.log";
            if (file_exists($fName)) {
                $fileNames[] = $fName;
            }
        }
        if (!empty($fileNames)) {
            $res = [];
            foreach ($fileNames as $fileName) {
                $data = file_get_contents($fileName);
                $data = explode(self::SYMBOL, $data);
                unset($data[0]);
                if (!empty($content)) {
                    foreach ($data as $v) {
                        if (strpos($v, $content) !== false) {
                            $res[] = $v;
                        }
                    }
                } else {
                    $res = $data;
                }
                unset($data);
            }
            if ($type) {
                foreach ($res as &$r) {
                    $r = unserialize($r);
                }
            }
            
            if ($type) {
                $href = route('log.search', [
                    'content' => $content,
                    'type'    => false,
                    'dirType' => $dirType,
                    'start'   => $startS,
                    'end'     => $endS,
                ]);
                $name = '解码';
            } else {
                $href = route('log.search', [
                    'content' => $content,
                    'type'    => true,
                    'dirType' => $dirType,
                    'start'   => $startS,
                    'end'     => $endS,
                ]);
                $name = '转码';
            }
            dump($fileNames);
            echo "<pre>";
            echo "<a href='$href'><button>$name</button></a>&ensp;<span style='color: red'>转码的时间是不转码的10倍以上</span><br>";
            print_r($res);
            
        }
        
    }
    
    public static function log($param, $site = self::MERCHANTS_SITE)
    {
        $attached = [
            '日志类型' => "正常",
        ];
        $param    = array_merge($attached, $param);
        $str      = ToolController::log($param);
        $fileName = self::dir("success", $site);
        file_put_contents($fileName, self::SYMBOL . serialize($str), FILE_APPEND | LOCK_EX);
    }
    
    public static function error($param, $site = self::MERCHANTS_SITE)
    {
        $attached = [
            '日志类型' => "错误",
        ];
        $param    = array_merge($attached, $param);
        $str      = ToolController::log($param);
        $fileName = self::dir("error", $site);
        file_put_contents($fileName, self::SYMBOL . serialize($str), FILE_APPEND | LOCK_EX);
    }
    
    public static function dir($type, $site = self::MERCHANTS_SITE)
    {
        $m = date("m");
        $d = date("d");
        $y = date("Y");
        if (!is_dir('log')) {
            mkdir('log');
        }
        if (!is_dir("log/{$site}")) {
            mkdir("log/{$site}");
        }
        if (!is_dir("log/{$site}/{$type}")) {
            mkdir("log/{$site}/{$type}");
        }
        if (!is_dir("log/{$site}/{$type}/{$y}")) {
            mkdir("log/{$site}/{$type}/{$y}");
        }
        if (!is_dir("log/{$site}/{$type}/{$y}/{$m}")) {
            mkdir("log/{$site}/{$type}/{$y}/{$m}");
        }
        $name = "log/{$site}/{$type}/{$y}/{$m}/$d.log";
        
        return $name;
    }

//    public function listenLog()
//    {
//        $sch = new SchedulerController();
//        set_time_limit(0);
//        ignore_user_abort(true);
//        //设置IP和端口号
//        $config  = config("system.tcp_log");
//        $address = $config['ip'];
//        $port    = $config['port'];
//        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
//            echo "socket_create() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n";
//            die;
//        }
//        //阻塞模式
//        if (socket_set_block($sock) == false) {
//            echo "socket_set_block() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n";
//            die;
//        }
//        if (socket_bind($sock, $address, $port) == false) {
//            echo "socket_bind() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n";
//            die;
//        }
//        if (socket_listen($sock, 4) == false) {
//            echo "socket_listen() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n";
//            die;
//        }
//        do {
//            if (($msgsock = socket_accept($sock)) === false) {
//                echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error()) . "/n";
//                die;
//            }
//            $buf = socket_read($msgsock, 8192);
//            $buf = json_decode($buf, true);
//            if ($buf['logType'] == 'success') {
//                $sch->newTask($this->yLog($buf));
//            } else {
//                $sch->newTask($this->yError($buf));
//            }
//            $sch->run();
//            socket_close($msgsock);
//        } while (true);
//        socket_close($sock);
//    }
    
    
    public function listenLog()
    {
        set_time_limit(0);
        @ini_set('memory_limit', '512M');
        ignore_user_abort(true);
        $scheduler = new SchedulerController();
        $config    = config("system.tcp_log");
        $address   = $config['ip'];
        $port      = $config['port'];
        $scheduler->newTask(log_server($address, $port));
        $scheduler->run();
        
    }
    
    private function yLog($buf)
    {
        yield self::log($buf, $buf['site']);
        
    }
    
    private function yError($buf)
    {
        yield self::error($buf, $buf['site']);
    }
    
    
}

/**
 * api站点的写法
 */
class Api
{
    const TPS_API_SITE = "tpsApi";
    const SUCCESS      = "success";
    const ERROR        = "error";
    
    public static function log($data, $type = self::SUCCESS)
    {
        $param = [
            'site'    => self::TPS_API_SITE,
            'logType' => $type,
        ];
        $data  = json_encode(array_merge($data, $param));
        $res   = self::save($data);
        
        return $res;
    }

//    private static function save($data)
//    {
//        error_reporting(0);
//        set_time_limit(0);
//        $config       = C("tcp_log");
//        $service_port = 8000;
//        $address      = $config['ip'];
//        $socket       = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//        if ($socket === false) {
//            die;
//        }
//        if (socket_connect($socket, $address, $service_port) == false) {
//            $error = socket_strerror(socket_last_error());
//            echo "socket_connect() failed./n", "Reason: {$error} /n";
//            die;
//        }
//        socket_write($socket, $data, strlen($data));
//        socket_close($socket);
//    }
    
    private static function save($data)
    {
        $config       = C("tcp_log");
        $service_port = $config['port'];
        $address      = $config['ip'];
        $fp           = stream_socket_client("tcp://$address:$service_port", $errno, $errstr);
        
        if (!$fp) {
            return "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, $data);
            while (!feof($fp)) {
                return fgets($fp, 1024);
            }
            fclose($fp);
        }
        
    }
    
}