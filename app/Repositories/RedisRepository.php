<?php

namespace App\Repositories;

use App\Jobs\SendReminderSms;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Messages\BaseMessage;
use Overtrue\Wechat\Staff;
use Anchu\Ftp\Facades\Ftp;
use Overtrue\Wechat\Broadcast;
use Qiniu\Auth;
use Queue;
use App;
use Illuminate\Http\Request;


class RedisRepository{

	static function gpipe($data)
	{
		$re = new \CachePredis();
		$result = $re->gpipe($data,'clusterclient');
		return $result;
	}
}