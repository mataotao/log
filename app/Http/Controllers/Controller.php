<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use vendor\PHPExcel\Classes\PHPExcel;
//引入app
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * json返回数据
     * @param $state
     * @param $data
     * @param $message
     * @return string
     */
    public function returnSuccess($state,$data,$message){
        return json_encode(array(
            'state'     => $state,
            'data'      => $data,
            'message'   => $message,
        ));
        die;
    }

    public function getNumber(){
        $number = 0;
        if(!empty($_GET['page'])){
            $num = $_GET['page']*config('msc.page_size',10);
            $number = $num-config('msc.page_size',10);
        }
        return	$number+1;
    }

    //自动加载
    public function __get($class)
    {
        //获取驼峰首个单词
        $file_name = preg_replace('/([a-z])([A-Z])/', "$1_$2", $class);
        $file_name_arr = explode('_', $file_name);
        $file_prefix = isset($file_name_arr[0]) ? $file_name_arr[0] :'';

        //检查文件是否存在
        if (substr($class, strlen($class) - 3)=='Lib') {//自动加载lib
            $class_load = "App\Http\Librarys\\".$class;
            if (!class_exists($class_load)) { 
                $class_name = $file_prefix.'\\'.$class;           
                $class_load = "App\Http\Librarys\\".$class_name;
            }
        } elseif (substr($class, strlen($class) - 5)=='Model') {//自动加载model
            $class_load = "App\Entities\\".$class;
            if (!class_exists($class_load)) { 
                $class_name = $file_prefix.'\\'.$class;           
                $class_load = "App\Entities\\".$class_name;
            }
        } elseif (substr($class, strlen($class) - 3)=='App') {//自动加载App
            $class_load = "App\Application\\".$class;
            if (!class_exists($class_load)) {
                $class_name = $file_prefix.'\\'.$class;           
                $class_load = "App\Application\\".$class_name;
            }
        }  elseif (substr($class, strlen($class) - 6)=='Domain') {//自动加载Domain
            $class_load = "App\Domain\\".$class;
            if (!class_exists($class_load)) {
                $class_name = $file_prefix.'\\'.$class;           
                $class_load = "App\Domain\\".$class_name;
            }
        } else {
            //自动加载model（规则的）
            $class_load = "App\Entities\\".$class;
            if (!class_exists($class_load)) {

                $array = [$file_prefix,'Agency','Cms','StockSir','Wlzx'];
                foreach ($array as $k => $v) {
                    $class_load = "App\Entities\\".$v.'\\'.$class;
                    if (class_exists($class_load)){
                        break;
                    }
                }
            }
            if (!class_exists($class_load)) return;
        }        

        App::bindIf($class_load, null, true);
        $obj = App::make($class_load);
        return $obj;
    }
    //自定义 end

}
