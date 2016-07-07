<template>
	<div class="page-couponlist">
        <header class="masthead" v-el:masthead>
            <h1>我的兑酒券</h1>
        </header>
		<ul id="coupon-list">
            <li v-for="coupon in list" class="status-{{coupon.status}}" :style="{backgroundImage:'url('+(coupon.status===0?coupon.img:coupon.img_disable)+')'}">
                <div class="content">
                    <h3 class="name">{{coupon.name}}</h3>
                    <p class="date">有效期<br>{{coupon.start_time | date 'yyyy/MM/dd'}} 至 {{coupon.expire_time | date 'yyyy/MM/dd'}}</p>
                    <span class="status">{{COUPON_STATUS[coupon.status]}}</span>
                </div>
            </li>
        </ul>

        <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
        <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
        <screen-message v-if="loadingStatus==2&&list.length==0" message="兑酒券为空"></screen-message>
	</div>
</template>

<style lang="sass">
@import "../scss/variables";

.page-couponlist {
    background: #0E0909 url(../img/couponlist-bg.jpg) no-repeat 50% 0 fixed;
    background-size: cover;
    text-align: center;
    min-height: 100vh;
    min-height: calc(100vh - #{$tabBarHeight});

    .masthead {
        height: 40px;
        line-height: 40px;
        text-align: left;
        h1 {
            font-size: 1em;
            margin-left: 1em;
        }
    }
}

#coupon-list {
    padding-top: 55px;
    color: #fff;

    li {
        box-sizing: border-box;
        background: no-repeat 50%;
        background-size: contain;
        width: 349px;
        height: 122px;
        margin: 0 auto;
        overflow: hidden;

        .content {
            text-align: right;
            margin-top: 20px;
            margin-right: 30px;
            transform-origin: 100% 50%;
            transform: scale(.9);
        }

        .name {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .date,
        .status {
            font-size: 12px;
        }

        .date {
            margin: 0 0 5px;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            background: $mainDark;
            border-radius: 6px;
            padding: .4em 1em;
            color: #fff;
        }

        &.status-1,
        &.status-2,
        &.status-4 {
            .status {
                background: #A6A6A6;
            }
        }
    }
}

@media (max-width: 360px) {
    #page-couponlist {
        li {
            background-size: contain;
            width: 320px;
            height: 112px;

            .content {
                margin-top: 16px;
            }
        }
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
            list: [],

            COUPON_STATUS: {
                0: "未使用",
                1: "已使用",
                2: "已过期",
                3: "初始状态",
                4: "已失效",
                5: "使用中"
            },

            offset: 0,
            limit: 27,
            page: 0,
            perPage: 9,

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
        let $win = $(window);
        let $doc = $(document);
        let winHeight = $win.height();

        $win.on("scroll", utils.throttle(() => {
            if (this.loadingStatus == 1) return false;
            if ($doc.height() - winHeight - $win.scrollTop() < 400) {
                this.fetch();
            };
        }, 100));

        window.scrollTo(0, 0);
    },
    beforeDestroy() {
        $(window).off("scroll");
    },
    methods: {
        fetch() {
            if (this.offset > this.currLimit) {
                this.page++;
                return false;
            } else if (this.loadingStatus == 2) {
                return false;
            };
            this.loadingStatus = 1;
            this.$api.get("Coupon/list", {
                offset: this.offset,
                limit: this.limit
            }).then(function (data) {
                if (data.total == 0) {
                    this.loadingStatus = 2;
                    return false;
                };
                this.list = this.list.concat(data.list);
                this.offset += data.total;
                this.page++;
                if (data.total < this.limit) {
                    this.loadingStatus = 2;
                } else {
                    this.loadingStatus = 0;
                };
            }, function (data) {
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        }
    }
}
</script>