<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>Mr.Stock 管理后台</title>

    <meta name="keywords" content="Mr.Stock 管理后台">
    <meta name="description" content="Mr.Stock 管理后台">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link href="{{asset('h+/css/bootstrap.min14ed.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/font-awesome.min93e3.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/style.min862f.css')}}" rel="stylesheet">
    <style>
        .J_tabUpdate{
            right: 140px!important;
            width:60px!important;
        }
        .arrow{
            margin-right: -59px;
            right: 260px!important;
            margin-top: 0;
        }
        .J_tabBack{
            right: 200px!important;
            width:60px!important;
            margin-right: -59px;
        }
    </style>
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<?php
$function_arr =  Session::get('FunctionsList');
if(empty($function_arr)){
    $function_arr = array();
}
$errorsInfo = (array)$errors->getMessages();
if (!empty($errorsInfo)) {
    //array_shift() 删除数组中第一个元素，并返回被删除元素的值
    $errorsInfo = array_shift($errorsInfo);
}
?>

{{--循环开始--}}
@forelse($errorsInfo as $errorItem)
    <div class="pnotice" style="border: #ad0051 2px solid;border:#ebccd1 1px solid;display: none;">
        <div class="" style="background-color: #f2dede;">
            <div style="float: left;" style="color: #a94442;">{{$errorItem}}</div>
            <div style="float:right;margin-right: 2px;cursor: pointer;" class="closeNotice">&nbsp;X&nbsp;</div>
            <div style="clear: both;"></div>
        </div>
    </div>
@empty
@endforelse
{{--循环结束--}}
    <div id="wrapper">
    <div class="pnotice"  style="color: #a94442;display: none;"  id="NoReplay">0</div>
        <?php
        
        $errorsInfo =(array)$errors->getMessages();
        if(!empty($errorsInfo)){
            $errorsInfo = array_shift($errorsInfo);
        }
        ?>

        @forelse($errorsInfo as $errorItem)
        <div class="pnotice" style="display: none;">{{$errorItem}}</div>
        @empty
        @endforelse

        <!--左侧导航开始-->
        @include('layouts/left')
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0; display: none">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

                    </div>
                </nav>
            </div>
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="/" class="active J_menuTab">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight arrow"><i class="fa fa-forward"></i>
                </button>
                {{--<button class="roll-nav roll-right J_tabUpdate"><i class="fa fa-refresh"></i> 刷新</button>--}}
                <button href="javascript:history.back(-1)" class="roll-nav roll-right J_tabBack" onclick="javascript :history.back(-1);"><i class="fa fa-reply"></i> 返回</button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="{{ url('/logout') }}" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                @if(@$is_edit  ==  1)
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="{{ route('Agency.AgencyDetail') }}" frameborder="0" seamless></iframe>
                @else
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="{{ route('Index.Index') }}" frameborder="0" seamless></iframe>
                @endif

            </div>
            <div class="footer">
                <div class="pull-right">&copy; 2016-2020 <a href="javascript:void(0)" target="_blank">tangjun</a>
                </div>
            </div>
        </div>
        <!--右侧部分结束-->

        <!--右侧边栏结束-->

    </div>
    <script src="{{asset('h+/js/jquery.min.js')}}"></script>
    <script src="{{asset('h+/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('h+/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/layer/layer.min.js')}}"></script>
    <script src="{{asset('h+/js/hplus.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('h+/js/contabs.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/pace/pace.min.js')}}"></script>
    <div id="pop" style="display:none;">
        <style type="text/css">
            *{margin:0;padding:0;}
            #pop{background:#fff;width:260px;border:1px solid ;font-size:12px;position: fixed;right:10px;bottom:10px;}
            #popHead{line-height:32px;background:#f6f0f3;border-bottom:1px solid #e0e0e0;position:relative;font-size:12px;padding:0 0 0 10px;height: 32px}
            #popHead h2{font-size:14px;color:#666;line-height:32px;height:32px;margin-top: 0}
            #popHead #popClose{position:absolute;right:10px;top:1px;z-index: 100000}
            #popHead a#popClose:hover{color:#f00;cursor:pointer;}
            #popContent{padding:5px 10px;height: 100px;}
            #popTitle a{line-height:24px;font-size:14px;font-family:'微软雅黑';color:#333;font-weight:bold;text-decoration:none;}
            #popTitle a:hover{color:#f60;}
            #popIntro{text-indent:24px;line-height:160%;margin:5px 0;color:#666;}
            #popMore{text-align:right;border-top:1px dotted #ccc;line-height:24px;margin:8px 0 0 0;}
            #popMore a{color:#f60;}
            #popMore a:hover{color:#f00;}
        </style>
        <div id="popHead">
            <a id="popClose" title="关闭">关闭</a>
            <h2 style="color: #00a0e9">温馨提示</h2>
        </div>
        <div id="popContent">
            <dl>
                <dd id="popIntro" style="color: #1a1a1a"></dd>
            </dl>
            <p id="popMore">
            <a  class="J_menuItem" href="{{ route('AgencyQuestions.index') }}">查看 »</a></p>
        </div>
    </div>


