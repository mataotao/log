/**
 * Created by Administrator on 2015/12/15 0015.
 */
var pars;
$(function(){
    pars = JSON.parse(($("#parameter").val()).split("'").join('"'));
    switch(pars.pagename){
        case "rolemanage_detail":rolemanage_detail();break; //rolemanage_detailҳ��
        case "rolemanage":rolemanage();break; //rolemanageҳ��
    }

});

function rolemanage_detail(){
    /**
     *角色权限管理（设置权限）checkbox选择处理
     *曾洁
     *QQ：283020075
     *2015-12-15
	update：zengjie（2015-12-22 10:56） （最近更新/更改 作者及时间）     **/
    $(function(){
        var $check_label=$(".check_label");
        var $btn_padding=$(".btn_padding");
        var $function_btn_padding = $('.functions').children('.btn_padding');
        $check_label.click(function(){
            var _self = $(this);
            check_label_click(this,function(){
                setTimeout(function(){
                    //console.log(_self.nextAll('.ibox-content').children('.btn_padding'));
                    _self.nextAll('.ibox-content').children('.btn_padding').each(function(){
                        //console.log('-------------',$(this).hasClass('btn-default2'));
                        btn_padding_click_two(this);
                    })
                },3);
            });
        });

        $btn_padding.click(function(){
            btn_padding_click(this);
        });
        $function_btn_padding.click(function () {
            function_btn_padding_click(this);
        });

        function function_btn_padding_click(obj) {
            var hidevalue1= $(obj).parent().prev("button").attr("hidevalue");
            var thisvalue1=$(obj).parents(".ibox-content").siblings(".check_label").attr("hidevalue");
            if($(obj).hasClass("btn_focus")){
                $(obj).parent().prev("button").addClass("btn_focus");
                $(obj).parent().prev("button").before("<input type='hidden' name='permission_id[]' value='"+hidevalue1+"'>");
                $(obj).parents(".ibox-content").siblings(".check_label").children(".check_icon").addClass("check").after("<input type='hidden' name='permission_id[]' value=''>");
                $(obj).parents(".ibox-content").siblings(".check_label").children(".check_icon").next("input").attr("value",thisvalue1);
            }
        }

        function btn_padding_click(obj){
            //console.log(obj);
            //console.log(obj.className);
            var hidevalue= $(obj).attr("hidevalue");
            var hidevalue1= $(obj).parent().prev("button").attr("hidevalue");
            var thisvalue=$(obj).parent(".ibox-content").siblings(".check_label").attr("hidevalue");
            var thisvalue1=$(obj).parents(".ibox-content").siblings(".check_label").attr("hidevalue");
            //console.log($(obj).prop('class'));
            //console.log($(obj).attr('hidevalue'));
            // console.log($(obj).hasClass("btn_focus"));
            if($(obj).hasClass("btn_focus")){
                var flag = 0;
                $(obj).parent().children("button").each(function () {
                        if($(this).hasClass("btn_focus")) {
                            flag += 1;
                        }
                    });
                if(flag == 1){
                    $(obj).parent().prev("input").prev('button').removeClass("btn_focus").addClass("btn-default2");
                    $(obj).parent().prev('button').removeClass("btn_focus").addClass("btn-default2");
                    $(obj).parent().prev('button').prev("input").remove();
                    $(obj).parent().prev("input").remove();
                    var f = 0;
                    $(obj).parent().parent().children("button").each(function () {
                        if($(this).hasClass("btn_focus")) {
                            f += 1;
                        }
                    });
                    if(f === 0){
                        $(obj).parents(".ibox-content").siblings(".check_label").children(".check_icon").removeClass("check").next("input").remove();
                    }
                }

                $(obj).next('.functions').find('.btn_padding').prev("input").remove();
                $(obj).next('.functions').find('.btn_padding').removeClass("btn_focus").addClass("btn-default2");
                $(obj).removeClass("btn_focus");
                $(obj).addClass("btn-default2");
                $(obj).attr("checked",false);
                $(obj).prev("input").remove();
                if($(obj).parent(".ibox-content").children("button").hasClass("btn_focus")){
                    return false;
                }else{
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").removeClass("check").next("input").remove();
                }
            }else{
                $(obj).parent().prev("button").addClass("btn_focus");
                $(obj).parent().prev("button").before("<input type='hidden' name='permission_id[]' value='"+hidevalue1+"'>");
                $(obj).parents(".ibox-content").siblings(".check_label").children(".check_icon").addClass("check").after("<input type='hidden' name='permission_id[]' value=''>");
                $(obj).parents(".ibox-content").siblings(".check_label").children(".check_icon").next("input").attr("value",thisvalue1);

                /*$(obj).next('.functions').find('.btn_padding').addClass("btn_focus");
                $(obj).next('.functions').find('.btn_padding').each(function(){
                    $(this).before("<input type='hidden' name='permission_id[]' value='"+$(this).attr('hidevalue')+"'>");
                });*/
                $(obj).addClass("btn_focus");
                $(obj).removeClass("btn-default2");
                $(obj).attr("checked",true);
                $(obj).before("<input type='hidden' name='permission_id[]' value=''>");
                $(obj).prev("input").attr("value",hidevalue);
                if($(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").next("input").size() == "0"){
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").addClass("check").after("<input type='hidden' name='permission_id[]' value=''>");
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").next("input").attr("value",thisvalue);
                }
            }
        }

        function btn_padding_click_two(obj){
            //console.log(obj);
            //console.log(obj.className);
            var hidevalue= $(obj).attr("hidevalue");
            var thisvalue=$(obj).parent(".ibox-content").siblings(".check_label").attr("hidevalue");
            //console.log($(obj).prop('class'));
            //console.log($(obj).attr('hidevalue'));
            // console.log($(obj).hasClass("btn_focus"));
            if(! $(obj).hasClass("btn_focus")){
                $(obj).next('.functions').find('.btn_padding').prev("input").remove();
                $(obj).next('.functions').find('.btn_padding').removeClass("btn_focus");
                $(obj).removeClass("btn_focus");
                $(obj).addClass("btn-default2");
                $(obj).attr("checked",false);
                $(obj).prev("input").remove();
                if($(obj).parent(".ibox-content").children("button").hasClass("btn_focus")){
                    return false;
                }else{
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").removeClass("check").next("input").remove();
                }
            }else{
                $(obj).next('.functions').find('.btn_padding').addClass("btn_focus");
                $(obj).next('.functions').find('.btn_padding').each(function(){
                    $(this).before("<input type='hidden' name='permission_id[]' value='"+$(this).attr('hidevalue')+"'>");
                });
                $(obj).addClass("btn_focus");
                $(obj).removeClass("btn-default2");
                $(obj).attr("checked",true);
                $(obj).before("<input type='hidden' name='permission_id[]' value=''>");
                $(obj).prev("input").attr("value",hidevalue);
                if($(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").next("input").size() == "0"){
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").addClass("check").after("<input type='hidden' name='permission_id[]' value=''>");
                    $(obj).parent(".ibox-content").siblings(".check_label").children(".check_icon").next("input").attr("value",thisvalue);
                }
            }
        }
        function check_label_click(obj,callback){
            var hidevalue;
            var thisvalue=$(obj).attr("hidevalue");
            $(this).siblings(".ibox-content").children("button").each(function(){
                hidevalue= $(obj).attr("hidevalue");
                if($(obj).next("input").size()=="0"){
                    $(obj).before("<input type='hidden' name='permission_id[]' value=''>");
                    $(obj).next("input").attr("value",hidevalue);
                }
            });
            if($(obj).children(".check_icon").hasClass("check")){
                $(obj).children(".check_icon").removeClass("check");
                $(obj).children(".check_icon").next("input").remove();
                $(obj).siblings(".ibox-content").find("button").attr("checked",false);
                $(obj).siblings(".ibox-content").find("button").removeClass("btn_focus");
                $(obj).siblings(".ibox-content").find("button").addClass("btn-default2");
                $(obj).siblings(".ibox-content").find("input").remove();
            }else{
                $(obj).children(".check_icon").addClass("check");
                $(obj).children(".check_icon").after("<input type='hidden' name='permission_id[]' value=''>");
                $(obj).children(".check_icon").next("input").attr("value",thisvalue);
                $(obj).siblings(".ibox-content").find("button").attr("checked",true);
                $(obj).siblings(".ibox-content").find("button").addClass("btn_focus");
                $(obj).siblings(".ibox-content").find("button").removeClass("btn-default2");
            }
            typeof callback === 'function' && callback.call(this);
        }
        //保存提交
        $("#saveForm").click(function(){
            $("#authForm").submit();
        });
    })
}

function rolemanage(){
    /**
     *角色权限管理弹出框处理
     *吴冷眉
     *QQ：2632840780
     *2015-12-15
     *update：wulengmei（2015-12-15 17:25） （最近更新/更改 作者及时间）
     **/
    $(function(){
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
                layer.confirm('确认删除？', {
                    title:'删除',
                    btn: ['是','否'] //��ť
                }, function(){
                    window.location.href="/auth/delete-role?id="+id;
                });
            });
        }
        choice_from();
        delete_user();
        $('#Form1').delegate('#sure','click', function () {
            $("#Form1").submit();
        });
        $('#Form2').delegate('#sure-notice','click', function () {
            $("#Form2").submit();
        });
        //$('#Form1').bootstrapValidator({
        //    message: 'This value is not valid',
        //    feedbackIcons: {/*输入框不同状态，显示图片的样式*/
        //        valid: 'glyphicon glyphicon-ok',
        //        invalid: 'glyphicon glyphicon-remove',
        //        validating: 'glyphicon glyphicon-refresh'
        //    },
        //    fields: {/*验证*/
        //        name: {/*键名username和input name值对应*/
        //            message: 'The username is not valid',
        //            validators: {
        //                notEmpty: {/*非空提示*/
        //                    message: '用户名不能为空'
        //                }
        //            }
        //        },
        //        description: {
        //            validators: {
        //                notEmpty: {
        //                    /*非空提示*/
        //                    message: '地址不能为空'
        //                }
        //            }
        //        }
        //    }
        //});
    });
}