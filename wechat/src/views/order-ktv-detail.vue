<template>
    <div class="page-ktvorder">
        <figure class="ktv-pic" :style="order.ktvinfo | backgroundImage"></figure>

        <div v-if="order.ktvinfo">
            <section class="pay-countdown" v-if="timeRemaining > -1">
                <h4>支付剩余时间</h4><time>{{timeRemaining | humanTime}}</time>
            </section>

            <section class="baseinfo">
                <div class="action">
                    <span class="status">{{order.order_status | statusName}}</span>
                    <button type="button" class="btn" v-if="isOrderCancelable" @click.stop="cancelOrder" v-el:cancel-btn><span class="text">取消订单</span><span class="icon icon-spinner"></span></button>
                </div>
                <h2 class="name" v-link="{ name: 'detail', params: { id: order.ktvinfo.xktvid }}">{{order.ktvinfo.xktvname}}</h2>
                <span class="rating"><span class="full"></span><span class="stars" :style="{width:order.ktvinfo.rate*20+'%'}"></span></span>

                <p class="refund-tip" v-if="order.order_status==21">您的款项将于3-5 个工作日返还您的账户</p>
            </section>

            <list-view class="bg">
                <li><a :href="'tel:'+order.ktvinfo.telephone"><span class="icon lineicon-phone"></span>{{order.ktvinfo.telephone}}</a></li>
                <li class="item-address"><a @click="openMap"><span class="icon lineicon-location"></span><span class="value">{{order.ktvinfo.address}}</span></a></li>
            </list-view>

            <ul class="detail">
                <li><span class="label">时间</span><span class="value">{{orderDate}} <span class="duration">共 <strong>{{(this.order.endtime - this.order.starttime)/60/60}}</strong> 小时</span></span></li>
                <li><span class="label">包房</span><span class="value">{{order.room_name}}</span></li>
                <li><span class="label">套餐</span><span class="value">{{order.taocan_info?order.taocan_info.name:"无"}}</span></li>
                <li><span class="label">兑酒券</span><span class="value">{{order.coupon_info.count?order.coupon_info.name+((order.order_status==4||order.order_status==7||order.order_status==14)?"（已返还）":""):"无"}}</span></li>
            </ul>

            <ul class="detail">
                <li v-if="order.taocantype==0"><span class="label">价格</span><span class="value">¥ {{order.price}}</span></li>
                <li><span class="label">手机</span><span class="value">{{order.mobile | mobileFormat}}</span></li>
            </ul>
        </div>

        <figure class="qrcode" v-if="canDisplayQrcode">
            <img :src="order.qrcode" />
            <figcaption>请将此二维码出示给工作人员</figcaption>
        </figure>

        <modal v-if="order.ktvinfo" id="rating-modal" :title="'为 ' + order.ktvinfo.xktvname + ' 评分'" btn-text="提交" :btn-disabled="!isRatingComplete" :submit="postReview" v-ref:rating-modal>
            <table class="rating-list">
                <tr><th><span class="icon icon-decoration"></span>装修</th><td><rating :value.sync="ratings.decoration"></rating></td></tr>
                <tr><th><span class="icon icon-sound"></span>音响</th><td><rating :value.sync="ratings.sound"></rating></td></tr>
                <tr><th><span class="icon icon-service"></span>服务</th><td><rating :value.sync="ratings.service"></rating></td></tr>
                <tr><th><span class="icon icon-consumer"></span>消费</th><td><rating :value.sync="ratings.consumer"></rating></td></tr>
                <tr><th><span class="icon icon-food"></span>食物</th><td><rating :value.sync="ratings.food"></rating></td></tr>
            </table>

            <div class="app-rating">
                <h4>为 夜点娱乐 评分</h4>
                <rating :value.sync="ratings.app" :size="22"></rating>
            </div>
        </modal>

        <button type="button" class="btn-float btn-comment" v-if="order.order_status==5&&!order.rating.status" @click="$refs.ratingModal.open()"><span></span></button>

        <button type="button" class="btn-float btn-pay" @click="payOrder" v-if="timeRemaining > -1" v-el:pay-btn><span></span></button>

        <flash-message id="order-success-message" btn-text="分享给好友" v-ref:order-success-message>
            <div class="screen1">
                <div class="icon icon-smile"></div>
                <p class="message">您预订的KTV已经兑换成功，感谢您对夜点的支持，夜点赠送您一张新的兑酒券，将于5分钟之内发放到您的账户，次日可用。<br />祝您欢唱愉快！</p>
                <hr /><h4>夜点，预订你的KTV派对</h4>
                <p class="text">分享给你的好友们，领取免费兑酒券…</p>
                <span class="arrow"></span>
            </div>
            <div class="share-tip"></div>
        </flash-message>

        <div id="order-redpacket" v-el:order-redpacket>
            <span class="arrow"></span>
            <div class="body">
                <div class="redpacket"></div>
                <p class="rate_title" v-if="!order.is_pingjia.status">为这次预订打个分吧</p>
                <p>
                    <rating :value.sync="ratings.app" size="28" color="#FDE04A" :once="true" :disabled="!!order.is_pingjia.status" @change="redpacketRating"></rating>
                </p>
                <p class="thanks">感谢您的参与！</p>
                <span class="close" @click="$els.orderRedpacket.style.display='none'"></span>
            </div>
        </div>

        <flash-message v-ref:flash-message></flash-message>

        <loading v-if="loadingStatus==1"></loading>
        <screen-message v-if="loadingStatus==-1" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.page-ktvorder {
    margin-bottom: 74px;

    .ktv-pic {
        height: 120px;
        background: $mainDarker no-repeat 50%;
        background-size: cover;
    }

    .pay-countdown {
        margin-top: 20px;
        text-align: center;

        h4 {
            margin-bottom: .5em;
        }

        time {
            font-size: 2em;
        }
    }

    .baseinfo {
        margin: 20px;

        .action {
            float: right;
            font-size: 12px;

            .status {
                text-align: right;
                display: block;
                margin-top: 2px;
                margin-bottom: 7px;
                color: #7B2431;
            }

            .btn {
                float: right;
                border: 1px solid currentColor;
                width: 60px;
                height: 24px;
                margin-top: -3px;

                .icon {
                    @include rsprite($icon-loader-group);
                    display: none;
                    margin-top: 3px;
                }

                &.loading {
                    .text {
                        display: none;
                    }
                    .icon {
                        display: inline-block;
                    }
                }
            }
        }

        .name {
            font-size: 16px;
            color: #fff;
            min-height: 1em;
            margin-bottom: 10px;
        }

        .rating {
            width: 80px;
            height: 12px;
            display: inline-block;
            position: relative;

            .full,
            .stars {
                @include rsprite($icon-stars-group, false);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .full {
                opacity: .5;
            }
        }

        .refund-tip {
            text-align: center;
        }
    }

    .list-view {
        font-weight: lighter;
        
        a {
            color: #fff;
        }

        .item-address .value {
            display: block;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
    }

    .detail {
        border-top: 1px solid $border;
        border-bottom: 1px solid $border;
        padding: 10px 20px;
        font-weight: 200;

        + .detail {
            border-top: none;
        }

        li {
            overflow: hidden;
            margin: 15px 0;
        }

        .label {
            float: left;
        }
        .value {
            float: right;
            text-align: right;
            margin-left: 1em;
        }
        .duration {
            display: block;
            margin-top: 5px;
            strong {
                font-weight: 200;
                font-size: 20px;
            }
        }
    }

    .qrcode {
        margin: 30px 0;
        text-align: center;
        font-size: 12px;

        img {
            margin-bottom: 15px;
        }
    }

    .btn-float {
        background-color: #900D1E;
    }

    .btn-comment {
        span {
            @include rsprite($btn-ljpj-group);
        }
    }

    .btn-pay {
        span {
            @include rsprite($btn-wxzf-group);
        }        
    }
}

#order-success-message {
    .flash-message-dialog {
        width: 100%;
    }
    .screen1 {
        width: 290px;
        margin-left: auto;
        margin-right: auto;
    }
    .icon {
        margin-bottom: 0;
    }
    .message {
        width: 280px;
        margin-left: auto;
        margin-right: auto;
    }
    hr {
        background: $text;
        height: 1px;
        border: none;
        margin: 30px 0;
    }
    .text {
        margin-top: 0;
    }
    .arrow {
        display: block;
        margin: 0 auto;
        @include rsprite($share-coupon-arrow-group);
    }
    .share-tip {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 371px;
        background: url(../img/share-coupon.png) no-repeat 50%;
        background-size: contain;
        margin-bottom: 50%;
        display: none;
        animation: shareTip .5s;
    }
    .btn {
        margin-top: 20px;
    }

    &.show-share {
        .screen1 {
            visibility: hidden;
        }
        .share-tip {
            display: block;
        }
    }
}
@keyframes shareTip {
    from {
        opacity: 0;
        transform: translate3d(0, -50px, 0);
    }
}

#order-redpacket {
    background: rgba(0,0,0,.88);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 4;
    color: $main;
    font-size: 16px;
    display: none;

    .arrow {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 68px;
        height: 85px;
        background: url(../img/redpacket_arrow.png) no-repeat 50%;
        background-size: contain;
    }
    .body {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 255px;
        text-align: center;
        transform: translate3d(-50%, -50%, 0);
    }
    .redpacket {
        width: 255px;
        height: 204px;
        background: url(../img/redpacket.png) no-repeat 50%;
        background-size: contain;
        margin: 0 auto 30px;
    }
    .c-rating {

    }
    .thanks {
        display: none;
    }
    .close {
        display: block;
        width: 37px;
        height: 37px;
        background: url(../img/redpacket_close.png) no-repeat 50%;
        background-size: contain;
        margin: 0 auto;
    }
}

