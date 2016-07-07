<?php (INAPP !== true) && die('Error !'); ?>

            <section id="achievement">
<?php
if(empty($data)) {
?>
                目前还没有用户受邀。
<?php
} else {
?>
                <div>
                    <p><span>日期：<?php echo date('Y');?>年<?php echo date('m');?>月<?php echo date('d');?>日</span></p>
                    <p>
                        当前二维码成功邀请：<?php echo $data['data']['today']; ?>人
                        <br />
                        <span>（上限<?php echo $data['data']['today_limit']; ?>次）</span>
                    </p>
                    <p>
                        到目前为止：
                        <br />
                        您已成功邀请<?php  echo $data['data']['count_all']; ?>人
                    </p>
                    <p>累计积分：<?php  echo $data['data']['point_all']; ?>分</p>
                </div>
                <input type="button" value="关闭" onclick="javascript:WeixinJSBridge.invoke('closeWindow',{},function(){});" />
<?php
}
?>
            </section>