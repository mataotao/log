{{--引入css和js--}}
@extends('layouts.usermanage')
@include('UEditor::head')
{{--css区域开始--}}
@section('only_css')
    <style>
        .btn-success {
            background-color: #16beb0 !important;
            border-color: #16beb0 !important;
        }

        .modal-title {
            color: #676a6c !important;
        }

        tbody td .state1 {
            color: #1ab394 !important;
        }

        body {
            font-family: 微软雅黑;
            font-size: 14px;
        }

        .table > tbody > tr > td {
            font-size: 14px;
        }

        .table > tbody > tr > td > span, .table > tbody > tr > td > a {
            font-size: 8px;
        }

        .mybox {
            text-align: center;
            padding: 20px;
        }

        .mybox img {
            max-width: 80%;
        }

        .tag_css{
            border : 1px solid red;
        }
    </style>
@stop
{{--css区域结束--}}

{{--js区域开始--}}
@section('footer_js')
    <script>
        function ch(){
            var k = $("#se").val();
            var href = window.location.href;

            if(href.indexOf("?")==-1){
                window.location.href = href+"?type="+k;
            }else{
                window.location.href = href+"&type="+k;
            }



        }
        $("#se").on('change',ch);

        function ch2(){
            var k = $("#site").val();
            var href = window.location.href;
            if(href.indexOf("?")==-1){
                window.location.href = href+"?site="+k;
            }else{
                window.location.href = href+"&site="+k;
            }

        }
        $("#site").on('change',ch2);
    </script>
@stop
{{--js区域结束--}}

{{--内容主体区域开始--}}
@section('body')


    <input type="hidden" id="parameter" value="{'pagename':'rolemanage'}"/>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>log</h5><br>
                <a href="{{route('home.viewLog')}}" target="_blank" class="btn btn-success" style="margin: 0 0 0 -25px">storage</a>
            </div>
            <div class="ibox-content">
                <div class="form-group">
                    <label style="padding: 5px">站点:</label>
                    <select name="channel" class="input-sm form-control input-s-sm inline" style="padding: 0 9px" id="site">
                        @foreach(\App\Http\Controllers\LogViewController::ARRAY_SITE as $k=>$value)
                            <option @if($site==$k) selected="selected" @endif value="{{$k}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <label style="padding: 5px">日志类型:</label>
                    <select name="channel" class="input-sm form-control input-s-sm inline" style="padding: 0 9px" id="se">
                        @foreach(\App\Http\Controllers\LogViewController::ARRAY_SUCCESS_ERROR as $k=>$value)
                        <option @if($type==$k) selected="selected" @endif value="{{$k}}">{{$value}}</option>
                        @endforeach

                    </select>
                </div>
                <form role="form" class="form-inline" method="get" action="{{route('log.search')}}">
                    <div class="form-group">
                        <label for="exampleInputEmail2" style="padding: 5px">搜索关键字：</label>
                        <input type="text" name="content" class="form-control" value="{{@$formData['content']}}" placeholder="请输入搜索的关键字">
                    </div>
                    <div class="form-group">
                        <label style="padding: 5px">搜索时间:</label>
                        <input type="text" id="asc_time" name="start" value="{{@$formData['start']}}"
                               class="form-control layer-date"
                               onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
                        &nbsp;-&nbsp;
                        <input type="text" id="asc_time2" name="end" value="{{@$formData['end']}}"
                               class="form-control layer-date"
                               onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
                    </div>
                    <input type="hidden" name="dirType" value="{{$type}}">
                    <input type="hidden" name="site" value="{{$site}}">
                    <button type="submit" class="btn btn-sm btn-primary"
                            style="background:#16BEB0;border:1px solid #16BEB0;margin-left:20px " id="search">搜索
                    </button>
                </form>
            </div>
            {{--列表区域开始--}}
            <form id="listForm">
                <div class="container-fluid ibox-content">
                    <table class="table table-striped" id="table-striped">
                        <thead>
                        <tr>
                            <th>文件名</th>
                            <th>文件路径</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(!empty($arr))
                            @foreach($arr as $key => $val)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$val['name']}}</td>
                                    <td>
                                        <a href="{{route('log.view',['name'=>$val['name']])}}" target="_blank">预览</a>
                                        <a href="{{route('log.load',['name'=>$val['name']])}}" target="_blank">下载</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>
                    <div class="pull-left">
                    </div>
                    {{--  <div class="btn-group pull-right" style="margin-top: 20px">
                          <form  action="{{ route('ServiceClass.serviceClassIndex') }}" method="get">
                           &ensp;跳至&ensp;<input  style="width:50px;height: 28px" type="text" name="page" class=""onkeyup="this.value=this.value.replace(/\D/g,'')" value=@if(!empty($_GET['page'])){{$_GET['page']}} @else 1 @endif> 页&ensp;<button class="btn pull-right" style="height: 30px" type="submit">跳转</button></form>
                       </div>--}}
                    <div class="btn-group pull-right">
                    </div>
                </div>
            </form>

            {{--列表区域结束--}}

        </div>
    </div>
@stop
