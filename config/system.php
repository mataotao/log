<?php
function C($key)
{
	return config ( 'system.'.$key );
}
return [
	'static_file_url' => 'http://static.guxiansheng.cn/',
	'upload_image_url' => 'http://'.$_SERVER['HTTP_HOST'],
	'page_num' => 10,//每页显示条数
	'tpl_type' =>[
		1=>'模板一（投顾专用）',
		2=>'模板二（其他机构专用）',
		3=>'模板三（个人类专用）'
	],
	/*益友汇价格*/
	'yiyouhui_jiage'=>'0.01',
	/*通用服务包价格*/
	'price'=>[
		'30'=>30,
		'90'=>90,
		'180'=>180,
		'365'=>365,
	],
    //对应关联的公众号1、罗军，2曾非 ，3周宇 4 唐郸 5 刘永平 6左毅...',
	'wechat_type'=>[
        '1'=>'luojun',
        '2'=>'zenfei',
        '3'=>'zhouyu',
        '4'=>'tangdan',
        '5'=>'liuyongping',
        '6'=>'zuoyi',
		/*....*/
	],

	'sentinel_servers'=>[
		'192.168.10.243:7000',
		'192.168.10.243:7001',
		'192.168.10.243:7002'
	],

	'redis_config'=>array('clusterclient'=>array(
										'prefix' => 'stocksir_',
										'type'=>'clusterclient',
										'seeds_nodes' =>array(  
											'192.168.10.243:7001',
											'192.168.10.243:7001',
											'192.168.10.243:7002',   
										),
										'master_groups'=>array(
											'5460' =>array(array('host'=>'192.168.10.243','port'=>7000,'pconnect'=>0),array('host'=>'192.168.10.243','port'=>7003,'pconnect'=>0)),
											'10922'=>array(array('host'=>'192.168.10.243','port'=>7001,'pconnect'=>0),array('host'=>'192.168.10.243','port'=>7004,'pconnect'=>0)),
											'16383'=>array(array('host'=>'192.168.10.243','port'=>7002,'pconnect'=>0),array('host'=>'192.168.10.243','port'=>7005,'pconnect'=>0))
										),
										'slaves'=>array(
											'5460'=>array(array('host'=>'192.168.10.243','port'=>7003,'pconnect'=>0,'db'=>0)),
											'10922'=>array(array('host'=>'192.168.10.243','port'=>7001,'pconnect'=>0,'db'=>0)),
											'16383'=>array(array('host'=>'192.168.10.243','port'=>7002,'pconnect'=>0,'db'=>0))
										),
									),
								),
    'inner_push_agency_service_id'=>[],
    'h5_jump_url_open'=>'https://h5.api.guxiansheng.cn/',
    'clb_api_url'=>'http://clb.api.guxiansheng.cn/index.php?',
	'is_identity'=>[
		0 => '未合规',
	    1 => '已合规',
	],

	'is_contract'=>[
		   0=> '未签订',
	       1=> '已签订',

	],

	'clb_api_guxiansheng_url'=>'http://clb.api.guxiansheng.cn/index.php?limit_type=2&member_id=1&key=&key_sxjg=5925a33441bca7147e587d62cceb753c4b4632dd7b5b194065b44928ac86679a!&',
	'seller_api_guxiansheng_url'=>'http://seller.api.guxiansheng.cn/index.php?limit_type=2&member_id=1&key=&key_sxjg=5925a33441bca7147e587d62cceb753c4b4632dd7b5b194065b44928ac86679a!&',
	'seller_combine_new_member_id'=>'1',
	'seller_combine_new_member_key'=>'',
	'seller_combine_new_key_sxjg'=>'5925a33441bca7147e587d62cceb753c4b4632dd7b5b194065b44928ac86679a!',
	'seller_combine_new_limit_type'=>'2',
    'tps_api_url'=>"http://tps.api.guxiansheng.cn/index.php?",
    'content_api_url' => 'http://content.api.guxiansheng.cn/index.php?',
    'third_token'=>"9da4fe1d789f1fcb4df20a7bead68115",
    //退款调用order key
    'order_key'=>"20170807third",
    //获取所有股票
    'stock_code_url'=>"https://static.guxiansheng.cn/hq_info.json",

    'content_api'    =>'http://content.api.guxiansheng.cn/index.php?c=best_combine&a=get_sanfang_zuhe_list&',
    
    'tcp_log'=>[
        'port'=>34501,
        'ip'=>'127.0.0.1',
    ],
];