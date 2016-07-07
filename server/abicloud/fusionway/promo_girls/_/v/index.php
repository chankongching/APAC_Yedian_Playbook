<?php (INAPP !== true) && die('Error !'); ?>

            <section id="readytoclose">
                <p>您已经绑定，页面即将关闭。</p>
                <input type="button" id="close" value="关闭" onclick="WeixinJSBridge.invoke('closeWindow',{},function(res){});" />
            </section>
            <script type="text/javascript">
                var countdown_close = 0;
                function countdown(sec) {
                    $("#close").val("关闭(" + sec + ")");
                    countdown_close = setTimeout(function(){
                        if(sec === 0){
                            clearTimeout(countdown_close);
                            WeixinJSBridge.invoke('closeWindow',{},function(res){
                                
                            });
                            return;
                        }
                        sec = sec - 1;
                        countdown(sec);
                    }, 1000);
                }
                countdown(2);
            </script>