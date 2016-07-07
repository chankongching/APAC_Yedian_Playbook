<template>
    <div class="page-book">
        <section class="section-times">
            <h2 class="section-title"><span class="icon icon-clock"></span>选择到店时间</h2>

            <div class="section-bd">
                <ul>
                    <li v-for="time in arrivalTimeList | limitBy arrivalTimeListLimit" :class="{active:time==startTime}" @click="startTime=time"><span>{{time | morrow arrivalTimeList[0]}}</span></li>
                </ul>

                <div class="more" @click="arrivalTimeListLimit=99" v-if="arrivalTimeList.length>8 && arrivalTimeListLimit==8">更多进场时间</div>
                <div class="less" @click="arrivalTimeListLimit=8" v-if="arrivalTimeListLimit==99">收起</div>
            </div>
        </section>

        <section class="section-baseinfo">
            <div class="section-bd">
                <h3 class="name">{{ktv.xktvname}}</h3>
                <p class="meta">{{orderMeta}}</p>
            </div>
        </section>

        <section class="section-package" v-if="package">
            <h2 class="section-title"><span class="icon icon-beer"></span>已选酒水套餐</h2>
            <div class="section-bd" :class="{huanchang:package.huanchang}">
                <span class="check"></span>
                <h5 class="name">{{package.pre_txt}}{{package.name}}</h5>
                <div class="secrow" v-if="!package.huanchang">
                    <span class="orig-price">原价{{package.price}}</span>
                    <span class="yd-price">夜点价¥{{package.price_yd}}</span>
                </div>
            </div>
        </section>

        <section class="section-coupon" v-if="ktv.sjq">
            <h2 class="section-title"><span class="icon icon-djq"></span>选择兑酒券</h2>
            <div class="section-bd" :class="{empty:!couponList.length}" @click="$refs.couponModal.open()">
                <span>{{couponText}}</span>
            </div>
        </section>

        <section class="section-rules" v-if="terms">
            <h2 class="section-title"><span class="icon icon-tips"></span>小提示</h2>
            <div class="section-bd">
                <ol>
                    <li v-for="term in terms">{{term}}</li>
                </ol>
            </div>
        </section>

        <section class="section-mobile">
            <div class="section-bd" @click="$refs.editMobileModal.open()">
                <span class="icon icon-mobile"></span>
                <span class="label">手机</span>
                <span class="value"><input type="text" class="penone" placeholder="请先绑定您的手机号码" v-model="userMobile"></span>
            </div>
        </section>

        <p class="remark">预订说明: 通过夜点预订KTV，只需要在夜点提交订单，达到KTV现场支付，即可享受夜点最强黄金档的价格优惠。</p>

        <button type="button" class="btn-float btn-order" :disabled="isInvalidOrder" @click="submitOrder" v-el:submit-btn><span></span></button>

        <flash-message v-ref:flash-message></flash-message>

        <loading v-if="loadingStatus==1"></loading>
        <screen-message v-if="loadingStatus==-1" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>

        <modal id="coupon-modal" title="选择兑酒券" :closeable="false" :submit="updateCoupon" v-ref:coupon-modal>
            <ul class="coupon-list">
                <li v-for="cp in couponList" :class="{active:coupon==cp}" @click="coupon=cp" :style="{backgroundImage:'url(http://t1.intelfans.com/'+(cp.status===0?cp.img:cp.img_disable)+')'}">
                    <span class="check"></span>
                    <div class="content">
                        <h3 class="name">{{cp.name}}</h3>
                        <p class="date">有效期<br>{{cp.start_time | date 'yyyy/MM/dd'}} 至 {{cp.expire_time | date 'yyyy/MM/dd'}}</p>
                    </div>
                </li>
                <li class="none" :class="{active:coupon==couponNone}" @click="coupon=couponNone"><span class="check"></span> 不使用任何兑酒券</li>
            </ul>

            <h4 class="rule-title">兑酒券使用说明</h4>
            <ol class="rule-content">
                <li>每次预订只能使用一张兑酒券。</li>
                <li>啤酒的品牌和数量以兑酒券的标识为准。</li>
                <li>兑酒券的使用时间以兑酒券的标识为准。</li>
                <li>通过完成订单所获得的兑酒券，次日可用，有效期14天。</li>
                <li>以任何形式取消订单，兑酒券都将会返还到用户账户，下次预订还可继续使用。</li>
                <li>每位用户每天可使用两张兑酒券。</li>
            </ol>
        </modal>

        <modal id="edit-mobile-modal" title="绑定手机号码" :btn-disabled="isInvalidMobileForm" :submit="updateMobile" v-ref:edit-mobile-modal>
            <list-view class="no-arrow">
                <li class="item-phone"><a @click="$els.phone.focus()"><span class="icon icon-phone"></span><input type="tel" placeholder="手机" v-model="editMobile.mobile" maxlength="11" v-el:phone></a></li>
                <li class="item-captcha"><a><input type="number" placeholder="验证码" maxlength="6" v-model="editMobile.captcha"><button type="button" class="btn btn-sendcaptcha" @click="$api.sendCode(editMobile.mobile)">发送验证码</button></a></li>
            </list-view>
        </modal>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";
