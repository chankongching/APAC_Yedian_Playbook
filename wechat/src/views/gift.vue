<template>
    <div class="page-gift">
        <figure class="gift-pic" v-adaptive-background="gift.product_mainpic"></figure>

        <div class="gift-content" v-if="gift.product_id">
            <section class="gift-info">
                <span class="points">{{gift.productsale_points}} 积分</span>
                <h2 class="name">{{gift.productsale_name}}</h2>
                <div class="desc">{{{gift.productsale_cont1}}}</div>
            </section>

            <section class="gift-exchange clearfix">
                <span class="points">需要积分 {{gift.productsale_points}}</span>
                <h3 class="title">兑换数量</h3>

                <span class="quantity" v-if="!gift.real">1</span>
                <spin-box v-if="gift.real" :max="maxQuantity" :value.sync="order.quantity"></spin-box>

                <span class="my-points">我的当前积分 {{userPoints}}</span>
            </section>

            <button type="button" class="btn-float" @click="$refs.exchangeModal.open()" v-if="userPoints>=gift.productsale_points">立即兑换</button>
            <button type="button" class="btn-float" disabled v-else>积分不足</button>
        </div>

        <modal id="exchange-modal" class="red-placeholder" :btn-disabled="isInvalidOrder" :submit="submitOrder" btn-text="下一步" v-ref:exchange-modal>
            <h4 class="gift-name">{{gift.productsale_name}}</h4>
            <div class="gift-quantity">
                <span class="points">需要积分 {{gift.productsale_points}}</span>
                <h3 class="title">兑换数量</h3>

                <spin-box v-if="gift.real" :max="maxQuantity" :value.sync="order.quantity"></spin-box>
                <span class="quantity" v-else>1</span>
            </div>
            <list-view class="no-arrow">
                <li v-if="gift.real"><a @click="$els.name.focus()"><span class="icon icon-user"></span><input type="text" placeholder="收件人" v-model="order.name" v-el:name></a></li>
                <li><a @click="$els.mobile.focus()"><span class="icon icon-phone"></span><input type="tel" placeholder="手机" v-model="order.mobile" maxlength="11" v-el:mobile></a></li>
                <li class="item-captcha" v-if="!gift.real"><a><input type="number" placeholder="验证码" maxlength="6" v-model="order.captcha"><button type="button" class="btn btn-sendcaptcha" @click="$api.sendCode(order.mobile)">发送验证码</button></a></li>
                <li class="item-city" v-if="gift.real"><a><city-selector :province.sync="order.province" :city.sync="order.city" :district.sync="order.district"></city-selector></a></li>
                <li v-if="gift.real"><a @click="$els.address.focus()"><span class="icon icon-home"></span><input type="text" placeholder="收件地址" v-model="order.address" v-el:address></a></li>
            </list-view>
        </modal>

        <loading v-if="loadingStatus==1"></loading>
        <screen-message v-if="loadingStatus==-1" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";

.page-gift {
    margin-bottom: 74px;
}

.gift-pic {
    background: #fff no-repeat 50%;
    background-size: contain;
    height: 185px;
}

.gift-content {
    position: relative;
    padding-top: 30px;

    &:before {
        content: "";
        position: absolute;
        top: -15px;
        left: 0;
        width: 100%;
        height: 15px;
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.4));
    }
}

.gift-info {
    margin: 0 20px 30px;

    .name {
        font-size: 16px;
        margin-bottom: 10px;
        color: #fff;
    }

    .points {
        font-size: 12px;
        color: #fff;
        line-height: 22px;
        padding: 0 8px;
        border: 1px solid $border;
        border-radius: 11px;
        float: right;
        margin-top: -4px;
    }

    .desc {
        line-height: 1.5;
        font-weight: lighter;

        br {
            display: none;
        }
        dl,
        dt,
        dd {
            margin: 0;
        }
    }
}

.gift-exchange {
    margin: 30px 20px;
    clear: both;

    .title {
        color: #fff;
        font-size: 1em;
        margin-bottom: 15px;
    }    
    .points {
        float: right;
        font-weight: lighter;
    }
    .quantity {
        display: block;
        text-align: center;
        font-size: 16px;
        line-height: 46px;
        color: #fff;
        border: 1px solid $border;
        margin-bottom: 15px;
    }
    .my-points {
        float: right;
        font-weight: lighter;
    }
}

