
/**
 * Created by Administrator on 2015/12/15 0015.
 */
var pars;
$(function(){
    pars = JSON.parse(($("#parameter").val()).split("'").join('"'));
    switch(pars.pagename){
        case "Pay_video":Pay_video();break;
    }

});


function Pay_video(){
    /**
     *角色权限管理弹出框处理
     *吴冷眉
     *QQ：2632840780
     *2015-12-15
     *update：wulengmei（2015-12-15 17:25） （最近更新/更改 作者及时间）
     **/
    $(function(){
        var currentpage = $('.opera').attr('currentpage');
        function  choice_from(){
            $("#add_role").click(function(){

                $("#Form1").show();
                $("#Form2").hide();
            });
            $(".edit_role").click(function(){

                $("#edit_id").val($(this).parent().siblings(".open-id").text());
                $("#edit_name").val($(this).parent().siblings(".role_name").text());
                $("#edit_des").val($(this).parent().siblings(".role_descrip").text())

                $("#Form2").show();
                $("#Form1").hide();
            })
        }
        function  delete_user(){
            $('.delete').click(function(){
                var id = $(this).attr('data');
                var delete_url = $(this).attr('url');
                layer.confirm('确认删除？', {
                    title:'删除',
                    btn: ['是','否']
                }, function(){
                    window.location.href=delete_url+'?id='+id+'&page='+currentpage;
                });
            });
        }

        //上架
        function onbord(){
            $('.onbord').click(function(){
                var _this = $(this);
                var type = '';
                var msg = '';
                if(_this.attr('data') == undefined){
                    var arr = '';

                    msg = '确定要下架该视频么？下架后将无法在前台展示';
                }else {

                    var arr = parseInt(_this.attr('data'));
                    type = '&type=one';
                    msg = '确定要重新上架该视频么？上架后将在前台展示';
                }

                if (arr == undefined || arr == '') {
                    $('input[type=checkbox]').each(function (i) {
                        if ($(this).is(":checked")) {
                            if ($(this).attr('class') != 'all') {
                                arr += $(this).val() + ',';
                            }
                        }
                    });
                }


                if(arr) {
                    layer.confirm(msg, {
                        title: '提示',
                        btn: ['确定', '取消']
                    }, function () {


                        if (arr) {
                            window.location.href = _this.attr('url') + '?idarr=' + arr + type+'&page='+currentpage;
                        }
                    });

                }else{
                    layer.alert('没有选择视频');
                }

            });
        }

        ////上架
        function top(){


            $('.top').click(function(){
                alert(currentpage);
                if($(this).attr('data')){
                    window.location.href=$(this).attr('url')+'?id='+$(this).attr('id')+'&page='+currentpage;
                }
            });
        }

        //下架
        function downbord(){
            //var arr = '';
            $('.downbord').click(function(){
                var url = $(this).attr('url');
                var id = $(this).attr('data');
                layer.confirm('确定要下架该视频么？下架后将无法在前台展示', {
                    btn: ['确定','取消']
                }, function(){
                    window.location.href=url+'?id='+id+'&page='+currentpage;
                });
            });
        }
        //导出
        function exportVideo(){
            var arr = '';
            $('.exportVideo').click(function(){
                $('input[type=checkbox]').each(function(i){
                    if($(this).is(":checked")){
                        if($(this).attr('class') !== 'all'){
                            arr += $(this).val()+',';
                        }
                    }
                });
                var dataStr = '';

                var question_user_name               = $(this).attr('data1');
                var question_user_phone            = $(this).attr('data2');
                var question_user_qq				= $(this).attr('data3');
                var question_user_question			= $(this).attr('data4');
                var created_at			= $(this).attr('data5');
                dataStr = 'question_user_name='+question_user_name+'|'+'question_user_phone='+question_user_phone+'|'+'question_user_qq='+question_user_qq+'|'+'question_user_question='+question_user_question+'|'+'created_at='+created_at;
				window.location.href=$(this).attr('url')+'?idarr='+arr+'&data='+dataStr;

            });
        }
        //显示查看窗口
        function showVideo(){
            $('.showVideo').click(function(){
                $('#Form1').show();
            });
        }
        //修改视频信息
        function edit_role(){
            $(".edit_role").click(function(){
                var Form2 = $('#Form2');
                $('#Form1').css('display','none');
                var id = $(this).attr('data');

                //替换获取用户信息的id
                var info_url = $(this).attr('url')+"?id="+id;
                if(id){
                    $.ajax({
                        type:'POST',
                        url: info_url,
                        dataType:'json',
                        success: function(res){
                            var data = res.data;
                            $('#Form2').find('input[name=title]').val(data.title);
                            $('#Form2').find('input[name=subtitle]').val(data.subtitle);

                            //$('#Form2').find('input[name=length_time]').val(data.length_time);
                            $('#Form2').find('input[name=type]').val(data.type);
                            $('#Form2').find('input[name=teacher]').val(data.teacher);
                            $('#Form2').find('textarea[name=album]').html(data.album);
                            $('#Form2').find('input[name=puqing_video]').val(data.puqing_video);
                            $('#Form2').find('input[name=gaoqing_video]').val(data.gaoqing_video);
                            $('#Form2').find('input[name=chaoqing_video]').val(data.chaoqing_video);
                            $('#Form2').find('textarea[name=Intro]').html(data.Intro);
                            $('#Form2').find('input[name=status]').val(data.status);
                            $('#Form2').find('input[name=img]').val(data.img);
                            $('#Form2').find('img').attr('src',"{{ config('system.static_file_url') }}"+data.img);
                        }
                    });
                }



                //替换更新的id
                var str_url = Form2.attr('action')
                var update_url = str_url.replace(/user\/update\/\d{1,}/ig,"user/update/"+id);
                Form2.attr('action',update_url);

                //准备dom
                //setTimeout(function(){
                //    Form2.data('bootstrapValidator').resetForm();
                //},500);

                Form2.show('slow');
            })
        }
        exportVideo();
    });
}