@extends('layouts.base')
@section('meta')
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
@stop
@section('head_css')
    <style>
        .submitBnt{
            position:relative;
            display:block;
            width:100%;
            height:34px;
            border:1px solid #e5e6e7;
            font-family:'Microsoft yahei';
        }
        .submitBnt .btnMark{
            width:200px;
            height:26px;
            background:#64C8F6;/*#2E79D6*/
            text-align:center;
            line-height:26px;
            color:#fff;
            cursor:pointer;
            position:absolute;
            top:3px;
            left:10px;
            z-index:1;
        }
        .AddBtn{
            width:150px;
            height:26px;
            background:#64C8F6;/*#2E79D6*/
            text-align:center;
            line-height:26px;
            color:#fff;
            cursor:pointer;
            z-index:1;
        }
        .submitBnt:hover .btnMark{
            background:#11C2F8;
        }
        .submitBnt input{
            position:absolute;
            top:0;
            width:100%;
            display:block;
            opacity:0;
            z-index:2;
            cursor:pointer;
        }
        .control-label{
            text-align:right;
            padding-top:7px;
        }
    </style>
    <link href="{{asset('h+/css/bootstrap.min14ed.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/font-awesome.min93e3.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('h+/css/style.min862f.css')}}" rel="stylesheet">
    <link href="{{asset('common/css/bootstrapValidator.css')}}" rel="stylesheet">
    <link href="{{asset('common/css/common.css')}}" rel="stylesheet">
    <link href="{{asset('common/select2-4.0.0/css/select2.css')}}" rel="stylesheet">
@stop


@section('head_js')
    <script src="{{asset('h+/js/jquery.min.js')}}"></script>
    <script src="{{asset('h+/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/js/ajaxupload.js')}}"></script>
    <script src="{{asset('h+/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('h+/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/layer/layer.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/layer/laydate/laydate.js')}}"></script>
    <script src="{{asset('h+/js/hplus.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('h+/js/contabs.min.js')}}"></script>
    <script src="{{asset('h+/js/plugins/pace/pace.min.js')}}"></script>
    <script src="{{asset('common/js/bootstrapValidator.js')}}"></script>
    <script type="text/javascript" src="{{asset('common/select2-4.0.0/js/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('common/select2-4.0.0/js/i18n/zh-CN.js')}}"></script>
    <script src="{{asset('admin/js/app.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            $('.see').click(function(){
                layer.confirm($(this).attr('msg'), {
                    title:'警告',
                    btn: ['是']
                });
            })
          /*  if (window.frames.length == parent.frames.length || self == top) {
                window.location.href = '/';
            }*/
        })

    </script>
@stop