.gift-exchange .quantity,
#exchange-modal .quantity {
    display: block;
    text-align: center;
    font-size: 16px;
    line-height: 46px;
    color: #fff;
    border: 1px solid $border;
    margin-bottom: 15px;
}

#exchange-modal {
    .modal-body {
        margin: 20px 0 30px;
    }
    .gift-name {
        position: relative;
        top: -3px;
        margin-left: 30px;
    }
    .gift-quantity {
        margin: 30px 30px 0;
        font-weight: lighter;
        .title {
            font-size: 1em;
            font-weight: lighter;
        }
        .points {
            float: right;
        }
        .quantity {
            border-color: rgba($main,0.42);
            color: $main;
        }
        .quantity,
        .spinbox {
            margin-top: 15px;
        }
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
            gift: {},

            order: {
                quantity: 1,
                captcha: "",
                name: "",
                mobile: "",
                province: "",
                city: "",
                district: "",
                address: ""
            },

            userPoints: 0,

            loadingStatus: 1,
            errorMsg: ""
        }
    },
    route: {
        data() {
            this.$api.getUserInfo(true).then(function(data){
                this.userPoints = data.points;
                this.order.name = data.sname;
                this.order.mobile = data.stel;
                this.order.province = data.prov;
                this.order.city = data.city;
                this.order.district = data.county;
                this.order.address = data.address;

                this.fetch();
            });
        }
    },
    ready() {
        window.scrollTo(0, 0);
    },
    methods: {
        fetch() {
            this.loadingStatus = 1;
            this.$api.get("gift/giftdetail", {
                giftid: this.$route.params.id
            }).then(function(data) {
                data.data.real = data.data.productsale_cata3 == "实物";
                this.gift = data.data;
                this.loadingStatus = 2;

                this.$wxShare({
                    title: "用积分就可以兑换［" + this.gift.productsale_name + "］",
                    desc: "我在夜点的积分商城里面看的这个不错哦…",
                    titleTL: "我在夜点的积分商城里面看到［" + this.gift.productsale_name + "］不错哦…",
                    imgUrl: this.gift.product_mainpic
                });
            }, function(data){
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        },
        submitOrder() {
            this.$refs.exchangeModal.setLoading(true);
            if (this.gift.real) {
                this.$api.post("gift/orderreal", {
                    giftid: this.gift.product_id,
                    giftcount: this.order.quantity,
                    sname: this.order.name,
                    stel: this.order.mobile,
                    prov: this.order.province,
                    city: this.order.city,
                    county: this.order.district,
                    address: this.order.address
                }).then(function(data){
                    this.$router.go({
                        name: "gift-order",
                        params: {
                            id: data.order_result
                        }
                    });
                }, function(data){
                    alert(data.msg || "提交失败");
                });
            } else {
                this.$api.verifyCode(this.order.mobile, this.order.captcha).then(function() {
                    return this.$api.post("gift/ordervirtual", {
                        giftid: this.gift.product_id,
                        giftcount: 1,
                        mobile: this.order.mobile
                    });
                }).then(function(data) {
                    this.$router.go({
                        name: "gift-order",
                        params: {
                            id: data.order_result
                        }
                    });
                }).catch(function(data) {
                    this.$refs.exchangeModal.setLoading(false);
                    alert(data.msg || "提交失败");
                });
            };
        }
    },
    computed: {
        maxQuantity() {
            return Math.max(1, Math.floor(this.userPoints / this.gift.productsale_points));
        },
        isInvalidOrder() {
            if (this.gift.real) {
                return !(this.order.quantity > 0 && utils.isMobile(this.order.mobile) && this.order.name != "" && this.order.province != "" && this.order.city != "" && this.order.district != "" && this.order.address != "");
            } else {
                return !(utils.isMobile(this.order.mobile) && utils.isCaptcha(this.order.captcha));
            };
        }
    }
}
</script>