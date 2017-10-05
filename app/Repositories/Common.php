<?php

namespace App\Repositories;

use App\Jobs\SendReminderSms;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redis;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Messages\BaseMessage;
use Overtrue\Wechat\Staff;
use Anchu\Ftp\Facades\Ftp;
use Overtrue\Wechat\Broadcast;
use Qiniu\Auth;
use Queue;
use App;
use Illuminate\Http\Request;
use Predis;
use App\Entities\Wlzx\WlzxTeacherAsc;

class Common{
    static $RedisCluster = '';
    const SUCCESS = 1; //正常返回
    const ERROR = -1;  //错误返回
    /**
     * 给指定手机号发送系统短信
     *
     * @param $mobile string 手机号
     * @param $message string 消息内容
     *
     * @return void
     *
     * @version 1.0
     * @author limingyao <limingyao@misrobot.com>
     * @date ${DATE} ${TIME}
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     *
     */
    public static function sendSms($mobile,$message){

        (new SendReminderSms($mobile,$message))->onQueue('sms');

    }

    /**
     * 上传图片(支持多张)
     * @access public
     *
     * @param $request object 请求对象
     * @param $message string 需要获取的文件的 name
     *
     * @return ['路径1'，'路径2'，'路径3'，…………]
     *
     * @version 1.0
     * @author Luohaihua <Luohaihua@misrobot.com>
     * @date 2015-11-15 17:51
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     *
     */
    public static function saveImags(Request $request,$name){
        $images=$request->file($name);
        $pathLIst=[];
        foreach($images as $image)
        {
            if(!is_null($image))
            {
                $fileObject=$image->openFile();
                $content=$fileObject->fread($fileObject->getSize());
                $fileName=date('YmdHis').rand(1,99999).'.'.$image->getClientOriginalExtension();
                $savePath='/'.date('Ym').'/'.date('d').'/'.$fileName;
                $dir=Storage::disk('images');
                $result=$dir->put($savePath,$content);
                if($result)
                {
                    $pathLIst[]='/images'.$savePath;
                }
                else
                {
                    $pathLIst[]='';
                }
            }
        }
        return $pathLIst;
    }
    /**
     * 上传图片(单张)
     * @access public
     *
     * @param $request object 请求对象
     * @param $message string 需要获取的文件的 name
     *
     * @return string  路径
     *
     * @version 1.0
     * @author Luohaihua <Luohaihua@misrobot.com>
     * @date 2015-11-15 17:51
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     *
     */
    public static function saveImage(Request $request,$name){
        if($request->hasFile($name) && $request->file($name)->isValid()){

            $image=$request->file($name);
            $content=file_get_contents($image->getRealPath());
            $destinationPath='/'.date('YmdHis',time()).'_'.str_random(6).'.'.$image->getClientOriginalExtension();

            $result=Storage::disk('images')->put($destinationPath,$content);

            if($result)
            {
                $path='/images'.$destinationPath;
            }
            else
            {
                $path=false;
            }
            return $path;
        }
        else
        {
            throw new \Exception('没有上传文件');
        }
    }

    /**导入excel表格
     * Method name getExclData
     * @param $name file的name名
     * @param bool|true $cnHeader
     * Return mixed
     */
    public static function getExclData($name,$cnHeader=true){
        ini_set("max_execution_time", "1800");
        $excl=Input::file($name);

        if(empty($excl))
        {
            throw new \Exception('没有上传文件');
        }

        // 判断上传文件正确性-added by wangjiang on 2015-12-2 18:00
        if (true != $excl->isValid())
        {
            throw new \Exception('上传文件错误');
        }

        //dd($cnHeader);
        //英文表头直接读取
        if($cnHeader==='en')
        {
            $data=Excel::load($excl -> getRealPath(),function($reader){
            },'UTF-8')->get();
        }
        else
        {
            //中文表头 或者 不要表头
            $data=Excel::load($excl -> getRealPath(),function($reader){
                $reader->noHeading();
            },'UTF-8')->get();
        }
        $ExclData=[];
        foreach($data as $items)
        {
            $sheet=[];
            //判断是否为中文表头
            if($cnHeader)
            {
                $itemsInfo=$items->first();
                if(count($itemsInfo)<=0)
                {
                    throw new \Exception('没有找到首行');
                }

                $keyList=$itemsInfo->toArray();
                foreach($items as $rowNum=>$rows)
                {
                    //如果是表头
                    if($rowNum==0)
                    {
                        foreach($rows as $headName)
                        {
                            //检查表头单元格格式
                            if(!is_string($headName)&&strlen($headName))
                            {
                                throw new \Exception('请设置表头单元格格式为文本');
                            }
                        }
                        continue;
                    }
                    $rowData=[];
                    foreach($rows as $keyIndex=>$value)
                    {
                        if(strlen($keyList[$keyIndex])==0)
                        {
                            continue;
                        }
                        $rowData[$keyList[$keyIndex]]=$value;
                    }
                    $sheet[]=$rowData;
                }
            }
            else
            {
                foreach($items as $rows)
                {
                    $sheet[]=$rows->toArray();
                }
                //$ExclShell[$items->getTitle()]=$sheet;
            }
            $ExclShell[$items->getTitle()]=$sheet;
        }

        return $ExclShell;
    }

