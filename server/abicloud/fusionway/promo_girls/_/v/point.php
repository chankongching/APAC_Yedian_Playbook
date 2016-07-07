<?php (INAPP !== true) && die('Error !'); ?>
<?php
switch($a) {
    case 'list':
?>
            <section id="list">
                <header>
                    <div></div>
                    <h1>积分兑换</h1>
                    <p>当前积分4500</p>
                </header>
                <ul id="items">
                    <li>
                        <a href="index.php?m=point&a=detail&id=1">
                            <div></div>
                            <h4>飞利浦精品刮胡刀</h4>
                            <p>
                                500积分<br />剩余数量：50
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?m=point&a=detail&id=1">
                            <div></div>
                            <h4>飞利浦精品刮胡刀</h4>
                            <p>
                                500积分<br />剩余数量：50
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?m=point&a=detail&id=1&disabled=disabled">
                            <div></div>
                            <h4>飞利浦精品刮胡刀</h4>
                            <p>
                                500积分<br />剩余数量：50
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?m=point&a=detail&id=1&disabled=disabled">
                            <div></div>
                            <h4>飞利浦精品刮胡刀</h4>
                            <p>
                                500积分<br />剩余数量：50
                            </p>
                        </a>
                    </li>
                </ul>
            </section>
<?php
    break;
    case 'detail':
        $disabled = isset($_GET['disabled']) ? 'disabled' : '';
?>
            <section id="detail">
                <header>
                    <div></div>
                </header>
                <div id="content">
                    <h1>飞利浦精品刮胡刀</h1>
                    <span>剩余数量：50</span>
                    <p>这里利用了浮动，最后的结果仅仅是看着没问题，当然了，如果你只是简单的展示文本和图片，这种方法已经够用了，但是如果你想用js做点交互，比如#content内部有个需要拖拽的元素，它的top最小值肯定不能是0，否则就被#nav挡住了。</p>
                </div>
                <form id="action" action="index.php?m=point&a=confirm" method="post">
                    <input type="hidden" name="id" value="1" />
                    <span>
                        兑换数量
                        <input type="number" <?php echo $disabled; ?> value="1" name="quantity" />
                    </span>
                    <span>
                        需要积分
                        <input type="number" readonly="" value="500" />
                    </span>
                    <input type="submit" class="submit <?php echo $disabled; ?>" <?php echo $disabled; ?> name="submit" value="兑换" />
                </form>
            </section>
<?php
    break;
    case 'confirm':
?>
            <section id="confirm">
                <div id="info">
                    <div>
                    </div>
                    <div>
                        <h4>飞利浦精品刮胡刀</h4>
                        <p>消费积分：500</p>
                    </div>
                </div>
                <form id="submit" action="index.php?m=point&a=submit" method="post">
                    <input type="hidden" name="id" value="1" />
                    <input type="hidden" name="quantity" value="1" />
                    <h3>您的手机号码是：13800000000</h3>
                    <span>
                        <input type="number" name="captcha" class="captcha" placeholder="输入验证码" />
                    </span>
                    <span>
                        <input type="button" value="获取验证码" />
                    </span>
                    <input type="submit" class="submit" name="submit" value="提交" />
                </form>
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