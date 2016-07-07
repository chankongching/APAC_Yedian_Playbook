<?php (INAPP !== true) && die('Error !'); ?>

            <section>
                <img id="qrcode_img" style="display: block;position: absolute;top: 40%;left: 50%;margin-top: -40%;margin-left: -40%;width: 80%;" src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?php echo $qr; ?>" />
                <p style="text-align: center;position: absolute;bottom: 20%;left: 50%;margin-left: -8em;width: 16em;">每邀请一位新用户<br />请及时点击二维码刷新</p>
            </section>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#qrcode_img').click(function(){
                        $(this).fadeOut('fast', function(){
                            window.location.reload();
                        });
                        
                    });
                });
            </script>