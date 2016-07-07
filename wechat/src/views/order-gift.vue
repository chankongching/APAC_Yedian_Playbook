<template>
    <div class="order-list order-list-gift">
        <ul>
            <li v-for="order in list | limitBy currLimit" :data-status="order.order_status" v-link="{name:'gift-order',params:{id:order.order_no}}">
                <header class="header">
                    <span class="name">{{order.sellordergoods_name}}</span>
                    <span class="status">{{order.order_status}}</span>
                </header>
                <div class="content">
                    <p class="info amount">订单数量 <em>{{order.sellordergoods_num}}</em></p>
                    <span class="info">消费积分 <em>{{order.sellorder_pointdeduction}}</em></span>
                    <span class="ordertime">{{order.sellorder_datetime | date 'yyyy年MM月dd日 hh:mm'}}下单</span>
                </div>
                <figure class="pic" v-adaptive-background="order.sellordergoods_mainpic"></figure>
            </li>
        </ul>
        
        <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
        <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" @click="fetch"></screen-message>
        <screen-message v-if="loadingStatus==2&&list.length==0" message="无订单"></screen-message>
    </div>
</template>

<style lang="sass">
@import "../scss/variables";

.order-list-gift {
    li {
        .header {
            border-bottom: 1px solid $mainDarker;
        }
        .content {
            .amount {
                margin-bottom: 15px;
            }
            .ordertime {
                float: right;
                color: $mainDark;
            }
            em {
                font-size: 20px;
                font-style: normal;
                font-weight: lighter;
            }
        }
        .pic {
            background-color: #fff;
            background-size: contain;
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
            this.$api.get("gift/giftorderlistnew", {
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