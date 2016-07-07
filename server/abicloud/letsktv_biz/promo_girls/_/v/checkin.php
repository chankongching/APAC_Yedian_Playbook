<?php (INAPP !== true) && die('Error !'); ?>

            <section id="checkin">
                <form>
                    <div>
                        <h3><?php if($ktv_today) {
                            echo '您今天签到于：<br />'.$ktv_today;;
                        } else {
                            echo '您今天还没有签到，请先签到。';
                        }?><h3>
                        <p>区域：<select name="district" id="district"><option value="0">请选择</option></select></p>
                        <p>KTV：<select name="ktvid" id="ktvid"><option value="0">请选择</option></select></p>
                        <p class="tips">如果未出现您所在的KTV，请立刻联系您的主管。</p>
                    </div>
                    <input class="submit" type="submit" value="签到" />
                    <div class="checkin_error"></div>
                </form>
            </section>
            <section id="readytoclose" style="display: none;">
                <p>签到成功，页面即将关闭。</p>
                <input type="submit" value="关闭" onclick="javascript:WeixinJSBridge.invoke('closeWindow',{},function(res){});" />
            </section>
            <script type="text/javascript">
                $(document).ready(function(){
                    var ktvs = <?php echo $ktvs;?>;
                    $.each(ktvs, function(district, ktvs_district) {
                        $('#district').append($('<option>', { 
                            value: district,
                            text : district
                        }));
                    });
                    $('#district').change(function(){
                        $('#ktvid').find('option').remove().end();
                        if($(this).val() == '0') {
                            $('#ktvid').append($('<option>', { 
                                value: 0,
                                text : '请选择'
                            }));
                        } else {
                            $.each(ktvs[$(this).val()], function(i, ktv) {
                                $('#ktvid').append($('<option>', { 
                                    value: ktv[0],
                                    text : ktv[1]
                                }));
                            });
                        }
                    });
                    $('#checkin form').submit(function(){
                        if($('#ktvid').val() == '0') {
                            return false;
                        }
                        $.ajax({
                            url :"index.php?m=checkin",
                            type : "post",
                            data : {
                                'ktvid' : $('#ktvid').val(), 
                            },
                            dataType : "json",
                            success : function(response){
                                if(response.status == 1){
                                    $('#checkin').stop().hide();
                                    $('#readytoclose').stop().show(function(){
                                        setTimeout(function(){
                                            WeixinJSBridge.invoke('closeWindow',{},function(res){});
                                        }, 3000);
                                    });
                                } else {
                                    $('.checkin_error').stop().text(response.error).show(function(){
                                        setTimeout(function(){
                                            $('.checkin_error').stop().hide().text('');
                                        }, 2000);
                                    });
                                }
                            }
                        });
                        return false;
                    });
                });
            </script>