    public static function getExclData_($name){
        ini_set("max_execution_time", "1800");
        $excl=Input::file($name);
        if(empty($excl))
        {
            throw new \Exception('没有上传文件');
        }
        if (true != $excl->isValid())
        {
            throw new \Exception('上传文件错误');
        }
        $data=Excel::load($excl -> getRealPath(),function($reader){
            $reader->noHeading();
        },'UTF-8')->get();

        $sheet=[];
        $itemsInfo=$data->first();
        $keyList=$itemsInfo->toArray();
        if(count($keyList) <= 1){
            throw new \Exception('上传数据为空');
        }
       foreach($keyList as $k => $v){
            if(is_array($v) && count($v) > 0 ){
                foreach($v as $key => $val){
                    if(!empty($val) && is_array($val) || is_object($val)){
                        $keyList[$k][$key] = $val->toDateString();
                    }
                }
            }else{
                return array();
            }
        }
        /*       dd($keyList);
                foreach($data as $rowNum=>$rows) {
                    if ($rowNum != 0) {
                        $rowData = [];
                        foreach ($rows as $keyIndex => $value) {
                            var_dump($keyList[$keyIndex]);
                            $rowData[$keyList[$keyIndex]] = $value;
                        }
                        $sheet[] = $rowData;

                    }
                }*/
        return $keyList;
    }

    public static function getExclExport($data,$title="第三方用户excel导入示例"){
        Excel::create($title,function($excel) use ($data){
            $excel->sheet('sheet1', function($sheet) use ($data){
                $sheet->rows($data);
            });
            $excel->sheet('sheet2');
            $excel->sheet('sheet3');
        })->export('xls');
}

