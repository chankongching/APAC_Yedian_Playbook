<?php (INAPP !== true) && die('Error !'); ?>

            <section id="achievement">
<?php
if(empty($data) || $data['invite'] < 1) {
?>
                目前还没有用户受邀。
<?php
} else {
?>
                <div>
                    <p><span>日期：<?php echo date('Y');?>年<?php echo date('m');?>月<?php echo date('d');?>日</span></p>
                    <p>
                        当前二维码成功邀请：<?php echo $data['today']; ?>人
                    </p>
                    <p>
                        到目前为止：
                        <br />
                        您已成功邀请 <?php echo $data['invite']; ?>人
                    </p>
                    <p>
                        累计积分：<?php echo $data['point_all']; ?>分<br />
                        已用积分：<?php echo $data['point_used']; ?>分<br />
                        剩余积分：<?php  echo $data['point_all'] - $data['point_used']; ?>分
                    </p>
                </div>
                <input type="button" value="关闭" onclick="javascript:WeixinJSBridge.invoke('closeWindow',{},function(){});" />
<?php
}
?>
            </section>