@import "../scss/rsprite";
@import "../scss/mixins";

.page-book {
    margin-bottom: 74px;

    section {
        margin: 20px 0;
    }
    .section-title {
        font-size: 1em;
        margin-left: 20px;
        margin-bottom: 15px;

        .icon {
            display: inline-block;
            margin-right: 10px;
            vertical-align: -4px;
        }
        .icon-clock { @include rsprite($icon-clock-group); }
        .icon-beer { @include rsprite($icon-beer-group); }
        .icon-djq { @include rsprite($icon-djq-group); }
        .icon-tips { @include rsprite($icon-tips-group); }
    }
    .section-bd {
        background: $mainDarker;
        padding: 20px;
    }

    .section-times {
        ul {
            margin: -10px;
            overflow: hidden;
        }
        li {
            width: 25%;
            float: left;
            span {
                display: block;
                margin: 5px;
                text-align: center;
                line-height: 34px;
                border: 1px solid #98252F;
                border-radius: 4px;
                white-space: nowrap;
            }
        }
        .active { 
            span {
                background: #98252F;
            }
        }

        .more,
        .less {
            margin-top: 20px;
            margin-bottom: -5px;
            text-align: center;

            &:after {
                content: '';
                position: relative;
                top: -3px;
                display: inline-block;
                width: 6px;
                height: 6px;
                border-bottom: 2px solid #fff;
                border-right: 2px solid #fff;
                margin-left: 5px;
                transform: rotate(45deg);
                opacity: 0.8;
            }
        }
        .less {
            &:after {
                top: 1px;
                transform: rotate(-135deg);
            }
        }
    }

    .section-baseinfo {
        .section-bd {
            background: none;
        }
        .name {
            font-size: 1em;
            margin-bottom: 10px
        }
        .meta {
            color: #FFC59C;
            margin: 0;
        }
    }

    .section-package {
        .section-bd {
            position: relative;
            padding-right: 70px;
        }
        li {
            position: relative;
            padding: 15px 20px 15px 60px;
            border-bottom: 1px solid $borderDark;
        }
        .check {
            position: absolute;
            top: 50%;
            right: 20px;
            border: 1px solid #fff;
            background: rgba(#fff, 0.5);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            margin-top: -10px;
            &:after {
                content: '';
                border: 1px solid #fff;
                border-top-width: 0;
                border-right-width: 0;
                width: 7px;
                height: 4px;
                transform: rotate(-45deg);
                float: left;
                margin: 5px 0 0 5px;
            }
        }
        .name {
            font-size: 14px;
            margin-top: 1px;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .secrow {
            text-align: right;
        }
        .orig-price {
            font-size: 12px;
            text-decoration: line-through;
        }
        .yd-price {
            color: $brown;
            margin-left: .5em;
        }
        .btn {
            border-radius: 3px;
            background: $brown;
            color: #4A4A4A;
            width: 56px;
            height: 24px;
            margin-left: 30px;
            font-size: 12px;
            box-shadow: 0 1px 5px rgba(#000,0.2);
        }
        .huanchang {
            .name {
                margin: 0;
            }
        }
    }

    .section-coupon,
    .section-mobile {
        .section-bd {
            position: relative;
            &:after {
                content: "";
                position: absolute;
                top: 50%;
                right: 20px;
                margin-top: -7px;
                @include rsprite($icon-listview-arrow-group);
            }
        }
    }

    .section-coupon {
        .section-title {
            .icon {
                margin-left: -10px;
            }
        }
        .empty {
            pointer-events: none;
            &:after {
                display: none;
            }
        }
    }

    .section-rules {
        .section-bd {
            background: none;
            padding-top: 10px;
            padding-bottom: 10px;
            ol {
                list-style: inside decimal;
                font-size: 12px;
                margin: 5px 0 5px 25px;
            }
            li {
                margin-bottom: .5em;
                line-height: 1.4;

                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }

    .section-mobile {
        .icon-mobile {
            display: inline-block;
            vertical-align: middle;
            margin-right: 3px;
            @include rsprite($icon-mobile-group);
        }
        .label {
            vertical-align: middle;
        }
        input {
            float: right;
            width: 10em;
            height: 1em;
            text-align: right;
            margin-top: 1px;
            margin-right: 20px;
            &::placeholder {
                opacity: 0.5;
                color: #fff;
            }
        }
    }

    .remark {
        font-size: 12px;
        margin: 20px;
        line-height: 1.5;
    }

    .btn-order {
        background-color: #900D1E;

        span {
            @include rsprite($btn-tjdd-group);
        }
    }
}


#coupon-modal {
    .coupon-list {
        color: #fff;
        margin-top: 10px;
        margin-bottom: 20px;

        li {
            position: relative;
            background: no-repeat 50%;
            background-size: contain;
            height: 110px;
            overflow: hidden;

            .content {
                text-align: right;
                margin-top: 30px;
                margin-right: 15px;
                transform-origin: 100% 50%;
                transform: scale(.9);
                font-size: 12px;
            }

            .name {
                margin-bottom: 5px;
                font-size: 1em;
            }

            .date {
                margin: 0 0 5px;
                line-height: 1.5;
            }
        }
        .active {
            .check {
                width: 26px;
                height: 26px;
                background: $main;
                border-radius: 50%;
                position: absolute;
                top: 0;
                right: 0;
                &:after {
                    content: '';
                    border: 2px solid #000;
                    border-top-width: 0;
                    border-right-width: 0;
                    width: 13px;
                    height: 7px;
                    transform: rotate(-45deg);
                    float: left;
                    margin: 6px 0 0 5px;
                }
            }
        }
        .none {
            font-size: 12px;
            text-align: center;
            margin-top: 10px;
            height: auto;

            .check {
                display: inline-block;
                vertical-align: -2px;
                border: 1px solid #fff;
                background: rgba(#fff, 0.5);
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin-right: 10px;
            }

            &.active {
                .check {
                    position: static;
                    &:after {
                        content: '';
                        border: 1px solid #fff;
                        border-top-width: 0;
                        border-right-width: 0;
                        width: 7px;
                        height: 4px;
                        transform: rotate(-45deg);
                        float: left;
                        margin: 2px 0 0 2px;
                    }
                }
            }
        }
    }
    .rule-title {
        margin: 10px 30px;
        font-size: 1em;
    }
    .rule-content {
        list-style: inside decimal;
        color: #fff;
        margin: 0 30px;
        font-size: 12px;
        margin-bottom: 1em;
        li {
            margin-bottom: .5em;
            line-height: 1.4;
        }
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
            ktv: {},
            now: null,
            lastorder: null,
            terms: [],

            arrivalTimeList: [],
            arrivalTimeListLimit: 8,

            startTime: null,
            endTime: null,

            day: null,
            roomtype: null,
            course: null,
            package: null,
            coupon: null,

            couponList: [],
            couponText: "",
            couponNone: {
                id: 0,
                name: "不使用任何兑酒券"
            },

            userMobile: "",
            editMobile: {
                mobile: "",
                captcha: ""
            },

            loadingStatus: 1,
            errorMsg: ""
        }
    },
    route: {
        canActivate() {
            if (process.env.NODE_ENV !== "production") {
                if (window.__bookdata) {
                    localStorage.setItem("bookdata", JSON.stringify(window.__bookdata));
                } else {
                    let localdata = localStorage.getItem("bookdata");
                    if (localdata) window.__bookdata = JSON.parse(localdata);
                }
            }

            return !!window.__bookdata;
        },
        data() {
            let bookdata = window.__bookdata;

            window.__bookdata = null;

            if (bookdata.course.yesterday && bookdata.course.crossday) {
                let date = new Date(bookdata.day.date);
                date.setDate(date.getDate() + 1);
                bookdata.day.date = date.toISOString();
            }

            this.ktv = bookdata.ktv;
            this.day = bookdata.day;
            this.course = bookdata.course;
            this.roomtype = bookdata.roomtype;
            this.package = bookdata.package;
            this.lastorder = bookdata.lastorder;
            this.terms = bookdata.terms;

            let timeList = [];
            let timeRange = [];
            let startTime = bookdata.starttime;
            let endTime;

            if (bookdata.lastorder) {
                endTime = this.getEarlierTime(bookdata.lastorder, bookdata.course.starttime, bookdata.course.endtime);

                if (endTime == bookdata.course.endtime) {
                    endTime = utils.adjustTime(bookdata.course.endtime, -1);
                } else if (endTime == bookdata.lastorder && utils.diffTime(endTime, bookdata.course.endtime) <= 0.5) {
                    endTime = utils.adjustTime(endTime, 0, -30);
                }
            } else {
                endTime = utils.adjustTime(bookdata.course.endtime, -1);
            }

            let newEndTime = endTime;
            if (this.package.duration) newEndTime = utils.adjustTime(endTime, -this.package.duration + 1);

            // newEndTime 不在套餐时间段内，也不等于结束时间 || newEndTime 等于开始时间
            if ((!utils.isBetweenTime(newEndTime, startTime, endTime) && newEndTime != endTime) || newEndTime == startTime) {
                timeList.push(startTime);
            } else {
                endTime = newEndTime;
                if (utils.isBeforeTime(startTime, endTime)) {
                    timeRange.push({
                        start: startTime,
                        end: endTime
                    });
                } else {
                    timeRange.push({
                        start: startTime,
                        end: "24:00"
                    }, {
                        start: "00:00",
                        end: endTime
                    });
                };

                timeRange.forEach(function(range){
                    let times = [];
                    let [startHour, startMinute] = range.start.split(":").map(n => parseInt(n));
                    let [endHour, endMinute] = range.end.split(":").map(n => parseInt(n));

                    for (let hour = startHour; hour <= endHour; hour++) {
                        let strHour = utils.padZero(hour);
                        times.push(strHour + ":00", strHour + ":30");
                    }

                    if (startMinute == 30) times.shift();
                    if (endHour == 24) {
                        times.pop();
                        times.pop();
                    } else if (endMinute < 30) {
                        times.pop();
                    }

                    timeList = timeList.concat(times);
                });
            }

            this.arrivalTimeList = timeList;
            this.startTime = timeList[0];

            this.coupon = this.couponNone;

            this.$api.getUserInfo(true).then(function(data) { 
                this.userMobile = data.mobile;

                if (this.ktv.sjq) {
                    return this.fetchCouponList();
                } else {
                    return true;
                }
            }).then(function(){
                this.loadingStatus = 2;
            });
        }
    },
    ready() {
        window.scrollTo(0, 0);
    },
    methods: {
        fetchCouponList() {
            return this.$api.get("coupon/availablelist").then(function(data) {
                if (data.total){
                    let list = data.list;
                    list.sort(function(a, b) {
                        return a.count == b.count ? a.expire_time - b.expire_time : b.count - a.count;
                    });
                    this.couponText = "已选择：" + list[0].name;
                    this.couponList = list;
                    this.coupon = list[0];

                } else {
                    this.couponText = data.wrong_msg || "无";
                }
            }, function(){
                this.couponText = "获取失败";
            });
        },
        updateMobile() {
            this.$api.verifyCode(this.editMobile.mobile, this.editMobile.captcha).then(function() {
                return this.$api.updateUserInfo({
                    "mobile": this.editMobile.mobile
                });
            }).then(function(data) {
                alert("手机号绑定成功！");
                this.userMobile = data.mobile;
                this.$refs.editMobileModal.close();
            }).catch(function(data) {
                alert(data.msg || "绑定失败");
            });
        },
        updateCoupon() {
            this.couponText = this.coupon.id == 0 ? this.coupon.name : "已选择：" + this.coupon.name;
            this.$refs.couponModal.close();
        },
        submitOrder(skip) {
            this.$els.submitBtn.disabled = true;

            let startDate = new Date(this.day.date);
            let [startHour, startMinute] = this.startTime.split(":").map(n => parseInt(n));
            let [endHour, endMinute] = this.endTime.split(":").map(n => parseInt(n));
            startDate.setHours(startHour, startMinute);

            if (this.course.crossday == 2 || utils.isBeforeTime(this.startTime, this.course.starttime)) {
                startDate.setDate(startDate.getDate() + 1);
            }

            let endDate = new Date(this.day.date);
            endDate.setHours(endHour, endMinute);
            if (endDate < startDate) endDate.setDate(endDate.getDate() + 1);

            let params = {
                ktvid: this.ktv.xktvid,
                roomtype: this.roomtype.id,
                starttime: utils.parseDate("yyyy-MM-dd hh:mm:ss", startDate),
                endtime: utils.parseDate("yyyy-MM-dd hh:mm:ss", endDate),
                couponid: this.coupon.id,
                taocantype: this.package.huanchang ? 1 : 0,
                taocanid: this.package.huanchang ? "" : this.package.id
            };

            this.$api.post("booking/submitorder_new", params).then(function(data) {
                let msg = "<p>您的订单已提交成功<br>我们会尽早为您安排合适的时间<br>请留意夜点娱乐公众号为您发送的订单信息</p>";
                msg += "<h4>预订说明</h4><p>通过夜点预订KTV，只需要在夜点提交订单，到达KTV现场支付，即可享受夜点最强黄金档的价格优惠。</p>";
                this.$refs.flashMessage.show("success", msg, function() {
                    this.$router.replace("/order");
                });
            }, function(data) {
                alert(data.msg || "预订失败");
                this.$els.submitBtn.disabled = false;
            });
        },
        isTomorrow(time, start, end) {
            return utils.isBeforeTime(start, end) && utils.isBeforeTime(time, start);
        },
        getEarlierTime(time, starttime, endtime) {
            if (this.isTomorrow(time, starttime, endtime)) {
                // 同一天，但 time 是次日
                return utils.maxTime(endtime, time);
            } else if (utils.isBeforeTime(starttime, time) && utils.isBeforeTime(endtime, time)) {
                // 不是同一天，但 time 在今天
                return utils.maxTime(endtime, time);
            } else {
                return utils.minTime(endtime, time);
            }
        } 
    },
    computed: {
        isInvalidMobileForm() {
            return !(utils.isMobile(this.editMobile.mobile) && utils.isCaptcha(this.editMobile.captcha));
        },
        isInvalidOrder() {
            return !this.startTime || !this.package || !this.userMobile;
        },
        endTime() {
            if (this.package.huanchang) {
                return this.package.duration === 0 ? this.course.endtime : utils.adjustTime(this.startTime, this.package.duration);
            } else {
                if (this.package.type === 0) {
                    return this.course.endtime;
                } else {
                    return utils.minTime(utils.adjustTime(this.startTime, this.package.longtime), this.course.endtime);
                }
            }
        },
        orderMeta() {
            if (!this.package) return "";

            let date = new Date(this.day.date);
            let duration = utils.diffTime(this.startTime, this.endTime);

            if (this.course.crossday == 2 || utils.isBeforeTime(this.startTime, this.course.starttime)) {
                date.setDate(date.getDate() + 1);
            }

            if (this.package.huanchang) {
                if (duration > this.package.duration && this.package.duration > 0) duration = this.package.duration;
            }

            return (utils.parseDate("yyyy年MM月dd日", date)) + " " + this.startTime + "-" + this.endTime + " 欢唱" + duration + "小时 " + this.roomtype.name + "（" + this.roomtype.desc + "）";
        }
    },
    filters: {
        morrow(time, starttime) {
            return (utils.isBeforeTime(time, starttime) ? "次日" : "") + time;
        }
    }
}
</script>