    /**
     * 发送微信通知
     */
    public static function sendWeiXin($openId,$message){
        $weixinservice= App::make('wechat.staff');
        try{
            return $weixinservice->send($message)->to($openId);

        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * 创建 微信消息
     * 格式 ：
     * $msg=Common::CreateWeiXinMessage([
    ['title'=>'测试文本1'],
    ['title'=>'测试文本','picUrl'=>'http://image.golaravel.com/5/c9/44e1c4e50d55159c65da6a41bc07e.jpg']
    ]);
     */
    public static function CreateWeiXinMessage($msgArray){
        $message = Message::make('news')->items(
            function() use ($msgArray){
                $msgData=[];
                foreach($msgArray as $key=>$item)
                {
                    $itemData=Message::make('news_item')->title($item['title']);
                    foreach($item as $feild=>$value)
                    {
                        if($feild==='desc')
                        {
                            $itemData=$itemData->description($value);
                        }
                        if($feild==='url')
                        {
                            $itemData=$itemData->url($value);
                        }
                        if($feild==='picUrl')
                        {
                            $itemData=$itemData->picUrl($value);
                        }
                    }
                    $msgData[]=$itemData;
                }
                return $msgData;
            }
        );
        return $message;
    }

    /**
     * 将Excl导入产生的数组(二维) ，其中 中文的字段换成对应的英文
     * @param $data
     * @param array $nameToEn
     * @return array
     * @throws \Exception
     */
    public static function arrayChTOEn($data,$nameToEn=[]){
        if(is_string($nameToEn))
        {
            $nameToEn=config($nameToEn);
        }

        if(empty($nameToEn))
        {
            throw new \Exception('中英文字段对照配置不存在');
        }
        $newData=[];
        foreach ($data as $key=>$item) {
            $row=[];
            foreach($item as $keyName=>$keyValue){
                $row[$nameToEn[$keyName]]=$keyValue;
            }
            $newData[]=$row;
        }
        return $newData;
    }

    /**
     * 微信的发送方法
     * @param $openid
     * @param $msg
     * @return bool
     * @throws \Exception
     * @throws \Overtrue\Wechat\Exception
     */
    public static function sendMsg($openid,$msg){
        if(empty($openid))
        {
            throw new \Exception('没有找到用户的微信OpenID');
        }
        $userService = new \Overtrue\Wechat\Staff(config('wechat.app_id'), config('wechat.secret'));
        return $userService->send($msg)->to($openid);
    }

    /*
    public static function putNamesToRows ($data, $config='')
    {
        if (empty($config))
        {
            throw new \Exception('config not found error');
        }

        if (!is_array($data))
        {
            throw new \Exception('data is not array');
        }

        global $map;
        if (is_string($config))
        {
            $map = config($config);
        }

        foreach ($data as $k => $v)
        {
            foreach ($v as $_k => $_v)
            {
                $data[$k][$map[$_k]] = $_v;
                unset($data[$k][$_k]);
            }
        }

        return $data;
    }
    */
    //微信群发


    /**
     * 微信群发
     * @access public
     *
     * @param object    $message        微信消息对象（可以使用Common::CreateWeiXinMessage 创建）
     * @param array $   OpendIdArray    接收的微信opendID列表 e.g:['oI7UquKmahFwGV0l2nyu_f51nDJ4','oI7UquPKycumti7NU4HQYjVnRjPo']
     * @return void
     *
     * <pre>
     * $Message  =   Common::CreateWeiXinMessage(
     *      [
     *          [
     *              'title' =>'邀请通知',
     *              'desc'  =>'osce考试第一期邀请',
     *              'url'=>'http://www.baidu.com'
     *          ],
     *          //['title'=>'osce考试第一期邀请','url'=>'http://www.baidu.com'],
     *      ]
     * );
     *  //Common::sendWeiXin('oI7UquKmahFwGV0l2nyu_f51nDJ4',$Message);//单发
     *  Common::sendWeixinToMany($Message,['oI7UquKmahFwGV0l2nyu_f51nDJ4','oI7UquPKycumti7NU4HQYjVnRjPo']);//群发
     * </pre>
     * @version 1.0
     * @author Luohaihua <Luohaihua@misrobot.com>
     * @date 2016-01-07 21:04
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     *
     */
    static public function sendWeixinToMany(BaseMessage $message,array $OpendIdArray){
        if(count($OpendIdArray)==0)
        {
            throw new \Exception('你选择的接收用户数量为0');
        }
        $broadcast = new Broadcast(config('wechat.app_id'), config('wechat.secret'));
        $broadcast->send($message)->to($OpendIdArray);
    }
    /**
     * 图片上传
     */
    static public function Upload($filename = 'file',$size = null){
        if(!$_FILES[$filename]['tmp_name']){
            return [0,'没有上传文件'];
        }

        if($size && $size*1024 < $_FILES[$filename]['size']) return [0,'请上传不超过'.$size.'KB大小的,JPG.PNG.JPEG格式的图片'];


        $realName = $_FILES[$filename]['name'];
        $ext = explode('.',$realName);
        $extArray = [
            'jpg',
            'png',
            'jpeg',
            'gif',
        ];
        if(!in_array(strtolower($ext[1]),$extArray)){
            return [2,'文件格式不正确'];
        }


        $fileName = $_FILES[$filename]['tmp_name'];
        //dd($fileName);
        $userInfo = \Auth::user();
        $nowFileName = date('Y-m-d').'-'.rand(11111,9999999).'-'.$userInfo->id.'.'.$ext[1];
        $url = 'attach/adv/'.$nowFileName;
        $uploadFile = Ftp::connection('connection1')->uploadFile($fileName,$url,FTP_ASCII);//or FTP_BINARY

        if($uploadFile){
            return [1,$url];
        }else{
            return [4,'上传失败'];
        }
    }
    /**
     *天天牛股的图片上传限制
     * huangweiyuan
    */

    static public function ttnbUpload($filename = 'file',$size = null){
        if(!$_FILES[$filename]['tmp_name']){
            return [0,'没有上传文件'];
        }


        $realName = $_FILES[$filename]['name'];
        $ext = explode('.',$realName);
        $extArray = [
            'jpg',
            'png',
            'jpeg',
        ];
        if(!in_array(strtolower($ext[1]),$extArray)){
            return [2,'文件格式不正确'];
        }

        $fileName = $_FILES[$filename]['tmp_name'];
        //dd($fileName);
        $userInfo = \Auth::user();
        $nowFileName = date('Y-m-d').'-'.rand(11111,9999999).'-'.$userInfo->id.'.'.$ext[1];
            //限制文件大小
            $ttnbsize = filesize($_FILES[$filename]['tmp_name']);
            if ($ttnbsize > 2048576) {
                return [0,'请上传不超过2M大小的,JPG.PNG.JPEG格式的图片'];
            }

        $url = 'attach/adv/'.$nowFileName;
        $uploadFile = Ftp::connection('connection1')->uploadFile($fileName,$url,FTP_ASCII);//or FTP_BINARY

        if($uploadFile){
            return [1,$url];
        }else{
            return [4,'上传失败'];
        }
    }

    /**
     * json返回数据
     * @param $state
     * @param $data
     * @param $message
     * @return string
     */
     static public function returnSuccess($state,$data,$message){
        return json_encode(array(
            'state'     => $state,
            'data'      => $data,
            'message'   => $message,
        ));
    }
 /**
  * 广告系统的图片限制
  * huangweiyuan
 */
    static public function advUpload($filename = 'file',$size = null){
        if(!$_FILES[$filename]['tmp_name']){
            return [0,'没有上传文件'];
        }

        if($size && $size*2048 < $_FILES[$filename]['size']) return [0,'请上传不超过200KB大小的,JPG.PNG.JPEG格式的图片'];


        $realName = $_FILES[$filename]['name'];
        $ext = explode('.',$realName);
        $extArray = [
            'jpg',
            'png',
            'jpeg',
        ];
        if(!in_array(strtolower($ext[1]),$extArray)){
            return [2,'文件格式不正确'];
        }


        $fileName = $_FILES[$filename]['tmp_name'];
        //dd($fileName);
        $userInfo = \Auth::user();
        $nowFileName = date('Y-m-d').'-'.rand(11111,9999999).'-'.$userInfo->id.'.'.$ext[1];
        $url = 'attach/adv/'.$nowFileName;
        $uploadFile = Ftp::connection('connection1')->uploadFile($fileName,$url,FTP_ASCII);//or FTP_BINARY

        if($uploadFile){
            return [1,$url];
        }else{
            return [4,'上传失败'];
        }
    }

    /**
     * 判断是普通用户还是超级管理员
     */
    static public function checkAdmin($user){
        if($user->id == '102'){
            return 'admin';
        }else{
            //返回该用户对应的机构id
            return $user->affiliation;
        }
    }

    static public function queue_ppush($data=array(),$db=3){
        $linkInfo = array(
            'host' => config('database.redis.default.host','127.0.0.1'),
            'port' => config('database.redis.default.port',6379),
            'database' => $db
        );

        $key = $data['stage'].'_TABLE_1';
        $redis = new Predis\Client($linkInfo);
        return $redis -> rpush( $key ,  serialize($data));
    }   

    static public function queue_lpush($key,$data=array(),$db=3){
        $linkInfo = array(
            'host' => config('database.redis.default.host','127.0.0.1'),
            'port' => config('database.redis.default.port',6379),
            'database' => $db
        );

        $redis = new Predis\Client($linkInfo);
        return $redis -> rpush( $key ,  serialize($data));
    }

    //二维数组去掉重复值 并保留键值
    static public function array_unique_fb($array2D)
    {
        foreach ($array2D as $k=>$v)
        {
            $val = join(",",$v);//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[$k] = $val;
        }
        $keyArr = [];
        foreach ($array2D[0] as $key => $val){
            $keyArr[] = $key;
        }
        $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v)
        {
            $array=explode(",",$v); //再将拆开的数组重新组装
            foreach ($keyArr as $key => $val){
                $temp2[$k][$val] =$array[$key];
            }
        }
        return $temp2;
    }

	//hash写入，不存在才写入
    static public function hsetnx($table,$key,$data=array(),$db=3){
		$redis = self::getRedisCluster();
    	try {
        	if(!empty($redis))
        	{
        		return $redis -> hsetnx($table , $key , serialize($data));
        	}
		}
    	catch (\RedisException $ex)
        {
        	self::$RedisCluster = null;
			\Log::error($ex);
        }
        return null;
    }

    
    static public function hset($table,$key,$data=array(),$db=3){
        $redis = self::getRedisCluster();
        try {
            if(!empty($redis))
            {
                return $redis -> hset($table , $key , serialize($data));
            }
        }
        catch (\RedisException $ex)
        {
            self::$RedisCluster = null;
            \Log::error($ex);
        }
        return null;
    }


    /**
     * @method 获取HASH值
     * @url
     * @access public
     * @author wanglifu <358630827@qq.com>
     * @date
     * @copyright
     */
    static public function hget($table,$key,$db=3){

        $redis = self::getRedisCluster();
        try {
        	if(!empty($redis))
        	{
        		return $redis->hget($table, $key);
        	}
        }
        catch (\RedisException $ex)
        {
        	self::$RedisCluster = null;
			\Log::error($ex);
        }
		return null;
    }


    static public function hdel($table,$key,$db=3){

    	$redis = self::getRedisCluster();
    	try {
	        
	        if(!empty($redis))
        	{
	        	return $redis -> hdel ( $table , $key ) ;
        	}
    	}
    	catch (\RedisException $ex)
        {
        	self::$RedisCluster = null;
			\Log::error($ex);
        }
        return null;
    }

    static public function zset($table,$key,$data=array(),$db=3){
        $redis = self::getRedisCluster();
        try {
            if(!empty($redis))
            {
                return $redis -> zAdd($table , $key , $data);
            }
        }
        catch (\RedisException $ex)
        {
            self::$RedisCluster = null;
            \Log::error($ex);
        }
        return null;
    }

    static public function zgetCount($table){
        $redis = self::getRedisCluster();
        try {
            if(!empty($redis))
            {
                return $redis -> zcard($table);
            }
        }
        catch (\RedisException $ex)
        {
            self::$RedisCluster = null;
            \Log::error($ex);
        }
        return null;
    }

    //判断表单是否重复提交 1是0否
    static public function IsRepeatSubmit(){
        $result=0;
		
    }
    
	static function getRedisCluster(){
    
		try {
	    	if(!self::$RedisCluster){
	            $sentinel_servers = config('system.sentinel_servers');
	            self::$RedisCluster = new \RedisCluster(NUll,$sentinel_servers,1,1);
	        } 
		}
		catch (\RedisClusterException $ex)
		{
			self::$RedisCluster = null;
			\Log::error($ex);
		}
		
		return self::$RedisCluster;
    }
    public static function albumExport($data,$title){
//        dd($data);
        Excel::create($title,function($excel) use ($data){
            $excel->sheet('sheet1', function($sheet) use ($data){
                $sheet->rows($data);
            });
            $excel->sheet('sheet2');
            $excel->sheet('sheet3');
        })->export('xls');
    }
    
    public static function newGroupFlag($agency_id){
        $agencyInfo = App\Entities\Agency\Agency::where('agency_id',$agency_id)->first();
        $agency_code = $agencyInfo->code;
        $group_flag = '';
        if ($agency_code == 'jjjty'){
            $group_flag = 'jujing_new';
        }
        if ($agency_code == 'lgd'){
            $group_flag = 'legudao_new';
        }
        if ($agency_code == 'srdg'){
            $group_flag = 'sirendinggu_new';
        }
        if ($agency_code == 'ttnb'){
            $group_flag = 'tiantianniubao_new';
        }
        return $group_flag;
    }	
    
    public static function success_message($data = [])
    {
        $data = array('code' => self::SUCCESS, 'message' => 'ok', 'data' => $data);
        exit(json_encode($data));
    }
    
    public static function error_message($message)
    {
        $data = array('code' => self::ERROR, 'message' => $message, 'data' => []);
        exit(json_encode($data));
    }

    /**
     * 弹出alert错误提示框
     * @param $message
     */
    public static function alert($message)
    {
        exit("<script>alert('{$message}');</script>");
    }
    
    /**红条
     * @param $data
     * @return bool
     */
    public static function headerMessage($data)
    {
        $key = "agency:hongtiao:new:";
        if (empty($data['member_id'])) {
            return false;
        }
        $time = time();
        while ($memberId = array_splice($data['member_id'], 0, 1000)) {
            foreach ($memberId as $value) {
                $content      = [
                    'content'  => $data['content'],
                    'add_time' => $time,
                ];
                
                $item['args'] = [
                    $value,
                    serialize($content),
                ];
                $item['call'] = 'hset';
                $rData[$key."{$value}"]  = $item;
            }
            RedisRepository::gpipe($rData);
        }
    }
}