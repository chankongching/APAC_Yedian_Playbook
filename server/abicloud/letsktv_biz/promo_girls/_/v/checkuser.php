<?php (INAPP !== true) && die('Error !'); ?>

            <section id="checkuserstatus">
                <form>
                    <p class="input"><label><input name="phone" id="phone" placeholder="请输入您的手机号码" /></label></p>
                    <p class="button"><input id="send_yzm" type="button" value="获取验证码" /></p>
                    <p class="input"><label><input name="captcha" id="captcha" placeholder="在此填写验证码" /></label> </p>
                    <p class="button"><input type="submit" id="captchasubmit" value="提交" /></p>
                </form>
                <div class="checkuserstatus_error"></div>
            </section>
            <section id="checkuserinfo" style="display: none;">
                <form>
                    <div>
                        <h3>请确认您的个人信息</h3>
                        <p>姓　　名：<span id="checkname"></span></p>
                        <p>手机号码：<span id="checkphone"></span></p>
                        <p class="tips">如果信息与本人不符合，请立刻联系您的主管。</p>
                        <div class="checkuserinfo_error"></div>
                    </div>
                    <input type="submit" value="确认" />
                </form>
            </section>
            <section id="readytoclose" style="display: none;">
                <p>绑定成功，页面即将关闭。</p>
                <input type="submit" value="关闭" onclick="javascript:WeixinJSBridge.invoke('closeWindow',{},function(res){});" />
            </section>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#cancel').click(function(){
                        window.location.href = window.location.href;
                        return false;
                    });
                    $('#checkuserinfo form').submit(function(){
                        $.ajax({
                            url :"index.php?m=confirmuser",
                            type : "post",
                            dataType : "json",
                            success : function(response){
                                if(response.status == 1){
                                    $('#checkuserinfo').stop().hide();
                                    $('#readytoclose').stop().show();
                                    $('#readytoclose').stop().show(function(){
                                        setTimeout(function(){
                                            WeixinJSBridge.invoke('closeWindow',{},function(res){});
                                        }, 3000);
                                    });
                                } else {
                                    $('.checkuserinfo_error').stop().text(response.error).show(function(){
                                        setTimeout(function(){
                                            $('.checkuserinfo_error').stop().hide().text('');
                                        }, 2000);
                                    });
                                }
                            }
                        });
                        return false;
                    });
                    $('#checkuserstatus form').submit(function(){
                        var phone   = $.trim($("#phone").val());
                        var captcha = $.trim($("#captcha").val());
                        $.ajax({
                            url :"index.php?m=checkuser",
                            type : "post",
                            data : {
                                'phone'     : phone, 
                                'captcha'   : captcha
                            },
                            dataType : "json",
                            success : function(response){
                                if(response.status == 1){
                                    $('#checkuserstatus').hide();
                                    $('#checkname').text(response.data.name);
                                    $('#checkphone').text(response.data.phone);
                                    $('#checkuserinfo').show();
                                } else {
                                    $('.checkuserstatus_error').stop().text(response.error).show(function(){
                                        setTimeout(function(){
                                            $('.checkuserstatus_error').stop().hide().text('');
                                        }, 2000);
                                    });
                                }
                            }
                        });
                        return false;
                    });
                    $("#send_yzm").click(function(){
                        if($(this).hasClass("disabled")){
                            return false;
                        }
                        verifycode = "";
                        sendSMC();
                        return false;
                    });
                });
                function sendSMC(){
                    var phone = $.trim($("#phone").val());
                    if(!checkTel(phone)){
                        $('.checkuserstatus_error').stop().text('请填写正确的手机号').show(function(){
                            setTimeout(function(){
                                $('.checkuserstatus_error').stop().hide().text('');
                            }, 2000);
                        });
                        return false;
                    }
                    $.ajax({
                        url :"index.php?m=sendcaptcha",
                        type : "post",
                        data : {
                            'phone' : phone
                        },
                        dataType : "json",
                        success : function(response){
                            if(response.status == 1){
                                $("#phone").prop("readonly", true);
                                $("#send_yzm").prop("disabled", true);
                                countdown(120);
                            } else {
                                $('.checkuserstatus_error').stop().text(response.error).show(function(){
                                    setTimeout(function(){
                                        $('.checkuserstatus_error').stop().hide().text('');
                                    }, 2000);
                                });
                                if(typeof response.data == 'number') {
                                    $("#phone").prop("readonly", true);
                                    $("#send_yzm").prop("disabled", true);
                                    countdown(response.data);
                                }
                            }
                        }
                    });
                }
                function countdown(sec) {
                    $("#send_yzm").val("重新发送(" + sec + ")");
                    countdown_timeout = setTimeout(function(){
                        if(sec === 0){
                            clearTimeout(countdown_timeout);
                            $("#mobile").prop("readonly", false);
                            $("#send_yzm").prop("disabled", false).val("发送验证码");
                            return;
                        }
                        sec = sec - 1;
                        countdown(sec);
                    }, 1000);
                }
                function checkTel(value) {
                    var isMob=/^(13[0-9][0-9]{8}|15[0-9][0-9]{8}|17[0-9][0-9]{8}|18[0-9][0-9]{8})$/;
                    if(isMob.test(value)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            </script>