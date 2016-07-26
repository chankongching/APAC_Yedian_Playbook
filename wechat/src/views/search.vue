<template>
    <div class="page-search">
        <header class="masthead" v-el:masthead>
            <form class="search-form" @submit.prevent="search">
                <input type="search" autocomplete="off" v-model="keyword">
                <button type="submit"><span class="icon icon-search"></span></button>
            </form>
        </header>

        <div class="ktv-list-container">
            <ul class="ktv-list">
                <li v-for="ktv in list | limitBy currLimit" :style="ktv.piclist[0].bigpicurl | backgroundImage">
                    <a v-link="{ name: 'detail', params: { id: ktv.xktvid }}">
                        <span v-if="ktv.taocan" class="stamp stamp-goldpkg"></span>
                        <div class="content">
                            <div class="marks">
                                <span v-if="ktv.sjq" class="mark mark-djq"></span>
                                <span v-if="ktv.online_pay" class="mark mark-zxf"></span>
                            </div>
                            <h3 class="title">{{ktv.xktvname}}</h3>
                            <span class="rating"><span class="full"></span><span class="stars" :style="{width:ktv.rate*20+'%'}"></span></span>
                            <p class="address">{{ktv.address}}</p>
                            <span class="distance" v-if="ktv | distance">{{ktv | distance}}KM</span>
                        </div>
                    </a>
                </li>
            </ul>

            <loading v-show="loadingStatus==1" :inline-mode="list.length>0"></loading>
            <screen-message v-if="loadingStatus==-1" :inline-mode="list.length>0" message="加载失败, 点击重试" :info="errorMsg" @click="fetch"></screen-message>
            <screen-message v-if="loadingStatus==2&&list.length==0" message="未找到"></screen-message>
        </div>
    </div>
</template>

<style lang="sass">
.page-search {
    padding-top: 44px;

    .masthead {
        transition: .3s ease;

        &.hide {
            transform: translate3d(0, -100%, 0);
        }
    }

    .search-form {
        position: static;
    }
}
</style>

<script>
import utils from "../libs/utils";

export default {
    data() {
        return {
            keyword: this.$route.query.q,
            city: this.$route.query.city,

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
            utils.getLocation(null, null, this.fetch);
        }
    },
    ready() {
        let masthead = this.$els.masthead;
        let $win = $(window);
        let $doc = $(document);
        let winHeight = $win.height();

        $doc.on("touchstart.page", function(event) {
            let start = $win.scrollTop();

            $win.one("scroll", function(event) {
                let scrollTop = $win.scrollTop();
                let distance = scrollTop - start;

                if (scrollTop > 44 && distance > 0) {
                    masthead.classList.add("hide");
                } else {
                    masthead.classList.remove("hide");
                };
            });
        });

        $win.on("scroll", utils.throttle(() => {
            let scrollTop = $win.scrollTop();
            if (this.loadingStatus == 1) return false;
            if ($doc.height() - winHeight - scrollTop < 400) {
                this.fetch();
            };
            if (scrollTop < 44) {
                masthead.classList.remove("hide");
            };
        }, 100));
    },
    beforeDestroy() {
        $(document).off(".page");
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
            this.$api.get("booking/xktvsearchlist", {
                offset: this.offset,
                limit: this.limit,
                name: this.keyword,
                city: this.city
            }, true).then(function (data) {
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
        },
        search() {
            this.loadingStatus = 0;
            this.list = [];
            this.page = this.offset = 0;
            this.fetch();
        }
    },
    computed: {
        currLimit() {
            return this.page * this.perPage;
        }
    }
}
</script>