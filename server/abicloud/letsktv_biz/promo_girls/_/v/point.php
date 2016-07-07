<?php (INAPP !== true) && die('Error !'); ?>
<?php
switch($a) {
    case 'list':
?>
            <section id="list">
                <header>
                    <div></div>
                    <h1>积分兑换</h1>
                    <p>当前积分<?php echo $user_qr['point'];?></p>
                </header>
                <ul id="items">
<?php
foreach($gifts as $k=>$gift) {
?>
                    <li>
                        <a href="index.php?m=point&a=detail&id=<?php echo $k;?>">
                            <div style="background-image: url(<?php echo $gift['img']['small'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;"></div>
                            <h4><?php echo $gift['name'];?></h4>
                            <p>
                                <?php echo $gift['point'];?>积分
                            </p>
                        </a>
                    </li>
<?php
}
?>
<!--
<?php
foreach($gifts as $k=>$gift) {
?>
                    <li>
                        <a href="index.php?m=point&a=detail&id=<?php echo $k;?>">
                            <div style="background-image: url(<?php echo $gift['img']['small'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;"></div>
                            <h4><?php echo $gift['name'];?></h4>
                            <p>
                                <?php echo $gift['point'];?>积分
                            </p>
                        </a>
                    </li>
<?php
}
?>
<?php
foreach($gifts as $k=>$gift) {
?>
                    <li>
                        <a href="index.php?m=point&a=detail&id=<?php echo $k;?>">
                            <div style="background-image: url(<?php echo $gift['img']['small'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;"></div>
                            <h4><?php echo $gift['name'];?></h4>
                            <p>
                                <?php echo $gift['point'];?>积分
                            </p>
                        </a>
                    </li>
<?php
}
?>
<?php
foreach($gifts as $k=>$gift) {
?>
                    <li>
                        <a href="index.php?m=point&a=detail&id=<?php echo $k;?>">
                            <div style="background-image: url(<?php echo $gift['img']['small'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;"></div>
                            <h4><?php echo $gift['name'];?></h4>
                            <p>
                                <?php echo $gift['point'];?>积分
                            </p>
                        </a>
                    </li>
<?php
}
?>
-->
                </ul>
            </section>
<?php
    break;
    case 'detail':
?>
            <section id="detail">
                <header>
                    <div style="height: 100%;background-image: url(<?php echo $gifts[$id]['img']['big'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;"></div>
                </header>
                <div id="content">
                    <h1><?php echo $gifts[$id]['name']?></h1>
                    <span>价值<?php echo $gifts[$id]['costs']?>元</span>
                    <p><?php echo $gifts[$id]['detail']?></p>
                </div>
                <form id="action" action="index.php?m=point&a=confirm" method="post">
                    <input type="hidden" name="id" id="id" value="<?php echo $id?>" />
                    <span>
                        兑换数量
                        <input type="number" <?php echo $disabled; ?> value="1" id="quantity" name="quantity" />
                    </span>
                    <span>
                        需要积分
                        <input type="text" readonly="" id="costs" disabled="" data-point="<?php echo $gifts[$id]['point']?>" value="<?php echo $gifts[$id]['point']?>" />
                    </span>
                    <input type="submit" class="submit <?php echo $disabled; ?>" <?php echo $disabled; ?> name="submit" id="submit" value="兑换" />
                </form>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#submit').click(function(check){
                            var id = $('#id').val();
                            var quantity = $('#quantity').val();
                            var r = /^\+?[1-9][0-9]*$/;
                            if(!r.test(quantity)) {
                                alert('请输入正确的数量');
                                $('#quantity').focus();
                                return false;
                            }
                            $.ajax({
                                url :"index.php?m=point&a=confirm&check",
                                type : "post",
                                async : false, 
                                data : {
                                    'id' : id, 
                                    'quantity' : quantity
                                },
                                dataType : "json",
                                success : function(response){
                                    if(response.status == 0) {
                                        alert(response.error);
                                        check.preventDefault();
                                        return false;
                                    } else {
                                        $('#action').submit();
                                    }
                                }, 
                                error : function(response){
                                    check.preventDefault();
                                    return false;
                                }
                            });
                        });
                        $('#quantity').change(function(){
                            $('#costs').val($('#quantity').val() * $('#costs').attr('data-point'));
                        });
                    });
                </script>
            </section>
