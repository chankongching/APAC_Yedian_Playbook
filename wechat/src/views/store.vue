<template>
    <div class="page-store">
        <header class="banner">
            <h1 class="title">通过积分兑换礼品</h1>
            <span class="points">我的积分 {{userPoints}}</span>
        </header>

        <section class="gift-list-continer">
           <ul class="gift-list">
                <li v-for="gift in list | limitBy currLimit">
                    <a v-link="{name:'gift',params:{id:gift.product_id}}">
                        <figure class="pic" :style="{backgroundImage:'url('+gift.product_mainpic+')'}"></figure>
                        <div class="content">
                            <h4 class="name">{{gift.productsale_name}}</h4>
                            <span class="points">{{gift.productsale_points}} 积分</span>
                        </div>
                    </a>
                </li>
            </ul>

            <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
            <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" @click="fetch"></screen-message>
            <screen-message v-if="loadingStatus==2&&list.length==0" message="列表为空"></screen-message>
        </section>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";

.page-store {
    .banner {
        height: 158px;
        background: url(../img/store-banner.jpg) no-repeat 50%;
        background-size: cover;
        text-align: center;
        overflow: hidden;

        .title {
            font-size: 16px;
            margin: 70px auto 10px;
        }
        .points {
            font-size: 12px;
            font-weight: lighter;
        }
    }
}

.gift-list-continer {
    position: relative;
    background: $main;

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

.gift-list {
    padding: 15px 7px;
    overflow: hidden;

    li {
        float: left;
        width: 50%;
        animation: fadeIn .3s;

        a {
            background: $main;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            text-align: center;
            margin: 7px;
            display: block;
        }

        .pic {
            background: no-repeat 50%;
            background-size: cover;
            padding-top: 100%;
        }

        .content {
            padding: 20px 20px 15px;
        }

        .name {
            color: #fff;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            margin-bottom: 10px;
            line-height: 1;
            font-weight: lighter;
        }

        .points {
            display: inline-block;
            line-height: 22px;
            padding: 0 8px;
            border: 1px solid $border;
            border-radius: 11px;
            font-size: 12px;
            font-weight: lighter;
            color: #eee;
            transform: scale(0.9);
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

            userPoints: 0,

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
            this.$api.getUserInfo(true).then(function(data){
                this.userPoints = data.points;

                this.fetch();
            });
        }
    },
    ready() {
        if (this.$route.query.fromTabBar) window.scrollTo(0, 0);

        let $win = $(window);
        let $doc = $(document);
        let winHeight = $win.height();

        $win.on("scroll", utils.throttle(() => {
            if (this.loadingStatus == 1) return false;
            if ($doc.height() - winHeight - $win.scrollTop() < 400) {
                this.fetch();
            };
        }, 100));

        this.$wxShare({
            title: "预订KTV欢唱，还可兑换各种好礼，别说我没告诉你哦！",
            desc: "想要唱歌不排队还能免费兑换惊喜好礼？来夜点一键预订KTV吧！",
            titleTL: "用夜点预订KTV欢唱，还可兑换各种好礼，别说我没告诉你哦！"
        });
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
            this.$api.get("gift/Giftlist", {
                type: 1,
                offset: this.offset,
                limit: this.limit
            }).then(function(data) {
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
            }, function(data) {
                this.errorMsg = data.msg;
                this.loadingStatus = -1;
            });
        }
    },
    computed: {
        currLimit() {
            return this.page * this.perPage;
        }
    }
}
</script>