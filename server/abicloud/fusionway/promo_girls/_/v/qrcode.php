<?php (INAPP !== true) && die('Error !'); ?>

            <section>
                <img style="display: block;position: absolute;top: 40%;left: 50%;margin-top: -40%;margin-left: -40%;width: 80%;" src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?php echo $qr; ?>" />
                <p style="text-align: center;position: absolute;bottom: 20%;left: 50%;margin-left: -8em;width: 16em;">该二维码将在每天早上8:00更新<br />请及时刷新</p>
            </section>