#rating-modal {
    .rating-list {
        clear: both;
        margin: 20px 7%;
        width: 86%;
        color: $text;
        text-align: center;

        th, td {
            padding: 10px 0;
        }

        th {
            width: 40%;
            font-weight: 200;
        }
        .icon {
            @extend %sprite;
            width: 26px;
            height: 26px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .c-rating {
            text-align: left;
        }

        .icon-decoration { @include sprite-position($ratingicon-decoration); }
        .icon-sound { @include sprite-position($ratingicon-sound); }
        .icon-service { @include sprite-position($ratingicon-service); }
        .icon-consumer { @include sprite-position($ratingicon-consumer); }
        .icon-food { @include sprite-position($ratingicon-food); }
    }

    .app-rating {
        h4 {
            margin-left: 30px;
            margin-bottom: 20px;
        }
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
            ORDER_STATUS: {
                1  : "预约中…",    // Pending
                14 : "已过期",     // Expired
                17 : "等待支付",   // Waiting Cash
                18 : "已支付",     // Paid by Wechat
                19 : "已支付",     // Paid by Alipay
                20 : "已支付",     // Paid by Cash
                21 : "退款中",     // Refunding
                22 : "退款完成",   // Refunded
                3  : "已确认",     // Confirmed
                7  : "已取消",     // Canceled
                5  : "已完成",     // Complete
                4  : "已满房",     // Rejected
                8  : "已删除"      // Deleted
            },

            order: {},

            timeRemaining: -1,

            ratings: {
                decoration: 0,
                sound: 0,
                service: 0,
                consumer: 0,
                food: 0,
                app: 0
            },

            loadingStatus: 1,
            errorMsg: ""
        }
    },
    route: {
        data() {
            this.fetch();
        }
    },
    ready() {
        window.scrollTo(0, 0);

        if (this.$route.query.share) this.showShareLayer(this.$route.query.share);
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    methods: {
        fetch() {
            this.loadingStatus = 1;
            this.$api.get("booking/Orderdetail", {
                ordercode: this.$route.params.id
            }).then(function (data) {
                this.order = data;
                this.loadingStatus = 2;
                if (data.is_pingjia.status) this.ratings.app = data.is_pingjia.appRating;
                if (data.order_status == 3) this.timer = setInterval(this.checkStatus.bind(this), 5e3);
                if (data.is_pay_online && data.order_status == 17) this.payCountdown(data.payendtime - data.now);
            }, function(data) {
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        },
        cancelOrder() {
            let order = this.order;

            if (!confirm("确定要取消" + order.ktvinfo.xktvname + " " + (utils.parseDate("yyyy年MM月dd日 hh:mm", new Date(order.starttime * 1000))) + " " + order.room_name + "的订单吗？")) {
                return false;
            };

            let promise;

            this.$els.cancelBtn.classList.add("loading");

            if (order.is_pay_online) {
                promise = this.$api.get("booking/cancelpayorder", {
                    ordercode: order.order_code,
                });
            } else {
                promise = this.$api.post("booking/cancelorder", {
                    ordercode: order.order_code,
                    ktvid: order.ktvinfo.xktvid
                });
            }

            promise.then(function(data) {
                this.order.order_status = order.is_pay_online ? 21 : 7;
                this.$els.cancelBtn.classList.remove("loading");
            }, function(data){
                alert(data.msg || "取消订单失败");
            });
        },
        openMap() {
            if (window.isWXReady) {
                wx.openLocation({
                    latitude: this.order.ktvinfo.lat,
                    longitude: this.order.ktvinfo.lng,
                    name: this.order.ktvinfo.xktvname,
                    address: this.order.ktvinfo.address,
                    scale: 14
                });
            } else {
                this.$router.go({
                    name: "map",
                    query: {
                        lat: this.order.ktvinfo.lat,
                        lng: this.order.ktvinfo.lng,
                        name: this.order.ktvinfo.xktvname,
                        address: this.order.ktvinfo.address
                    }
                });
            };
        },
        checkStatus() {
            this.$api.get("booking/checkstatus", {
                order_code: this.order.order_code
            }).then(function (data) {
                if (data.status == 5) {
                    this.order.order_status = data.status;
                    clearInterval(this.timer);
                    this.showShareLayer(data.ShareCoupon.code);
                };
            });
        },
        showShareLayer(code) {
            /*
            this.$refs.orderSuccessMessage.show(null, null, function(){
                if (this.btnText != "关闭") {
                    this.$trackEvent("分享给好友", "click");
                    this.$el.querySelector(".share-tip").style.top = -this.$el.querySelector(".flash-message-dialog").getBoundingClientRect().top + 30 + "px";
                    this.$el.classList.add("show-share");
                    this.btnText = "关闭";
                    return false;
                }
            });
            */
            this.$els.orderRedpacket.style.display = "block";
            this.$wxShare({
                title: "夜点送你广州KTV兑酒券！",
                desc: "好友K歌局，有酒才痛快！夜点免费兑酒券，等你来抢！",
                link: "http://letsktv.chinacloudapp.cn/dist/redpacket/?coupon=" + code,
                imgUrl: "http://letsktv.chinacloudapp.cn/dist/redpacket/img/weixin_share_pic.jpg"
            });
        },
        postReview() {
            this.$api.post("feedback/comment", {
                ktvid: this.order.ktvinfo.xktvid,
                openid: this.$user.openid,
                DecorationRating: this.ratings.decoration,
                SoundRating: this.ratings.sound,
                ServiceRating: this.ratings.service,
                FoodRating: this.ratings.food,
                ConsumerRating: this.ratings.consumer,
                appRating: this.ratings.app
            }).then(function (data) {
                this.order.rating.status = 1;
                this.$refs.ratingModal.close();
                this.$refs.flashMessage.show("smile", "<p>评价成功</p>");
            }, function(data){
                alert(data.msg || "提交失败");
            });
        },
        payCountdown(seconds) {
            this.timeRemaining = seconds;
            this.timeRemainingLast = Date.now();

            this.timer = setInterval(() => {
                if (this.timeRemaining > 0) {
                    let now = Date.now();
                    this.timeRemaining -= Math.round((now - this.timeRemainingLast) / 1e3);
                    this.timeRemainingLast = now;
                } else {
                    this.timeRemaining = -1;
                    clearInterval(this.timer);
                }
            }, 1e3);
        },
        payOrder() {
            this.$api.wechatPay(this.order.order_code).then(function(data) {
                alert("支付成功");
                clearInterval(this.timer);
                this.timeRemaining = -1;
                this.order.order_status = 18;
            }, function(data) {
                alert(data.msg || "支付失败");
            });
        },
        redpacketRating(rate) {
            this.$api.post("feedback/commentapp", {
                ktvid: this.order.ktvinfo.xktvid,
                orderid: this.order.order_code,
                appRating: rate
            });
            $(this.$els.orderRedpacket).find(".thanks").show();
        }
    },
    filters: {
        statusName(statusCode) {
            return this.ORDER_STATUS[statusCode];
        },
        backgroundImage(ktvinfo) {
            return ktvinfo ? {
                backgroundImage: "url(" + utils.encodeBackgroundImageUrl(ktvinfo.piclist[0].bigpicurl) + ")"
            } : null;
        },
        mobileFormat(mobile) {
            return mobile && mobile.replace(/(\d{3})(\d{4})(\d{4})/, "$1 $2 $3");
        },
        humanTime(seconds) {
            return utils.padZero(Math.floor(seconds / 60)) + ":" + utils.padZero(seconds % 60);
        }
    },
    computed: {
        orderDate() {
            if (this.order.starttime && this.order.endtime) {
                let date = new Date(this.order.starttime * 1000);
                let hour = date.getHours();

                return "周" + "日一二三四五六".split("")[date.getDay()] + utils.parseDate("（M月d日）", date) + (hour<5?"凌晨":hour<12?"上午":hour<14?"中午":hour<18?"下午":"晚上") + utils.parseDate("hh:mm", date) + "-" + utils.parseDate("hh:mm", new Date(this.order.endtime * 1000));
            } else {
                return "";
            }
        },
        isRatingComplete() {
            return Object.keys(this.ratings).every(item => this.ratings[item]);
        },
        isOrderCancelable() {
            return this.order.order_status == 1 || this.order.order_status == 3 || this.order.order_status == 18;
        },
        canDisplayQrcode() {
            return !(this.order.order_status == 1 || this.order.order_status == 4 || this.order.order_status == 7 || this.order.order_status == 14 || this.order.order_status == 17);
        }
    }
}
</script>