</body>
<script>
    window.onload= function NoReplay()
    {  
      //>>判断多少条数据未处理
          @if(in_array('AgencyQuestions.getDetailsById',$function_arr))
            $(document).ready(function () {
                var old = $("#NoReplay").text();
                var str = '暂无数据';

                $.ajax({
                    type: "get",
                    url: "{{route('AgencyQuestions.getCountNoReplay')}}",
                    dataType: "json",
                    success: function (data) {
                        if (data["NoReplay"] == 0) {
                            $("#NoReplay").css('display', 'none');
                        } else {
                            if (old != data["NoReplay"]) {
                                str = '您好,你现在有' + '<span style="color: red">' + data["NoReplay"] + '</span>' + '条数据未处理,赶紧去回复吧！',
                                        $("#popIntro").append(str);
                                $("#pop").css('display', 'block');

                            }

                        }
                    }
                });
            });
          @endif
   }  
   $(function(){
   /* if("{{ @$userInfo->agency_code }}"=="srdg"){
        //获取当前用户所属机构（只有私人定股有这个需求）
        setInterval('NoReplay()',5000);//轮询执行，3s一次
        //end
    }*/

        //错误提示
        var msg = $('.pnotice').text();
        if(msg==''){
            $("#passwdTip").css('display','none');
            return;
        }else{
            $("#passwdTip").css('display','block');
        }

    })


    $(function(){
       $('#popClose').click(function(){

           $("#pop").css('display','none');
       })

    })



    $(function(){
        if("{{ @$userInfo->agency_code }}"=="wlzx"){
            wlzxNoReplay();
        }
        //未来之星的提示
        function wlzxNoReplay(){
            var old = $("#NoReplay").text();
            var str = '暂无数据';
            $.ajax({
                type: "get",
                url: "{{route('wlzx.questions.getCountNoReplay')}}",
                dataType: "json",
                success: function (data) {
                    if (data["NoReplay"] == 0) {
                        $("#NoReplay").css('display', 'none');
                    } else {
                        if (old != data["NoReplay"]) {
                            str = '您好,你现在有' + '<span style="color: red">' + data["NoReplay"] + '</span>' + '条数据未处理,赶紧去提问管理回复吧！',
                                    $("#popIntro").append(str);
                            $("#pop").css('display', 'block');
                            $('#popMore').find('.J_menuItem').hide();

                        }

                    }
                }
            });
        }
        
        $('.J_tabUpdate').click(function () {
            $('iframe').each(function () {
                var _this = $(this);
//                console.log(_this.prop('src'));
                if(_this.css("display") != 'none'){
                    _this.attr('src', _this.attr('src'));
                }
            })

        })
        
    })

</script>

<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:17:11 GMT -->
</html>