<?php
    break;
    case 'confirm':
?>
            <section id="confirm">
                <div id="info">
                    <div style="background-image: url(<?php echo $gifts[$id]['img']['big'];?>);background-repeat: no-repeat;background-size: cover;background-color: transparent;">
                    </div>
                    <div>
                        <h4><?php echo $gifts[$id]['name']?></h4>
                        <p>消费积分：<?php echo $quantity * $gifts[$id]['point']?></p>
                    </div>
                </div>
                <form id="submit" action="index.php?m=point&a=submit" method="post">
                    <input type="hidden" name="id" value="<?php echo $id?>" />
                    <input type="hidden" name="quantity" value="<?php echo $quantity?>" />
                    <h3>您的手机号码是：<?php echo $phone?></h3>
                    <span>
                        <input type="number" name="captcha" id="captchaval" class="captcha" placeholder="输入验证码" />
                    </span>
                    <span>
                        <input type="button" id="captcha" value="获取验证码" />
                    </span>
                    <input type="submit" class="submit" name="submit" id="submit_btn" value="提交" />
                </form>
                <style>
                #tip{position: fixed;width: 60%;margin-left: 10%;height: 200px;top:100px;left: 0;background: black;text-align: center;border-radius: 20px;padding: 10%;}
                #tip #closepage{border: 0;padding: 0;margin: 0;margin-top: 20px;padding:10px 30px;background: white;color: black;border-radius: 10px;}
                </style>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#submit').submit(function(){
                            $("#submit_btn").prop("disabled", true);
                            $.ajax({
                                url :"index.php?m=point&a=submit",
                                type : "post",
                                data : {
                                    'id' : <?php echo $id?>, 
                                    'quantity' : <?php echo $quantity?>, 
                                    'captcha' : $('#captchaval').val()
                                },
                                dataType : "json",
                                success : function(response){
                                    if(response.status == 1) {
                                        var html = '<div id="tip">'+response.error+'<br /> <button id="closepage">关闭</button></div> ';
                                        $('body').append(html);
                                        $("#closepage").on('click',function(){
                                            WeixinJSBridge.invoke('closeWindow',{},function(res){});
                                        });
                                        // var st = setTimeout(function () {
                                        //     $('#tip').remove();
                                        //     clearTimeout(st);
                                        // },2000);
                                        // alert(response.error);
                                        // setTimeout(function(){
                                        //     WeixinJSBridge.invoke('closeWindow',{},function(res){});
                                        // }, 3000);
                                    } else {
                                        $("#submit_btn").prop("disabled", false);
                                        alert(response.error);
                                    }
                                }, 
                                error : function(response){
                                    
                                }
                            });
                            return false;
                        });
                        $('#captcha').click(function(){
                            $.ajax({
                                url :"index.php?m=point&a=sendcaptcha",
                                data : {
                                    'phone' : <?php echo $phone?>
                                },
                                dataType : "json",
                                success : function(response){
                                    if(response.status == 1){
                                        $("#captcha").prop("disabled", true);
                                        countdown(120);
                                    } else {
                                        alert(response.error);
                                    }
                                }
                            });
                        });
                    });
                    function countdown(sec) {
                        $("#captcha").val("重新发送(" + sec + ")");
                        countdown_timeout = setTimeout(function(){
                            if(sec === 0){
                                clearTimeout(countdown_timeout);
                                $("#captcha").prop("disabled", false).val("发送验证码");
                                return;
                            }
                            sec = sec - 1;
                            countdown(sec);
                        }, 1000);
                    }
                </script>
            </section>
<?php
    break;
    case 'submit':
?>
            <section id="done">
                <h3>恭喜您兑换成功！</h3>
                <div id="info">
                    <div>
                    </div>
                    <div>
                        <h4>飞利浦精品刮胡刀</h4>
                        <p>消费积分：500</p>
                    </div>
                </div>
                <div id="tips">
                    <p>请于xxxx年xx月xx日之后，凭借短信兑换码、身份证、手机号码至指定地点领取物品。</p>
                    <p>短信兑换码稍后将发送至您的手机，请妥善保管。</p>
                </div>
                <p class="button">
                    <input type="button" value="完成" onclick="javascript:window.location.href='index.php?m=point';" />
                </p>
            </section>
<?php
    break;
}
?>